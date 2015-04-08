<?php
$event_id = 514;

$a = 'http://donate.thespeedgamers.com/xml.php?eid='.$event_id;
file_get_contents($a);
//var_dump($a);
?>