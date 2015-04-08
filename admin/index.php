<?php

require "config.php";
require "connect.php";

require_once '../classes/Membership.php';
$membership = New Membership();

$membership->confirm_Member();
session_start();
$sid = session_id();
$userid = mysqli_query($link, "SELECT id FROM users WHERE sessionid = '$sid'");

while ($row = $userid->fetch_assoc()) {
$getEvents = mysqli_query($link, "SELECT * FROM `dc_events` WHERE user_id ='".$row["id"]."'");
}
$event = array();
while ($event = mysqli_fetch_assoc($getEvents)) {
	$events[] = $event;
}


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
<div id="nav">
	<ul>
		<li><a href="/admin">Admin Home</a> &bull;</li>
		<li><a href="/admin/new-event.php">New Event</a> &bull;</li>
		<li><a href="login.php?status=loggedout">Log Out</a></li>
	</ul>
</div>


<div id="events">
 <table>
    <tr>
      <th>Event Name</th>
			<th></th>
			<th></th>
			<th></th>
    </tr>
    <?php if ($events != null) {
					foreach ($events as $g) {?>
<?
$sum = $link->query("SELECT SUM(donation_amount) AS Total, COUNT(name) AS Donors FROM dc_donations WHERE event_id = '" . mysqli_real_escape_string($link, $g['event_id']) . "' ");
?>
    <tr>
      <td><?=$g['title']?></td>
	  <td><?php if(mysqli_num_rows($sum))
			{
			?>
			<?php
				while($row = mysqli_fetch_assoc($sum))
				{
                    		 echo money_format('Total: $%.2n', $row['Total']); 
                    

				}
			}
			?>
			</td>
			<td><a href="eventDetails.php?id=<?=$g['event_id']?>">View</a></td>
			<td><a href="edit.php?id=<?=$g['event_id']?>">Edit</a></td>
			<td><a style="color:red" href="delete.php?id=<?=$g['event_id']?>">Delete</a></td>
    </tr>
    <? }
} else { ?>
    <tr>
      <td>No events found. Please create a new event.</td>

    </tr>
<?php } ?>
  </table>
</div>

</body>
</html>