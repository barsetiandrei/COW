<?php 
	require '../php/session.php';
	include '../php/db_connect.php';
	include '../php/metrics.php';

	$conn = OpenCon();
	$uid = $_SESSION['uid'];
	$caloriesGrandTotal = $carbsGrandTotal = $fatGrandTotal = $proteinGrandTotal = $sodiumGrandTotal = $sugarGrandTotal = 0;

	function showMeal($meal, $conn, $mealName) {
		$caloriesTotal = $carbsTotal = $fatTotal = $proteinTotal = $sodiumTotal = $sugarTotal = 0;
		global $caloriesGrandTotal, $carbsGrandTotal, $fatGrandTotal, $proteinGrandTotal, $sodiumGrandTotal, $sugarGrandTotal;

		echo '<form action="/php/food-actions.php" method="post">';

		if ($meal[0] == "") {
			echo "<tr><th>Nothing selected, please add some items.</th></tr>";
			echo '<td><button name="pick-' . $mealName . '" class="btn">Pick Food</button></td>';
			echo '<td><button name="add-' . $mealName . '" class="btn">Add Food</button></td></form>';
		} else {
			foreach ($meal as $item) {
				$ar = explode("-", $item);
				$foodId = $ar[0];
				$quantity = $ar[1];
				$multiplicationIndex = $quantity/100;

				if ($res = $conn->query("SELECT * FROM foods WHERE id='" . $foodId . "'")) {
					$food = $res->fetch_all(MYSQLI_ASSOC);
					echo '<tr>';
					echo "<td>" . $food[0]["name"] . "</td>";
					echo "<td>" . $quantity . "g</td>";
					echo "<td>" . $calories = round($food[0]["calories"]*$multiplicationIndex) . "</td>";
					echo "<td>" . $carbs = round($food[0]["carbs"]*$multiplicationIndex) . "</td>";
					echo "<td>" . $fat = round($food[0]["fat"]*$multiplicationIndex) . "</td>";
					echo "<td>" . $protein = round($food[0]["protein"]*$multiplicationIndex) . "</td>";
					echo "<td>" . $sodium = round($food[0]["sodium"]*$multiplicationIndex) . "</td>";
					echo "<td>" . $sugar = round($food[0]["sugar"]*$multiplicationIndex) . "</td>";
					echo '<td><button name="remove-' . $mealName . '-' . $foodId . '" class="btn">Remove</button></td>';
					echo "</tr>";

					$caloriesTotal += $calories;
					$carbsTotal += $carbs;
					$fatTotal += $fat;
					$proteinTotal += $protein;
					$sodiumTotal += $sodium;
					$sugarTotal += $sugar;
				}
			}

			echo "<tr>";
			echo '<td><button name="pick-' . $mealName . '" class="btn">Pick Food</button></td>';
			echo '<td><button name="add-' . $mealName . '" class="btn">Add Food</button></td></form>';
			echo "<td>" . $caloriesTotal . "</td>";
			echo "<td>" . $carbsTotal . "</td>";
			echo "<td>" . $fatTotal . "</td>";
			echo "<td>" . $proteinTotal . "</td>";
			echo "<td>" . $sodiumTotal . "</td>";
			echo "<td>" . $sugarTotal . "</td>";
			echo "</tr>";

			$caloriesGrandTotal += $caloriesTotal;
			$carbsGrandTotal += $carbsTotal;
			$fatGrandTotal += $fatTotal;
			$proteinGrandTotal += $proteinTotal;
			$sodiumGrandTotal += $sodiumTotal;
			$sugarGrandTotal += $sugarGrandTotal;
		}

		
	}

	if($result = $conn->query("SELECT * FROM dailyplan WHERE uid='" . $uid . "'")){
		if ($row = $result->fetch_all(MYSQLI_ASSOC)) {
			$breakfast = explode(";", $row[0]["breakfast"]);
			$lunch = explode(";", $row[0]["lunch"]);
			$dinner = explode(";", $row[0]["dinner"]);
			$snacks = explode(";", $row[0]["snacks"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cal-O-Web</title>
	<link rel="stylesheet" type="text/css" href="/css/mydailyplan.css"/>
</head>
<body>
	<section class="hero">
	<div class="top-bar">
		<img src="/images/logo.png">
	</div>
	<div class="top-button">
		<a href="/pages/mycalorieskeeper.php" class="login-btn">My Calories Keeper</a>
		<a href="/pages/calculator.php">Calculator</a>
		<a href="/php/generateXML.php">Generate Report</a>
		<a href="/pages/reset.php">Change Password</a>
		<form action="/php/signout.php" method="post">
			<button id="signout" class="login-btn">Sign Out</button>
		</form>
	</div>
	</section>

   <h1 id="breakfast"> Breakfast</h1>
   <table>
       <tr>
	      <th> Food Name</th>
		  <th> Quantity</th>
		  <th> Calories</th>
		  <th> Carbs</th>
		  <th> Fat</th>
		  <th> Protein</th>
		  <th> Sodium (mg)</th>
		  <th> Sugar</th>
		  <th></th>
		</tr> 

		<?php showMeal($breakfast, $conn, "breakfast"); ?>
	    
   </table>
   
   <h1> Lunch</h1>
   <table>
        <tr>
			<th> Food Name</th>
			<th> Quantity</th>
			<th> Calories</th>
			<th> Carbs</th>
			<th> Fat</th>
			<th> Protein</th>
			<th> Sodium (mg)</th>
			<th> Sugar</th>
			<th></th>
		</tr> 

		<?php showMeal($lunch, $conn, "lunch"); ?>

   </table>

   <h1> Dinner</h1>
   <table>
        <tr>
			<th> Food Name</th>
			<th> Quantity</th>
			<th> Calories</th>
			<th> Carbs</th>
			<th> Fat</th>
			<th> Protein</th>
			<th> Sodium (mg)</th>
			<th> Sugar</th>
			<th></th>
		</tr> 

		<?php showMeal($dinner, $conn, "dinner"); ?>

   </table>
   
   <h1> Snacks</h1>
    <table>
        <tr>
			<th> Food Name</th>
			<th> Quantity</th>
			<th> Calories</th>
			<th> Carbs</th>
			<th> Fat</th>
			<th> Protein</th>
			<th> Sodium (mg)</th>
			<th> Sugar</th>
			<th></th>
		</tr>  		  
		
		<?php showMeal($snacks, $conn, "snacks"); ?>

	</table>
	   <br>
	   <br>
	   <div>
	<table>
		<th>
			<td>Calories</td>
			<td>Carbs</td>
			<td>Fat</td>
			<td>Protein</td>
			<td>Sodium (mg)</td>
			<td>Sugar</td>
		</th>
        <tr>
		  <td>Totals</td>
		  <?php
		  	echo "<td>". $caloriesGrandTotal ."</td>";
		  	echo "<td>". $carbsGrandTotal ."</td>";
		  	echo "<td>". $fatGrandTotal ."</td>";
		  	echo "<td>". $proteinGrandTotal ."</td>";
		  	echo "<td>". $sodiumGrandTotal ."</td>";
		  	echo "<td>". $sugarGrandTotal ."</td>";

		  	$_SESSION['totals']['calories'] = $caloriesGrandTotal;
			$_SESSION['totals']['carbs'] = $carbsGrandTotal;
			$_SESSION['totals']['fat'] = $fatGrandTotal;
			$_SESSION['totals']['protein'] = $proteinGrandTotal;
			$_SESSION['totals']['sodium'] = $sodiumGrandTotal;
			$_SESSION['totals']['sugar'] = $sugarGrandTotal;
			
			updateMetrics();
		  ?>
	    </tr>	
   		<tr>
			<td>Your Daily Goal</td>
			<td>1590</td>
			<td>199</td>
			<td>53</td>
			<td>79</td>
			<td>2300</td>
			<td>60</td>
	    </tr>	
    	<tr>   
			<td>Remaining</td>
			<td>782</td>
			<td>75</td>
			<td>27</td>
			<td>44</td>
			<td>2114</td>
			<td>31</td>
	    </tr>	
	</table>
    </div>  		  
	   
	<?php 
		//Close brackets from up top.
		}
	}
	?>
</body>
</html>
