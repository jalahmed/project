<?php 
include_once 'application_controller.php';
require_once("dao/userDAO.php");
//require_once("helpers/src/facebook.php");

                                                     

session_start();

class homeController extends application_controller {
   
    function index(){

        if(isset($_SESSION['loginname']) && $_SESSION['loginname']!='')
          redirect_to(site_url()."users/user_home");
//include 'views/users/connectfacebook.php';
               
//if ($me) {
//
//       redirect_to(site_url()."users/adduser_by_facebook");
//            }


        return $this->render("views/users/index.php", compact('facebook', 'var2','var3'));
    }
 
}

?>
