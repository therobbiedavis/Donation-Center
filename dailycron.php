<?php

require "admin/config.php";
require "admin/connect.php";

$eventid = mysqli_query($link, "SELECT event_id FROM dc_events");

while ($row = $userid->fetch_assoc()) {
$getEvents = mysqli_query($link, "SELECT * FROM `dc_events` WHERE user_id ='".$row["id"]."'");
}
$event = array();
while ($event = mysqli_fetch_assoc($getEvents)) {
	$events[] = $event;
}
?>