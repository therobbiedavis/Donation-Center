<?php
$id = $_GET['eid'];
$site_url = 'http://donate.thespeedgamers.com/statistics/stream-stats-cron.php?eid='.$id;
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
?>