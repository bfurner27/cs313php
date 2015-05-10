<?php
	session_start();


	function displayFile() 
	{
		$file = fopen("results.txt", "r+");

		$display = "The results of the survey! <br />\n";		
		
		$foodPref = fgets($file);
		$fruitPref = fgets($file);
		$vegPref = fgets($file);
		$meatPref = fgets($file);
		$dairyPref = fgets($file);
		$otherPref = fgets($file);
		$frustrated = fgets($file);
		$favFood = fgets($file);

		fclose($file);

		$foodPref = explode(",", $foodPref);
		$display .= "<h3>" . $foodPref[0] . "</h3>\n";

		$fruitPref = explode(",", $fruitPref);
		$display .= "<div>";
		for ($i = 0; $i < count($fruitPref); $i++)
		{
			$items = explode("-", $fruitPref[$i]);
			$display .= $items[0] . ": " . $items[1] . "<br />";
		}
		$display .= "</div>";


		$display .= "<h3>" . $foodPref[1] . "</h3>\n";
		$vegPref = explode(",", $vegPref);
		$display .= "<div>";
		for ($i = 0; $i < count($vegPref); $i++)
		{
			$items = explode("-", $vegPref[$i]);
			$display .= $items[0] . ": " . $items[1] . "<br />";
		}
		$display .= "</div>";

		
		$display .= "<h3>" . $foodPref[2] . "</h3>\n";
		$meatPref = explode(",", $meatPref);
		$display .= "<div>";
		for ($i = 0; $i < count($meatPref); $i++)
		{
			$items = explode("-", $meatPref[$i]);
			$display .= $items[0] . ": " . $items[1] . "<br />";
		}
		$display .= "</div>";


		$display .= "<h3>" . $foodPref[3] . "</h3>\n";
		$dairyPref = explode(",", $dairyPref);
		$display .= "<div>";
		for ($i = 0; $i < count($dairyPref); $i++)
		{
			$items = explode("-", $dairyPref[$i]);
			$display .= $items[0] . ": " . $items[1] . "<br />";
		}
		$display .= "</div>";


		$display .= "<h3>" . $foodPref[4] . "</h3>\n";
		$otherPref = explode(",", $otherPref);
		$display .= "<div>";
		for ($i = 0; $i < count($otherPref); $i++)
		{
			$items = explode("-", $otherPref[$i]);
			$display .= $items[0] . ": " . $items[1] . "<br />";
		}
		$display .= "</div>";


		$display .= "<h3>Frustration Level:</h3>";
		$display .= "<div>";
		$frustrated = explode(",", $frustrated);
		for ($i = 0; $i < count($frustrated); $i++)
		{
			$items = explode("-", $frustrated[$i]);
			$display .= $items[0] . ": " . $items[1] . "<br />";
		}
		$display .= "</div>";

		$display .= "<h3>Favorite Foods:</h3>";
		$favFood = explode(",", $favFood);
		$display .= "<div>";
		for ($i = 0; $i < count($favFood); $i++)
		{
			$display .= $favFood[$i] . "<br />";
		}
		$display .= "</div>";


		return $display;
	}

	function updateFile () 
	{
		$file = fopen("results.txt", "w+");
		
		if ($file === false)
		{
			$formatedString = "Fruit,Vegetables,Meats,Dairy,Other\n";
			$formatedString .= "Sapodilla-0,Safou-0,Dates-0,Grapefruit-0\n";
			$formatedString .= "Brussel Sprouts-0,Fat Hen-0,Parsnips-0,Yams-0\n";
			$formatedString .= "Duck-0,Alligator-0,Goat-0,Catfish-0\n";
			$formatedString .= "Goat Milk-0,Eggs-0,Swiss Cheese-0,Ice Cream-0\n";
			$formatedString .= "Black Licorice-0,Soy Milk-0,Water-0,Black Jack Gum-0\n";
			$formatedString .= "Number Frustrated-0,Number Not Frustrated-0\n";

			fwrite($file, $formatedString);
		}
		else
		{
			$formatedString = "Fruit,Vegetables,Meats,Dairy,Other\n";
			//read in foodPref variables
			$foodPref = fgets($file);
			$fruitPref = fgets($file);
		
			//count fruit results
			$fruitPref = explode(",", $fruitPref);
			$fruitPrefU = $_POST['fruitPref'];
			for ($i = 0; $i < count($fruitPref); $i++)
			{
				$itemCount = explode("-", $fruitPref[$i]);
				for ($h = 0; $h < count($fruitPrefU); $h++)
				{
					if ($itemCount[0] === $fruitPrefU[$h])
					{
						$itemCount[1]++;
					}
				}
				$itemCount = implode("-", $itemCount);
				$fruitPref[$i] = $itemCount;
			}
			$fruitPref = implode(",", $fruitPref);
			$formatedString .= $fruitPref . "\n";

			//count vegitable results
			$vegPref = fgets($file);

			$vegPref = explode(",", $vegPref);
			$vegPrefU = $_POST['vegPref'];
			for ($i = 0; $i < count($vegPref); $i++)
			{
				$itemCount = explode("-", $vegPref[$i]);
				for ($h = 0; $h < count($vegPrefU); $h++)
				{
					if ($itemCount[0] === $vegPrefU[$h])
					{
						$itemCount[1]++;
					}
				}
				$itemCount = implode("-", $itemCount);
				$vegPref[$i] = $itemCount;
			}
			$vegPref = implode(",", $vegPref);
			$formatedString .= $vegPref . "\n";


			$meatPref = fgets($file);

			$meatPref = explode(",", $meatPref);
			$meatPrefU = $_POST['meatPref'];
			for ($i = 0; $i < count($meatPref); $i++)
			{
				$itemCount = explode("-", $meatPref[$i]);
				for ($h = 0; $h < count($meatPrefU); $h++)
				{
					if ($itemCount[0] === $meatPrefU[$h])
					{
						$itemCount[1]++;
					}
				}
				$itemCount = implode("-", $itemCount);
				$meatPref[$i] = $itemCount;
			}
			$meatPref = implode(",", $meatPref);
			$formatedString .= $meatPref . "\n";

			//count dairy foods
			$dairyPref = fgets($file);

			$dairyPref = explode(",", $dairyPref);
			$dairyPrefU = $_POST['dairyPref'];
			for ($i = 0; $i < count($dairyPref); $i++)
			{
				$itemCount = explode("-", $dairyPref[$i]);
				for ($h = 0; $h < count($dairyPrefU); $h++)
				{
					if ($itemCount[0] === $dairyPrefU[$h])
					{
						$itemCount[1]++;
					}
				}
				$itemCount = implode("-", $itemCount);
				$dairyPref[$i] = $itemCount;
			}
			$dairyPref = implode(",", $dairyPref);
			$formatedString .= $dairyPref . "\n";

			$otherPref = fgets($file);
			$otherPref = explode(",", $otherPref);
			$otherPrefU = $_POST['otherPref'];
			for ($i = 0; $i < count($meatPref); $i++)
			{
				$itemCount = explode("-", $otherPref[$i]);
				for ($h = 0; $h < count($otherPrefU); $h++)
				{
					if ($itemCount[0] === $otherPrefU[$h])
					{
						$itemCount[1]++;
					}
				}
				$itemCount = implode("-", $itemCount);
				$otherPref[$i] = $itemCount;
			}
			$otherPref = implode(",", $otherPref);
			$formatedString .= $otherPref . "\n";
			
			$frustrated = fgets($file);
			$frustratedU = $_POST['frustrating'];
			$frustrated = explode("," , $frustrated);
			$one = 1;
			$zero = 0;
			if ($frustratedU === "true")
			{
				$itemCount1 = explode("-", $frustrated[$zero]);
				$itemCount1[$one]++;
				$itemCount1 = implode("-", $itemCount1);
				$frustrated[$zero] = $itemCount1;
			}
			else
			{
				$itemCount2 = explode("-", $frustrated[$one]);
				$itemCount2[$one]++;
				$itemCount2 = implode("-", $itemCount2);
				$frustrated[$one] = $itemCount2;
			}
			$frustrated = implode(",", $frustrated);
			$formatedString .= $frustrated . "\n";

			$favFood = fgets($file);
			$favFoodU = $_POST['favFood'];
			$favFood .= $favFoodU;
			$formatedString .= $favFood . "\n";

			fwrite($file, $formatedString);

		}
		fclose($file);

	}
?>
<!DOCTYPE html>

<html>
	<head>
		<title>Survey Results</title>

	</head>

	<body>
		<?php
			if (!isset($_SESSION['visited']) || $_SESSION['visited'] > 1)
			{
				$results = displayFile();
			echo $results;

			}
			else
			{
				updateFile();
				$results = displayFile();
			}
			//$foodPref = $_POST['foodPref'];
			//$fruitPref = $_POST['fruitPref'];
			//$vegPref = $_POST['vegPref'];
			//$meatPref = $_POST['meatPref'];
			//$dairyPref = $_POST['dairyPref'];
			//$otherPref = $_POST['otherPref'];
			//$favFood = $_POST['favFood'];
			//$frustrated = $_POST['frustrating'];
		?>
	</body>
</html>