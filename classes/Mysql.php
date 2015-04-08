<?php

require_once '../includes/constants.php';

class Mysql {
	private $conn;
	
	function __construct() {
		$this->conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or 
					  die('There was a problem connecting to the database.');
	}
	
	function verify_Username_and_Pass($un, $pwd) {
				
		$query = "SELECT *
				FROM users
				WHERE username = ? AND password = ?
				LIMIT 1";
				
		if($stmt = $this->conn->prepare($query)) {
			$stmt->bind_param('ss', $un, $pwd);
			$stmt->execute();
			
			if($stmt->fetch()) {
				$stmt->close();
				return true;
			}
		}
		
	}
	
	
	function set_sid($un, $sid) {
				
		$query = "UPDATE users SET sessionid='$sid' WHERE username = '$un'";
				
			if ($result = $this->conn->query($query)) {
				return true;
			}
	}
	
	function unset_sid($sid) {
				
		$query = "UPDATE users SET sessionid = NULL WHERE sessionid = '$sid'";
				
			if ($result = $this->conn->query($query)) {
				return true;
			}
	}

}