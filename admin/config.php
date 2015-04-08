<?php

// Fill your PayPal email below.
// This is where you will receive the donations.

$myPayPalEmail = 'paypal@email.com';


// The paypal URL:
$payPalURL = 'https://www.paypal.com/cgi-bin/webscr';

$event_id = 514;


// Your goal in USD:
//$goal = 80;

// Your Event name:
//$event = "Mega Man Marathon for Rocking H Ranch";


// Demo mode is set - set it to false to enable donations.
// When enabled PayPal is bypassed.

$demoMode = true;

if($demoMode)
{
	$payPalURL = 'demo_mode.php';
}
?>