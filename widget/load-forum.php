<?php
$eid = $_GET['eid'];
$site_url = "http://".$_SERVER['SERVER_NAME'].'/Donation-Center/';$cache = $site_url.'widget/cache/widget-forum.'.$eid.'.cache';


if (file_exists($cache)) {
$ch = curl_init();
$timeout = 5; // set to zero for no timeout
curl_setopt ($ch, CURLOPT_URL, $site_url);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

ob_start();
curl_exec($ch);
curl_close($ch);
$file_contents = ob_get_contents();
ob_end_clean();

echo $file_contents;
} else {
$site_url_nonexist = $site_url.'/widget/widget-forum.php?eid='.$eid;

$ch = curl_init();
$timeout = 5; // set to zero for no timeout
curl_setopt ($ch, CURLOPT_URL, $site_url_nonexist);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

ob_start();
curl_exec($ch);
curl_close($ch);
$file_contents = ob_get_contents();
ob_end_clean();

echo $file_contents;

}
?>
