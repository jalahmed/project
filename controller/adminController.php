<?php

include_once 'application_controller.php';
require_once("dao/userDAO.php");
class adminController extends application_controller {

    function admin_login() {
        
        unset($_SESSION['admin_session_id']);
        
        session_destroy();
        return $this->render("views/users/login.php", compact('var', 'var2','var3'));
    }

    function admin_login_check() {
        if(!isset($_SESSION['admin_session_id']) && $_SESSION['admin_session_id']=="")
            redirect_to(site_url()."admin/admin_login");
    }
    public function loginAdmin() {
        
        
        $user =  $_POST['user_name'];
     
        $password = $this->encrypt_password($_POST['user_pass']);
        
        
        

        $userDAO =new userDAO();
        $answer=$userDAO->verifyAdminlogin($user);
        
        if($answer!="1") {
            $_SESSION['message']= "Invalid Email Address!";
             redirect_to(site_url()."admin/admin_login");
        } else {

                $userDAO =new userDAO();
                $answer=$userDAO->logAdmin($user,$password);
                
                if($answer!="1") {
                   $_SESSION['message']= "Invalid Password";
                    redirect_to(site_url()."admin/admin_login");
                }  else {
                    return $this->render("views/users/admin_home.php", compact('var', 'var2','var3'));
                    $_SESSION['message']='1';
                }
            }


        }
    
    public function encrypt_password($password) {
        $encrypt_password=md5(md5(SALT.$password));
        return $encrypt_password;
    }
    public  function logoutadmin() {
        unset($_SESSION['admin_name']);
        unset($_SESSION['venderid']);
        session_destroy();
        redirect_to(site_url()."admin/admin_login");
        
// redirect_to(site_url()."home/index");

    }

}

// require_once("./views/layout/header.php");
?>

