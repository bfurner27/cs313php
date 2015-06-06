<?php session_start() ?>
<!DOCTYPE html>
<?php
	require('requestDb.php');
	$db = requestDb(); 

	//prepare and get all values requested from database
	$queryString = "SELECT g.name FROM groups as g
					JOIN hosts AS h ON h.h_id = g.host_id
					WHERE h.user_id=:userId";

	$statement = $db->prepare($queryString);
	$statement->bindValue(':userId', $_SESSION['user_id']);
	$statement->execute();

	$results = $statement->fetchAll();
?>

<html lang="en" >
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="reading_group.css" />
		<script src="http://code.jquery.com/jquery-1.11.3.min.js" > </script>
		<script src="reading_group.js"></script>
		<title>Manage Groups</title>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<body class="container-fluid">
		<h1 class='page-header'>Manage Groups</h1>
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
					<li><a href="about.php">About</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
					<?php 
						if (isset($_SESSION['username']))
						{	
							if ($_SESSION['isHost'] != 0)
							{
								echo "<li class='active'><a href='manage_groups.php' ><span class='glyphicon glyphicon-user'></span> Manage Groups</a></li>";
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
				
		<div id="groups">
			<?php 
				//display the group name and have the link set up
			echo "<div class='bookNameManage'><a href='create_new_group.php'><span class='glyphicon glyphicon-plus'></span> Create Group</a></div>";
				foreach ($results as $row) {
					$groupName = $row[0];
					$groupName = explode(" ", $groupName);
					$groupName = implode("", $groupName);
					//found this regex online at http://stackoverflow.com/questions/5689918/php-strip-punctuation
					$regEx = "/[^\w]+/";
					$groupName = preg_replace($regEx, "", $groupName);
					echo "<div class='groupNameManage'>" . $row[0] . "</div>";
					
					//put the form that will submit to the create new book page and will submit the group name
					echo "<div class='indentOneTab' id='id2$groupName'><a href='#'><span class='glyphicon glyphicon-plus'></span> Add Book</a></div>";
					echo "<form id='form2$groupName' action='create_new_book.php' method='POST' >";
					echo "<input type='hidden' name='nameOfGroup' value='" . $row[0] . "' hidden='hidden'/>";
					echo "</form>";

					$query = "SELECT b.title FROM books as b
					JOIN groups AS g ON b.group_id = g.g_id
					WHERE g.name = :name";

					$queryBooks = $db->prepare($query);
					$queryBooks->bindParam(':name', $row[0]);
					$queryBooks->execute();

					$bookNames = $queryBooks->fetchAll();

					foreach ($bookNames as $bookName) 
					{

						//format the id for button so that jquery can be done with it and everything can be selected properly
						$idBookName = explode(' ', $bookName[0]);
						$idBookName = implode('', $idBookName);
						$regEx = "/[^\w]+/";
						$idBookName = preg_replace($regEx, "", $idBookName);

						echo "<div class='indentOneTab makeBold bookNameManage'>" . $bookName[0] . "</div>";

						
						$query = "SELECT c.c_id,c.c_date,c.title,c.comment,u.username FROM comments AS c
						JOIN users AS u ON u.u_id = c.user_id
						JOIN groups AS g ON g.g_id = c.group_id
						JOIN books AS b ON b.b_id = c.book_id
						WHERE g.name = :groupName AND b.title = :bookTitle
						ORDER BY c.c_timestamp";

						$requestComments = $db->prepare($query);
						$requestComments->bindParam(':groupName', $row[0]);
						$requestComments->bindParam(':bookTitle', $bookName[0]);
						$requestComments->execute();

						$comments = $requestComments->fetchAll();


						echo "<div class='indentTwoTabs'><table class='table table-striped'>";
						echo "<tr><th></th><th>Date</th><th>Username</th><th>Title</th><th>Comment</th></tr>";
						foreach ($comments as $comment)
						{
							echo "<tr id='id" . $comment['c_id'] . "'>";
							echo "<td><input type='checkbox' name='deleteComment$idBookName' value='" . $comment['c_id'] . "' /></td>";
							echo "<td>" . $comment['c_date'] . "</td>";
							echo "<td>" . $comment['username'] . "</td>";
							echo "<td>" . $comment['title'] . "</td>";
							echo "<td>" . $comment['comment'] . "</td>";
							echo "</tr>";
						}

						echo "</table></div>";
						echo "<div class='indentTwoTabs'><button class='btn btn-primary' type='button' id='button$idBookName'>Delete Selected</button></div>";

						//create the javascript and jquery for the form to be submitted correctly and for the comments to be updated in the database
						echo "<script>\n";
						echo "$('#button$idBookName').on('click', function () {\n";
						echo "var commentsToDelete = [];\n";
						echo "$(\"input:checkbox[name~='deleteComment$idBookName']:checked\").each( function () {\n";
						echo "\tcommentsToDelete.push($(this).val());\n";	
						echo "});\n";
						echo "var queryString = 'delete='\n";
						echo "for (i = 0; i < commentsToDelete.length; i++) {\n";
						echo "queryString += commentsToDelete[i] + ',';\n";
						echo "var formatJQRequest = '#id' + commentsToDelete[i];\n";
						echo "$(formatJQRequest).remove();\n";
						echo "}\n";
						echo "$.post('delete_comment.php', queryString);\n";
						echo "});\n";
						echo "</script>\n";


						//add the jquery to submit the form when the link is clicked, which indicates a submit.
						echo "<script>" .
						"$('#id2$groupName').on('click', function() {
						$('#form2$groupName').submit();
						});" . 
						"</script>";
					}

				}

			?>

		<?php 
			/*$groupName = "";
			foreach ($results as $row) {
				$groupName = $row[0];
				$groupName = explode(" ", $groupName);
				$groupName = implode("", $groupName);

				$regEx = "/[^\w]+/";
				$groupName = preg_replace($regEx, "", $groupName);
				
				//output form information
				echo "<form action='group_display_page.php' method='get' id='form$groupName'>" 
				."<input type='hidden' name='groupName' value='" . $row[0] . "' />"
				. "</form>";

				//output javascript to submit and handle form
				echo "<script>" .
				"$('#id$groupName').on('click', function() {
					$('#form$groupName').submit();
				});" . 
				"</script>";
			}*/
		?>

		</div>
	</body>

</html>