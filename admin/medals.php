<?
require "config.php";
require "connect.php";

require_once '../classes/Membership.php';
$membership = New Membership();

$membership->confirm_Member();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=0.5, max-scale=1.0">
<title>Donation Center</title>

<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" media="screen" type="text/css" href="../css/styles.css?10" />
<link rel="stylesheet" media="screen and (max-device-width: 640px)" type="text/css" href="../css/mobile.css?" />

</head>

<body>

</body>
</html>