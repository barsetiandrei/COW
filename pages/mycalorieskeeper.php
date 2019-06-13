<?php 
	require '../php/session.php';
	include '../php/db_connect.php';
	include '../php/metrics.php';

	$conn = OpenCon();
	$uid = $_SESSION['uid'];
	$totalCaloricIntake = 100;
	$profileNotSet = $metricsNotSet = false;

	function getTotalCalories($meal, $conn) {
		global $totalCaloricIntake;

		foreach ($meal as $item) {
			$food = explode("-", $item);
			$foodId = $food[0];
			$quantity = $food[1]/100;

			if ($result = $conn->query("SELECT calories FROM foods WHERE id='". $foodId ."'")) {
				$f = $result->fetch_all(MYSQLI_ASSOC);
				$totalCaloricIntake += round($f[0]["calories"]*$quantity);
			}
		}
	}

	if ($result = $conn->query("SELECT age, gender, height, weight, exercise, goal FROM profile WHERE uid='". $uid ."'")) {
		if ($profile = $result->fetch_all(MYSQLI_ASSOC)) {
			$activity = array(1.2, 1.375, 1.55, 1.725, 1.9);

			if ($profile[0]["gender"] == "male") {
				$bmr = 66 + 13.7*$profile[0]["weight"] + 5*$profile[0]["height"] - 6.8*$profile[0]["age"];
			} else {
				$bmr = 655 + 9.6*$profile[0]["weight"] + 1.8*$profile[0]["height"] - 4.7*$profile[0]["age"];
			}

			$exercise = $profile[0]["exercise"];
			$maintain = round($activity[$exercise]*$bmr);
			$goal = $profile[0]["goal"];
			$personWeight = $profile[0]["weight"];
		} else {
			$profileNotSet = true;
		}
		
	}

	if ($result = $conn->query("SELECT id FROM metrics WHERE uid='". $uid ."'")) {
		if (!$result->fetch_all(MYSQLI_ASSOC)) {
			$metricsNotSet = true;
			echo "metrics not set";
		}
	}

	if (!$profileNotSet) {
		if ($result = $conn->query("SELECT breakfast, lunch, dinner, snacks FROM dailyplan WHERE uid='". $uid ."'")) {
			$dailyplan = $result->fetch_all(MYSQLI_ASSOC);

			$breakfast = explode(";", $dailyplan[0]["breakfast"]);
			$lunch = explode(";", $dailyplan[0]["lunch"]);
			$dinner = explode(";", $dailyplan[0]["dinner"]);
			$snacks = explode(";", $dailyplan[0]["snacks"]);

			if ($breakfast[0] != "") {
				getTotalCalories($breakfast, $conn);
			}
			if ($lunch[0] != "") {
				getTotalCalories($lunch, $conn);
			}
			if ($dinner[0] != "") {
				getTotalCalories($dinner, $conn);
			}
			if ($snacks[0] != "") {
				getTotalCalories($snacks, $conn);
			}

		}
	}
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cal-O-Web</title>
	<link rel="stylesheet" type="text/css" href="/css/mycalorieskeeper.css"/>
