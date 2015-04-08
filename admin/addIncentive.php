<?php

require "config.php";
require "connect.php";

require_once '../classes/Membership.php';
$membership = New Membership();

$membership->confirm_Member();

$admin_title = 'Manage Gallery Categories';
$admin_subtitle = 'Edit Gallery_category';

$id = $_GET['id'];
$incentives = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM `dc_incentives` WHERE `event_id` = '$id'"));


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

</head>
<body>
<div id="nav">
	<ul>
		<li><a href="/admin">Admin Home</a> &bull;</li>
		<li><a href="/admin/new-event.php">New Event</a> &bull;</li>
		<li><a href="login.php?status=loggedout">Log Out</a></li>
	</ul>
</div>

<div id="gallery_categories_edit">
  <form action="./functions.php?f=add_incentive" method="post">
  <input type="hidden" name="id" value="<?=$id?>" id="id">
    <table>
		<tr>
			<td>Incentive Name</td>
			<td><input type="text" name="name" value="" /></td>
		</tr>
		<tr>
			<td>Amount<span style="font-size:0.8em"></span></td>
			<td><input type="number" class="numeric required" name="amount" value="" /></td>
		</tr>
		
		<tr>
			<td colspan="2">
				<input type="submit" name="submitBtn" value="Submit" id="submitBtn">
			</td>
		</tr>
    </table>
  </form>
</div>

