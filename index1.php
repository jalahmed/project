<?php
// framework's front controller
// specify parameters for autoloading classes
spl_autoload_register(NULL, FALSE);
spl_autoload_extensions('.php');
spl_autoload_register(array('Autoloader', 'load'));
// Input Filters
session_start();
require_once("helpers/class.inputfilter_clean.php");
require_once("helpers/common.php");
require_once("config/init.php");

$myFilter = new InputFilter();
// process input
if(isset($_POST))
    $_POST = $myFilter->process($_POST);
if(isset($_GET))
    $_GET = $myFilter->process($_GET);

// define custom ClassNotFoundException exception class
class ClassNotFoundException extends Exception {
}
// define Autoloader class
class Autoloader {
    // attempt to autoload a specified class
    public static function load($class) {
        if (class_exists($class, FALSE)) {
            return;
        }
        $file = 'controller/' . $class . '.php';
        if (!file_exists($file)) {
            //eval('class'.$class.'{}');
            //eval("class".$class."{}");
            throw new Exception('File ' . $file . ' not found.');
        }
        require_once($file);
        unset($file);
        if (!class_exists($class, FALSE)) {
            eval('class ' . $class . '{}');
            throw new ClassNotFoundException('Class ' . $class . ' not found.');
        }
    }
}

// handle request and dispatch it to the appropriate controller
try {
    Dispatcher::dispatch();
}
catch (ClassNotFoundException $e) {
    echo $e->getMessage();
    exit();
}
catch (Exception $e) {
    echo $e->getMessage();
    exit();
}// End front controller

?>