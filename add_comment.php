<?php
	if (isset($_POST['groupId']))
	{
		$title = htmlspecialchars($_POST['title'], ENT_QUOTES);
		$comment = htmlspecialchars($_POST['comment'], ENT_QUOTES);
	
		require('requestDb.php');
		$db = requestDb();

		$query = "INSERT INTO comments(title,c_date,comment,book_id,group_id,user_id,c_timestamp)
		VALUES (:title,CURDATE(),:comment,:bookId,:groupId,:userId, CURRENT_TIMESTAMP())";

		$updateComments = $db->prepare($query);
		$updateComments->bindParam(':title', $title);
		$updateComments->bindParam(':comment', $comment);
		$updateComments->bindParam(':bookId', $_POST['bookId']);
		$updateComments->bindParam(':groupId', $_POST['groupId']);
		$updateComments->bindParam(':userId', $_POST['userId']);

		$updateComments->execute();

		//What we want returned as output
		echo "<div class='panel panel-info'>";
		echo "<div class='panel-heading displayCommentHeading'>" . $_POST['username'] . "</div>";
		echo "<div class='panel-body displayComment'>";
		if ($title != "") 
		{
		 	echo $title . "...<br />";
		}
		echo $comment . "</div>";
		echo "</div>";
	}
?>