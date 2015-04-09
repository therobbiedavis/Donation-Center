<?php

require_once "connect.php";

require_once '../classes/Membership.php';
$membership = New Membership();

$membership->confirm_Member();
$sid = session_id();
$params = array(':sid' => $sid);
$userid = $link->prepare("SELECT id FROM users WHERE sessionid = :sid");
$userid->execute($params);

while ($row = $userid->fetch(PDO::FETCH_ASSOC)) {
	$params = array(':id' => $row["id"]);
	$getEvents = $link->prepare("SELECT * FROM `dc_events` WHERE user_id = :id");
	$getEvents->execute($params);
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
		<li><a href="new-event.php">New Event</a> &bull;</li>
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
<?php
while ($event = $getEvents->fetchAll()) {
		if ($event != null) {
					foreach ($event as $g) {
						$params = array(':eid' => $g['event_id']);
						$sum = $link->prepare("SELECT SUM(donation_amount) AS Total, COUNT(name) AS Donors FROM dc_donations WHERE event_id = :eid");
						$sum->execute($params);
?>
    <tr>
      <td><?=$g['title']?></td>
	  <td><?php if($sum->rowCount() > 0)
			{
			?>
			<?php
				while($row = $sum->fetch(PDO::FETCH_ASSOC))
				{
                    		 echo "Total: $".number_format($row['Total'], 0);

				}
			}
			?>
			</td>
			<td><a href="eventDetails.php?eid=<?=$g['event_id']?>">View</a></td>
			<td><a href="edit.php?eid=<?=$g['event_id']?>">Edit</a></td>
			<td><a style="color:red" href="delete.php?eid=<?=$g['event_id']?>">Delete</a></td>
    </tr>
    <?php }
} else { ?>
    <tr>
      <td>No events found. Please create a new event.</td>

    </tr>
<?php }
}?>
  </table>
</div>

</body>
</html>
