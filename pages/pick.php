<?php 
	require '../php/session.php';
	include '../php/db_connect.php';
	$conn = OpenCon();

	if (isset($_GET['meal']) and ($_GET['meal'] == "breakfast" or $_GET['meal'] == "lunch" or $_GET['meal'] == "dinner" or $_GET['meal'] == "snacks")) {
		$meal = $_GET['meal'];
	} else {
		header("Location: /pages/mydailyplan.php");
		exit();
	}

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
   <h1>Food List</h1>
   <table>
       <tr>
	      <th> Food Name</th>
		  <th> Calories</th>
		  <th> Carbs</th>
		  <th> Fat</th>
		  <th> Protein</th>
		  <th> Sodum</th>
		  <th> Sugar</th>
		  <th></th>
		</tr> 
		<form action="/php/food-actions.php" method="post">
		<?php 
			if ($result = $conn->query("SELECT * FROM foods")){
				if ($foods = $result->fetch_all(MYSQLI_ASSOC)) {
					foreach ($foods as $food) {
						echo '<tr>';
						echo "<td>" . $food["name"] . "</td>";
						echo "<td>" . $calories = $food["calories"] . "</td>";
						echo "<td>" . $carbs = $food["carbs"] . "</td>";
						echo "<td>" . $fat = $food["fat"] . "</td>";
						echo "<td>" . $protein = $food["protein"] . "</td>";
						echo "<td>" . $sodium = $food["sodium"] . "</td>";
						echo "<td>" . $sugar = $food["sugar"] . "</td>";
						echo '<td><button name="addex-'. $meal . '-' . $food["id"] . '" class="btn">Pick Food</button></td>';
						echo "</tr>";
					}
				}
			}
		?>
	    </form>
   </table>
   
</body>
</html>