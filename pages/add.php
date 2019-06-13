<?php  
	require '../php/session.php';

	if (isset($_GET['error']) and !empty($_GET['error'])) {
		$error = $_GET['error'];

		switch ($error) {
			case 'empty':
				$message = "Fields can not be empty!";
				break;
			
			case 'exists':
				$message = "This item already exists!";
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
	<link rel="stylesheet" type="text/css" href="/css/add.css"/>
</head>
<body background="/images/background.png">  
	
  <div class="register">
	<h1> Add a new food.</h1>
	<form action="/php/add-backend.php" method="post">
	    <input type="text" placeholder="Name" name="name" required>
		<br>
	    <input type="text" placeholder="Calories" name="calories" pattern="\d*" required>
		<br>
	    <input type="text" placeholder="Carbs" name="carbs" pattern="\d*" required>
		<br>
		<input type="text" placeholder="Fat" name="fat" pattern="\d*" required>
		<br>
		<input type="text" placeholder="Protein" name="protein" pattern="\d*" required>
		<br>
		<input type="text" placeholder="Sodium" name="sodium" pattern="\d*" required>
		<br>
		<input type="text" placeholder="Sugar" name="sugar" pattern="\d*" required>
		<br>
		<button name="add-submit" class="registerbtn">Add</button>
    </form>
    <p><?php if (isset($message)) { echo $message;} ?></p>
	</div>	
</body>
</html>