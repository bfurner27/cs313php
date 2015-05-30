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

	$query = "SELECT g.name,b.title,b.author,b.publisher,b.description,b.picture_link,b.b_id,g.g_id FROM groups AS g
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
		
		echo "<h3 id='groupAdministrator'>Your group administrator is: " . $results[0][0] . " <br />Contact info: " . $results[0][1] . "</h3>";
	}
?>

<?php 
	function displayBooksAndComments($booksData, $db) 
	{
		foreach ($booksData as $book)
		{
		 	//output the book information
		 	
		 	echo "<div class='displayBook'>";
		 	echo "<h2>" . $book['title'] . '</h2>';
		 	echo "<div class='formatInfoText'><b>Author:</b> " . $book['author'] . '<br /><b>Publisher:</b> ' . $book['publisher']
		 	. '<br /></div>';
		 	echo "<div class='displayBookPicture'><img class='formatPicture' src='" . $book['picture_link'] . "' alt='" . $book['picture_link']
		 	 . "' /></div>";
		 	echo "<div class=''></div>";
			echo "<div class='displayBookInfo'><div class='displayDescriptionTitle'><b>Description</b></div>" . $book['description'] . '</div>';

		 	echo '</div>';

		 	$query = "SELECT b.title,c.title,c.c_date,c.comment,u.username FROM books AS b
			JOIN comments AS c ON b.b_id = c.book_id
			JOIN users AS u ON c.user_id = u.u_id
			WHERE b.title = :bookTitle
			ORDER BY c.c_timestamp";

			$requestComments = $db->prepare($query);
			$requestComments->bindValue(':bookTitle', $book['title']);
			$requestComments->execute();

			$comments = $requestComments->fetchAll();

			//output the comments for the book
			echo "<div class='container-fluid'><div class='panel-group' id='addPanel'>";
			foreach ($comments as $comment)
			{	//format each individual comment so it will display in the correct location
				echo "<div class='panel panel-info' >";
				echo "<div class='panel-heading displayCommentHeading'>" . $comment['username'] ."</div>";
				echo "<div class='panel-body displayComment'>";
				if ($comment['title'] != "") 
				{
					echo $comment['title'] . "... <br />";
				}
				echo $comment['comment'] . "</div>";
				echo "</div>";
			}
			
			if (isset($_SESSION['username']))
			{
				$bookTitle = $book['title'];
				$bookTitle = explode(' ', $bookTitle);
				$bookTitle = implode('', $bookTitle);

				//this is to enable us to input additional items
				echo "<div id='insertItem$bookTitle' hidden='hidden'></div>";


				//this is the new comment box so if someone wants to add additional comments
				echo "<div class='panel panel-info id='newComment'>";
				echo "<div class='panel-heading displayCommentHeading'>add comment ...</div><div class='panel-body'>"
				. "<input type='text' class='postTitleBox' id='titleId$bookTitle' maxlength='256' placeholder='Title ...'/>"
				. "<textarea name='comment' id='id$bookTitle' row='1' class='postCommentBox' placeholder='Comment ...'></textarea>"
				. "<br /><button id='button$bookTitle' type='submit' class='postButton'>Post</button></div>";
				echo "</div>";


				//display the jquery that will send the ajax request to insert information into the database
				$groupName = $_GET['groupName'];
				$userId = $_SESSION['user_id'];
				$bookId = $book['b_id'];
				$groupId = $book['g_id'];
				$username = $_SESSION['username'];

				//put a request to update the database here and display the results
				echo "\n<script>\n";
				echo "$('#button$bookTitle').on('click', function () {\n";
				echo "if ($('#id$bookTitle').val() == '') {\n";
			
				//in the case the comment box is empty these are the error message conditions
				echo "$('#id$bookTitle').attr('placeholder', 'ERROR: You cannot post an empty comment');\n";
				echo "$('#id$bookTitle').css({'border-color':'red', 'border-width':'2px', 'border-style':'solid'});\n";
				echo "} else {\n"; //end of if statement
			
				//reformat the string to the default look
				echo "$('#id$bookTitle').attr('placeholder', 'Comment ...');\n";
				echo "$('#id$bookTitle').css({'border-color':'', 'border-width':'', 'border-style':''});\n";

				//put everything in an array so the query string will be built correctly
				echo "var params = {\n group : '$groupName',\n groupId : '$groupId', username : '$username',\n"
				. "userId : '$userId',\n bookId : '$bookId' };\n";
				echo "params['comment'] = $('#id$bookTitle').val(); params['title'] = $('#titleId$bookTitle').val();";
			
				//send the ajax request
				//found some of this from jquery reference on post function
				echo "\tvar posting = $.post('add_comment.php', $.param(params))\n";
				echo "posting.done(function (data) { \n"
				. "\t$(data).insertBefore('#insertItem$bookTitle');\n"
				. "});\n";
				echo "$('#id$bookTitle').val('');\n";
				echo "$('#titleId$bookTitle').val('')";
				echo "}\n"; //end of else for if statement
				echo "});\n";
				echo "</script>\n";
			}
		} //this is the end of the loop for the books
	} //This is the display portion for the books and comments
?> 
