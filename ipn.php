<?php
/**
 *  PHP-PayPal-IPN Example
 *
 *  This shows a basic example of how to use the IpnListener() PHP class to 
 *  implement a PayPal Instant Payment Notification (IPN) listener script.
 *
 *  For a more in depth tutorial, see my blog post:
 *  http://www.micahcarrick.com/paypal-ipn-with-php.html
 *
 *  This code is available at github:
 *  https://github.com/Quixotix/PHP-PayPal-IPN
 *
 *  @package    PHP-PayPal-IPN
 *  @author     Micah Carrick
 *  @copyright  (c) 2011 - Micah Carrick
 *  @license    http://opensource.org/licenses/gpl-3.0.html
 */
 
require "admin/config.php";
require "admin/connect.php";
 
/*
Since this script is executed on the back end between the PayPal server and this
script, you will want to log errors to a file or email. Do not try to use echo
or print--it will not work! 

Here I am turning on PHP error logging to a file called "ipn_errors.log". Make
sure your web server has permissions to write to that file. In a production 
environment it is better to have that log file outside of the web root.
*/
ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');


// instantiate the IpnListener class
include('ipnlistener.php');
$listener = new IpnListener();


/*
By default the IpnListener object is going  going to post the data back to PayPal
using cURL over a secure SSL connection. This is the recommended way to post
the data back, however, some people may have connections problems using this
method. 

To post over standard HTTP connection, use:
$listener->use_ssl = false;

To post using the fsockopen() function rather than cURL, use:
$listener->use_curl = false;
*/

/*
The processIpn() method will encode the POST variables sent by PayPal and then
POST them back to the PayPal server. An exception will be thrown if there is 
a fatal error (cannot connect, your server is not configured properly, etc.).
Use a try/catch block to catch these fatal errors and log to the ipn_errors.log
file we setup at the top of this file.

The processIpn() method will send the raw data on 'php://input' to PayPal. You
can optionally pass the data to processIpn() yourself:
$verified = $listener->processIpn($my_post_data);
*/
try {
    $listener->requirePostMethod();
    $verified = $listener->processIpn();
} catch (Exception $e) {
    error_log($e->getMessage());
    exit(0);
}


/*
The processIpn() method returned true if the IPN was "VERIFIED" and false if it
was "INVALID".
*/
if ($verified) {
    /*
    Once you have a verified IPN you need to do a few more checks on the POST
    fields--typically against data you stored in your database during when the
    end user made a purchase (such as in the "success" page on a web payments
    standard button). The fields PayPal recommends checking are:
    
        1. Check the $_POST['payment_status'] is "Completed"
	    2. Check that $_POST['txn_id'] has not been previously processed 
	    3. Check that $_POST['receiver_email'] is your Primary PayPal email 
	    4. Check that $_POST['payment_amount'] and $_POST['payment_currency'] 
	       are correct
    
    Since implementations on this varies, I will leave these checks out of this
    example and just send an email using the getTextReport() method to get all
    of the details about the IPN.  
    */
    
    // 1. Make sure the payment status is "Completed" 
    if ($_POST['payment_status'] != 'Completed') { 
        // simply ignore any IPN that is not completed
        exit(0); 
    }

    // 5. Ensure the transaction is not a duplicate.

    $txn_id = $link->real_escape_string($_POST['txn_id']);
    $sql = "SELECT COUNT(*) FROM dc_donations WHERE transaction_id = '$txn_id'";
    $r = $link->query($sql);
    
    if (!$r) {
        error_log(mysqli_error($link));
        exit(0);
    }
    
	$exist = mysqli_fetch_assoc($r);
	mysqli_free_result($r);
	$exists = $exist['COUNT(*)'];
    
    if ($exists > 0) {
        $errmsg .= "'txn_id' has already been processed: ".$_POST['txn_id']."\n";
    }
    
    if (!empty($errmsg)) {
    
        // manually investigate errors from the fraud checking
        $body = "IPN failed fraud checks: \n$errmsg\n\n";
        $body .= $listener->getTextReport();
        mail('localretard@gmail.com', 'IPN Fraud Warning', $body);
        
    } else {
    
    
    $amount = $_POST['mc_gross'];
    $first_name = esc($_POST['first_name']);
	$event_id = esc($_POST['custom']);
    $last_name = esc($_POST['last_name']);
    $full_name = ($first_name." ".$last_name);
	$incentivename = $_POST['option_selection1'];
	
	$query = "SELECT id FROM dc_incentives WHERE `name` LIKE '$incentivename' AND `event_id` = '$event_id'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result, MYSQLI_BOTH);
	$incentiveid = $row['id'];
		
	$link->query("INSERT INTO dc_donations (transaction_id, name, donor_email,donation_amount,original_request,event_id,incentive_id)
						VALUES (
							'".esc($_POST['txn_id'])."',
							'$full_name',
							'".esc($_POST['payer_email'])."',
							".(float)$amount.",
							'".esc(http_build_query($_POST))."',
							'".$event_id."',
							'".$incentiveid."'
						)");
						
	file_get_contents('http://donate.thespeedgamers.com/xml.php?eid='.$event_id);
	exec('/usr/local/php5/bin/php /home/tsgweb/donate.thespeedgamers.com/statistics/stream-stats-cron.php');
	}

} else {
    /*
    An Invalid IPN *may* be caused by a fraudulent transaction attempt. It's
    a good idea to have a developer or sys admin manually investigate any 
    invalid IPN.
    */
    mail('localretard@gmail.com', 'Invalid IPN', $listener->getTextReport());
}

function esc($str)
{
	global $link;
	return $link->real_escape_string($str);
}

?>