<?php
include 'EpiCurl.php';
include 'EpiOAuth.php';
include 'EpiTwitter.php';
include 'keys.php';

$Twitter = new EpiTwitter($consumerKey, $consumerSecret);

echo '<a href="' . $Twitter->getAuthenticateUrl() . '">
<img src="twitterButton.png" alt="sign in with twitter" />
</a>';
?>