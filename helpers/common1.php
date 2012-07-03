<?php
ob_start();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 function site_url(){
    $_SERVER['FULL_URL'] = 'http';
    if($_SERVER['HTTPS']=='on'){$_SERVER['FULL_URL'] .=  's';}
    $_SERVER['FULL_URL'] .=  '://';
    if($_SERVER['SERVER_PORT']!='80') $_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$_SERVER['SCRIPT_NAME'];
    else
    $_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'];
    // comment following 2 lines if code is on root
    $uri = explode("/", $_SERVER['PHP_SELF']);
    $_SERVER['FULL_URL'] .= "/" . $uri[1] . "/";
    // Uncomment following 1 line if code is on root
//    $_SERVER['FULL_URL'] .= "/";
    ///////////////////////////////////////////////
    return $_SERVER['FULL_URL'];
 }
 function get_controller(){
    return isset($_SESSION['controller']) ? $_SESSION['controller'] : false;
 }
 function get_action(){
    return isset($_SESSION['action']) ? $_SESSION['action'] : false;
 }
 function redirect_to($url) {
    print("<script> window.location.href='".$url."'</script>");
}
?>

