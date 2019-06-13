<?php 
	if(isset($_POST['register-submit'])){
		include 'db_connect.php';
		$conn = OpenCon();

		$firstName = trim($_POST['firstname']);
		$lastName = trim($_POST['lastname']);
		$email = trim($_POST['email']);
		$confirmEmail = trim($_POST['confemail']);
		$password = trim($_POST['password']);
		$confirmPassword = trim($_POST['confpassword']);
	
		if (empty($firstName) or empty($lastName) or empty($email) or empty($confirmEmail) or empty($password) or empty($confirmPassword)) {
			header("Location: /pages/register.php?error=empty");
			exit();
		} else if ($email != $confirmEmail) {
			header("Location: /pages/register.php?error=emailnm");
			exit();
		} else if ($password != $confirmPassword) {
			header("Location: /pages/register.php?error=passnm");
			exit();
		} else {
			$firstName = mysqli_real_escape_string($conn, $firstName);
			$lastName = mysqli_real_escape_string($conn, $lastName);
			$email = mysqli_real_escape_string($conn, $email);
			$password = password_hash(mysqli_real_escape_string($conn, $password), PASSWORD_DEFAULT);

			if ($result = $conn->query("SELECT id FROM users WHERE email='" . $email . "'")) {		
				if($row = $result->fetch_all(MYSQLI_ASSOC)){
					header("Location: /pages/register.php?error=emailex");
					exit();
				} else if ($conn->query("INSERT INTO `cow`.`users` (`id`, `firstname`, `lastname`, `email`, `password`) VALUES (NULL, '" . $firstName . "', '" . $lastName . "', '" . $email . "', '" . $password . "')")) {
					$result = $conn->query("SELECT id FROM users WHERE email='". $email ."'");
					$uid = $result->fetch_all(MYSQLI_ASSOC);
					if ($conn->query("INSERT INTO `cow`.`dailyplan` (`id`, `uid`, `breakfast`, `lunch`, `dinner`, `snacks`) VALUES (NULL, '". $uid[0]['id'] ."', '', '', '', '');")) {
						header("Location: /pages/login.php");
						exit();
					}
				}
			}
		}
	} else {
		header("Location: ../index.php");
		exit();
	}
?>