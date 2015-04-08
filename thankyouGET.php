<?php

require "admin/config.php";
require "admin/connect.php";

/*
 update: 06/27/2011
  - updated to use cURL for better security, assumes PHP version 5.3
*/

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-synch';

$tx_token = $_GET['tx'];

$pp_hostname = "www.paypal.com"; 
 
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-synch';
 
$tx_token = $_GET['tx'];
$auth_token = "puILmGH8fWRmZ5C7dteodjsKxfn7wHnyE65u1Y48zWYJHZhEw9LT4OepfN8";
$req .= "&tx=$tx_token&at=$auth_token";
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$pp_hostname/cgi-bin/webscr");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
//set cacert.pem verisign certificate path in curl using 'CURLOPT_CAINFO' field here,
//if your server does not bundled with default verisign certificates.
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $pp_hostname"));
$res = curl_exec($ch);
curl_close($ch);
 
if(!$res){
    //HTTP ERROR
}else{
     // parse the data
    $lines = explode("\n", $res);
    $keyarray = array();
    if (strcmp ($lines[0], "SUCCESS") == 0) {
        for ($i=1; $i<count($lines);$i++){
        list($key,$val) = explode("=", $lines[$i]);
        $keyarray[urldecode($key)] = urldecode($val);
    }
    // check the payment_status is Completed
    // check that txn_id has not been previously processed
    // check that receiver_email is your Primary PayPal email
    // check that payment_amount/payment_currency are correct
    // process payment
    $firstname = $keyarray['first_name'];
    $lastname = $keyarray['last_name'];
    $itemname = $keyarray['item_name'];
    $amount = $keyarray['payment_gross'];
    $email = $keyarray['payer_email'];

    }
    else if (strcmp ($lines[0], "FAIL") == 0) {
        // log for manual investigation
    }
}


if(isset($_POST['submitform']) && isset($_GET['tx']))
{
	$_POST['nameField'] = esc($_POST['nameField']);
	$_POST['emailField'] =  esc($_POST['emailField']);
	$_POST['messageField'] = esc($_POST['messageField']);
	
	$error = array();
	
	//if(mb_strlen($_POST['nameField'],"utf-8")<2)
	//{
	//	$error[] = 'Please fill in a valid name.';
	//}
	
	//if(mb_strlen($_POST['messageField'],"utf-8")<2)
	//{
	//	$error[] = 'Please fill in a longer message.';
	//}
	
	//if(!validateURL($_POST['websiteField']))
	//{
	//	$error[] = 'The URL you entered is invalid.';
	//}

	$errorString = '';
	if(count($error))
	{
		$errorString = join('<br />',$error);
	}
	else
	{
		$link->query("	INSERT INTO dc_comments (transaction_id, name, email, message)
						VALUES (
							'".esc($_GET['tx'])."',
							'".$_POST['nameField']."',
							'".$_POST['emailField']."',
							'".$_POST['messageField']."'
						)");
		
		if(mysqli_affected_rows($link)==1)
		{
			$messageString = 'You were added to our donor list!';
		}
		mysqli_close($link);
	}
}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width">
<title>Thank you!</title>

<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" media="screen" type="text/css" href="css/styles.css?2" />
<link rel="stylesheet" media="screen and (max-device-width: 640px)" type="text/css" href="css/mobile.css?2" />

<script type="text/javascript">
function formfocus()
{
     document.getElementById("messageField").focus();
}

window.onload = formfocus;
</script>

</head>

<body class="thankyouPage">

<div id="main-thankyou">
    <h1>Thank you!</h1>
    <h2>Leave us a comment.</h2>

	<div class="lightSection">
    	<form name="comments" action="" method="post">
        	<div class="field">
                
                <input type="hidden" id="nameField" name="nameField" value="<?php echo ("$firstname $lastname"); ?>"/>
			</div>
            
            <div class="field">
                
                <input type="hidden" id="emailField" name="emailField" value="<?php echo ("$email"); ?>"/>
			</div>
            
			<div class="field">
                <label for="messageField">Message</label>
                <textarea rows="5" name="messageField" id="messageField"></textarea>
            </div>
            
            <div class="button">
            	<input type="submit" value="Submit" />
                <input type="hidden" name="submitform" value="1" />
                <input type="hidden" name="txn_id" value="<?php echo $_POST['tx'];?>" />
            </div>
			<p>We store names and emails for contest usage only.</p>
        </form>
        
        <?php
		if($errorString)
		{
			echo '<p class="error">'.$errorString.'</p>';
		}
		else if($messageString)
		{
			echo '<p class="success">'.$messageString.'</p>';
		}
		?>
        
    </div>


</body>
</html>


<?php

function esc($str)
{
	global $link;
	
	if(ini_get('magic_quotes_gpc'))
			$str = stripslashes($str);
	
	return $link->real_escape_string(htmlspecialchars(strip_tags($str)));
}

function validateURL($str)
{
	return preg_match('/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&amp;:\/~\+#]*[\w\-\@?^=%&amp;\/~\+#])?/i',$str);
}
?>