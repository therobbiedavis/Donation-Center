<?php
    // Before implementing this code, you should use your own username, password and database name values.

	//include('http://donate.thespeedgamers.com/admin/variables.php');
	
    $link = mysql_connect("localhost", "tsgadmin_beta", "luxenst");   
    mysql_select_db("tsgadmin_chipin_db") or die("Could not select database"); 
    
	//get stat event
	$eventid = mysql_query("SELECT event_id FROM dc_events WHERE current = 1");
	$row = mysql_fetch_array($eventid);
	$event = $row["event_id"];
	
    //get Donations
    $xmlChipin = simplexml_load_file("http://donate.thespeedgamers.com/xml/".$event.".xml");
    
    $current = mysql_query("SELECT * FROM stats WHERE event_id = '$event' ORDER BY id DESC");
    while(($c = mysql_fetch_array($current)) !== FALSE)
    {
	    $id[] = $c[0];
    }
    

	
	
    $donationtotal = $xmlChipin->collectedAmount;
    //$donationtotal = 200;
    //$donationtotal = mysql_query("SELECT don_total FROM donations WHERE id = ".$id[0]);
    //$ddt = mysql_fetch_assoc($donationtotal);
    
    $donationdiff = mysql_query("SELECT don_total FROM stats WHERE id = ".$id[0]);
    if ($donationdiff == false) {
    	$donationdiff = 0;
    	$dd = $donationdiff;
    	$donationdiff = bcsub($donationtotal, $dd, 2);
    } else {
    $dd = mysql_fetch_assoc($donationdiff);
    $donationdiff = bcsub($donationtotal, $dd['don_total'], 2);
    }
    

    

    
    $donationgoal = $xmlChipin->targetAmount;
    $contributors = $xmlChipin->contributors;
    $time = mysql_query("SELECT hour FROM stats ORDER BY id DESC");
    $t = mysql_fetch_assoc($time);
    $nextt = $t['hour']+1;

	if ($t['hour'] =="$marathonLength") {
		$nextt = 0;
		if ($nextt = 0) {
			$nextt ++;
		}
	die();
	}

    print_r("Current ID: ".$id[0]."<br/>");
    if ($dd <= 0) {
		print_r("Last Donation Total: 0<br/>");
    } else {
    	print_r("Last Donation Total: ".$dd['don_total']."<br/>");
    }
    print_r("Current Donation Total: ".$donationtotal."<br/>");
    print_r("Donation Difference: ".$donationdiff."<br/>");
    print_r( $donationtotal."<br/>");
    print_r("Latest Hour: ".$t['hour']."<br/>");
    print_r("Next Hour: ".$nextt."<br/>");
    
	if ($id[0] == NULL) {
	mysql_query("INSERT INTO stats (hour, don_total, don_diff, event_id) VALUES (".$nextt.", 0, 0, '".$event."')");
	} else {
   mysql_query("INSERT INTO stats (hour, don_total, don_diff, event_id) VALUES (".$nextt.", ".$donationtotal.", ".$donationdiff.", '".$event."')");
	}	
    echo mysql_error();
?>