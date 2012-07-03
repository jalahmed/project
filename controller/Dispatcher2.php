<?php
class Dispatcher {
    // dispatch request to the appropriate controller/method
    public static function dispatch() {
        $doc_url = explode('/', trim($_SERVER['PHP_SELF'], '/'));
        $url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $controller = $url[array_search($doc_url[sizeof($doc_url)-2],$url)+1];
    $action = $url[array_search($doc_url[sizeof($doc_url)-2],$url)+2];
    $arg = $url[array_search($doc_url[sizeof($doc_url)-2],$url)+3];

        $DefaultController = "home";
        $DefaultAction = "index";
        // get controller name
        $controller = !is_null($controller) ? $controller . 'Controller' : $DefaultController . 'Controller';
        $_SESSION['controller'] = !is_null($controller) ? $controller : $DefaultController;
        // get method name of controller
        $method = !is_null($action) ? $action : $DefaultAction;
        $_SESSION['action'] = $method;

        // create controller instance and call the specified method
        $file = 'controller/' . $controller . '.php';
        if (!file_exists($file)) {
            throw new Exception('File ' . $file . ' not found.');
        }

        $cont = new $controller;
        if(!method_exists($controller,$method)){
            throw new ClassNotFoundException('Action ' . $method . ' not found.');
        }else{
            $cont->$method($arg);
        }
    }
}// End Dispatcher class
/////////////////////////////////////////
?>