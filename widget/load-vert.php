<?php
$id = $_GET['id'];

$site_url = 'http://donate.thespeedgamers.com/widget/cache/widget-vert.'.$id.'.cache';

if (file_exists($site_url)) { 
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
$site_url_nonexist = 'http://donate.thespeedgamers.com/widget/widget-vert.php?id='.$id;

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