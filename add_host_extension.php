<?php 
	session_start();

	if (isset($_POST['hostName']) && isset($_POST['hostEmail']))
	{
		require('requestDb.php');
		$db = requestDb();

		$query = "INSERT INTO hosts(name,email,user_id)
		VALUES(:name,:email,:userId)";

		$insertHost = $db->prepare($query);
		$insertHost->bindParam(':name', htmlspecialchars($_POST['hostName']));
		$insertHost->bindParam(':email', htmlspecialchars($_POST['hostEmail']));
		$insertHost->bindParam(':userId', $_SESSION['user_id']);
		$insertHost->execute();

		$query = "UPDATE users SET is_host = 1 WHERE u_id = :userId";
		$updateUser = $db->prepare($query);
		$updateUser->bindParam(':userId', $_SESSION['user_id']);
		$updateUser->execute();

		$_SESSION['is_host'] = 1;

		header('location:create_new_group.php');
	}

	
?>
<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Create new group</title>
		<link rel="stylesheet" type="text/css" href="reading_group.css" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js" > </script>
		<script src="reading_group.js" ></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
	</head>

	<body class="newGroupPageFormat">
		<h1 class='center'>Become a Host</h1>
		<form action='add_host_extension.php' method='post' onsubmit=''>
			<label>Please fill out all the information below</label> <br />
			<label for='hostName'>Host Name: </label> <input type="text" name="hostName" id="hostName" class='form-control'/> <br /><br />
			<label>Please fill out the first book's information, you will be able to add books later</label><br />
			<label for='hostEmail'>Host Email:</label><input type="text" name="hostEmail" id="hostEmail" class='form-control'/> <br />
			<button type="submit" id="submitButton" class='postButton'>Submit</button> <br />
		</form>	
	</body>

</html>