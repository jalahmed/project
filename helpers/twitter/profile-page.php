<?php

// include Epi
require_once 'keys.php';
require_once 'EpiCurl.php';
require_once 'EpiOAuth.php';
require_once 'EpiTwitter.php';
	    $Twitter = new EpiTwitter($consumerKey, $consumerSecret);

if(isset($_GET['oauth_token']) || (isset($_COOKIE['oauth_token']) && isset($_COOKIE['oauth_token_secret'])))
{
// user accepted access
	if( !isset($_COOKIE['oauth_token']) || !isset($_COOKIE['oauth_token_secret']) )
	{
		// user comes from twitter
	    $Twitter->setToken($_GET['oauth_token']);
		$token = $Twitter->getAccessToken();
		setcookie('oauth_token', $token->oauth_token);
		setcookie('oauth_token_secret', $token->oauth_token_secret);
		$Twitter->setToken($token->oauth_token, $token->oauth_token_secret);

	}
	else
	{
	 // user switched pages and came back or got here directly, stilled logged in
	 $Twitter->setToken($_COOKIE['oauth_token'],$_COOKIE['oauth_token_secret']);
	}

    $user= $Twitter->get_accountVerify_credentials();
	echo "
	<p>
	Username: <br />
	<strong>{$user->screen_name}</strong><br />
	Profile Image:<br/>
	<img src=\"{$user->profile_image_url}\"><br />
	Last Tweet: <br />
	<strong>{$user->status->text}</strong><br/>

	</p>";

}
elseif(isset($_GET['denied']))
{
 // user denied access
 echo 'You must sign in through twitter first';
}
else
{
// user not logged in
 echo 'You are not logged in';
}