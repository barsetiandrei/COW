<?php
	if (isset($_POST['calculator-btn'])) {
		include 'db_connect.php';
		session_start();
		$uid = $_SESSION['uid'];
		$conn = OpenCon();

		$age = trim($_POST['age']);
		$gender = trim($_POST['gender']);
		$height = trim($_POST['height']);
		$weight = trim($_POST['weight']);
		$exercise = trim($_POST['exercise']);
		$goal = trim($_POST['goal']);

		if(!is_numeric($age) or !is_numeric($height) or !is_numeric($weight) or empty($gender) or empty($goal)) {
			header("Location: ../pages/calculator.php?error=empty");
			exit();
		} else {
			$age = mysqli_real_escape_string($conn, $age);
			$gender = mysqli_real_escape_string($conn, strtolower($gender));
			$height = mysqli_real_escape_string($conn, $height);
			$weight = mysqli_real_escape_string($conn, $weight);
			$exercise = mysqli_real_escape_string($conn, strtolower($exercise));
			$goal = mysqli_real_escape_string($conn, strtolower($goal));

			if ($result = $conn->query("SELECT id FROM profile WHERE uid='" . $uid . "'")) {
				if($row = $result->fetch_all(MYSQLI_ASSOC)){
					if ($conn->query("UPDATE profile SET age='". $age ."', gender='". $gender ."', height='". $height ."', weight='". $weight ."', exercise='". $exercise ."', goal='". $goal ."'")) {
						header("Location: /pages/mycalorieskeeper.php");
						exit();
					}
				} else if ($conn->query("INSERT INTO `cow`.`profile` (`id`, `uid`, `age`, `gender`, `height`, `weight`, `exercise`, `goal`) VALUES (NULL, '". $uid ."', '". $age ."', '". $gender ."', '". $height ."', '". $weight ."', '". $exercise ."', '". $goal ."' )")) {
					header("Location: /pages/mycalorieskeeper.php");
					exit();
				}
			}
		}

	} else {
		header("Location: ../index.php");
		exit();
	}
?>
