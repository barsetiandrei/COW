<?php
	if (isset($_POST['change-submit'])) {
		$oldPassword = trim($_POST['oldpw']);
		$password = trim($_POST['password']);
		$passwordCheck = trim($_POST['password-check']);

		if (!empty($oldPassword) and !empty($password) and !empty($passwordCheck)) {
			if ($password == $passwordCheck) {
				include 'db_connect.php';
				session_start();

				$uid = $_SESSION['uid'];
				$conn = OpenCon();

				$oldPassword = mysqli_real_escape_string($conn, $oldPassword);
				$password = mysqli_real_escape_string($conn, $password);
				$passwordCheck = mysqli_real_escape_string($conn, $passwordCheck);

				if ($result = $conn->query("SELECT password FROM users WHERE id='". $uid ."'")) {
					if($row = $result->fetch_all(MYSQLI_ASSOC)) {
						if(password_verify($oldPassword, $row[0]['password'])) {
							if ($conn->query("UPDATE users SET password='". password_hash($password, PASSWORD_DEFAULT) ."' WHERE id='". $uid ."'")) {
								header("Location: /pages/mycalorieskeeper.php");
								exit();
							}
						} else {
							header("Location: /pages/reset.php?error=wrongpass");
							exit();
						}
					}
				}
			} else {
				header("Location: /pages/reset.php?error=nomatch");
				exit();
			}
		} else{
			header("Location: /pages/reset.php?error=empty");
			exit();
		}
	}
?>