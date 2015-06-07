<?php session_start();
	require('requestDb.php');
	$db = requestDb();
?>
<!DOCTYPE html>

<html lang="en">
	<head>
		<title>Reading Groups</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="survey.css" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js" > </script>
		<script src="reading_group.js"></script>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<body class="container-fluid">
		<h1 class="page-header">Groups</h1>
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header" >
					<a href="homePage.php" class="navbar-brand" >Reading Group Center</a>
				</div>
				<div>
					<ul class="nav navbar-nav">
					<li><a href="homePage.php">Home</a></li>
					<li class="active"><a href="reading_groups_page.php">Groups</a></li>
					<?php 
						if (isset($_SESSION['username']))
						{
							echo "<li><a href='user_home_page.php'>User Page</a></li>";
						}
					?>
					<li><a href="about.php">About</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
					<?php 
						if (isset($_SESSION['username']))
						{
							if ($_SESSION['isHost'] != 0)
							{
								echo "<li><a href='manage_groups.php' ><span class='glyphicon glyphicon-user'></span> Manage Groups</a></li>";
							}
							echo "<li><a id='logout' href='#'><span class='glyphicon glyphicon-log-out'></span>Logout</a></li>";
						} 
						else 
						{
							echo "<li><a href='create_new_account.php' ><span class='glyphicon glyphicon-user'></span> Sign Up</a></li>";
							echo "<li><a href='login_page.php' ><span class='glyphicon glyphicon-log-in'></span> Login</a></li> ";
						}
					?> 
					</ul>
				</div>
			</div>
		</nav>
		<?php
			echo "<form id='logoutForm' action='login_page.php' method='post'>"
			. "<input type='hidden' name='logout' value='logout'/>"
			. "</form><script>$('#logout').on('click', function() { $('#logoutForm').submit(); });</script>";
		?>

		<?php requestAndDisplayGroupInfo($db); ?>

	</body>
</html>

<?php
function requestAndDisplayGroupInfo($db) 
{
	//Request all the Group Names
	$queryGroups = "SELECT name,g_id FROM groups";

	$requestGroupNames = $db->prepare($queryGroups);
	$requestGroupNames->execute();
	$groupNames = $requestGroupNames->fetchAll();
	
	//Display group names and request info if the user is a member of the group
	echo "<table><tr><th>Groups</th></tr>";
	foreach ($groupNames as $row)
	{
		$groupName = $row[0];
		$isInGroup = 0;
		if (isset($_SESSION['username']))
		{
			$queryUsersGroups = "SELECT 1 FROM users AS u
			JOIN users_groups AS ug ON u.u_id = ug.user_id
			JOIN groups AS g ON ug.group_id = g.g_id
			WHERE g.name=:groupName AND u.username=:username";

			$requestIsInGroup = $db->prepare($queryUsersGroups);
			$requestIsInGroup->bindValue(':groupName', $groupName);
			$requestIsInGroup->bindValue(':username', $_SESSION['username']);
			$requestIsInGroup->execute();

			$isInGroup = $requestIsInGroup->fetchAll();
		}

		$groupName = str_replace(" ", "", $groupName);
		//found this regex online at http://stackoverflow.com/questions/5689918/php-strip-punctuation
		$regEx = "/[^\w]+/";
		$groupName = preg_replace($regEx, "", $groupName);
		

		echo "<tr><td hidden='hidden'>";
		if (count($isInGroup) < 1 && isset($_SESSION['username'])) 
		{
			echo "<a href='' id='Add$groupName' ><span id='glyphA$groupName' class='glyphicon glyphicon-plus'></span></a>";
		}
		else if (isset($_SESSION['username'])) 
		{
			echo "<a href='' id='Remove$groupName'><span id='glyphR$groupName' class='glyphicon glyphicon-remove red' ></span></a>";
		}
		echo "</td><td><a href='' name='$groupName' id='id$groupName'>" . $row[0] . "</a></td></tr>";
		
	}
	echo "</table>";
	if (isset($_SESSION['username']))
	{
		echo "<script> $('td').show(); </script>";

		//add script for ajax request to add a group
		$groupId = $row['g_id'];
		echo "<script>\n";
		echo "$('#Add$groupName').on('click', function () {\n";
		echo "var queryString = {\n";
		echo "command : 'add',\n";
		echo "groupId : '$groupId'\n";
		echo "};";
		echo "$.post('add_remove_groups.php', $.param(queryString));\n";
		echo "$('#glyphA$groupName').attr('class', 'glyphicon glyphicon-remove');\n";
		echo "});\n";
		echo "</script>\n";


		//remove group
		echo "<script>\n";
		echo "$('#Remove$groupName').on('click', function () {\n";
		echo "var queryString = {\n";
		echo "command : 'remove', \n";
		echo "groupId : '$groupId' \n";
		echo "};\n";
		echo "var posting = $.post('add_remove_groups.php', $.param(queryString));\n";
		echo "$('#glyphR$groupName').attr('class', 'glyphicon glyphicon-plus');\n";
		echo "});\n";
		echo "</script>\n";
	}

	foreach ($groupNames as $row) 
	{
		$groupName = $row[0];
		$groupName = explode(" ", $groupName);
		$groupName = implode("", $groupName);

		$regEx = "/[^\w]+/";
		$groupName = preg_replace($regEx, "", $groupName);
				
		//output form information
		echo "<form action='group_display_page.php' method='get' id='form$groupName'>" 
		."<input type='hidden' name='groupName' value='" . $row[0] . "' />"
		. "</form>";

		//output javascript to submit and handle form
		echo "<script>" .
		"$('#id$groupName').on('click', function() {
		$('#form$groupName').submit();
		});" . 
		"</script>";
	}
}

?>