<html
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:addthis="http://www.addthis.com/help/api-spec">
    <head>



    </head>
    <body>
<?php
require 'facebook.php';


$facebook = new Facebook(array(
  'appId'  => '167176563327414',
  'secret' => '46d4362497ec083e320c93cee96f3fba',
  'cookie' => true,
));

$result = $facebook->api(array(
	'method' => 'fql.query',
	'query' => 'select fan_count from page where page_id =148044735250699;'
));

$fb_fans = $result[0]['fan_count'];



    ?>
    </body>
</html>

