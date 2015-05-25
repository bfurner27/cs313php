<?php session_start();
	if (!isset($_SESSION['username']) || !isset($_SESSION['password']))
	{
		header("location:login_page.php");	
	} ?>
<!DOCTYPE html>

<?php

	require('requestDb.php');
	$db = requestDb(); 

	//prepare and get all values requested from database
	$queryString = "SELECT u.name, u.username, g.name FROM users AS u
	JOIN users_groups AS ug ON u.u_id = ug.user_id
	JOIN groups AS g ON ug.group_id = g.g_id
	WHERE u.username = :username";

	$statement = $db->prepare($queryString);
	$statement->bindValue(':username', $_SESSION['username']);
	$statement->execute();

	$results = $statement->fetchAll();
?>

<html>
	<head lang="en">
		<meta charset="utf-8" />
		<title><?php echo $results[0][0] . "'s " ?> Home Page</title>
		<link rel="stylesheet" type="text/css" href="reading_group.css" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js" > </script>
		<script src="reading_group.js" ></script>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">

	</head>

	<body class="container-fluid">
		<h1 id="title" class="page-header">  
		<?php 
			echo $results[0][0] . "'s Home Page";
		?> 
		</h1>

		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header" >
					<a href="homePage.php" class="navbar-brand" >Reading Group Center</a>
				</div>
				<div>
					<ul class="nav navbar-nav">
					<li><a href="homePage.php">Home</a></li>
					<li><a href="reading_groups_page.php">Groups</a></li>
					<li class="active"><a href="#">User Page</a></li>
					<li><a href="about.php">About</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
					<?php
						if($_SESSION['isHost'] == 0) 
						{
							echo "<li><a href='add_host_extension.php' ><span class='glyphicon glyphicon-user'></span> Become Group Host</a></li>";
						}
						else 
						{
							echo "<li><a href='manage_groups.php' ><span class='glyphicon glyphicon-user'></span> Manage Groups</a></li>";
						}
					?> 
					<li><a id="logout" href="#"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li> 
					</ul>
				</div>
			</div>
		</nav>

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
					echo "<tr><td><a href='#' name='$groupName' id='id$groupName'>" . $row[2] . "</a></td></tr>";
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