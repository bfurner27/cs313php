<?php session_start() ?>
<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="utf-8" />
		<title><?php echo $_GET['groupName'] . "'s Page" ?></title>
		<link rel="stylesheet" type="text/css" href="reading_group.css" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js" > </script>
		<script src="reading_group.js" ></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<body class="container-fluid">
		<h1 class='page-header'> <?php echo $_GET['groupName']; ?></h1>
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
					<li><a href="#">About</a></li>
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

<?php
	if (!isset($_GET['groupName'])) 
	{
		header('location:user_home_page.php');
	}

	require('requestDb.php');
	$db = requestDb(); 

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
?> 
	</body>
</html>


<?php 
	function displayHeaderInfo($results) 
	{
		
		echo "<h3>Your group administrator is: " . $results[0][0] . " <br />Contact info: " . $results[0][1] . "</h3>";
	}
?>

<?php 
	function displayBooksAndComments($booksData, $db) 
	{
		foreach ($booksData as $book)
		{
		 	//output the book information
		 	
		 	echo '<h2>' . $book['title'] . '</h2>';
		 	echo '<table>';
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
			echo "<div class='container-fluid'><div class='panel-group'>";
			foreach ($comments as $comment)
			{
				echo "<div class='panel panel-info'>";
				echo "<div class='panel-heading'>" . $comment['username'] ."</div>";
				echo "<div class='panel-body'>";
				if ($comment['title'] !== "") 
				{
					echo $comment['title'] . "... <br />";
				}
				echo $comment['comment'] . "</div>";
				//echo "<tr><th>" . $comment['title'] . $comment['username'] . '</th></tr>';
				//echo '<tr><td>' . $comment['comment'] . '</td></tr>';
				echo "</div>";
			}
			
			$bookTitle = $book['title'];
			$bookTitle = explode(' ', $bookTitle);
			$bookTitle = implode('', $bookTitle);
			//echo "<form action='group_display_page.php' method='POST' id='form$bookTitle'>";
			echo "<div class='panel panel-info'>";
			echo "<div class='panel-heading'>add comment ...</div><div class='panel-body'><textarea name='comment' row='1' class='postCommentBox'></textarea>"
			. "<br /><button id='button$bookTitle' type='submit' class='postButton'>Post</button></div>";
			echo "</div>";
			//. "</form>";
			echo '</div></div>';

			//put a request to update the database here and display the results
			echo "<script>";
			echo "$('#button$bookTitle').on('click', function () {";
			echo "alert('Sorry: this portion of the website is not working yet!'); });";
			echo "</script>";
		} //this is the end of the loop for the books
	} //This is the display portion for the books and comments
?> 
