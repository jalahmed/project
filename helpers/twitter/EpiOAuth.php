<?php
class EpiOAuth
{
  public $version = '1.0';

  protected $requestTokenUrl;
  protected $accessTokenUrl;
  protected $authenticateUrl;
  protected $authorizeUrl;
  protected $consumerKey;
  protected $consumerSecret;
  protected $token;
  protected $tokenSecret;
  protected $signatureMethod;
  protected $useSSL = false;

  public function getAccessToken()
  {
    $resp = $this->httpRequest('GET', $this->getUrl($this->accessTokenUrl));
    return new EpiOAuthResponse($resp);
  }

  public function getAuthenticateUrl($token = null)
  {
    $token = $token ? $token : $this->getRequestToken();
    return $this->getUrl($this->authenticateUrl) . '?oauth_token=' . $token->oauth_token;
  }

  public function getAuthorizationUrl($token = null)
  {
    $token = $token ? $token : $this->getRequestToken();
    return $this->getUrl($this->authorizeUrl) . '?oauth_token=' . $token->oauth_token;
  }

  public function getRequestToken()
  {
    $resp = $this->httpRequest('GET', $this->getUrl($this->requestTokenUrl));
    return new EpiOAuthResponse($resp);
  }

  public function getUrl($url)
  {
    if($this->useSSL === true)
      return preg_replace('/^http:/', 'https:', $url);

    return $url;
  }

  public function httpRequest($method = null, $url = null, $params = null, $isMultipart = false)
  {
    if(empty($method) || empty($url))
      return false;

    if(empty($params['oauth_signature']))
      $params = $this->prepareParameters($method, $url, $params);

    switch($method)
    {
      case 'GET':
        return $this->httpGet($url, $params);
        break;
      case 'POST':
        return $this->httpPost($url, $params, $isMultipart);
        break;
    }
  }

  public function setToken($token = null, $secret = null)
  {
    $this->token = $token;
    $this->tokenSecret = $secret;
  }

  public function useSSL($use = false)
  {
    $this->useSSL = (bool)$use;
  }

  protected function addOAuthHeaders(&$ch, $url, $oauthHeaders)
  {
    $_h = array('Expect:');
    $urlParts = parse_url($url);
    $oauth = 'Authorization: OAuth realm="' . $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'] . '",';
    foreach($oauthHeaders as $name => $value)
    {
      $oauth .= "{$name}=\"{$value}\",";
    }
    $_h[] = substr($oauth, 0, -1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $_h);
  }

  protected function buildHttpQueryRaw($params)
  {
    $retval = '';
    foreach((array)$params as $key => $value)
      $retval .= "{$key}={$value}&";
    $retval = substr($retval, 0, -1);
    return $retval;
  }

  protected function curlInit($url)
  {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if($this->useSSL === true)
    {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    }
    return $ch;
  }

