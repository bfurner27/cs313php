<?php
	if (isset($_POST['bookTitle']) && isset($_POST['groupName'])) 
	{
		$groupName = htmlspecialchars($_POST['groupName']);
		$bookTitle = htmlspecialchars($_POST['bookTitle']);

		$author = "";
		if (isset($_POST['author'])) 
		{
			$author = htmlspecialchars($_POST['author']);
		}

		
		$pictureLink = "";		
		echo "Your picture link is: "  . $pictureLink;	

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

		require('requestDb.php');
		$db = requestDb();

		$query = "SELECT g_id FROM groups WHERE name=:groupName";

		$requestGroupId = $db->prepare($query);
		$requestGroupId->bindParam(':groupName', $groupName);
		$requestGroupId->execute();
		$result = $requestGroupId->fetch();

		$groupId = $result['g_id'];


		require('add_book.php');
		insertBook($db, $bookTitle, $author, $publisher, $description, $pictureLink, $groupId);

		header('location:manage_groups.php');
	}
?>
<!DOCTYPE html>

<html>
	<head lang="en">
		<meta charset="utf-8" />
		<title>New Book</title>
		<link rel="stylesheet" type="text/css" href="reading_group.css" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js" > </script>
		<script src="reading_group.js" ></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	
	<body class="newGroupPageFormat">
		<h1 class='center'>Add a New Book</h1>
		<form action='create_new_book.php' method='post' >
			<?php
				if (isset($_POST['nameOfGroup']))
				{
					$groupN = htmlspecialchars($_POST['nameOfGroup']);
					echo "<input type='hidden' name='groupName' value='$groupN' / >";
				}
				if (!isset($_POST['groupName']) && !isset($_POST['nameOfGroup']))
				{
					header('location:manage_groups.php');
				}


			?>
			<label for='bookTitle'>Book Title</label><input type="text" name="bookTitle" id="bookTitle" class='form-control'/> <br />
			<label for='author'>Author:</label> <input type="text" name="author" id="author" class='form-control' /> <br />
			<label for='publisher'>Publisher:</label> <input type="text" name="publisher" id="publisher" class='form-control' /> <br />
			<label for='description'>Description:</label> <br /><textarea name="description" id="description" class='form-control' > </textarea> <br />
			<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
			<label for='picture'>Upload Image:</label> <input type="file" name="picture" id='picture' enctype="multipart/form-data" /> <br />
			<button type="submit" id="submitButton" class='postButton'>Submit</button> <br />
		</form>
	</body>

</html>

<?php
function addFile() 
{





}
?>