<?php
/****************************************************
CallerService.php

This file uses the constants.php to get parameters needed
to make an API call and calls the server.if you want use your
own credentials, you have to change the constants.php

Called by TransactionDetails.php, ReviewOrder.php,
DoDirectPaymentReceipt.php and DoExpressCheckoutPayment.php.

****************************************************/
/****************************************************
constants.php

This is the configuration file for the samples.This file
defines the parameters needed to make an API call.

PayPal includes the following API Signature for making API
calls to the PayPal sandbox:

API Username 	sdk-three_api1.sdk.com
API Password 	QFZCWN5HZM8VBG7Q
API Signature 	A.d9eRKfd1yVkRrtmMfCFLTqa6M9AyodL0SJkhYztxUi8W9pCXF6.4NI

Called by CallerService.php.
****************************************************/
/**
 # API user: The user that is identified as making the call. you can
 # also use your own API username that you created on PayPal’s sandbox
 # or the PayPal live site
 */
//for 3-token -> API_USERNAME,API_PASSWORD,API_SIGNATURE  are needed


define('API_USERNAME', 'ilsab_1295952532_biz_api1.ilsainteractive.com');
//define('API_USERNAME', 'meer_a_1295951319_biz_api1.yahoo.com');
//

/**
 # API_password: The password associated with the API user
 # If you are using your own API username, enter the API password that
 # was generated by PayPal below
 # IMPORTANT - HAVING YOUR API PASSWORD INCLUDED IN THE MANNER IS NOT
 # SECURE, AND ITS ONLY BEING SHOWN THIS WAY FOR TESTING PURPOSES
 */

define('API_PASSWORD', '1295952552');
//define('API_PASSWORD', '1295951333');

/**
 # API_Signature:The Signature associated with the API user. which is generated by paypal.
 */

define('API_SIGNATURE', 'AJR86Lu7GksmVflhY0Xh0zu1pt.XAUaU-oQHxbLkDK31O0IXm6sQt4Is');
//define('API_SIGNATURE', 'Alulir.O2-rNVHMARm9iWa2BqjzZAnIaahX-d9puDTpDXmb7HpXwXX8z');

/**
 # Endpoint: this is the server URL which you have to connect for submitting your API request.
 */

define('API_ENDPOINT', 'https://api-3t.sandbox.paypal.com/nvp');

/*
 # Third party Email address that you granted permission to make api call.
*/
define('SUBJECT','');
/*for permission APIs ->token, signature, timestamp  are needed
define('AUTH_TOKEN',"4oSymRbHLgXZVIvtZuQziRVVxcxaiRpOeOEmQw");
define('AUTH_SIGNATURE',"+q1PggENX0u+6vj+49tLiw9CLpA=");
define('AUTH_TIMESTAMP',"1284959128");
*/
/**
 USE_PROXY: Set this variable to TRUE to route all the API requests through proxy.
 like define('USE_PROXY',TRUE);
 */
define('USE_PROXY',FALSE);
/**
 PROXY_HOST: Set the host name or the IP address of proxy server.
 PROXY_PORT: Set proxy port.

 PROXY_HOST and PROXY_PORT will be read only if USE_PROXY is set to TRUE
 */
define('PROXY_HOST', '127.0.0.1');
define('PROXY_PORT', '808');

