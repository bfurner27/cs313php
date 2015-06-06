<?php session_start();
	if (isset($_SESSION['username'])) 
	{
		header("location:user_home_page.php");
	} 
	else if (isset($_POST['username']) && isset($_POST['password']))
	{
		require('password.php');
		$username = htmlspecialchars($_POST['username']);
		$password = htmlspecialchars($_POST['password']);
		$password = password_hash($password, PASSWORD_DEFAULT);
		$name = "";
		if (isset($_POST['name'])) 
		{
			$name = htmlspecialchars($_POST['name']);
		}

		require('requestDb.php');
		$db = requestDb();

		$query = "INSERT INTO users(name,username,password)
				VALUES (:name,:username,:password)";
		$insertNewUser = $db->prepare($query);
		$insertNewUser->bindParam(':name', $name);
		$insertNewUser->bindParam(':username', $username);
		$insertNewUser->bindParam(':password', $password);

		$insertNewUser->execute();

		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		$_SESSION['isHost'] = 0;
		$_SESSION['user_id'] = $db->lastInsertId();

		header('location:user_home_page.php');
	}
 ?>
<!DOCTYPE html>


<html>
	<head>
		<meta charset="utf-8" />
		<title>Create new user</title>
		<link rel="stylesheet" type="text/css" href="reading_group.css" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js" > </script>
		<script src="reading_group.js" ></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
	</head>

	<body class='newGroupPageFormat'>
		<h1 class='center'>Welcome New User</h1>
		<form action='create_new_account.php' method="post" id="newUserForm" onsubmit="return verifyNewAccountInfo()"> 
			<label>Please fill out the following information</label> <br />
			<label for='inputName'>Name:</label> <input class='form-control' type='text' name='name' id='inputName' /> <br />
			<label for='inputUsername'>Username:</label> <input class='form-control' type='text' name='username' id='inputUsername' /> <br />
			<div hidden='hidden' id='passwordError' >Passwords did not match</div>
			<label>Password:</label> <input class='form-control' type='password' name='password' id='password' /> <br />
			<label>Password Confirmation:</label> <input class='form-control' type='password' id='passwordCheck' > <br />
			<button type="submit" id="submitForm" >Submit</button> <br />
		</form>
	</body>
</html>