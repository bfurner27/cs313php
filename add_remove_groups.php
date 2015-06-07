<?php
	session_start();
	require('requestDb.php');
	$db = requestDb();

	if (isset($_POST['command']) && isset($_POST['groupId']))
	{
		$groupId = htmlspecialchars($_POST['groupId']);
		$userId = htmlspecialchars($_SESSION['user_id']);
		if ($_POST['command'] == 'add')
		{
			$query = "SELECT 1 FROM users_groups WHERE group_id=:groupId AND user_id=:userId";
			$checkIfExists = $db->prepare($query);
			$checkIfExists->bindParam(':groupId', $groupId);
			$checkIfExists->bindParam(':userId', $userId);
			$checkIfExists->execute();

			$doesExist = $checkIfExists->fetchAll();
			if (count($doesExist) < 1)
			{
				$query = "INSERT INTO users_groups(group_id, user_id) 
				VALUES (:groupId, :userId)";

				$connectGroup = $db->prepare($query);
				$connectGroup->bindParam(':groupId', $groupId);
				$connectGroup->bindParam(':userId', $userId);

				$connectGroup->execute();
			}
		}
		else if ($_POST['command'] == 'remove')
		{
			$query = "DELETE FROM users_groups WHERE user_id=:userId AND group_id=:groupId";

			$deleteGroup = $db->prepare($query);
			$deleteGroup->bindParam(':groupId', $groupId);
			$deleteGroup->bindParam(':userId', $userId);

			$deleteGroup->execute();
			echo "deleted the item!";
		}
	}
	echo "made it here!";
?>