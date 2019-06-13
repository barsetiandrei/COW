<?php
	if (isset($_POST["add-submit"])) {
		include 'db_connect.php';
		$conn = OpenCon();

		$name = trim($_POST["name"]);
		$calories = trim($_POST["calories"]);
		$carbs = trim($_POST["carbs"]);
		$fat = trim($_POST["fat"]);
		$protein = trim($_POST["protein"]);
		$sodium = trim($_POST["sodium"]);
		$sugar = trim($_POST["sugar"]);

		if(empty($name) or !is_numeric($calories) or !is_numeric($carbs) or !is_numeric($fat) or !is_numeric($protein) or !is_numeric($sodium) or !is_numeric($sugar)){
			header("Location: ../pages/add.php?error=empty");
			exit();
		} else {
			$name = mysqli_real_escape_string($conn, strtolower($name));
			$calories = mysqli_real_escape_string($conn, $calories);
			$carbs = mysqli_real_escape_string($conn, $carbs);
			$fat = mysqli_real_escape_string($conn, $fat);
			$protein = mysqli_real_escape_string($conn, $protein);
			$sodium = mysqli_real_escape_string($conn, $sodium);
			$sugar = mysqli_real_escape_string($conn, $sugar);
			
			if ($result = $conn->query("SELECT id FROM foods WHERE name='" . $name . "'")) {
				if($row = $result->fetch_all(MYSQLI_ASSOC)){
					header("Location: /pages/add.php?error=exists");
					exit();
				} else if ($conn->query("INSERT INTO `cow`.`foods` (`id`, `name`, `calories`, `carbs`, `fat`, `protein`, `sodium`, `sugar`) VALUES (NULL, '". $name ."', '". $calories ."', '". $carbs ."', '". $fat ."', '". $protein ."', '". $sodium ."', '". $sugar ."')")) {
					header("Location: /pages/mydailyplan.php");
					exit();
				}
			}
		}
	} else {
		header("Location: ../index.php");
		exit();
	}
?>