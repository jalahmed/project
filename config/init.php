<?php

/* Application Configuration */ 
require_once("connection.php");
//define('SALT','bd60fb8e226184c28a0cc6b5f0891efa');
define('SALT','bd60fb8e226184c28a0cc6b5f0891efa');
define('HOST_DIR','birthday');
define('DIRECTORY_ROOT',realpath(dirname(_FILE_)));
define('SITE_ROOT','http://'.$_SERVER['SERVER_NAME'].'/'.HOST_DIR);
//define('DAO_DIR',DIRECTORY_ROOT.'/dao');
define('CONTROLLER_DIR',DIRECTORY_ROOT.'/controller');
//define('VIEWS_DIR',DIRECTORY_ROOT.'/views');
//define('IMAGES_DIR',SITE_ROOT.'/public/images');
define('STYLES_DIR',SITE_ROOT.'/public/styles');
//define('SCRIPTS_DIR',SITE_ROOT.'/public/scripts');
//define('SESSION_NAMESPACE','IVRWEB');
define('UPLOADED_IMAGES_DIR','public/uploaded_images/');
define('ALLOWED_CLAIMED','6');
define('DAYS_BEFORE_CLAIMED','20');
//define('DATE',date('Y-m-d H:i:s')); // Datetime

// Create session object for to execute the constructor
//$Session = new Session();
//$Messenger = new Messenger();

// Session Error Handling
ob_start();