<?php 
	require '../php/session.php';
	include '../php/db_connect.php';

	$conn = OpenCon();
	$uid = $_SESSION['uid'];

	foreach($_POST as $key => $value) {
		$info = explode("-", $key);
		$meal = $info[1];
		if (isset($info[2])) {
			$foodId = $info[2];
		}
		
		if ($info[0] == "pick") {
			header("Location: ../pages/pick.php?meal=" . $info[1]);
			exit();
		} else if ($info[0] == "remove") {
			if ($result = $conn->query("SELECT " . $meal . " FROM dailyplan WHERE uid='" . $uid . "'")) {
				$food = $result->fetch_all(MYSQLI_ASSOC);
				$array = explode(";", $food[0][$meal]);
				
				foreach ($array as $key => $item) {
					if (strpos($item, $foodId."-") === 0) {
						unset($array[$key]);
						break;
					}
				}

				$updated = implode(";", $array);

				if ($conn->query("UPDATE dailyplan SET " . $meal . " = '" . $updated . "' WHERE uid='" . $uid . "'")) {
					header("Location: ../pages/mydailyplan.php");
					exit();
				}
			}
		} else if ($info[0] == "add") {
			header("Location: ../pages/add.php");
			exit();
		} else if ($info[0] == "addex") {			
			if ($result = $conn->query("SELECT " . $meal . " FROM dailyplan WHERE uid='" . $uid . "'")) {
				$food = $result->fetch_all(MYSQLI_ASSOC);
				if (empty($food[0][$meal])) {
					$updated = $foodId . "-100";
				} else {
					$updated = $food[0][$meal] . ";" . $foodId . "-100";
				}
				
				if ($conn->query("UPDATE dailyplan SET " . $meal . " = '" . $updated . "' WHERE uid='" . $uid . "'")) {
					header("Location: ../pages/mydailyplan.php");
					exit();
				}
			}	
		}
	}
?>