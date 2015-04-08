<?php

/* Database config */

$db_host		= 'localhost';
$db_user		= 'user';
$db_pass		= 'pass';
$db_database		= 'dbname';

/* End config */


$link = new mysqli($db_host,$db_user,$db_pass,$db_database);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

mysqli_set_charset($link, 'utf8');
//mysql_select_db($db_database,$link);

?>