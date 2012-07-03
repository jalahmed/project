<?php
include_once 'application_controller.php';
require_once("dao/userDAO.php");

//include_once 'views/users/connectfacebook.php';
//require_once("helpers/EasyGoogleMap.class.php");
class usersController extends application_controller {
    
    public function sign_in_user(){
        $user=$_POST['user_name'];
        $password=$_POST['password'];
        
        $userDao = new userDAO();
        $check_user=$userDao->sign_user($user,$password);
        
        if($check_user == "1"){
            $_SESSION['user'] = $user;
            redirect_to(site_url()."users/home");
        }else{
            $_SESSION['message']="Please enter correct information";
               return $this->render("views/users/index.php",compact('var1','var2')); 
            }
        
    }

    public function home(){
        if(isset($_SESSION['user']) && $_SESSION['user'] !=""){
            return $this->render("views/users/home.php",compact('var1','var2')); 
        }else{
            redirect_to(site_url());
        }
    }
    public function logout_user(){
        unset($_SESSION['user']);
        session_destroy();
        redirect_to(site_url());
    }
    
}

?>