/* Define the PayPal URL. This is the URL that the buyer is
   first sent to to authorize payment with their paypal account
   change the URL depending if you are testing on the sandbox
   or going to the live PayPal site
   For the sandbox, the URL is
   https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=
   For the live site, the URL is
   https://www.paypal.com/webscr&cmd=_express-checkout&token=
*/
define('PAYPAL_URL', 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=');


/**
 # Version: this is the API version in the request.
 # It is a mandatory parameter for each API request.
 # The only supported value at this time is 2.3
 */

define('VERSION', '65.1');

// Ack related constants
define('ACK_SUCCESS', 'SUCCESS');
define('ACK_SUCCESS_WITH_WARNING', 'SUCCESSWITHWARNING');



if(defined('API_USERNAME'))
    $API_UserName=API_USERNAME;

if(defined('API_PASSWORD'))
    $API_Password=API_PASSWORD;

if(defined('API_SIGNATURE'))
    $API_Signature=API_SIGNATURE;

if(defined('API_ENDPOINT'))
    $API_Endpoint =API_ENDPOINT;

$version=VERSION;

if(defined('SUBJECT'))
    $subject = SUBJECT;
// below three are needed if used permissioning
if(defined('AUTH_TOKEN'))
    $AUTH_token= AUTH_TOKEN;

if(defined('AUTH_SIGNATURE'))
    $AUTH_signature=AUTH_SIGNATURE;

if(defined('AUTH_TIMESTAMP'))
    $AUTH_timestamp=AUTH_TIMESTAMP;


function nvpHeader() {
    if(defined('API_USERNAME'))
        $API_UserName=API_USERNAME;

    if(defined('API_PASSWORD'))
        $API_Password=API_PASSWORD;

    if(defined('API_SIGNATURE'))
        $API_Signature=API_SIGNATURE;

    if(defined('API_ENDPOINT'))
        $API_Endpoint =API_ENDPOINT;

    $version=VERSION;

    if(defined('SUBJECT'))
        $subject = SUBJECT;
// below three are needed if used permissioning
    if(defined('AUTH_TOKEN'))
        $AUTH_token= AUTH_TOKEN;

    if(defined('AUTH_SIGNATURE'))
        $AUTH_signature=AUTH_SIGNATURE;

    if(defined('AUTH_TIMESTAMP'))
        $AUTH_timestamp=AUTH_TIMESTAMP;
    global $API_Endpoint,$version,$API_UserName,$API_Password,$API_Signature,$nvp_Header, $subject, $AUTH_token,$AUTH_signature,$AUTH_timestamp;
    $nvpHeaderStr = "";

    if(defined('AUTH_MODE')) {
        //$AuthMode = "3TOKEN"; //Merchant's API 3-TOKEN Credential is required to make API Call.
        //$AuthMode = "FIRSTPARTY"; //Only merchant Email is required to make EC Calls.
        //$AuthMode = "THIRDPARTY";Partner's API Credential and Merchant Email as Subject are required.
        $AuthMode = "AUTH_MODE";
    }
    else {
        if(defined('API_USERNAME'))
            $API_UserName=API_USERNAME;

        if(defined('API_PASSWORD'))
            $API_Password=API_PASSWORD;

        if(defined('API_SIGNATURE'))
            $API_Signature=API_SIGNATURE;

        if(defined('API_ENDPOINT'))
            $API_Endpoint =API_ENDPOINT;

        $version=VERSION;

        if(defined('SUBJECT'))
            $subject = SUBJECT;
// below three are needed if used permissioning
        if(defined('AUTH_TOKEN'))
            $AUTH_token= AUTH_TOKEN;

        if(defined('AUTH_SIGNATURE'))
            $AUTH_signature=AUTH_SIGNATURE;

        if(defined('AUTH_TIMESTAMP'))
            $AUTH_timestamp=AUTH_TIMESTAMP;

        if((!empty($API_UserName)) && (!empty($API_Password)) && (!empty($API_Signature)) && (!empty($subject))) {
            $AuthMode = "THIRDPARTY";
        }

        else if((!empty($API_UserName)) && (!empty($API_Password)) && (!empty($API_Signature))) {
            $AuthMode = "3TOKEN";
        }

        elseif (!empty($AUTH_token) && !empty($AUTH_signature) && !empty($AUTH_timestamp)) {
            $AuthMode = "PERMISSION";
        }
        elseif(!empty($subject)) {
            $AuthMode = "FIRSTPARTY";
        }
    }
    switch($AuthMode) {

        case "3TOKEN" :
            $nvpHeaderStr = "&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature);
            break;
        case "FIRSTPARTY" :
            $nvpHeaderStr = "&SUBJECT=".urlencode($subject);
            break;
        case "THIRDPARTY" :
            $nvpHeaderStr = "&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature)."&SUBJECT=".urlencode($subject);
            break;
        case "PERMISSION" :
            $nvpHeaderStr = formAutorization($AUTH_token,$AUTH_signature,$AUTH_timestamp);
            break;
    }
    return $nvpHeaderStr;
}

/**
 * hash_call: Function to perform the API call to PayPal using API signature
 * @methodName is name of API  method.
 * @nvpStr is nvp string.
 * returns an associtive array containing the response from the server.
 */


function hash_call($methodName,$nvpStr) {
    if(defined('API_USERNAME'))
        $API_UserName=API_USERNAME;

    if(defined('API_PASSWORD'))
        $API_Password=API_PASSWORD;

    if(defined('API_SIGNATURE'))
        $API_Signature=API_SIGNATURE;

    if(defined('API_ENDPOINT'))
        $API_Endpoint =API_ENDPOINT;

    $version=VERSION;

    if(defined('SUBJECT'))
        $subject = SUBJECT;
// below three are needed if used permissioning
    if(defined('AUTH_TOKEN'))
        $AUTH_token= AUTH_TOKEN;

    if(defined('AUTH_SIGNATURE'))
        $AUTH_signature=AUTH_SIGNATURE;

    if(defined('AUTH_TIMESTAMP'))
        $AUTH_timestamp=AUTH_TIMESTAMP;
    //declaring of global variables
    global $API_Endpoint,$version,$API_UserName,$API_Password,$API_Signature,$nvp_Header, $subject, $AUTH_token,$AUTH_signature,$AUTH_timestamp;

// form header string
    $nvpheader=nvpHeader();
    //setting the curl parameters.
    $ch = curl_init();
    
    
   // curl_setopt($ch, CURLOPT_URL,$API_Endpoint);
    curl_setopt($ch, CURLOPT_URL,'https://api-3t.sandbox.paypal.com/nvp');
//        var_dump($API_Endpoint);

    curl_setopt($ch, CURLOPT_VERBOSE, 1);

    //turning off the server and peer verification(TrustManager Concept).
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POST, 1);

    //in case of permission APIs send headers as HTTPheders
    if(!empty($AUTH_token) && !empty($AUTH_signature) && !empty($AUTH_timestamp)) {
        $headers_array[] = "X-PP-AUTHORIZATION: ".$nvpheader;

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_array);
        curl_setopt($ch, CURLOPT_HEADER, false);
    }
    else {
        $nvpStr=$nvpheader.$nvpStr;
    }
    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
    //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php
    if(USE_PROXY)
        curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT);

    //check if version is included in $nvpStr else include the version.
    if(strlen(str_replace('VERSION=', '', strtoupper($nvpStr))) == strlen($nvpStr)) {
        $nvpStr = "&VERSION=" . urlencode($version) . $nvpStr;
    }

    $nvpreq="METHOD=".urlencode($methodName).$nvpStr;

    //setting the nvpreq as POST FIELD to curl


    curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);

    //getting response from server
    $response = curl_exec($ch);

    //convrting NVPResponse to an Associative Array
    $nvpResArray=deformatNVP($response);
    $nvpReqArray=deformatNVP($nvpreq);
    $_SESSION['nvpReqArray']=$nvpReqArray;

    if (curl_errno($ch)) {
        // moving to display page to display curl errors
        $_SESSION['curl_error_no']=curl_errno($ch) ;
        $_SESSION['curl_error_msg']=curl_error($ch);
        //$location = "APIError.php";
        $location = "checkoutBack";
        header("Location: $location");
    } else {
        //closing the curl
        curl_close($ch);
    }

    return $nvpResArray;
}

/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
 * It is usefull to search for a particular key and displaying arrays.
 * @nvpstr is NVPString.
 * @nvpArray is Associative Array.
 */

function deformatNVP($nvpstr) {

    $intial=0;
    $nvpArray = array();


    while(strlen($nvpstr)) {
        //postion of Key
        $keypos= strpos($nvpstr,'=');
        //position of value
        $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

        /*getting the Key and Value values and storing in a Associative Array*/
        $keyval=substr($nvpstr,$intial,$keypos);
        $valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
        //decoding the respose
        $nvpArray[urldecode($keyval)] =urldecode( $valval);
        $nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
    }
    return $nvpArray;
}
function formAutorization($auth_token,$auth_signature,$auth_timestamp) {
    $authString="token=".$auth_token.",signature=".$auth_signature.",timestamp=".$auth_timestamp ;
    return $authString;
}
?>