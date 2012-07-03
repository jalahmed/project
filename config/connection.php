<?php
// Database Configuration

class connection {
    public $host;
    public $db;
    public $user;
    public $password;
    public $conn;

    function connection() {
        $this->host	= "localhost";
        $this->db	= "photogallery";
        $this->user	= "root";
        $this->password = "";
        try {
            $this->conn = mysql_connect($this->host,$this->user,$this->password);
            mysql_select_db($this->db);
        }
        catch (Exception $ee) {
            $this->conn = FALSE;
        }
    }
    public function getConnection() {
        try {
            return $this->conn;
        }catch(Exception $e) {
            echo "Error".$e->getMessage();
        }

    }
    public function close() {
        try {
            mysql_close($this->conn);
        }catch(Exception $e) {
            echo "Error".$e->getMessage();
        }
    }
}
?>