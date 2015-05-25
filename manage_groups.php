<?php session_start() ?>
<!DOCTYPE html>

<html lang="en" >
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="reading_group.css" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js" > </script>
		<script src="reading_group.js"></script>
		<title>About Page</title>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<body class="container-fluid">
		<h1 class='page-header'>Manage Groups</h1>
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header" >
					<a href="homePage.php" class="navbar-brand" >Reading Group Center</a>
				</div>
				<div>
					<ul class="nav navbar-nav">
					<li><a href="homePage.php">Home</a></li>
					<li><a href="reading_groups_page.php">Groups</a></li>
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
								echo "<li class='active'><a href='manage_groups.php' ><span class='glyphicon glyphicon-user'></span> Manage Groups</a></li>";
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
		

		<div>
			<?php

			?>
		</div>
	</body>

</html>