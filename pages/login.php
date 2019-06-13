<?php 
	if (isset($_GET['error']) and !empty($_GET['error'])) {
		$error = $_GET['error'];

		switch ($error) {
			case 'empty':
				$message = "Fields can not be empty!";
				break;
			
			case 'nouser':
				$message = "The specified email address does not exist!";
				break;

			case 'wrongpass':
				$message = "The email address and password do not match.";
				break;

			default:
				break;
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title>Cal-O-Web</title>
	<link rel="stylesheet" type="text/css" href="/css/login.css"/>
</head>
<body background="/images/background.png">
	<div class="loginbox">
        <img src="/images/avatar.jpg" class="avatar">	
		  <h1>Login Here</h1>
		  <form action="/php/login-backend.php" method="post">
			  <p> E-mail:</p>
			  <input type="email" name="email" placeholder="Enter e-mail" required>
			  <p> Password </p>
			  <input type="password" name="password" placeholder="Enter password" required>
			  <button name="login-submit" class="loginbtn">Login</button>
		  </form>
		  <a href="/pages/register.php"> Don't have an account?</a>
		  <p><?php if (isset($message)) { echo $message;} ?></p>
	</div>
</body>
</html>
 
