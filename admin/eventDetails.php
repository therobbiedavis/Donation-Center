<?php

require "config.php";
require "connect.php";

require_once '../classes/Membership.php';
$membership = New Membership();

$membership->confirm_Member();

date_default_timezone_set('America/Chicago');

//Setting Local Monetary
setlocale(LC_MONETARY, 'en_US');

// Determining the URL of the page:
$url = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"]);
$id = $_GET['id'];

//Setting up queries
//Query for comments
$comments = $link->query("SELECT dc_comments.name, dc_comments.email, dc_comments.message, dc_donations.dt, dc_donations.donation_amount, dc_comments.incentive, dc_donations.event_id, dc_events.event_id, dc_events.current FROM dc_comments, dc_donations, dc_events WHERE dc_donations.transaction_id = dc_comments.transaction_id AND dc_donations.event_id = dc_events.event_id AND dc_events.event_id = '" . mysqli_real_escape_string($link, $id) . "' ORDER BY id DESC");

//Query for list
$list = $link->query("SELECT dc_donations.name, dc_donations.donor_email, dc_donations.donation_amount, dc_donations.dt, dc_events.event_id FROM dc_donations , dc_events WHERE dc_donations.event_id = dc_events.event_id AND dc_events.event_id = '" . mysqli_real_escape_string($link, $id) . "' ORDER BY dc_donations.dt DESC");

//Query for incentives
$incentives = $link->query("SELECT dc_incentives.id, dc_incentives.name, dc_incentives.hidden, dc_incentives.incentive, dc_incentives.event_id, dc_events.event_id FROM dc_incentives, dc_events WHERE dc_incentives.event_id = dc_events.event_id AND dc_events.event_id = '" . mysqli_real_escape_string($link, $id) . "' ORDER BY dc_incentives.dt DESC");

//Query for sum and total donors
$sum = $link->query("SELECT SUM(donation_amount) AS Total, COUNT(name) AS Donors FROM dc_donations WHERE event_id = '" . mysqli_real_escape_string($link, $id) . "' ");

$incentivesum = $link->query("SELECT dc_incentives.name, SUM(donation_amount) AS Total FROM dc_comments, dc_donations, dc_incentives WHERE dc_donations.transaction_id = dc_comments.transaction_id AND dc_donations.event_id = '" . mysqli_real_escape_string($link, $id) . "' AND dc_comments.incentive = dc_incentives.id and dc_comments.incentive = '2'");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=0.5, max-scale=1.0">
		<title>Donation Center</title>

		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" media="screen" type="text/css" href="../css/styles.css?4" />
		<link rel="stylesheet" media="screen and (max-device-width: 640px)" type="text/css" href="../css/mobile.css?1" />

	</head>

	<body>
		<div id="nav">
			<ul>
				<li><a href="/admin">Admin Home</a> &bull;</li>
				<li><a href="/admin/new-event.php">New Event</a> &bull;</li>
				<li><a href="login.php?status=loggedout">Log Out</a></li>
			</ul>
		</div>

