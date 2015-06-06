<?php
	session_start();

	require('password.php');

	if (isset($_POST['logout'])) 
	{
		session_unset();
		session_destroy();
		header("location:homePage.php");
	}
	else if (isset($_SESSION['username']) && isset($_SESSION['password'])) 
	{

		require("requestDb.php");

		$db = requestDb();

		//sanitize the query to increase security.
		$queryString = "SELECT username, password, is_host FROM users WHERE username = :username";
		$statement = $db->prepare($queryString);
		$statement->bindValue(':username', $_SESSION['username']);
		$statement->execute();

		$results = $statement->fetchAll(); 


		$isValidUserAndPass = false;
		if (strcasecmp($results[0][0], $_SESSION['username']) === 0) {
			if (password_verify($_SESSION['password'], $results[0][1])) {
				$_SESSION['username'] = $_SESSION['username'];
				$_SESSION['password'] = $_SESSION['password'];
				$_SESSION['isHost'] = $results[0][2];
				$_SESSION['user_id'] = $results[0][3];
				header("location:user_home_page.php");

			}
			else 
			{
				executeScript();
			}
		}
		else {
			executeScript();
		}
	} 
	else if (isset($_POST['username']) && isset($_POST['password'])) 
	{

		require('requestDb.php');
		$db = requestDb(); 
			//sanitize the input to make sure there is no html.	
		$username = $_POST['username'];
		$username = filter_var($username, FILTER_SANITIZE_STRING);
		$password = $_POST['password'];
		$password = filter_var($password, FILTER_SANITIZE_STRING);

		
		//sanitize the query to increase security.
		$query = 'SELECT username, password, is_host, u_id FROM users WHERE username = :username';
		$statement = $db->prepare($query);
		$statement->bindValue(':username', $username);
		$statement->execute();

		$results = $statement->fetchAll();

		//check the results to make sure the person is who they say they are
		$isValidUserAndPass = false;
		if (count($results[0]) > 1) {
			if (strcasecmp($results[0][0], $username) === 0) {
				if (password_verify($password, $results[0][1])) {
					$_SESSION['username'] = $username;
					$_SESSION['password'] = $password;
					$_SESSION['isHost'] = $results[0][2];
					$_SESSION['user_id'] = $results[0][3];
					header("location:user_home_page.php");
					$isValidUserAndPass = true;
				} else {
					executeScript();
				}
			} else {
				executeScript();
			}
		} else {
			executeScript();
		}
	}
	
?>
<!DOCTYPE html>


<html>
	<head>
		<meta charset="utf-8" />
		<title>Login Page</title>
		<link rel="stylesheet" type="text/css" href="reading_group.css" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js" > </script>
		<script src="reading_group.js" ></script>
	</head>

	<body >
		<form action="login_page.php" method="post" onsubmit = "return submitLogin()" > 
			<div id='errorMessage' hidden="hidden" >The username or password is incorrect</div> 
			<?php 
				function executeScript() {
					echo "<div id='errorMessage' onmouseover='showErrorLogin()'>The username or password is incorrect</div>";
				}  
			?>
			Username <input type="text" name="username" id="userName" /> <br />
			Password <input type="password" name="password" id="password" /> <br />
			<button type="submit">Log in</button>

		</form>
	</body>

</html>