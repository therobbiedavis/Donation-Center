<?php

require "admin/config.php";
require "admin/connect.php";
//print_r($_GET);
//echo "<br>\n";
//print_r($_POST);
echo $HTTP_REFERER; 



//Check to see which method to use either POST or GET
if(empty($_POST['txn_id'])) {
//Using GET
	$tx_token = $_GET['tx'];
	$eid = $_GET['cm'];
 
	$info = mysqli_query($link, "SELECT * FROM dc_donations WHERE transaction_id = '".$tx_token."' ORDER BY dt DESC LIMIT 1");
		while ($row = $info->fetch_assoc()) {	
			$name = $row['name'];
			$email = $row['donor_email'];
		}

	
} else {
 //Using POST
	
    $post = 1;


     // parse the data
    // check the payment_status is Completed
    // check that txn_id has not been previously processed
    // check that receiver_email is your Primary PayPal email
    // check that payment_amount/payment_currency are correct
    // process payment
	$tx_token = $_POST['txn_id'];
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $itemname = $_POST['item_name'];
    $amount = $_POST['payment_gross'];
    $email = $_POST['payer_email'];
	$name = $firstname." ".$lastname;
    $eid = $_POST['custom'];
	

	$incentivename = $_POST['option_selection1'];
	

	//echo $incentivename;
 }

//Query for incentives
$query = "SELECT id FROM dc_incentives WHERE `name` LIKE '$incentivename' AND `event_id` = '$eid'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result, MYSQLI_BOTH);
$incentiveid = $row['id'];

//echo $incentiveid;
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-synch';


/*
$pp_hostname = "www.paypal.com"; 
 
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-synch';
 
$auth_token = "puILmGH8fWRmZ5C7dteodjsKxfn7wHnyE65u1Y48zWYJHZhEw9LT4OepfN8";
$req .= "&tx=$tx_token";
//$req .= "&tx=$tx_token&at=$auth_token";
 
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
*/

	/*
    else if (strcmp ($lines[0], "FAIL") == 0) {
        // log for manual investigation
    }
}
	*/
 


if(isset($_POST['submitform']) )
{

	//if(mb_strlen($_POST['nameField'],"utf-8")<2)
	//{
		//$error[] = 'Please fill in a valid name.';
	//}
	
	//if(mb_strlen($_POST['messageField'],"utf-8")<2)
	//{
	//	$error[] = 'Please fill in a longer message.';
	//}
	
	if(empty($tx_token))
	{
		$error[] = 'Invalid Transaction ID.';

	}
	//if(!validateURL($_POST['websiteField']))
	//{
	//	$error[] = 'The URL you entered is invalid.';
	//}

	$errorString = '';
	if(count($error))
	{
		$errorString = join('<br />',$error);
	} else {
		$message = urlencode($_POST['messageField']);
		
		$query = "INSERT INTO dc_comments (transaction_id, name, email, message, incentive) VALUES ('".esc($tx_token)."', '".esc($_POST['nameField'])."', '".esc($_POST['emailField'])."', '".mysqli_real_escape_string($link, $message)."','".esc($_POST['incentive_id'])."')";
		mysqli_query($link, $query);
		
		if(mysqli_affected_rows($link)==1)
		{
			$messageString = 'Your comment has been added! You may now close this.';

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
<link rel="stylesheet" media="screen" type="text/css" href="css/styles.css?10" />
<link rel="stylesheet" type="text/css" media="only screen and (max-device-width: 480px)" href="css/mobile.css?28" />

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
			
		<? if (!empty($name) && !isset($messageString)) { ?>
			<input type="hidden" id="nameField" name="nameField" value="<?php echo esc($name);?>"/>
		<? } else { ?>
			<label for="nameField">Name</label>
			<input type="text" id="nameField" name="nameField" value=""/>
		<? } ?>

			</div>
            
            <div class="field">
 <? if (!empty($email) && !isset($messageString)) { ?>
	<input type="hidden" id="emailField" name="emailField" value="<?php echo esc($email); ?>"/>
<? } else { ?>
<label for="emailField">Email</label>
	<input type="text" id="emailField" name="emailField" value=""/>
	<? } ?>                
			</div>
            
			<div class="field">
                <label for="messageField">Message</label>
                <textarea rows="5" name="messageField" id="messageField"></textarea>
            </div>
            
            <div class="button">
            	<input type="submit" value="Submit" />
                <input type="hidden" name="submitform" value="1" />
                <input type="hidden" id="txn_id" name="txn_id" value="<?php echo $tx_token;?>" />
				<input type="hidden" id="incentive_id" name="incentive_id" value="<?php echo $incentiveid;?>" />
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