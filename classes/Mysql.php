<?php
require_once '/../includes/config.php';class Mysql {
	private $conn;
	function __construct() {		$this->conn =  new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASSWORD) or die('There was a problem connecting to the database.');	}
	function verify_Username_and_Pass($un, $pwd) {
		$params = array(':un' => $un);		$password = "SELECT password FROM users WHERE username = :un LIMIT 1";		$hashAndSalt = $this->conn->prepare($password);		$hashAndSalt->execute($params);		$passhash = $hashAndSalt->fetch(PDO::FETCH_ASSOC);		if (password_verify($pwd, $passhash['password'])) {			$options = [    		'cost' => 8,  		];			if (password_needs_rehash($passhash['password'], PASSWORD_BCRYPT)){				$password = password_hash($pwd, PASSWORD_BCRYPT, $options);				$params = array(':un' => $un, ':pwdrh' => $password);
				$pwdrh = "UPDATE users SET password = :pwdrh WHERE username = :un";
				$rehash = $this->conn->prepare($pwdrh);

				if ($rehash->execute($params)) {
					$this->conn = null;
					return true;

				} else {}
			}			return true;		}	}	function set_sid($un, $sid) {
		$params = array(':un' => $un, ':sid' => $sid);

		$query = "UPDATE users SET sessionid='$sid' WHERE username = '$un'";		$set = $this->conn->prepare($query);

			if ($set->execute($params)) {
				return true;
			}
	}	function unset_sid($sid) {
		$params = array(':sid' => $sid);

		$query = "UPDATE users SET sessionid = NULL WHERE sessionid = '$sid'";
		$unset = $this->conn->prepare($query);

			if ($unset->execute($params)) {

				return true;

			}

	}
}
