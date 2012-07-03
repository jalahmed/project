<?php
require_once ("config/connection.php");
//require_once ("includes/helper.php");
require_once ("helpers/helper.php");
class userDAO {
    public function verifyAdminlogin($user){
         try {
            $database = new connection();
            $connection = $database->getConnection();
            $sql = "select * from users where user_name='$user'";
            $result = mysql_query($sql,$connection);
            $ans=  mysql_fetch_array($result);
            if($ans){
                return "1";
            }else{
                return "0";
            }
            
            $database->close();
        }catch(Exception $e) {

        }
    }
    public function logAdmin($user,$password){
         try {
            $database = new connection();
            $connection = $database->getConnection();
            $sql = "select * from users where user_name='$user' AND user_password='$password'";
            $result = mysql_query($sql,$connection);
            $ans=  mysql_fetch_array($result);
            if($ans){
                return "1";
            }else{
                return "0";
            }
            
            $database->close();
        }catch(Exception $e) {

        }
    }
}

?>
