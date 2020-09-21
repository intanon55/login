<?php
class Database
{   
     //ตั้งค่าเชื่อมต่อ DATABASE
    private $host = "us-cdbr-east-02.cleardb.com";
    private $db_name = "heroku_b17c7c5f045d5cd";
    private $username = "bd70755a0d98e0";
    private $password = "0b0e9437";
    public $conn;
     
    public function dbConnection()
	{
     
	    $this->conn = null;    
        try
		{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        }
		catch(PDOException $exception)
		{
            echo "Connection error: " . $exception->getMessage();
        }
         
        return $this->conn;
    }
}
