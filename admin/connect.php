<?phprequire_once '/../includes/config.php';
try {
  $link = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASSWORD);
}
catch (PDOException $e){
  echo 'Connection failed: ' . $e->getMessage();
}

?>
