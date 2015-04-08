<?php

require "config.php";
require "connect.php";

require_once '../classes/Membership.php';
$membership = New Membership();

$membership->confirm_Member();
$sid = session_id();
$userid = mysqli_query($link, "SELECT id FROM users WHERE sessionid = '$sid'");
$sessid = $userid->fetch_assoc();
$id = $_GET['id'];

$action = $_GET['f'];
$todayDate = date('F j, Y  g:i a');
$currentTime = date('h:i:sA');


switch ($action)
  {
    case 'new':
	

	  
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
	  
	  
	  $uid = $g['event_id'];
	  $date = $_POST['end_date'];
	  $cronday = substr($date, -2);
	  $cronmonth = substr($date, -5, 2);


	  $job = "0 0 ".$cronday." ".$cronmonth." * /home/tsgadmin/public_html/donate.thespeedgamers.com/xml.php?eid=".$uid." >/dev/null 2>&1;";
	  

	  
	  //$gid = $g['event_id'];
	  /*
			//create the directory where the files will be stored
			$year_folder = '../../../../static.thespeedgamers.com/fanart/'.date('Y');
			$category_folder = '../../../../static.thespeedgamers.com/fanart/'.date('Y').'/'.htmlspecialchars($g['category_name']);
			
			if (is_dir($year_folder)){

			} else {
				mkdir('../../../../static.thespeedgamers.com/fanart/'.date('Y'));
			}
			
			if (is_dir($category_folder)){

			} else {
				mkdir('../../../../static.thespeedgamers.com/fanart/'.date('Y').'/'.htmlspecialchars($g['category_name']));
			}
			*/
			
			
      $success = mysqli_query($link, $postQuery);
      if ($success) { 
	  file_get_contents('http://donate.thespeedgamers.com/xml.php?eid='.$uid);
	  $output = shell_exec('crontab -l');
	  file_put_contents('/tmp/crontab.txt', $output.$job.PHP_EOL);
	  exec('crontab /tmp/crontab.txt');
	  $URL = './index.php'; }
      else { $URL = './new-event.php'; }
      break;
      
    case 'edit':
	
	//Edit Event Name
      $id = addslashes($_POST['id']);
      $g['title'] = addslashes($_POST['event_name']);
	  $g['image_url'] = addslashes($_POST['image_url']);
	  $g['targetAmount'] = $_POST['target_amount'];
      if (!empty($_POST['event_name'])) { $g['title'] = addslashes($_POST['event_name']); }
      $putQuery  = 'UPDATE `dc_events` SET ';
      foreach ($g as $key => $value) {
        $updates[] = "`$key` = '$value'";
      }
      $putQuery .= implode(', ', $updates);
      $putQuery .= ' WHERE `event_id` = \''.$id.'\';';
      $success = mysqli_query($link, $putQuery);
      //echo $putQuery;
      
      if ($success) { $URL = './index.php'; 
	      file_get_contents('http://donate.thespeedgamers.com/xml.php?eid='.$id);
      }
      else { $URL = './edit.php'; }
      
      break;
	  
		case 'make_current':
			$id = addslashes($_GET['id']);
			mysqli_query($link, "UPDATE `dc_events` SET `default` = '0'");
			mysqli_query($link, "UPDATE `dc_events` SET `default` = '1' WHERE `event_id` = '$id'");
			$URL = './index.php';
			break;
			
			case 'delete':
			
			if ($_GET['submitBtn'] == "Cancel") 
				{ 
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
				$URL = './eventDetails.php?id='.$_POST['id'].''; }
			else { $URL = './addIncentive.php?id='.$_POST['id'].''; }
		break;
			
		
		case 'hideincent':
			$i["id"] = addslashes($_GET['iid']);
			$i["hide"] = addslashes($_GET['hide']);
			$postQuery  = 'UPDATE `dc_incentives` SET hidden='.$i["hide"].' WHERE id='.$i["id"];

			$success = mysqli_query($link, $postQuery);
			if ($success) { 
				$URL = $_SERVER['HTTP_REFERER']; }
			else {  }
			
			//$URL = $_SERVER['HTTP_REFERER'];
		break;	
		
		
		
		case 'deleteincent':
			$i["id"] = addslashes($_GET['iid']);
			$postQuery  = 'DELETE FROM `dc_incentives` WHERE `id` = '.$i["id"];

			$success = mysqli_query($link, $postQuery);
			if ($success) { 
				$URL = $_SERVER['HTTP_REFERER']; }
			else {  }

		break;		
      
    default:
      break;
  }

header("Location: $URL");

//include(ROOT.'/inc/closedb.php');
?>