<?php

require "config.php";
require "connect.php";

require_once 'classes/Membership.php';
$membership = New Membership();

$membership->confirm_Member();

// Determining the URL of the page:
$url = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"]);

// Fetching the number and the sum of the donations:
list($number,$sum) = mysql_fetch_array(mysql_query("SELECT COUNT(*),SUM(donation_amount) FROM dc_donations"));


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Donation Center</title>

<link rel="stylesheet" type="text/css" href="styles.css" />

</head>

<body>

<div id="main">
    
    <div class="clear"></div>
    
    <div class="donors">
        
        <div class="list">
        
                <?php
			$list = mysql_query("SELECT dc_comments.name, dc_donations.donor_email, dc_donations.donation_amount, dc_donations.dt FROM dc_comments, dc_donations WHERE dc_comments.transaction_id = dc_donations.transaction_id AND dc_donations.event_id = 1 ORDER BY dc_donations.dt DESC");
			
			// Building the Donor List:
			
			if(mysql_num_rows($list))
			{
			?>
			<table>
				<tr>
				<th>Name:</th>
				<th>Email:</th>
				<th>Amount:</th>
				<th>Date:</th>
				</tr>
			<?php
				while($row = mysql_fetch_assoc($list))
				{
					?>
                    <tr>
                    	<td>
                    		<?php echo $row['name'] ?>
                    	</td>
                    	<td>
                    		<?php echo $row['donor_email'] ?>
                    	</td>
                    	<td>
                    		<?php 
                    		setlocale(LC_MONETARY, 'en_US');
                    		echo money_format('%.2n', $row['donation_amount']); ?>
                    	</td>
                    	<td>
                    		<?php
                    		echo date('F j, Y  g:i a', strtotime($row["dt"]));
                    		?>
                    	</td>
                    </tr>
                    
					<?php
				}
			}
		?>
			</table>
            
        </div> <!-- Closing the comments div -->
        
    </div> <!-- Closing the donors div -->
    
</div> <!-- Closing the main div -->


</body>
</html>
