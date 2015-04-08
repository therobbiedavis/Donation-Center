<?php
ob_start();

include('variables.php');

$id = $_GET['id'];

$url = "http://donate.thespeedgamers.com/xml/".$id.".xml";

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
<!-- <script src="globals.js?2"></script>-->
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.16.custom.css?2" rel="Stylesheet" />
<link type="text/css" href="widget.css?21" rel="Stylesheet" />
<link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>

<!--[if IE]>
        <link rel="stylesheet" type="text/css" href="ie.css" />
<![endif]-->

<!--[if IE 8]>
        <link rel="stylesheet" type="text/css" href="ie8.css?2" />
<![endif]-->

<!--[if IE 7]>
        <link rel="stylesheet" type="text/css" href="ie7.css" />
<![endif]-->

</head>
<body style="margin:0 8px;">

	<script type="text/javascript">
	$(function() {
		$( "#progressbar" ).progressbar({
			value: <?php if (!$xml){
			echo '0';} else {
			echo $progress; }?>
		});
	});
	</script>
	
	<script type="text/javascript">

		var reloading = true;
		window.setTimeout(function () { reloading = false; }, 10000);
	
		function reload() {
			if (!reloading){
				reloading = true;
				document.location.reload();
			}
		}
		
	</script>
	

<div id="moreInfo">
	<p id="closeInfoLink"><img width="11" height="11" alt="close" src="http://www.thespeedgamers.com/wp-content/images/close.png"></p>
	<div>
	<? echo $charity["description"]; ?>
		<div>
	Embed this widget: <span style="font-size:9px;">(Copy and paste into your website)</span> <textarea>&lt;script src="http://donate.thespeedgamers.com/widget.js?format=large&eid=<?php echo $id; ?>" type="text/javascript"&gt;&lt;/script&gt;</textarea>
	</div>
	</div>
</div>

<div id="widgetContainer">

	<span id="refresh" onClick="reload()"><img src="images/refresh.png" width="15" height="15" alt="refresh"/></span>
			
	<div class="donateimg">
		<a href="http://thespeedgamers.com" target="_blank"><img src="<?php echo $charity["banner"]; ?>" height="57" width="246" alt="Donate"/></a>
	</div>

	<div class="marathonInfo">
	
			<p>Donate to <a href="<? echo $charity_url; ?>" target="_blank"><? echo $event_title; ?></a></p>
	</div>
	<div class="donationInfo">
	<?php
		if ($donationgoal != 0) {	?>
	
		<span>Collected:</span> <?php if (!$xml){ echo '<span style="font-size:12px;">...</span>';} else { ?>$<? echo number_format((double)$donationtotal, 2, '.',','); }?>
		<br/>
		<span>Goal:</span> <?php if (!$xml){ echo '<span style="font-size:12px;">...</span>';} else { ?>$<? echo number_format((double)$donationgoal, 2, '.',','); }?>
		<br/>
		
		<? } else { ?>
		
		<span>Collected:</span> <?php if (!$xml){ echo '<span style="font-size:12px;">...</span>';} else { ?>$<? echo number_format((double)$donationtotal, 2, '.',','); }?>
		<br/>
		<? } ?>
	</div>

	
	<div id="progressbar">
		<span><?php if (!$xml){ echo '<span style="font-size:11px;">(We\'ll be back soon.)</span>';} else { echo $progress.'%'; }?></span>
	</div>

<?php if ( $status == 'ENDED' ) { ?>

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
			<a href="http://donate.thespeedgamers.com/?eid=<? echo $id ?>" target="_blank">Donate</a>
			<?php } ?>
		</div>
	</div>
	
	<?php } ?>
	
	<p id="moreInfoLink">More Info</p>
</div>

	<script type="text/javascript"> 
	$('#moreInfo').hide()
	 
	$('#moreInfoLink').click( function() {
		$('#moreInfo').show('fast', 'swing','')
	});
	
	$('#closeInfoLink').click( function() {
		$('#moreInfo').hide()
	});

	</script>
</body>
</html>

<?php
  $output = ob_get_contents();
  ob_end_clean();
  echo $output;

  $file = fopen('/home/tsgadmin/public_html/donate.thespeedgamers.com/widget/cache/widget.'.$id.'.cache', 'w');
  fwrite($file, $output);
  fclose($file);
?>