<?php
require 'Mysql.php';
class Membership {
	function validate_user($un, $pwd) {
		$mysql = New Mysql();
		$ensure_credentials = $mysql->verify_Username_and_Pass($un, $pwd);
		if($ensure_credentials) {
			$_SESSION['status'] = 'authorized';
			header("location: index.php");
		} else return "Please enter a correct username and password";
	}
	function log_User_Out() {
		if(isset($_SESSION['status'])) {
			unset($_SESSION['status']);
			$sid = session_id();
			$mysql = New Mysql();
			$mysql->unset_sid($sid);
			if(isset($_COOKIE[session_name()]))  {
				setcookie("PHPSESSID", "", time() - 3600, "/");
				session_destroy();
			}
		}
	session_start();
			$sid = session_id();
			$mysql = New Mysql();
			if ($mysql->unset_sid($sid)) {
				return true;
			}
	}
	function sid($un){
		if(!isset($_SESSION)){    	session_start();		}
		$sid = session_id();
		$mysql = New Mysql();
		if ($mysql->set_sid($un, $sid)) {
			return "Success!";
		}
	}
	function confirm_Member() {
		session_start();
		if($_SESSION['status'] !='authorized') header("location: login.php");
	}
}
