<?php session_start();
	if (!isset($_SESSION['username']) || !isset($_SESSION['password']))
	{
		header("location:login_page.php");	
	} ?>
<!DOCTYPE html>

<?php


	$dbHost = getenv('OPENSHIFT_MYSQL_DB_HOST');
	$dbPort = getenv('OPENSHIFT_MYSQL_DB_PORT');
	$dbUser = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
	$dbPassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
	$dbName = "reading_groups_app";
	try {

		$db = new PDO("mysql:host=$dbHost:$dbPort;dbname=$dbName", $dbUser, $dbPassword);

		//prepare and get all values requested from database
		$queryString = "SELECT u.name, u.username, g.name FROM users AS u
		JOIN users_groups AS ug ON u.u_id = ug.user_id
		JOIN groups AS g ON ug.group_id = g.g_id
		WHERE u.username = :username";

		$statement = $db->prepare($queryString);
		$statement->bindValue(':username', $_SESSION['username']);
		$statement->execute();

		$results = $statement->fetchAll();

	} catch (PDOEXCEPTION $ex) {
		Echo "ERROR: Unable to access the PDO";
	}

?>

<html>
	<head>
		<meta charset="utf-8" />
		<title><?php echo $results[0][0] . "'s " ?> Home Page</title>
		<link rel="stylesheet" type="text/css" href="reading_group.css" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js" > </script>
		<script src="reading_group.js" ></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	</head>

	<body>
		<h1 id="title"> 
			Welcome 
		<?php 
			echo $results[0][0];
		?> 
		</h1>

		<div id="logout">Logout</div>
		<form id="logoutForm" action="login_page.php" method="post">
			<input type="hidden" name="logout" value="logout"/>
		</form>

		<script>
			$("#logout").on('click', function() {
				$("#logoutForm").submit();
			});
		</script>

		<div id="groups">
		<table>
			<tr><th>Groups</th><tr>
			<?php 
				foreach ($results as $row) {
					$groupName = $row[2];
					$groupName = explode(" ", $groupName);
					$groupName = implode("", $groupName);
					//found this regex online at http://stackoverflow.com/questions/5689918/php-strip-punctuation
					$regEx = "/[^\w]+/";
					$groupName = preg_replace($regEx, "", $groupName);
					echo "<tr><td name='$groupName' id='id$groupName'>" . $row[2] . "</td></tr>";
				}
			?>

		</table>

		<?php 
			$groupName = "";
			foreach ($results as $row) {
				$groupName = $row[2];
				$groupName = explode(" ", $groupName);
				$groupName = implode("", $groupName);

				$regEx = "/[^\w]+/";
				$groupName = preg_replace($regEx, "", $groupName);
				
				//output form information
				echo "<form action='group_display_page.php' method='get' id='form$groupName'>" 
				."<input type='hidden' name='groupName' value='" . $row[2] . "' />"
				. "</form>";

				//output javascript to submit and handle form
				echo "<script>" .
				"$('#id$groupName').on('click', function() {
					$('#form$groupName').submit();
				});" . 
				"</script>";
			}
		?>

		</div>
	</body>
	
</html>