<?php 
	session_start();
	if (isset($_POST['groupName']) && isset($_POST['bookTitle'])) 
	{
		$groupName = htmlspecialchars($_POST['groupName']);
		$bookTitle = htmlspecialchars($_POST['bookTitle']);

		require('requestDb.php');
		$db = requestDb();

		$userId = $_SESSION['user_id'];

		$query = "SELECT h_id FROM hosts
				WHERE user_id=$userId";

		$requestHostId = $db->prepare($query);
		$requestHostId->execute();

		$id = $requestHostId->fetchAll();
		$hostId = $id[0][0];
		echo $_POST['private'];

		$query = "INSERT INTO groups(name,date_created,is_private,host_id)
		         VALUES (:groupName,CURDATE(),:isPrivate,:hostId)";
		$insertGroup = $db->prepare($query);
		$insertGroup->bindParam(':groupName', $groupName);
		$insertGroup->bindParam(':isPrivate', $_POST['private']);
		$insertGroup->bindParam(':hostId', $hostId);
		$insertGroup->execute();

		$newGroupId = $db->lastInsertId();


		$author = "";
		if (isset($_POST['author'])) 
		{
			$author = htmlspecialchars($_POST['author']);
		}
		//not working yet
		$pictureLink = "";
		if (isset($_POST['picture'])) 
		{

		}

		$publisher = "";
		if (isset($_POST['publisher']))
		{
			$publisher = htmlspecialchars($_POST['publisher']);
		}

		$description = "";
		if (isset($_POST['description']))
		{
			$description = htmlspecialchars($_POST['description']);
		}

		require('add_book.php');
		insertBook($db, $bookTitle, $author, $publisher, $description, $pictureLink, $newGroupId);


		$query = "INSERT INTO users_groups(user_id,group_id)
			VALUES($userId,$newGroupId)";

		$makePartOfGroup = $db->prepare($query);
		$makePartOfGroup->execute();

		header('location:manage_groups.php');
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
		<h1 class='center'>Create A New Group Page</h1>
		<form action='create_new_group.php' method='post' onsubmit=''>
			<label>Please fill out all the below information</label> <br />
			<label for='groupName'>Group Name:</label> <input type="text" name="groupName" id="groupName" class='form-control'/> <br /><br />
			<label for='privateGroup'>Is This a Private Group?</label>
			<label class='radio-inline'><input type="radio" name='private' id='privateGroupYes' />yes </label>
			<label class='radio-inline'><input type="radio" name='private' id='privateGroupNo' checked='checked'/>no</label> <br />
			<label>Please fill out the first book's information, you will be able to add books later</label><br />
			<label for='bookTitle'>Book Title</label><input type="text" name="bookTitle" id="bookTitle" class='form-control'/> <br />
			<label for='author'>Author:</label> <input type="text" name="author" id="author" class='form-control' /> <br />
			<label for='publisher'>Publisher:</label> <input type="text" name="publisher" id="publisher" class='form-control' /> <br />
			<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
			<label for='picture'>Upload Image:</label> <input type="file" name="picture" id='picture' enctype="multipart/form-data" />
			<label for='description'>Description:</label> <br /><textarea name="description" id="description" class='form-control' > </textarea> <br />
			<button type="submit" id="submitButton" class='postButton' >Submit</button> <br />
		</form>	
	</body>

</html>