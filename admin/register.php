<?phprequire_once "connect.php";if(!empty($_POST['username']) && !empty($_POST['pwd']) && !empty($_POST['email'])){  function sanitize($data) {      $data = trim($data);      $data = stripslashes($data);      $data = htmlspecialchars($data);      return $data;  }  $options = [    'cost' => 8,  ];  $username = sanitize($_POST['username']);
  $password = password_hash($_POST['pwd'], PASSWORD_BCRYPT, $options);
  $email = sanitize($_POST['email']);

  $params = array(':username' => $username);
  $checkusername = $link->prepare("SELECT * FROM users WHERE username = :username");
  $checkusername->execute($params);

  if($checkusername->rowCount() == 1) {
    $response =  "<h1>Error</h1>";
    $response =  "<p>Sorry, that username is taken. Please go back and try again.</p>";

  } else {
    $params = array(':username' => $username, ':password' => $password, ':email' => $email );
    $register = $link->prepare("INSERT INTO users (username, password, email) VALUES(:username, :password, :email)");

    if($register->execute($params)) {
      $response = "<h1>Success</h1>";
      $response =  "<p>Your account was successfully created. Please <a href=\"index.php\">click here to login</a>.</p>";
    } else {
      $response =  "<h1>Error</h1>";
      $response =  "<p>Sorry, your registration failed. Please go back and try again.</p>";
    }
  }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register!</title>
<link rel="stylesheet" type="text/css" href="../css/default.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.1/jquery.min.js"></script>
</head>

<body>
<div id="login">
	<form method="post" action="">
    	<h2>Register <small>enter your credentials</small></h2>
        <p>
        	<label for="name">Username: </label>
            <input type="text" name="username" />
        </p>

        <p>
        	<label for="pwd">Password: </label>
            <input type="password" name="pwd" />
        </p>
        <p>          <label for="email">Email: </label>            <input type="email" name="email" />        </p>
        <p>
        	<input type="submit" id="submit" value="Register" name="submit" />
        </p>
    </form>
    <?php if(isset($response)) echo "<h4 class='alert'>" . $response . "</h4>"; ?>
</div><!--end login-->
</body>
</html>
