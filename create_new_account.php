<?php session_start();
	if (isset($_SESSION['username'])) 
	{
		header("location:user_home_page.php");
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

	<body>
		<h1>Welcome New User</h1>
		<form action='create_new_account.php' method="post" id="newUserForm" onsubmit="return verifyNewAccountInfo()"> 
			Please fill out the following information <br />
			Name: <input type='text' name='name' id='inputName' /> <br />
			Username: <input type='text' name='username' id='inputUsername' /> <br />
			<div hidden='hidden' id='passwordError' >Passwords did not match</div>
			Password: <input type='password' name='password' id='password' /> <br />
			Password Confirmation: <input type='password' id='passwordCheck'> <br />
			<button type="submit" id="submitForm" >Submit</button>
		</form>
	</body>
</html>