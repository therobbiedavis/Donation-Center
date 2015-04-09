<?php
ob_start();
$site_url = "http://".$_SERVER['SERVER_NAME'].'/Donation-Center/';
$eid = $_GET['eid'];
$url = $site_url."xml/".$eid.".xml";


//Checking to see if Chipin is up
$ch = curl_init();
$timeout = 3; // set to zero for no timeout
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
ob_start();
$xml = curl_exec($ch);
ob_end_clean();
curl_close ($ch);
if (!$xml){

} else {

$xmlChipin = simplexml_load_file($url);
$donationtotal = $xmlChipin->collectedAmount;
$donationgoal = $xmlChipin->targetAmount;
$contributors = $xmlChipin->contributors;
$event_title = $xmlChipin->title;
$charity_url = $xmlChipin->url;

$status = $xmlChipin->status;
$endDate = $xmlChipin->endDate;

//$currentDate2 = echo $currentDate;
//$endDate2 = echo $endDate;

if ($donationgoal != 0) {
$progresscalc = $donationtotal/$donationgoal;
$progresscalc *= 100;
$progress = number_format($progresscalc, 2);
} else {
$progresscalc = 100;
$progress = number_format($progresscalc, 2);
}
//echo $xmlChipin->currentDate;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="jquery-ui-1.8.16.custom.min.js"></script>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.16.custom.css?2" rel="Stylesheet" />
<link type="text/css" href="widget-forum.css?19" rel="Stylesheet" />
<link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>

<!--[if lt IE 9]>
        <link rel="stylesheet" type="text/css" href="ie7-small.css?1" />
<![endif]-->

</head>
<body style="margin:0;padding:0;">
	<script>
	$(function() {
		$( "#progressbar" ).progressbar({
			value: <?php if (!$xml){
			echo '0';} else {
			echo $progress; }?>
		});
	});
	</script>
	<script>

		var reloading = true;
		window.setTimeout(function () { reloading = false; }, 10000);

		function reload() {
			if (!reloading){
				reloading = true;
				document.location.reload();
			}
		}

	</script>


<div id="widgetContainer" class="bg3 forum-bg">

	<div class="marathonInfo">
		<span id="refresh" onClick="reload()"><img src="<?php echo $site_url;?>widget/images/refresh.png" width="15" height="15" alt="refresh"/></span>
		<p>Donate <a href="<?php echo $charity_url; ?>" target="_blank"><?php echo $event_title; ?></a></p>
	</div>

<div id="inline">
	<div id="progressbar">
			<?php if (!$xml){ echo '<span>(We\'ll be back soon.)</span></div>'; } else { ?>
			<?php if ($donationgoal != 0) { ?><span>$<? echo number_format((double)$donationtotal, 2, '.',','); ?> of $<?php echo number_format((double)$donationgoal, 2, '.',','); ?> (<?php echo $progress; ?>%)</span> <?php } else { ?>
			<span><?php echo "Raised $".number_format((double)$donationtotal, 2, '.',','); ?>
		 <?php } ?>
	</div>

<?php } if ( $status == 'ENDED' ) { ?>

	<div class="button-box">
		<div class="button">
			Ended
		</div>
	</div>

	<?php } else { ?>

	<div class="button-box">
		<div class="button gray">
		<?php if (!$xml){ ?>
			<a target="_blank">Wait</a>
		<?php } else { ?>
			<a href= <?php echo $site_url;?>"/?eid=<?php echo $eid ?>" target="_blank">Donate</a>
			<?php } ?>
		</div>
	</div>

	<?php } ?>
</div>
</div>


</body>
</html>

<?php
  $output = ob_get_contents();
  ob_end_clean();
  echo $output;
	$sitePath = getcwd();
  $file = fopen($sitePath.'/widget/cache/widget-forum.'.$id.'.cache', 'w');
  fwrite($file, $output);
  fclose($file);

?>