  protected function encode_rfc3986($string)
  {
    return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode(($string))));
  }

  protected function generateNonce()
  {
    if(isset($this->nonce)) // for unit testing
      return $this->nonce;

    return md5(uniqid(rand(), true));
  }

  // parameters should already have been passed through prepareParameters
  // no need to double encode
  protected function generateSignature($method = null, $url = null, $params = null)
  {
    if(empty($method) || empty($url))
      return false;

    // concatenating and encode
    $concatenatedParams = $this->encode_rfc3986($this->buildHttpQueryRaw($params));

    // normalize url
    $normalizedUrl = $this->encode_rfc3986($this->normalizeUrl($url));
    $method = $this->encode_rfc3986($method); // don't need this but why not?

    $signatureBaseString = "{$method}&{$normalizedUrl}&{$concatenatedParams}";
    return $this->signString($signatureBaseString);
  }

  protected function httpGet($url, $params = null)
  {
    if(count($params['request']) > 0)
    {
      $url .= '?';
      foreach($params['request'] as $k => $v)
      {
        $url .= "{$k}={$v}&";
      }
      $url = substr($url, 0, -1);
    }
    $ch = $this->curlInit($url);
    $this->addOAuthHeaders($ch, $url, $params['oauth']);
    $resp = $this->curl->addCurl($ch);

    return $resp;
  }

  protected function httpPost($url, $params = null, $isMultipart)
  {
    $ch = $this->curlInit($url);
    $this->addOAuthHeaders($ch, $url, $params['oauth']);
    curl_setopt($ch, CURLOPT_POST, 1);
    // php's curl extension automatically sets the content type
    // based on whether the params are in string or array form
    if($isMultipart)
      curl_setopt($ch, CURLOPT_POSTFIELDS, $params['request']);
    else
      curl_setopt($ch, CURLOPT_POSTFIELDS, $this->buildHttpQueryRaw($params['request']));
    $resp = $this->curl->addCurl($ch);
    return $resp;
  }

  protected function normalizeUrl($url = null)
  {
    $urlParts = parse_url($url);
    $scheme = strtolower($urlParts['scheme']);
    $host = strtolower($urlParts['host']);
    $port = isset($urlParts['port']) ? intval($urlParts['port']) : 0;

    $retval = strtolower($scheme) . '://' . strtolower($host);

    if(!empty($port) && (($scheme === 'http' && $port != 80) || ($scheme === 'https' && $port != 443)))
      $retval .= ":{$port}";

    $retval .= $urlParts['path'];
    if(!empty($urlParts['query']))
    {
      $retval .= "?{$urlParts['query']}";
    }

    return $retval;
  }

  protected function prepareParameters($method = null, $url = null, $params = null)
  {
    if(empty($method) || empty($url))
      return false;

    $oauth['oauth_consumer_key'] = $this->consumerKey;
    $oauth['oauth_token'] = $this->token;
    $oauth['oauth_nonce'] = $this->generateNonce();
    $oauth['oauth_timestamp'] = !isset($this->timestamp) ? time() : $this->timestamp; // for unit test
    $oauth['oauth_signature_method'] = $this->signatureMethod;
    $oauth['oauth_version'] = $this->version;
    // encode all oauth values
    foreach($oauth as $k => $v)
      $oauth[$k] = $this->encode_rfc3986($v);
    // encode all non '@' params
    // keep sigParams for signature generation (exclude '@' params)
    // rename '@key' to 'key'
    $sigParams = array();
    if(is_array($params))
    {
      foreach($params as $k => $v)
      {
        if(strncmp('@',$k,1) !== 0)
        {
          $sigParams[$k] = $this->encode_rfc3986($v);
          $params[$k] = $this->encode_rfc3986($v);
        }
        else
        {
          $params[substr($k, 1)] = $v;
          unset($params[$k]);
        }
      }
    }

    $sigParams = array_merge($oauth, (array)$sigParams);

    // sorting
    ksort($sigParams);

    // signing
    $oauth['oauth_signature'] = $this->encode_rfc3986($this->generateSignature($method, $url, $sigParams));
    return array('request' => $params, 'oauth' => $oauth);
  }

  protected function signString($string = null)
  {
    $retval = false;
    switch($this->signatureMethod)
    {
      case 'HMAC-SHA1':
        $key = $this->encode_rfc3986($this->consumerSecret) . '&' . $this->encode_rfc3986($this->tokenSecret);
        $retval = base64_encode(hash_hmac('sha1', $string, $key, true));
        break;
    }

    return $retval;
  }

  public function __construct($consumerKey, $consumerSecret, $signatureMethod='HMAC-SHA1')
  {
    $this->consumerKey = $consumerKey;
    $this->consumerSecret = $consumerSecret;
    $this->signatureMethod = $signatureMethod;
    $this->curl = EpiCurl::getInstance();
  }
}

class EpiOAuthResponse
{
  private $__resp;

  public function __construct($resp)
  {
    $this->__resp = $resp;
  }

  public function __get($name)
  {
    if($this->__resp->code != 200)
      EpiOAuthException::raise($this->__resp->data, $this->__resp->code);

    parse_str($this->__resp->data, $result);
    foreach($result as $k => $v)
    {
      $this->$k = $v;
    }

    return $result[$name];
  }
}

class EpiOAuthException extends Exception
{
  public static function raise($message, $code)
  {
    switch($code)
    {
      case 400:
        throw new EpiOAuthBadRequestException($message, $code);
      case 401:
        throw new EpiOAuthUnauthorizedException($message, $code);
      default:
        throw new EpiOAuthException($message, $code);
    }
  }
}


class EpiOAuthBadRequestException extends EpiOAuthException{}
class EpiOAuthUnauthorizedException extends EpiOAuthException{}

