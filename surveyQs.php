<?php
	session_start();

	if (!isset($_SESSION['visited']))
	{
		$_SESSION['visited'] = 0;
	}

	$_SESSION['visited'] = $_SESSION['visited']++;

	if ($_SESSION['visited'] > 1)
	{
		header("location:survey.php");
	}
?>
<!DOCTYPE html>

<html>
	<head>
		<title>Favorite Fresh Foods Survey</title>
		<link rel="stylesheet" type="text/css" href="survey.css" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js" > </script>
		<script src="survey.js" > </script>
	</head>

	<body onload="load()">
		<h1> Welcome to the Fresh Foods Survey! </h1>
		<a href="survey.php" >Go to Results Page </a>

		<p> Thank you so much for your participation today!
		    Please fill out the below form as completely
		    and honestly as you can. Also make sure to fill 
		    in the form from top to bottom. 
		</p>
		<form action="survey.php" method="post">
			<div>
				Which category of foods would you prefer? <br />
				<input type="radio" name="foodPref" value="Fruits" onclick="fruitOptions()"/> Fruit <br />
				<input type="radio" name="foodPref" value="Vegetables" onclick="vegOptions()"/> Vegitables <br />
				<input type="radio" name="foodPref" value="Meats" onclick="meatOptions()" /> Meats <br />
				<input type="radio" name="foodPref" value="Dairy" onclick="dairyOptions()" /> Dairy <br />
				<input type="radio" name="foodPref" value="Other" onclick="otherOptions()" /> Other <br />

				<div id="fruitQs" onclick="fruitOptions()" >
					Which fruits do you prefer? <br />
					<input type="checkbox" name="fruitPref[]" value="Sapodilla" /> Sapodilla <br />
					<input type="checkbox" name="fruitPref[]" value="Safou" /> Safou <br />
					<input type="checkbox" name="fruitPref[]" value="Dates" /> Dates <br />
					<input type="checkbox" name="fruitPref[]" value="Grapefruit" /> Grapefruit <br />
				</div>

				<div id="vegQs">
					Which Vegitables do you prefer? <br >
					<input type="checkbox" name="vegPref[]" value="Brussel Sprouts" /> Brussel Sprouts <br />
					<input type="checkbox" name="vegPref[]" value="Fat Hen" /> Fat Hen <br />
					<input type="checkbox" name="vegPref[]" value="Parsnips" /> Parsnips <br />
					<input type="checkbox" name="vegPref[]" value="Yams" /> Yams <br />
				</div>

				<div id="meatQs" >
					Which meats do you prefer? <br />
					<input type="checkbox" name="meatPref[]" value="Duck" /> Duck <br />
					<input type="checkbox" name="meatPref[]" value="Alligator" /> Alligator <br />
					<input type="checkbox" name="meatPref[]" value="Goat" /> Goat <br />
					<input type="checkbox" name="meatPref[]" value="Catfish" /> Catfish <br />
				</div>

				<div id="dairyQs" >
					Which dairy do you prefer? <br />
					<input type="checkbox" name="dairyPref[]" value="Goat Milk" /> Goat Milk <br />
					<input type="checkbox" name="dairyPref[]" value="Eggs" /> Eggs <br />
					<input type="checkbox" name="dairyPref[]" value="Swiss Cheese" /> Swiss Cheese <br />
					<input type="checkbox" name="dairyPref[]" value="Ice Cream" /> Ice Cream <br />						
				</div>

				<div id="otherQs" >
					Which other food do you prefer? <br />
					<input type="checkbox" name="otherPref[]" value="Black Licorice" /> Black Licorice <br />
					<input type="checkbox" name="otherPref[]" value="Soy Milk" /> Soy Milk <br />
					<input type="checkbox" name="otherPref[]" value="Water" /> Water <br />
					<input type="checkbox" name="otherPref[]" value="Black Jack Gum" /> Black Jack Gum <br />
				</div>
				What is your favorite food: 
				<input type="text" name="favFood" id="" placeholder="ex: Tomatoes"/> <br />
				<div id="frustrated">
					Was this survey frustrating? <br />
					<input type="radio" name="frustrating" value="yes" /> Yes <br />
					<input type="radio" name="frustrating" value="no" /> No <br />
				</div>

			</div>
			<input type="submit" name="Submit" onmouseover="revealFrustrationQ()" />
		</form>
	</body>
</html>