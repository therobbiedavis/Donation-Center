<?php


require_once '/../includes/config.php';
class Mysql {
	private $conn;


	function __construct() {		$this->conn =  new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASSWORD) or die('There was a problem connecting to the database.');	}

	function verify_Username_and_Pass($un, $pwd) {
		$params = array(':un' => $un, ':pwd' => $pwd);
		$query = "SELECT *
				FROM users
				WHERE username = :un AND password = :pwd
				LIMIT 1";

		if($stmt = $this->conn->prepare($query)) {
			$stmt->execute($params);

			if($stmt->fetch()) {
				$this->conn = null;
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
