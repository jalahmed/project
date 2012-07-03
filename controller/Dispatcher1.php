<?php
class Dispatcher {
    // dispatch request to the appropriate controller/method
    public static function dispatch() {
        $url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        // Comment following line to run online site
        array_shift($url);
        $DefaultController = "home";
        $DefaultAction = "index";
        // get controller name
        $controller = !empty($url[0]) ? $url[0] . 'Controller' : $DefaultController . 'Controller';
        $_SESSION['controller'] = !empty($url[0]) ? $url[0] : $DefaultController;
        // get method name of controller
        $method = !empty($url[1]) ? $url[1] : $DefaultAction;
        $_SESSION['action'] = $method;
        // get argument passed in to the method
        $arg = !empty($url[2]) ? $url[2] : NULL;
        // create controller instance and call the specified method
        $cont = new $controller;
        if(!method_exists($controller,$method)){
            throw new ClassNotFoundException('Action ' . $method . ' not found.');
        }else{
            $cont->$method($arg);
        }
    }
}// End Dispatcher class
?>