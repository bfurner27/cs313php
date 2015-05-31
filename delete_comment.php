<?php
	require('requestDb.php');
	$db = requestDb();

	$deleteVals = explode(",",$_POST['delete']);

	foreach ($deleteVals as $deleteKey) 
	{
		$query = "DELETE FROM comments
				WHERE c_id=:deleteKey";
		$deleteComment = $db->prepare($query);
		$deleteComment->bindParam(':deleteKey', $deleteKey);
		$deleteComment->execute();
	}
?>