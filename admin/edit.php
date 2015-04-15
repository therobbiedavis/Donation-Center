<?php

require "config.php";
require "connect.php";

require_once '../classes/Membership.php';
$membership = New Membership();

$membership->confirm_Member();

$admin_title = 'Manage Gallery Categories';
$admin_subtitle = 'Edit Gallery_category';

$eid = $_GET['eid'];
$params = array(':eid' => $_GET['eid']);
$event = $link->prepare("SELECT * FROM `dc_events` WHERE `event_id` = :eid");
$event->execute($params);

$events = $event->fetch(PDO::FETCH_ASSOC);

$eventtitle = $events['title'];
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
  <form action="functions.php?f=edit" method="post">
  <input type="hidden" name="eid" value="<?php echo $eid; ?>" id="eid">
    <table>
		<tr>
			<td>Event Name</td>
			<td><input type="text" name="event_name" value="<?=$events['title'] ?>" /></td>
		</tr>
		<tr>
			<td>Target Amount <span style="font-size:0.8em">(enter zero for no target)</span></td>
			<td><input type="number" class="numeric required" name="target_amount" value="<?=$events['targetAmount'] ?>" /></td>
		</tr>
		<tr>
			<td>Image URL</td>
			<td><input type="text" name="image_url" value="<?=$events['image_url'] ?>" /></td>
		</tr>

		<tr>
			<td colspan="2">
				<input type="submit" name="submitBtn" value="Edit" id="submitBtn">
			</td>
		</tr>
    </table>
  </form>
</div>
