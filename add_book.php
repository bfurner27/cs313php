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

/*function addFile() 
{
	//found most of this from w3schools http://www.w3schools.com/php/php_file_upload.asp
	$targetDir = 'bookPictures/';
	$targetFile = $targetDir . basename($_FILES["picture"]);
	$uploadValid = true;
	$imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION);

	if (!is_uploaded_file($_FILES['picture']['tmp_name'])) 
	{
		$uploadValid = false;
	}

	//found at http://stackoverflow.com/questions/9136531/how-to-check-if-image-was-submitted-with-other-form-data
	if(isset($_FILES['picture']) && $_FILES['picture']['size'] > 0)
	{
		$check = getimagesize($_FILES['picture']['tmp_name']);
		if ($check !== false) 
		{
			$uploadValid = true;
		}
		else 
		{
			$uploadValid = false;
		}

	}

	//check if file already exists
	if (file_exists($targetFile)) 
	{
		$uploadValid = false;
	}

	if ($_FILES["picture"]['size'] > 500000) 
	{
		$uploadValid = false;
	}

	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
   	 	echo "ERROR: only JPG, JPEG, PNG & GIF files are allowed.";
    	$uploadValid = false;
	}

	// Check if $uploadValid is set to 0 by an error
	if ($uploadValid == false) {
		echo "ERROR: File Failed to upload";
	// if everything is ok, try to upload file
	} else {
    	if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
       		echo "The file ". basename( $_FILES["picture"]["name"]). " has been uploaded.";
       		return ($_FILES['picture']['name']);
    	} else {
       		echo "Sorry, there was an error uploading your file.";
    	}
	}

}*/
?>
