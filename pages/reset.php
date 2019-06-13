<?php 
	if (isset($_GET['error']) and !empty($_GET['error'])) {
		$error = $_GET['error'];

		switch ($error) {
			case 'empty':
				$message = "Fields can not be empty!";
				break;
			
			case 'wrongpass':
				$message = "The password you've introduced is wrong!";
				break;

			case 'nomatch':
				$message = "The passwords you've introduced do not match!";
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
	<link rel="stylesheet" type="text/css" href="/css/reset.css"/>
</head>
<body background="/images/background.png">
	<div class="reset">	
	<img src="/images/password.png" class="password">	
	<h1> Change password</h1>
	<form action="/php/reset-backend.php" method="post">
	<input type="password" name="oldpw" placeholder="Old password" required>
	<br>
	<input type="password" name="password" placeholder="New password" required>
	<br>
	<input type="password" name="password-check" placeholder="Confirm new password" required>
    <type>
	<button name="change-submit" class="btn">Change</button>
	</form>
	<a href="/pages/mycalorieskeeper.php">Back to the website.</a>
	<p><?php if (isset($message)) { echo $message;} ?></p>
</div>
</html>
