<?php
	function updateMetrics(){
		global $uid, $conn;
		$insert = "INSERT INTO `cow`.`metrics` (`id`, `uid`, `date`, `calories`, `carbs`, `fat`, `protein`, `sodium`, `sugar`) VALUES (NULL, '". $uid ."', NOW(), '". $_SESSION['totals']['calories'] ."', '". $_SESSION['totals']['carbs'] ."', '". $_SESSION['totals']['fat'] ."', '". $_SESSION['totals']['protein'] ."', '". $_SESSION['totals']['sodium'] ."', '". $_SESSION['totals']['sugar'] ."')";

		if ($result = $conn->query("SELECT * FROM metrics WHERE uid='". $uid ."'")) {
			if ($rows = $result->fetch_all(MYSQLI_ASSOC)) {
				$found = false;
				foreach ($rows as $row) {
					if ($row['date'] == date('Y-m-d')) {
						if ($row['calories'] != $_SESSION['totals']['calories'] or $row['carbs'] != $_SESSION['totals']['carbs'] or $row['fat'] != $_SESSION['totals']['fat'] or $row['protein'] != $_SESSION['totals']['protein'] or $row['sodium'] != $_SESSION['totals']['sodium'] or $row['sugar'] != $_SESSION['totals']['sugar']) {
							$conn->query("UPDATE metrics SET calories='". $_SESSION['totals']['calories'] ."', carbs='". $_SESSION['totals']['carbs'] ."', fat='". $_SESSION['totals']['fat'] ."', protein='". $_SESSION['totals']['protein'] ."', sodium='". $_SESSION['totals']['sodium'] ."', sugar='". $_SESSION['totals']['sugar'] ."' WHERE id='". $row['id'] ."' AND date='". $row['date'] ."'");
						}
						$found = true;
					}
				}
				if (!$found) {
					$conn->query($insert);
				}
			} else {
				$conn->query($insert);
			}
		}
	}

	function getMetrics($date){
		global $uid, $conn;

		if ($result = $conn->query("SELECT * FROM metrics WHERE uid='". $uid ."' ORDER BY date ASC")) {
			if ($rows = $result->fetch_all(MYSQLI_ASSOC)) {
				echo '<table>
						<tr>
							<th> Date</th>
							<th> Calories</th>
							<th> Carbs</th>
							<th> Fat</th>
							<th> Protein</th>
							<th> Sodium (mg)</th>
							<th> Sugar</th>
						</tr> 
				';

				$noneFound = true;

				switch ($date) {
					case 1:
						foreach ($rows as $row) {
							echo '<tr>';
							echo '<td>'. $row['date'] .'</td>';
							echo '<td>'. $row['calories'] .'</td>';
							echo '<td>'. $row['carbs'] .'</td>';
							echo '<td>'. $row['fat'] .'</td>';
							echo '<td>'. $row['protein'] .'</td>';
							echo '<td>'. $row['sodium'] .'</td>';
							echo '<td>'. $row['sugar'] .'</td>';
							echo '</tr>';
							$noneFound = false;
						}
						break;
					
					case 3:
						foreach ($rows as $row) {
							if ((strtotime("today")-strtotime($row['date'])) <= 3) {
								echo '<tr>';
								echo '<td>'. $row['date'] .'</td>';
								echo '<td>'. $row['calories'] .'</td>';
								echo '<td>'. $row['carbs'] .'</td>';
								echo '<td>'. $row['fat'] .'</td>';
								echo '<td>'. $row['protein'] .'</td>';
								echo '<td>'. $row['sodium'] .'</td>';
								echo '<td>'. $row['sugar'] .'</td>';
								echo '</tr>';
								$noneFound = false;
							}
						}
						break;

					case 7:
						foreach ($rows as $row) {
							$numberOfDays = (strtotime("today")-strtotime($row['date'])) / (60 * 60 * 24);

							if ($numberOfDays <= 7) {
								echo '<tr>';
								echo '<td>'. $row['date'] .'</td>';
								echo '<td>'. $row['calories'] .'</td>';
								echo '<td>'. $row['carbs'] .'</td>';
								echo '<td>'. $row['fat'] .'</td>';
								echo '<td>'. $row['protein'] .'</td>';
								echo '<td>'. $row['sodium'] .'</td>';
								echo '<td>'. $row['sugar'] .'</td>';
								echo '</tr>';
								$noneFound = false;
							}
						}
						break;

					case 14:
						foreach ($rows as $row) {
							$numberOfDays = (strtotime("today")-strtotime($row['date'])) / (60 * 60 * 24);

							if ($numberOfDays <= 14) {
								echo '<tr>';
								echo '<td>'. $row['date'] .'</td>';
								echo '<td>'. $row['calories'] .'</td>';
								echo '<td>'. $row['carbs'] .'</td>';
								echo '<td>'. $row['fat'] .'</td>';
								echo '<td>'. $row['protein'] .'</td>';
								echo '<td>'. $row['sodium'] .'</td>';
								echo '<td>'. $row['sugar'] .'</td>';
								echo '</tr>';
								$noneFound = false;
							}
						}
						break;

					case 30:
						foreach ($rows as $row) {
							$numberOfDays = (strtotime("today")-strtotime($row['date'])) / (60 * 60 * 24);

							if ($numberOfDays <= 30) {
								echo '<tr>';
								echo '<td>'. $row['date'] .'</td>';
								echo '<td>'. $row['calories'] .'</td>';
								echo '<td>'. $row['carbs'] .'</td>';
								echo '<td>'. $row['fat'] .'</td>';
								echo '<td>'. $row['protein'] .'</td>';
								echo '<td>'. $row['sodium'] .'</td>';
								echo '<td>'. $row['sugar'] .'</td>';
								echo '</tr>';
								$noneFound = false;
							}
						}
						break;

					default:
						break;
				}

				if ($noneFound) {
					echo "<td>You don't currently have any stored metrics</td>";
				}

				echo '<table>';
			} else {
				echo "No metrics available, please configure your daily plan.";
			}
		}
	}
?>