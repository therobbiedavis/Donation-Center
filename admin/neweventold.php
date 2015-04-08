<?php

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
<link rel="stylesheet" media="screen" type="text/css" href="../css/styles.css?3" />
<link rel="stylesheet" media="screen and (max-device-width: 640px)" type="text/css" href="../css/mobile.css?" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="../js/jquery.validate.min.js"></script>
</head>

<body>
	<div id="nav">

		<ul>

			<li><a href="/admin">Admin Home</a> &bull;</li>

			<li><a href="/admin/newEvent.php">New Event</a> &bull;</li>

			<li><a href="login.php?status=loggedout">Log Out</a></li>

		</ul>

	</div>

<div id="gallery_categories_new">
  <form action="./functions.php?f=new" id="form" method="post">
    <table>
		<tr>
			<td>Event Name</td>
			<td><input type="text"class="required" name="event_name" /></td>
		</tr>
		<tr>
			<td>Paypal Email</td>
			<td><input type="text" class="email required" name="paypal_email" /></td>
		</tr>
		<tr>
			<td>Target Amount</td>
			<td><input type="number" class="numeric required" name="target_amount" /></td>
		</tr>
		<tr>
			<td>End Date (yyyy-mm-dd)</td>
			<td><input type="text" name="end_date" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" name="submitBtn" value="Add" id="submitBtn">
			</td>
		</tr>
    </table>
  </form>
  <script>
  $(document).ready(function(){
    $("#form").validate();
  });
  </script>
</div>

</body>
</html>