<div id="main">
    <script src="//donate.thespeedgamers.com/widget.js?format=forum&eid=<?php echo $id; ?>" type="text/javascript"></script>

	
	<div id="incentives">
	<h3>Incentives <a href='addIncentive.php?id=<?php echo $id ?>'>Add One</a></h3>
	<?php
			if(mysqli_num_rows($incentives))
			{ ?>
							<table>
				<tr>
				<th>Name:</th>
				<th>Amount:</th>
				<th>Total:</th>
				</tr>
			<?php
				while($row = mysqli_fetch_assoc($incentives))
				{ 
				$incentivesum = $link->query("SELECT dc_incentives.name, SUM(donation_amount) AS Total FROM dc_comments, dc_donations, dc_incentives WHERE dc_donations.transaction_id = dc_comments.transaction_id AND dc_donations.event_id = '" . mysqli_real_escape_string($link, $id) . "' AND dc_comments.incentive = dc_incentives.id and dc_comments.incentive = '".$row['id']."'");
					while($row2 = mysqli_fetch_assoc($incentivesum)) {
				?>

				<tr>
                    	<td>
				<?php echo stripslashes($row['name']);?>
					</td>
					<td>
				<?php echo stripslashes('$'.$row['incentive']);?>
					</td>
					                    	<td>
				<?php echo stripslashes('$'.$row2['Total']);?>
					</td>
					<td>
					<?php if ($row["hidden"] == 1) {?>
						<a href="./functions.php?f=hideincent&iid=<?php echo $row['id'];?>&hide=0">Unhide</a>
					<? } else { ?>
						<a href="./functions.php?f=hideincent&iid=<?php echo $row['id'];?>&hide=1">Hide</a>
					<? } ?>
					</td>
					<td>
					<a style="color:red" href="./functions.php?f=deleteincent&iid=<?php echo $row['id'];?>">Delete</a>
					</td>
                   
                    
					<?php
				} ?>
				
				<?php }
				
			} else { ?>
			
			<p>
				<?php echo "No Incentives Yet. <a href='addIncentive.php?id=". $id ."'>Add One</a>";?>
			</p>
			<?php } ?>
			</table>
	</div>
	
    <div class="donors">
	<a name="donors"></a>
        <h3>The Comments <span style="font-size:0.3em"><a href="#list">View the donations</a></span></h3>
        
        <div class="comments">
        
        <?php

			
			// Building the Donor List:
			
			if(mysqli_num_rows($comments))
			{
				while($row = mysqli_fetch_assoc($comments))
				{
					?>
                    
                       	<div class="entry">
                            <p class="comment">
                            <?php 
								echo stripslashes(nl2br(urldecode($row['message']))); // Converting the newlines of the comment to <br /> tags
							?>
							<?php
							$incentivename = $link->query("SELECT name FROM dc_incentives WHERE event_id = '" . mysqli_real_escape_string($link, $id) . "' and id = '". $row['incentive'] . "'");
							$name = mysqli_fetch_assoc($incentivename);
							if ($name > 0) {
							echo "<br/><span style='font-size:12px'>Donated for " .$name['name']. "</span>";
							}
							?>
                            <span class="tip"></span>
                            </p>
                            
                            <div class="name">
                                <?php echo $row['name']?> <a class="url" href="mailto:<?php echo $row['email']?>">(Email)</a> - Donated <?php echo money_format('%.2n', $row['donation_amount']);?> - <span class="url"><? echo date('F j  -  g:i a', strtotime($row["dt"])); ?></span>
                            </div>
                        </div>
                    
					<?php
				}
				
			} else { ?>
			<p class="comment">
				<?php echo "No Comments Yet";?>
			</p>
			<?php } ?>
        
            
        </div> <!-- Closing the comments div -->
        
		
<?php
			// Building the Donor List:
			
			if(mysqli_num_rows($list))
			{
			$fp = fopen('download/'.$id.'.csv', 'w');
				while($row = mysqli_fetch_assoc($list)) { 
					$array[] = $row;
				}
			
				foreach ($array as $fields) {
					fputcsv($fp, $fields);
				}
			
			fclose($fp);
			?>
			
        
        <h3><a name="list"></a>The Donor List <span style="font-size:0.5em">(<a href="download/<?php echo $id ?>.csv">Export list to CSV</a>)</span> <span style="font-size:0.3em"><a href="#donors">View the comments</a></span></h3>
        <div class="list">
        

			
			<table>
				<tr>
				<th>Name:</th>
				<th>Email:</th>
				<th>Amount:</th>
				<th>Date:</th>
				</tr>
			<?php
				foreach ($array as $fields) {
					?>
                    <tr>
                    	<td>
                    		<?php echo $fields['name'] ?>
                    	</td>
                    	<td>
                    		<?php echo $fields['donor_email'] ?>
                    	</td>
                    	<td>
                    		<?php 
                    		setlocale(LC_MONETARY, 'en_US');
                    		echo money_format('%.2n', $fields['donation_amount']); ?>
                    	</td>
                    	<td>
                    		<?php
                    		echo date('F j, Y  g:i a', strtotime($fields["dt"]));
                    		?>
                    	</td>
                    </tr>
                    
					<?php
				}
			}
		?>
			</table>
			
			
            
        </div>
		
		       <h3>The Sum</h3>
        <div class="list">
        
                <?php

			
			// Building the Donor List:
			
			if(mysqli_num_rows($sum))
			{
			?>
			<table>
			<?php
				while($row = mysqli_fetch_assoc($sum))
				{
					?>
                    <tr>
                    	<td>
							<?php echo "Total Donors: ".$row['Donors']; ?>
							<br/>
                    		<?php echo money_format('Total: %.2n', $row['Total']); ?>
                    	</td>
                    </tr>
                    
					<?php
				}
			}
		?>
			</table>
			
			
            
        </div>
        
    </div> <!-- Closing the donors div -->
    
</div> <!-- Closing the main div -->

<?php mysqli_close($link); ?>
</body>
</html>
