<?php
if (empty($_GET['eid'])) {
	echo "ERROR: Missing 'eid'";
	die();
}

require '/admin/connect.php';

//Parse the URL to get the event ID
$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$p = parse_str(parse_url($url, PHP_URL_QUERY), $array);

$eid = $array['eid'];
//Setup PDO binds
$params = array(':eid' => $array['eid']);

//Prepare statement
$event_list = $link->prepare('SELECT dc_events.title, dc_events.charity_name, dc_events.event_id, dc_events.paypal_email, dc_events.targetAmount, dc_events.startDate, dc_events.endDate FROM dc_events WHERE dc_events.event_id = :eid');

//Execute statement, if an error echo error.
if ($event_list->execute($params)){

} else {
		echo "Failure!";
}

//Query to receive event incentives
$incentives = $link->prepare('SELECT dc_incentives.id, dc_incentives.name, dc_incentives.hidden, dc_incentives.incentive, dc_incentives.event_id, dc_events.event_id FROM dc_incentives, dc_events WHERE dc_incentives.event_id = dc_events.event_id AND dc_events.event_id = :eid ORDER BY dc_incentives.dt DESC');

//Execute statement, if an error echo error.
if ($incentives->execute($params)){

} else {
		echo "Failure!";
}

//Set variables from initial event details
while($row = $event_list->fetch(PDO::FETCH_ASSOC))
{
	foreach($row as $fieldname => $fieldvalue)
	{
    	if ($fieldname == 'title'){
	    	$title = $fieldvalue;
    	}

		if ($fieldname == 'paypal_email'){
			$paypal_email = $fieldvalue;
		}

		if ($fieldname == 'charity_name'){
			$charity_name = $fieldvalue;
		}

    }
}

//Set lowercase title for Paypal
$clean_title = preg_replace("/\ (?=[a-z\d])/i", "+", $title);

