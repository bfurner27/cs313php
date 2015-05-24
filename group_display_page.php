<?php session_start() ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title><?php echo $_GET['groupName'] . "'s Page" ?></title>
		<link rel="stylesheet" type="text/css" href="reading_group.css" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js" > </script>
		<script src="reading_group.js" ></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	</head>
<?php
	if (!isset($_GET['groupName'])) 
	{
		header('location:user_home_page.php');
	}

	$dbHost = getenv('OPENSHIFT_MYSQL_DB_HOST');
	$dbPort = getenv('OPENSHIFT_MYSQL_DB_PORT');
	$dbUser = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
	$dbPassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
	$dbName = "reading_groups_app";

	try 
	{
	$db = new PDO("mysql:host=$dbHost:$dbPort;dbname=$dbName", $dbUser, $dbPassword);
	
	$query = "SELECT h.name,h.email FROM groups AS g
	JOIN hosts AS h ON g.host_id = h.h_id
	WHERE g.name = :groupName";

	$statement = $db->prepare($query);
	$statement->bindValue(':groupName', $_GET['groupName']);
	$statement->execute();

	$results = $statement->fetchAll();
	displayHeaderInfo($results);

	$query = "SELECT g.name,b.title,b.author,b.publisher,b.description,b.picture_link FROM groups AS g
				JOIN books AS b ON g.g_id = b.group_id
				WHERE g.name = :groupName";

	$requestBookData = $db->prepare($query);
	$requestBookData->bindValue(':groupName', $_GET['groupName']);
	$requestBookData->execute();

	$bookData = $requestBookData->fetchAll();

	displayBooksAndComments($bookData, $db);
	} 
	catch (PDOEXCEPTION $ex) 
	{
		echo "ERROR: The PDO failed to work";
	}
?>




	<body>
		<?php 
		function displayHeaderInfo($results) 
		{
			echo "<h1>" . $_GET['groupName'] . "</h1>";
			echo "<h3>Your group administrator is: " . $results[0][0] . " <br />Contact info: " . $results[0][1] . "</h3>";
		}
		?>

		<?php 
		function displayBooksAndComments($booksData, $db) 
		{
			foreach ($booksData as $book)
			{
			 	//output the book information
			 	echo '<table>';
			 	echo '<tr><th>' . $book['title'] . '</th></tr>';
			 	echo "<tr><td><img src='" . $book['picture_link'] . "' alt='" . $book['picture_link'] . " width='128' height='128' /></td>";
			 	echo '<td>' . $book['description'] . '</td></tr>';
			 	echo '<tr><td>Author: ' . $book['author'] . '<br />Publisher: ' . $book['publisher'] . '</td>';
			 	echo '</table>';

			 	$query = "SELECT b.title,c.title,c.c_date,c.comment,u.username FROM books AS b
							JOIN comments AS c ON b.b_id = c.book_id
							JOIN users AS u ON c.user_id = u.u_id
							WHERE b.title = :bookTitle
							ORDER BY c.c_date DESC";

				$requestComments = $db->prepare($query);
				$requestComments->bindValue(':bookTitle', $book['title']);
				$requestComments->execute();

				$comments = $requestComments->fetchAll();

				//output the comments for the book
				echo '<table>';
				foreach ($comments as $comment)
				{
					echo '<tr><th>' . $comment['title'] . ' ' . $comment['c_date'] . '<br />' . $comment['username'] . '</th></tr>';
					echo '<tr><td>' . $comment['comment'] . '</td></tr>';
				}
				echo '</table>';
				$bookTitle = $book['title'];
				$bookTitle = explode(' ', $bookTitle);
				$bookTitle = implode('', $bookTitle);
				//echo "<form action='group_display_page.php' method='POST' id='form$bookTitle'>";
				echo "<tr><textarea name='comment' cols='50' rows='5' placeholder='comments ...'> </textarea>"
				. "<br /><button id='button$bookTitle' type='submit'>Post</button></tr>";
				//. "</form>";

				//put a request to update the database here and display the results
				echo "<script>";
				echo "$('#button$bookTitle').on('click', function () {";
				echo "alert('Sorry: this portion of the website is not working yet!'); });";
				echo "</script>";

			} //this is the end of the loop for the books
		} //This is the display portion for the books and comments?> 
	</body>
</html>