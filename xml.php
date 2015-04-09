<?phpob_start();
require "admin/connect.php";$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$p = parse_str(parse_url($url, PHP_URL_QUERY), $array);
$eid = $array['eid'];
$params = array(':eid' => $_GET['eid']);

$result1 = $link->prepare("SELECT dc_events.title, dc_events.paypal_email, dc_events.url, dc_events.event_url, dc_events.image_url, dc_events.event_id, dc_events.targetAmount, dc_events.startDate, dc_events.endDate FROM dc_events WHERE dc_events.event_id = :eid");
$result2 = $link->prepare("SELECT SUM(dc_donations.donation_amount) AS 'collectedAmount', COUNT(dc_donations.donation_amount) AS 'contributors' FROM dc_donations, dc_events WHERE dc_events.event_id = dc_donations.event_id AND dc_events.event_id = :eid");
$result3 = $link->prepare("SELECT dc_incentives.name, SUM(donation_amount) AS total FROM dc_comments, dc_donations, dc_incentives WHERE dc_donations.transaction_id = dc_comments.transaction_id AND dc_donations.event_id = :eid AND dc_comments.incentive = dc_incentives.id GROUP BY dc_incentives.name");
$result1->execute($params);$result2->execute($params);$result3->execute($params);
$currentDate = time();


$xml = new SimpleXMLElement('<chipin/>');


while($row = $result1->fetch(PDO::FETCH_ASSOC)){	foreach($row as $fieldname => $fieldvalue)	{
    	$child = $xml->addChild($fieldname, $fieldvalue);
    	if ($fieldname == 'event_id'){
	    	$event_id = $fieldvalue;
    	}

    	if ($fieldname == 'startDate'){
    		$startDate = $fieldvalue;
    		$xml->addChild('currentDate', $currentDate);
    	}

    	if ($fieldname == 'endDate'){
    		$endDate = $fieldvalue;
    	}
    }
}

while($row = $result2->fetch(PDO::FETCH_ASSOC)){
	foreach($row as $fieldname => $fieldvalue)	{
    	$child = $xml->addChild($fieldname, $fieldvalue);
  }
}

$xml->addChild('incentives')->addChild('incentive');
while($row = $result3->fetch(PDO::FETCH_ASSOC)){

	foreach($row as $fieldname => $fieldvalue)	{
			if ($fieldname == 'name')			{
				$namename = $fieldname;
				$namevalue = $fieldvalue;
			}

			if ($fieldname == 'total')			{
				$totalname = $fieldname;
				$totalvalue = $fieldvalue;
			}
			$xml->incentives->incentive->addChild($namename, $namevalue);
    }
}

if ($currentDate < $endDate){
	$xml->addChild('status', 'ACTIVE');
} else {
	$xml->addChild('status', 'ENDED');
}
$link = null;

Header('Content-type: text/xml');
echo $xml->asXML();
$cachefile = "xml/".$event_id.".xml";
// open the cache file "cache/home.html" for writing
$fp = fopen($cachefile, 'w');
// save the contents of output buffer to the file
fwrite($fp, ob_get_contents());
// close the file
fclose($fp);
// Send the output to the browser
ob_end_flush();
?>