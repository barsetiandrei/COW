<?php 
	session_start();

	if (!isset($_SESSION['user']) or !isset($_SESSION['uid'])) {
		header("Location: /pages/login.php");
		exit();
	}
?>