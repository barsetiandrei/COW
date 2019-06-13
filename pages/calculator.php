<?php 
	require '../php/session.php';

	if (isset($_GET['error']) and !empty($_GET['error'])) {
		$error = $_GET['error'];

		if ($error = "empty") {
			$message = "Please fill in all of the fields!";
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<title>Cal-O-Web</title>
	<link rel="stylesheet" type="text/css" href="/css/calculator.css"/>
</head>
<body>
     <h1>Calculate your calories now</h1>
	<div class="calculator-exterior">	
	<img src="/images/calculator.png" class="calculator">	
	<form action="/php/calculator-backend.php" method="post">
		<label for="Age">Age:</label>
		<input type="text" name="age" pattern="\d*" required>
		<br>
	     <label for="Gen">Gender:</label>
	        <select name="gender">
	            <option value="female">Female</option>
	            <option value="male">Male</option>
	        </select>
		<br>
		<label for="Height">Height (centimeters):</label>
		<input type="text" name="height" inputmode="numeric" pattern="\d*" required>

		<br>
		<label for="Your weight">Your weight (Kg):</label>
		<input type="text" name="weight" pattern="\d*" required>
	    <br>
	
        <label for="Exercitii">How often do you exercise?</label>
            <select name="exercise">
				<option value="0">I don`t</option>
				<option value="1">1-3 days/week </option>
				<option value="2">3-5 days/week</option>
				<option value="3">5 days/week</option>
				<option value="4">6-7 days/week</option>
				<option value="5">every day intense</option>
			</select>
        <br>     
 
        <label for="Goal">What do you want to achieve?</label>
        <select name="goal">
			<option value="maintain">To mantain</option>
			<option value="grow">To grow muscle mass</option>
			<option value="lose">To lose weight</option>
		</select>
   		<p><?php if (isset($message)) { echo $message;} ?></p>
        <button name="calculator-btn" class="btn">Calculate</button>
	</form>
	</div>
	</body>
</html>
    
     





















 






