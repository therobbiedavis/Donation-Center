<?php
/*
require "admin/config.php";
require "admin/connect.php";
*/
$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$p = parse_str(parse_url($url, PHP_URL_QUERY), $array);
$eid = $array['eid'];
/*
$result1 = $link->query("SELECT dc_events.title, dc_events.event_id, dc_events.paypal_email, dc_events.targetAmount, dc_events.startDate, dc_events.endDate FROM dc_events WHERE dc_events.event_id = '$eid'");
$result2 = $link->query("SELECT SUM(dc_donations.donation_amount) AS 'collectedAmount', COUNT(dc_donations.donation_amount) AS 'contributors' FROM dc_donations, dc_events WHERE dc_events.event_id = dc_donations.event_id AND dc_events.event_id = '$eid'");

while($row = mysqli_fetch_assoc($result1))
{
	foreach($row as $fieldname => $fieldvalue)
	{
    	if ($fieldname == 'title'){
	    	$title = $fieldvalue;
    	}
		
		if ($fieldname == 'paypal_email'){
			$paypal_email = $fieldvalue;
		}
    	
    }  
}

$clean_title = preg_replace("/\ (?=[a-z\d])/i", "+", $title);
*/

$amount = $_POST['amount'];
$os0 = $_POST['os0'];
$paypal_email = $_POST['business'];
$custom = $_POST['custom'];
$clean_title = $_POST['item_name'];

$custom = $eid;


header("Location: https://www.paypal.com/cgi-bin/webscr?&cmd=_xclick&business=$paypal_email&custom=$custom&on0=Incentive&os0=$os0&amount=$amount&item_name=$clean_title&no_shipping=1&no_note=1&rm=2&cbt=Leave+a+Comment&notify_url=http%3A%2F%2Fdonate.thespeedgamers.com%2Fipn.php?eid=$eid&return=https%3A%2F%2Fdonate.thespeedgamers.com%2Fthankyou.php");
//("https://www.paypal.com/cgi-bin/webscr?&cmd=_xclick&business=$paypal_email&custom=$custom&os0=$os0&amount=$amount&item_name=$clean_title&no_shipping=1&no_note=1&rm=2&cbt=Leave+a+Comment&notify_url=http%3A%2F%2Fdonate.thespeedgamers.com%2Fipn.php?eid=$eid&return=https%3A%2F%2Fdonate.thespeedgamers.com%2Fthankyou.php");
?>