<?php
/**
 * the class about database
 */
class Database
{
 
    private $host = "127.0.0.1";
    private $db_name = "api_db";
    private $username = "root";
    private $password = "201916ab";
    public $conn;
 
    /**
     * get the connection of database
     *
     * @return object the connection of database
     */
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>