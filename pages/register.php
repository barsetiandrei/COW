<?php 
	if (isset($_GET['error']) and !empty($_GET['error'])) {
		$error = $_GET['error'];

		switch ($error) {
			case 'empty':
				$message = "Fields can not be empty!";
				break;
			
			case 'emailnm':
				$message = "The email addresses do not match!";
				break;

			case 'passnm':
				$message = "The email passwords do not match!";
				break;

			case 'emailex':
				$message = "There already is a user with this email address!";
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
	<link rel="stylesheet" type="text/css" href="/css/register.css"/>
</head>
<body background="/images/background.png">  
	
  <div class="register">
  	<img src="/images/register.png" class="registerp">	
	<h1> Register here</h1>
	<form action="/php/register-backend.php" method="post">
	    <input type="text" placeholder="First Name" name="firstname" required>
		<br>
	    <input type="text" placeholder="Last Name" name="lastname" required>
		<br>
	    <input type="email" placeholder="E-mail" name="email" required>
		<br>
	    <input type="email" placeholder="Confirm e-mail" name="confemail" required>
		<br>
	    <input type="password" placeholder="Password" name="password" required>
		<br>
	    <input type="password" placeholder="Confirm password" name="confpassword" required>
	    <br>
		<button name="register-submit" class="registerbtn">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Sign in</a></p>
    <p><?php if (isset($message)) { echo $message;} ?></p>
	</div>	
</body>
</html>
 
