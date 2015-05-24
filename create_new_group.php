<?php 
	session_start();
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

	<body>
		<h1>Create A New Group Page</h1>
		<form>
			Please fill out all the below information <br />
			Group Name: <input type="text" name="groupName" id="groupName" /> <br /><br />
			Please fill out the first book information, you will be able to add books later<br />
			Book Title<input type="text" name="bookTitle" id="bookTitle" /> <br />
			Author: <input type="text" name="author" id="author" /> <br />
			Publisher: <input type="text" name="publisher" id="publisher" /> <br />
			Upload Image: <input type="file" name="picture" />
			Description: <br /><textarea name="description" id="description" > </textarea> <br />
			<button type="submit" id="submitButton" >Submit</button> <br />
		</form>	
	</body>

</html>