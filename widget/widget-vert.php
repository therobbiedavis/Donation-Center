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
$event_url = $xmlChipin->event_url;
$image_url = $xmlChipin->image_url;

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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="jquery-ui-1.8.16.custom.min.js"></script>
<script src="jquery.ui.progressbar.js"></script>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.16.custom.css?5" rel="Stylesheet" />
<link type="text/css" href="widget-vert.css?19" rel="Stylesheet" />

<!--[if lt IE 9]>
        <link rel="stylesheet" type="text/css" href="ie7-small.css?1" />
<![endif]-->

</head>
<body style="margin:0;padding:0;">

	<script>
	$(function() {
		$( "#progressbar" ).progressbar({
			value: <?php echo $progress ?>,
			orientation : 'vertical'
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

<div id="moreInfo">
	<p id="closeInfoLink"><img width="11" height="11" alt="close" src="http://d3pluk7ynweyvi.cloudfront.net/images/close.png" </p>
	<div>
	Embed this widget: <textarea><script type="text/javascript" src="http://donate.thespeedgamers.com/widget.js?format=small&eid=<?php echo $id; ?>"></script></textarea>
	</div>
</div>

<div id="widgetContainer">

	<div class="marathonInfo">
		<span id="refresh" onClick="reload()"><img src="http://d3pluk7ynweyvi.cloudfront.net/images/refresh.png" width="15" height="15" alt="refresh"/></span>
				<p>Donate <a href="<? echo $event_url; ?>" target="_blank"><? echo $event_title; ?></a></p>
	</div>


<?php
if ($donationgoal != 0) {	?>
	<div id="progressbar">
		<span><? echo $progress; ?>%</span>
	</div>
<?php } ?>

<?php if ($donationgoal == 0) { ?>	
	<div class="donationInfo" style="margin:10px 15px 0 15px;">
		Raised <span>$<? echo number_format((double)$donationtotal, 2, '.',',');?></span>

	</div>
<?php } ?>	
	
<?php if ( $status == 'ENDED' ) { ?>

	<div class="button-box">
		<div class="button">
			Ended
		</div>
	</div>

	<?php } else { ?>
	
	<div class="button-box">
		<div class="button">
		<?php if (!$xml){ ?>
			<a target="_blank">Wait</a>
		<?php } else { ?>
			<a href="http://donate.thespeedgamers.com/?eid=<? echo $id ?>" target="_blank">Donate</a>
			<?php } ?>
		</div>
	</div>
	
	<?php } ?>

<?php if ($donationgoal != 0) { ?>	
	<div class="donationInfo">
		Raised <span>$<? echo number_format((double)$donationtotal, 2, '.',',');?></span> of <span>$<? echo number_format((double)$donationgoal, 2, '.',',');?></span>

	</div>
<?php } ?>	
	

</div>



</body>
</html>

<?php
  $output = ob_get_contents();
  ob_end_clean();
  echo $output;

  $file = fopen('/home/tsgadmin/public_html/donate.thespeedgamers.com/widget/cache/widget-vert.'.$id.'.cache', 'w');
  fwrite($file, $output);
  fclose($file);
?>