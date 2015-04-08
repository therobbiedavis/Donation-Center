<?
//include("/home/tsgweb/donate.thespeedgamers.com/admin/config.php");
    $link = new mysqli("localhost", "tsgadmin_beta", "luxenst", "tsgadmin_chipin_db");
	$eventid = mysqli_query($link, "SELECT event_id FROM dc_events WHERE current = 1");
	$id = $_GET['eid'];

	mysqli_close($link); 
	
$xmlChipin = simplexml_load_file("http://donate.thespeedgamers.com/xml.php?eid=".$id);
$donationtotal = $xmlChipin->collectedAmount;
$donationgoal = $xmlChipin->targetAmount;


if ($donationgoal == 0){
	
	
} else {
$progresscalc = $donationtotal/$donationgoal;
$progresscalc *= 100;
$progress = number_format($progresscalc, 2);
}

date_default_timezone_set("America/Chicago");
$p['time'] = date("h:i a");
$time = $p['time'];


ob_start();
?>
<html>

	<head>
		<link rel="stylesheet" href="http://donate.thespeedgamers.com/statistics/stats.css?2" type="text/css" media="screen" />
		<link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
		
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	</head>
	
	<body>
	<span class="loading" style="text-align:center;"><img src="http://d3pluk7ynweyvi.cloudfront.net/images/ajax-loader.gif" /></span>
	<div>
	<?php if ($donationgoal == 0){ ?>
	<span style="margin:0 60px 0 0;"><span>Donation Total: $<? echo $donationtotal; ?></span>
	 <? } else {?>
		<span style="margin:0 60px 0 0;"><span>Donation Total: $<? echo $donationtotal." (".$progress."%)" ?></span>
	<?	} ?>
		<span style="margin:0 0 0 250px;"><span>Current Time: <? echo $time ?></span>
	
	</div>
		
		
	<script type="text/javascript">
    $(window).load(function() {
    	$('.loading').remove();
    });
    </script>
	</body>
</html>
	



<?php

  $output = ob_get_contents();
  ob_end_clean();
  echo $output;

  //unlink('/home/tsgweb/donate.thespeedgamers.com/statistics/stats.html');
  $file = fopen('/home/tsgadmin/public_html/donate.thespeedgamers.com/statistics/stats.html', 'w');
  fwrite($file, $output);
  fclose($file);
  
?>