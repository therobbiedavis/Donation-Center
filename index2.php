<?php

require "admin/config.php";
require "admin/connect.php";


$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$p = parse_str(parse_url($url, PHP_URL_QUERY), $array);
$eid = $array['eid'];


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
//header("Location: https://www.paypal.com/cgi-bin/webscr?&cmd=_xclick&business=$paypal_email&custom=$eid&amount=5&item_name=$clean_title&no_shipping=1&no_note=1&rm=2&cbt=Leave+a+Comment&notify_url=http%3A%2F%2Fdonate.thespeedgamers.com%2Fipn.php?eid=$eid&return=https%3A%2F%2Fdonate.thespeedgamers.com%2Fthankyou.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=0.5, max-scale=1.0">
<title>Donation Center</title>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" media="screen" type="text/css" href="../css/styles.css?10" />
<link rel="stylesheet" media="screen and (max-device-width: 640px)" type="text/css" href="../css/mobile.css?" />

</head>
<body style="margin:0 auto;text-align:center;">

    <h2>How much would you like to donate?</h2>
	<div style="position:relative;vertical-align:middle;">
<form style="margin: 30px;" action="redirect.php" method="post">
<input type="hidden" name="business" value="<?php echo $paypal_email; ?>">
<input type="hidden" name="custom" value="<?php echo $eid; ?>">
<input type="hidden" name="item_name" value="<?php echo $clean_title; ?>">
<div class="preset">
<select id="preset_amount" style="vertical-align:text-top;width:150px;" name="amount" id="amount">
<option value="5" selected >$5.00</option>
<option value="10">$10.00</option>
<option value="25">$25.00</option>
<option value="50">$50.00</option>
</select>
</div>
<br/>
<span style="font-size:18px;">OR<span>
<br/>
<span style="font-size:14px;">Input your amount (Working on it):</span>
<br/>
<div class="custom">
<input id="custom_amount" style="padding:3px;"type="number" name="amount" value="" onclick="document.getElementById('custom_amount').disabled = false;">
</div>
<br/>

<br/>
<input style="padding:3px; vertical-align:text-bottom;" type="submit" value="Submit">
</form>

<script type="text/javascript">

  $("div.custom").click(function() {
document.getElementById("preset_amount").disabled = true;
document.getElementById("custom_amount").disabled = false;
  })

  $("div.preset").click(function() {
document.getElementById("custom_amount").disabled = true;
document.getElementById("preset_amount").disabled = false;
  })

</script>
</div>
</body>
</html>