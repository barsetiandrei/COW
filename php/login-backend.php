<?php 
	if(isset($_POST['login-submit'])){
		$email = trim($_POST['email']);
		$password = trim($_POST['password']);

		if(!empty($email) and !empty($password)){
			include 'db_connect.php';
			$conn = OpenCon();

			$email = mysqli_real_escape_string($conn, $email);
			$password = mysqli_real_escape_string($conn, $password);

			if($result = $conn->query("SELECT id, email, password FROM users WHERE email='". $email ."'")){
				if($row = $result->fetch_all(MYSQLI_ASSOC)){
					if(password_verify($password, $row[0]['password'])){
						session_start();
						$_SESSION['user'] = $row[0]['email'];
						$_SESSION['uid'] = $row[0]['id'];

						header("Location: /pages/mydailyplan.php");
						exit();
					}else{
						header("Location: /pages/login.php?error=wrongpass");
						exit();
					}
				}else{
					header("Location: /pages/login.php?error=nouser");
					exit();
				}
			}else{
				die();
			}

			CloseCon($conn);
		}else{
			header("Location: /pages/login.php?error=empty");
			exit();
		}
	} else {
		header("Location: ../index.php");
		exit();
	}

?>