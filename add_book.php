<?php

function insertBook($db, $bookTitle, $author, $publisher, $description, $pictureLink, $newGroupId)
{
	$query = "INSERT INTO books(title,author,publisher,description,picture_link,group_id)
			VALUES (:title,:author,:publisher,:description,:picture_link,:newGroupId)";

	$insertBook = $db->prepare($query);
	$insertBook->bindParam(':title', $bookTitle);
	$insertBook->bindParam(':author', $author);
	$insertBook->bindParam(':publisher', $publisher);
	$insertBook->bindParam(':description', $description);
	$insertBook->bindParam(':picture_link', $pictureLink);
	$insertBook->bindParam(':newGroupId', $newGroupId);

	$insertBook->execute();

}

function addFile() 
{

}
?>