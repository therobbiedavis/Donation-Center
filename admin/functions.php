<?php

require_once "connect.php";

require_once '../classes/Membership.php';
$membership = New Membership();

$membership->confirm_Member();
$sid = session_id();
$params = array(':sid' => $sid);
$userid = $link->prepare("SELECT id FROM users WHERE sessionid = :sid");
$userid->execute($params);

$sessid = $userid->fetchAll();
if (empty($_GET['eid'])) {

} else {
  $eid = $_GET['eid'];
}


$action = $_GET['f'];
$todayDate = date('F j, Y  g:i a');
$currentTime = date('h:i:sA');


//Function to sanitize user input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

switch ($action) {
  case 'new':

    //Set Variable for event_id to use later
    $event_id = uniqid();

    //Prepare PDO param
    $params = array(
      ':eid' => $event_id,
      ':uid' => $sessid[0]['id'],
      ':title' => sanitize($_POST['event_name']),
      ':cname' => sanitize($_POST['charity_name']),
      ':iurl' => sanitize($_POST['image_url']),
      ':pemail' => sanitize($_POST['paypal_email']),
      ':url' => sanitize($_POST['url']),
      ':eurl' => sanitize($_POST['event_url']),
      ':tamt' => sanitize($_POST['target_amount']),
      ':start' => strtotime($todayDate),
      ':end' => strtotime($_POST['end_date']." ".$currentTime)
    );

    //Prepare SQL statement
    $link->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    $createEvent = $link->prepare("INSERT INTO `dc_events` (`event_id`, `user_id`, `title`, `charity_name`, `image_url`, `paypal_email`, `url`, `event_url`, `targetAmount`, `startDate`, `endDate`) VALUES (:eid, :uid, :title, :cname, :iurl, :pemail, :url, :eurl, :tamt, :start, :end)");


    /* OLD MySQLi Format
	  $g['event_id'] = uniqid();
	  $g['user_id'] = $sessid['id'];
      $g['title'] = addslashes($_POST['event_name']);
	  $g['charity_name'] = addslashes($_POST['charity_name']);
	  $g['image_url'] = addslashes($_POST['image_url']);
      $g['paypal_email'] = addslashes($_POST['paypal_email']);
      $g['url'] = addslashes($_POST['url']);
	  $g['event_url'] = addslashes($_POST['event_url']);
	  $g['targetAmount'] = $_POST['target_amount'];
	  $g['startDate'] = strtotime($todayDate);
	  $g['endDate'] = strtotime($_POST['end_date']." ".$currentTime);

      array_filter($g);
      $postQuery  = 'INSERT INTO `dc_events` (`';
      $postQuery .= implode('`, `', array_keys($g));
      $postQuery .= '`) VALUES (\'';
      $postQuery .= implode('\', \'', array_values($g));
      $postQuery .= '\');';
    */

	  $date = $_POST['end_date'];
	  $cronday = substr($date, -2);
	  $cronmonth = substr($date, -5, 2);

	  $job = "0 0 ".$cronday." ".$cronmonth." * ".$_SERVER["DOCUMENT_ROOT"]."Donation-Center/xml.php?eid=".$event_id." >/dev/null 2>&1;";

    if ($createEvent->execute($params)){
      file_get_contents($_SERVER["DOCUMENT_ROOT"].'Donation-Center/xml.php?eid='.$uid);
      $output = shell_exec('crontab -l');
      file_put_contents('/tmp/crontab.txt', $output.$job.PHP_EOL);
      exec('crontab /tmp/crontab.txt');
      $URL = './index.php';
    } else {
      $URL = './new-event.php';
    }

  break;

  case 'edit':
    //Edit Event Name
    print_r($_POST);

    if (empty($_POST['image_url'])){
      $params = array(
        ':iurl' => ''
      );
    } else {
      $params = array(
        ':iurl' => sanitize($_POST['image_url'])
      );
    }


      $params[':title'] = sanitize($_POST['event_name']);
      $params[':tamt'] = sanitize($_POST['target_amount']);
      $params[':eid'] = $_POST['eid'];
      $eid = $_POST['eid'];

    print_r($params);

    $updateEvent = $link->prepare("UPDATE `dc_events` SET `title` = :title, `image_url` = :iurl, `targetAmount` = :tamt WHERE `event_id` = :eid");


    $link->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    if ($updateEvent->execute($params)) {
      $URL = './index.php';
      file_get_contents("http://".$_SERVER['SERVER_NAME'].'/Donation-Center/xml.php?eid='.$eid);
    } else {
      $URL = './edit.php';
    }


    break;

		case 'make_current':
		  $id = addslashes($_GET['id']);
			mysqli_query($link, "UPDATE `dc_events` SET `default` = '0'");
			mysqli_query($link, "UPDATE `dc_events` SET `default` = '1' WHERE `event_id` = '$id'");
			$URL = './index.php';
		break;

		case 'delete':

			if ($_GET['submitBtn'] == "Cancel") {
					header("Location: /index.php");
			}

			$id = addslashes($_POST['id']);

			mysqli_query($link, "DELETE FROM `dc_events` WHERE `event_id` = '$id'");
			$URL = './index.php';
		break;








		case 'add_incentive':
			 $g['event_id'] = addslashes($_POST['id']);
			 $g['name'] = addslashes($_POST['name']);
			 $g['incentive'] = addslashes($_POST['amount']);

			array_filter($g);
			$postQuery  = 'INSERT INTO `dc_incentives` (`';
			$postQuery .= implode('`, `', array_keys($g));
			$postQuery .= '`) VALUES (\'';
			$postQuery .= implode('\', \'', array_values($g));
			$postQuery .= '\');';


			$success = mysqli_query($link, $postQuery);
			if ($success) {
				$URL = './eventDetails.php?id='.$_POST['id'].'';
      } else {
        $URL = './addIncentive.php?id='.$_POST['id'].'';
      }
		break;


		case 'hideincent':
			$i["id"] = addslashes($_GET['iid']);
			$i["hide"] = addslashes($_GET['hide']);
			$postQuery  = 'UPDATE `dc_incentives` SET hidden='.$i["hide"].' WHERE id='.$i["id"];

			$success = mysqli_query($link, $postQuery);
			if ($success) {
				$URL = $_SERVER['HTTP_REFERER'];
      } else {}

			//$URL = $_SERVER['HTTP_REFERER'];
		break;



		case 'deleteincent':
			$i["id"] = addslashes($_GET['iid']);
			$postQuery  = 'DELETE FROM `dc_incentives` WHERE `id` = '.$i["id"];

			$success = mysqli_query($link, $postQuery);
			if ($success) {
				$URL = $_SERVER['HTTP_REFERER'];
      } else {}

		break;

}

header("Location: $URL");

?>