//header("Location: https://www.paypal.com/cgi-bin/webscr?&cmd=_xclick&business=$paypal_email&incentive=&custom=$eid&amount=5&item_name=$clean_title&no_shipping=1&no_note=1&rm=2&cbt=Leave+a+Comment&notify_url=http%3A%2F%2Fdonate.thespeedgamers.com%2Fipn.php?eid=$eid&return=https%3A%2F%2Fdonate.thespeedgamers.com%2Fthankyou.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="initial-scale=0.8; maximum-scale=0.8;">
		<title>Choose Your Donation Amount</title>

		<script src="http://code.jquery.com/jquery-1.9.1.js?v=1"></script>
		<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js?v=1"></script>

		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700&v=1' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700&v=1' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" media="screen" type="text/css" href="css/donate.css?v=1" />
		<link rel="stylesheet" media="screen and (max-device-width: 640px)" type="text/css" href="css/mobile.css?v=1" />

	</head>
	<body style="margin:0 auto;text-align:center;">
		<div class="container">
			<h2>Donations for <?php echo $charity_name; ?></h2>
			<p>Thank you for considering to donate. From here you can choose either some preset amounts or enter your own.</p>

			<div style="position:relative;vertical-align:middle;">
				<form action="redirect.php?eid=<?php echo $eid;?>" method="post" id="donate">
					<input type="hidden" name="business" value="<?php echo $paypal_email; ?>">
					<input type="hidden" name="custom" value="<?php echo $eid; ?>">
					<input type="hidden" name="item_name" value="<?php echo $clean_title; ?>">

					<div class="preset" style="margin:0 auto;">
						<label></label>
						<div id="preset_amount" class="preset" style="vertical-align:text-top;margin-top:5px;">

							<input type="button" class="preset-box"  id="5" value="$5.00">
							<input type="button" class="preset-box"  id="10" value="$10.00">
							<input type="button" class="preset-box"  id="25" value="$25.00">
							<input type="button" class="preset-box"  id="50" value="$50.00">
							<input type="button" class="preset-box"  id="100" value="$100.00">

							<input type="hidden" id="amount "name="amount" value="">
						</div>
					</div>

					<br/>
					<span style="font-size:18px;">OR<span>
					<br/>

					<div class="custom">
						<label>Input custom amount: </label>
						<span style="display:inline;font-size:1.1em;font-weight:bold;">$</span><input id="custom_amount" style="margin:0;display:inline;padding:3px;"type="text" name="amount" value="" onclick="document.getElementById('custom_amount').disabled = false;">
						<p>Custom amounts must be a minimum of $1.</p>
					</div>

					<br/>


						<?php
							if($incentives->rowCount() > 0)
							{ ?>
								<div id="incentive">
									<h3>What are you donating towards?</h3>
									<select name="os0">
										<option value="0">Nothing</option>
									<?php
									while($row = $incentives->fetch(PDO::FETCH_ASSOC)) {
										if ($row['hidden'] == 0) { ?>
										<option value="<?php echo stripslashes($row['name']);?>"><?php echo stripslashes($row['name']); if ($row['incentive'] == '0') {} else { echo (' - $'.$row['incentive']);} ?></option>
									<?php
										}
									}
							}
						?>
									</select>

					</div>
					<br/>
					<input style="padding:3px; vertical-align:text-bottom;" type="submit" value="Donate">
				</form>

				<script type="text/javascript">
					$("#5").click(function() {
						var $amount = $(this).val().substr(1,4);
						$('input[name="amount"]').attr("value", $amount);
						$(this).fadeTo("fast", 1);
						$(".selected").fadeTo("fast", 0.7);
						$(".preset-box").removeClass("selected");
						$(this).toggleClass("selected")
						$(this).attr("selected", "selected");
					});

					$("#10").click(function() {
						var $amount = $(this).val().substr(1,5);
						$('input[name="amount"]').attr("value", $amount);
						$(this).fadeTo("fast", 1);
						$(".selected").fadeTo("fast", 0.7);
						$(".preset-box").removeClass("selected");
						$(this).toggleClass("selected");
						$(this).attr("selected", "selected");
					});

					$("#25").click(function() {
						var $amount = $(this).val().substr(1,5);
						$('input[name="amount"]').attr("value", $amount);
						$(this).fadeTo("fast", 1);
						$(".selected").fadeTo("fast", 0.7);
						$(".preset-box").removeClass("selected");
						$(this).toggleClass("selected");
						$(this).attr("selected", "selected");
					});

					$("#50").click(function() {
						var $amount = $(this).val().substr(1,5);
						$('input[name="amount"]').attr("value", $amount);
						$(this).fadeTo("fast", 1);
						$(".selected").fadeTo("fast", 0.7);
						$(".preset-box").removeClass("selected");
						$(this).toggleClass("selected");
						$(this).attr("selected", "selected");
					});

					$("#100").click(function() {
						var $amount = $(this).val().substr(1,6);
						$('input[name="amount"]').attr("value", $amount);
						$(this).fadeTo("fast", 1);
						$(".selected").fadeTo("fast", 0.7);
						$(".preset-box").removeClass("selected");
						$(this).toggleClass("selected");
						$(this).attr("selected", "selected");
					});

					$("div.custom").mouseenter(function() {
						document.getElementById("custom_amount").disabled = false;
						document.getElementById("preset_amount").disabled = true;
						$("div.preset").fadeTo('fast', 0.5);
						$("div.custom").fadeTo('fast', 1);
					});

					$("div.preset").mouseenter(function() {
						document.getElementById("custom_amount").disabled = true;
						document.getElementById("preset_amount").disabled = false;
						$("div.custom").fadeTo('fast', 0.5);
						$("div.preset").fadeTo('fast', 1);
					});

					$("div.custom").fadeTo('fast', 0.3);
					document.getElementById("custom_amount").disabled = true;

					$( "#donate" ).validate({
						rules: {
						amount: {
							required: true,
							number: true,
							min: 1
							}
						}
					});
				</script>

			</div>
		</div>
	</body>
</html>