</head>
<body>
	<section class="hero">
	<div class="top-bar">
		<img src="/images/logo.png">
	</div>
	<div class="top-button">
		<a href="/pages/mydailyplan.php" class="login-btn">My Daily Plan</a>
		<a href="/pages/calculator.php">Calculator</a>
		<a href="/php/generateXML.php">Generate Report</a>
		<a href="/pages/reset.php">Change Password</a>
		<form action="/php/signout.php" method="post">
			<button id="signout" class="login-btn">Sign Out</button>
		</form>
	</div>
	</section>

	<section class="content">
		<p><?php  
			if (isset($_POST['date'])) {
				$date = $_POST['date'];
			} else {
				$date = 1;
			}

			getMetrics($date);

			if (!$metricsNotSet) {
		?>
			<br>
			<form action="/pages/mycalorieskeeper.php" method="post">
				<select name="date" id="dates">
		            <option value="1" <?php if ($date == 1) { echo 'selected'; } ?>>All dates</option>
		            <option value="3" <?php if ($date == 3) { echo 'selected'; } ?>>Last 3 days</option>
		            <option value="7" <?php if ($date == 7) { echo 'selected'; } ?>>Last week</option>
		            <option value="14" <?php if ($date == 14) { echo 'selected'; } ?>>Last 2 weeks</option>
					<option value="30" <?php if ($date == 30) { echo 'selected'; } ?>>Last Month</option>
	        	</select>
	        	<button name="change-submit" class="btn">Change</button>
			</form>
		<?php } 
			if ($profileNotSet or $metricsNotSet) {
				echo "<br>Your profile has not yet been defined, please take a look at our 'Calculator' page in order to do this.";
			} else {
		?>
		</p>
		<br>
		<br>
		<h3>Caloric Intake:</h3>
		<br>
		<p><?php
			
				$difference = $totalCaloricIntake-$maintain;
				$weight = round(((0.45*(abs($difference)*7))/3500), 2);

				echo "Daily caloric total: ". $totalCaloricIntake ."<br>";
				echo "Number of calories necessary for maintianing: ". $maintain ."<br><br>";
				switch ($goal) {
					case 'maintain':
						if (abs($difference)/$maintain < 0.01) {
							echo "You're right on target with your desired caloric intake, keep it up!";
						} else if ($difference/$maintain > -0.2 and $difference/$maintain < 0) {
							echo "You're close to your goal of maintaining your weight. Your caloric intake is a bit low, you'll need about ". round(abs($difference)) ." extra calories to be on target";
						} else if ($difference/$maintain < 0.2 and $difference/$maintain > 0) {
							echo "You're close to your goal of maintaining your weight. Your caloric intake is a bit high, you'll need around ". round(abs($difference)) ." fewer calories to be on target";
						} else if ($difference < 0){
							echo "Your caloric intake is too low to maintain, at this rate you'll lose ". $weight ." Kg per week, you'll need to increase your caloric intake by ". abs(round($difference)) ." to maintain your weight.";
						} else if ($difference > 0){
							echo "Your caloric intake is too high to maintain, at this rate you'll gain ". $weight ." Kg per week, you'll need to decrease your caloric intake by ". abs(round($difference)) ." to maintain your weight.";
						}
						break;
					
					case 'grow':
						if (abs($difference)/$maintain < 0.01) {
							echo "Your caloric intake is just high enough to maintain, at this rate you won't gain weight.";
						} else if ($difference/$maintain > -0.2 and $difference/$maintain < 0) {
							echo "Your caloric intake is under the level required for maintaining, at this rate you'll loose a bit of weight, you'll need about ". round(abs($difference)) ." extra calories to at least maintain.";
						} else if ($difference/$maintain < 0.2 and $difference/$maintain > 0) {
							echo "Your caloric intake is around ". round(abs($difference)) ." calories above maintaining, you might want to increase your caloric intake accordingly. Currently, you will gain ". $weight ." Kg per week.";
						} else if ($difference < 0){
							echo "Your caloric intake is too low to gain weight, at this rate you'll lose ". $weight ." Kg per week, you'll need to increase your caloric intake by ". abs(round($difference)) ." to at least maintain your weight.";
						} else if ($difference > 0){
							if ($weight > 1.1) {
								echo "You're gaining weight a bit too fast, right now it's ". $weight ." Kg per week. You should stay bellow 1 Kg per week in order to experience healthy gains.";
							} else {
								echo "You're right on track, at this rate you'll gain ". $weight ." Kg per week, you can adjust your caloric intake accordingly in order to reach your desired weight.";
							}
						}
						break;

					case 'lose':
						if (abs($difference)/$maintain < 0.01) {
							echo "Your caloric intake is high enough to maintain your current weight, at this rate you won't lose weight.";
						} else if ($difference/$maintain > -0.2 and $difference/$maintain < 0) {
							echo "Your caloric intake is ". round(abs($difference)) ." calories bellow maintaining, you might want to decrease it accordingly. Currently, you will lose ". $weight ." Kg per week.";
						} else if ($difference/$maintain < 0.2 and $difference/$maintain > 0) {
							echo "You're close to your goal of maintaining your weight. Your caloric intake is a bit high, you'll need around ". round(abs($difference)) ." fewer calories to be on target";
						} else if ($difference < 0){
							if ($weight > 1.1) {
								echo "You're losing weight a bit too fast, right now it's ". $weight ." Kg per week. A healthy maximum weight loss is around 1 Kg per week. Please adjust your diet accordingly.";
							} else {
								echo "You're right on track, at this rate you'll lose ". $weight ." Kg per week, you can adjust your caloric intake accordingly in order to reach your desired weight.";
							}
							
						} else if ($difference > 0){
							echo "Your caloric intake is too high to loose weight, at this rate you'll gain ". $weight ." Kg per week, you'll need to decrease your caloric intake by ". abs(round($difference)) ." to at least maintain your weight.";

						}
						break;

					default:
						break;
				}
			//Close this else at the end.
			
		?></p>
		<br>
		<h3>Exercise: </h3>
		<br>
		<p><?php
			switch ($exercise) {
				case 0:
					echo "Currently you're not exercising at all, no matter your goal, this is not healthy.<br>";

					if ($goal == "maintain") {
						echo "Even for the goal of maintaining exercising at least a few days a week is recommended.";
					} else if ($goal == "gorw") {
						echo "For your goal of growing muscle, frequent exercise is required, try to exercise at least 1-3 days a week.";
					} else if ($goal == "lose") {
						echo "In order to lose weight and minimize muscle loss, at least 1-3 days of exercise ar recommended.";
					}
					break;

				case 1:
					echo "You're currently doing the minimum recommended exercise.<br>";

					if ($goal == "maintain") {
						echo "For maintiaining your weight this is fine as long as your caloric intake matches the amount needed for maintaining.";
					} else if ($goal == "gorw") {
						echo "For your goal of growing muscle, frequent exercise is required, try to exercise a bit more.";
					} else if ($goal == "lose") {
						echo "In order to lose weight and minimize muscle loss you can stay at this level but doing a bit more exercise is recommnded.";
					}
					break;
				
				case 2:
					echo "You're doing a healthy amount of exercise.<br>";

					if ($goal == "maintain") {
						echo "If you're careful about your caloric intake you will maintain your weight. <br>";
					} else if ($goal == "gorw") {
						echo "You're doing enough exercise to match your goal of growing muscle, you could however do more if you'd like.";
					} else if ($goal == "lose") {
						echo "The amount of exercise you're doing is enough to avoid muscle loss while losing weight as long as your caloric intake is appropriate.";
					}
					break;
				
				case 3:
					if ($goal == "maintain") {
						echo "The amount of exercise you're doing might lead to muscle gorwth and or weigt loss, if you'd like to maintain be careful of you caloric intake and do a bit less exercise. <br>";
					} else if ($goal == "gorw") {
						echo "You're right on track with your goal, you're doing the right amount of exercise for growing muscle.";
					} else if ($goal == "lose") {
						echo "The amount of exercise you're doing is optimal for weight loss, you might also gain some muscle, make sure to keep an eye on your caloric intake.";
					}
					break;

				case 4:
				case 5:	
					echo "This much exercise can be dangerous and will cause extra strain on your body.";
					if ($goal == "maintain") {
						echo "Maintaining your weight might not be possible with this much exercise. <br>";
					} else if ($goal == "gorw") {
						echo "If you're aiming to quickly grow muscle this might be ok but it is still recommended to do so more gradualy.";
					} else if ($goal == "lose") {
						echo "This might lead to quick weight loss and some muscle gain but it is still not recommened to keep this up for long periods.";
					}
					break;

				default:
					break;
			}
		?></p>
		<br>
		<h3>Protein</h3>
		<br>
		<p><?php
			$proteinIndex = array(0.8, 1.2, 1.4, 1.6, 1.8, 2.0);
			$protein = $_SESSION['totals']['protein'];
			$proteinGoal = round($protein*$proteinIndex[$exercise]);
			$proteinDifference = $proteinGoal - $protein;

			echo "Taking into account the ammount of exercise you do and your weight (". $personWeight ." Kg) you need to consume ". $proteinGoal ." grams of protein. ";

			if (abs($proteinDifference)/$proteinGoal < 0.01) {
				echo "Right now, you're on target, keep it up!";
			} else if ($proteinDifference/$proteinGoal > -0.2 and $proteinDifference/$proteinGoal < 0) {
				echo "Your protein intake is a bit low, you need ". round(abs($proteinDifference)) ." grams of protein more to be on target.";
			} else if ($proteinDifference/$proteinGoal < 0.2 and $proteinDifference/$proteinGoal > 0) {
				echo "Your protein intake is a bit high, you need ". round(abs($proteinDifference)) ." fewer grams of protein to be on target.";
			} else if ($proteinDifference < 0){
				echo "Your intake of protein is too low, you're ". round(abs($proteinDifference)) ." grams of protein off of your recommended number.";
			} else if ($proteinDifference > 0){
				echo "Your intake of protein is too high, you're ". round(abs($proteinDifference)) ." grams of protein off of your recommended number.";
			} 		

		?></p>
		<br>
		<h3>Carbs</h3>
		<br>
		<p><?php
			$carbs = $_SESSION['totals']['carbs'];
			$carbGoal = round($totalCaloricIntake / 8);
			$carbDifference = $carbGoal - $carbs;

			echo "Based on your current caloric intake, you need to consume ". $carbGoal ." grams of carbs. ";

			if (abs($carbDifference)/$carbGoal < 0.01) {
				echo "You're on target with your carb intake, keep it up!";
			} else if ($carbDifference/$carbGoal > -0.2 and $carbDifference/$carbGoal < 0) {
				echo "Your carb intake is low, you need ". round(abs($carbDifference)) ." grams of carbs more to reach the recommended value.";
			} else if ($carbDifference/$carbGoal < 0.2 and $carbDifference/$carbGoal > 0) {
				echo "Your carbs are on the high side, you need ". round(abs($carbDifference)) ." fewer grams of carbs to be on target.";
			} else if ($carbDifference < 0){
				echo "Your intake of carbs is too low, you're ". round(abs($carbDifference)) ." grams of carbs off of your recommended number.";
			} else if ($carbDifference > 0){
				echo "Your carbs levels are low, you're ". round(abs($carbDifference)) ." grams of carbs off of your recommended number.";
			} 	
		?></p>
	</section>
	<br>
	<h3>Fat</h3>
	<br>
	<p><?php
		$fat = $_SESSION['totals']['fat'];
		$fatGoal = round((0.2 * $totalCaloricIntake) / 9);
		$fatDifference = $fatGoal - $fat;

		echo "Your current caloric intake makes the amout of fat you need ". $fatGoal ." grams. ";

		if (abs($fatDifference)/$fatGoal < 0.01) {
			echo "Right now your fat intake is appropriate, keep it up!";
		} else if ($fatDifference/$fatGoal > -0.2 and $fatDifference/$fatGoal < 0) {
			echo "Your fat intake is low, you need ". round(abs($fatDifference)) ." grams of fat more to reach the recommended value.";
		} else if ($fatDifference/$fatGoal < 0.2 and $fatDifference/$fatGoal > 0) {
			echo "You should consume a bit less fat, you need ". round(abs($fatDifference)) ." fewer grams of carbs to be at the appropriate level.";
		} else if ($fatDifference < 0){
			echo "Your intake of fat is too low, you're ". round(abs($fatDifference)) ." grams off of your recommended number.";
		} else if ($fatDifference > 0){
			echo "Your fat levels are low, you're ". round(abs($fatDifference)) ." grams off of the right amount.";
		} 
	?></p>
	<br>
	<h3>Sodium</h3>
	<br>
	<p><?php 
		$sodium = $_SESSION['totals']['sodium'];

		echo "The American Heart Association recommends a maximum of 2300 mg of sodium per day, the true number being closer to no more than 1500 mg per day, right now your consuming ". $sodium ." mg per day. ";

		if ($sodium < 1500) {
			echo "You're right on track, keep it up.";
		} else if ($sodium > 1500) {
			echo "You should aim to get this number under 1500 mg/day.";
		} else if ($sodium > 2300) {
			echo "You're over the maximum allowed amount. Please make efforts to get this number lower.";
		}
	?></p>
	<br>
	<h3>Sugar</h3>
	<br>
	<p><?php
		$sugar = $_SESSION['totals']['sugar'];
		$sugarGoal = (0.1 * $totalCaloricIntake) / 4; 

		echo "Sugar should not represent more than 10% of your caloric intake, based on your caloric intake you should be consuming ". $sugar ." grams daily. ";

		if ($sugarGoal > $sugar) {
			echo "Right now you're only consuming ". $sugar ." grams, good job!";
		} else if ($sugarGoal < $sugar) {
			echo "You're eating around ". $sugar ." grams of sugar daily, you should aim to get this number under the threshold.";
		}

	} //Close else from begining
	?></p>
</body>
</html>
 
