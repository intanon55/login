<?php
require_once('config.php');
class USER
{	
	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function register($username,$email,$password)
	{
		try
		{
			$hash_password = password_hash($password, PASSWORD_DEFAULT);
			
			$stmt = $this->conn->prepare("INSERT INTO users(username,email,password) 
		                                               VALUES(:username, :email, :hash_password)");
												  
			$stmt->bindparam(":username", $username);
			$stmt->bindparam(":email", $email);
			$stmt->bindparam(":hash_password", $hash_password);										  
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	
	public function doLogin($username,$email,$password)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT id, username, email, password FROM users WHERE username=:username OR email=:email ");
			$stmt->execute(array(':username'=>$username, ':email'=>$email));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1)
			{
				if(password_verify($password, $userRow['password']))
				{
					$_SESSION['user_session'] = $userRow['id'];
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function doLogout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
}
