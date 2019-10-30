<?php

	function CreateReview($ReviewTitle, $ReviewDate, $ReviewBlurb, $ReviewText, $ReviewCreator){

		global $db;

		$query = $db->prepare("INSERT INTO tblreviews (ReviewTitle, ReviewDate, ReviewBlurb, ReviewText, ReviewCreator) 
								VALUES(:ReviewTitle, :ReviewDate, :ReviewBlurb, :ReviewText, :ReviewCreator)");

		$query->bindParam(':ReviewTitle', $ReviewTitle);
		$query->bindParam(':ReviewDate', $ReviewDate);
		$query->bindParam(':ReviewBlurb', $ReviewBlurb);
		$query->bindParam(':ReviewText', $ReviewText);
		$query->bindParam(':ReviewCreator', $ReviewCreator);

		$query->execute();
		$id = $db->lastInsertId();
		return $id;

	}

	function CreateImage($image, $imagename){

		global $db;

		$query = $db->prepare("INSERT INTO tblphotos (photo, name) VALUES (:photo, :name)");

		$query->bindParam(':photo', $image);
		$query->bindParam(':name', $imagename);

		$query->execute();
		$id = $db->lastInsertId();
		return $id;

	}

	function CreateReviewsImages($reviewID, $imageID){

		global $db;

		$query = $db->prepare("INSERT INTO tblreviewsphotos (aReview, aPhoto) VALUES (:reviewID, :imageID)");

		$query->bindParam(':reviewID', $reviewID);
		$query->bindParam(':imageID', $imageID);

		$query->execute();


	}

	function GetReviews(){

		global $db;

		$query = "SELECT ReviewTitle, ReviewDate, ReviewBlurb, tblusers.UserName, ReviewID, ReviewCreator FROM tblreviews INNER JOIN tblusers ON tblreviews.ReviewCreator = tblusers.UserID ORDER BY ReviewDate DESC, ReviewID DESC";

		$result = $db->query($query);
		return $result;

	}

	function GetReviewsImages($ReviewID){
		global $db;

		$query = $db->prepare("SELECT aPhoto FROM tblreviewsphotos WHERE aReview = :ReviewID");

		$query->bindParam(':ReviewID', $ReviewID);

		$query->execute();

		$result = $query->fetchAll();

		return $result;
	}

	function GetFlaggedReviews(){

		global $db;

		$query = "SELECT ReviewTitle, ReviewDate, ReviewBlurb, tblusers.UserName, ReviewID, ReviewCreator FROM tblreviews INNER JOIN tblusers ON tblreviews.ReviewCreator = tblusers.UserID WHERE Flagged = 1 ORDER BY ReviewDate DESC, ReviewID DESC";

		$result = $db->query($query);
		return $result;

	}

	function GetReviewByID($ReviewID){

		global $db;

		$query = $db->prepare("SELECT ReviewTitle, ReviewDate, ReviewText, ReviewBlurb, UserName, Flagged, ReviewCreator FROM tblreviews INNER JOIN tblusers ON tblreviews.ReviewCreator = tblusers.UserID WHERE ReviewID = :ReviewID");

		$query->bindParam(':ReviewID', $ReviewID);
	
		$query->execute();

		$result = $query->fetch(PDO::FETCH_ASSOC);

		return $result;

	}

	function GetReviewsByUserName($UserName){

		global $db;

		$query = $db->prepare("SELECT ReviewTitle, ReviewDate, ReviewBlurb, UserName, ReviewID FROM tblreviews INNER JOIN tblusers ON tblreviews.ReviewCreator = tblusers.UserID WHERE UserName = :UserName");

		$query->bindParam(':UserName', $UserName);
	
		$query->execute();

		$result = $query->fetchAll();

		return $result;

	}	

	function UpdateReview($ReviewTitle, $ReviewBlurb, $ReviewText, $ReviewID){

		global $db;

		$query = $db->prepare("UPDATE tblreviews SET ReviewTitle = :ReviewTitle, ReviewBlurb = :ReviewBlurb, ReviewText = :ReviewText
					WHERE ReviewID = :ReviewID");

		$query->bindParam(':ReviewTitle', $ReviewTitle);
		$query->bindParam(':ReviewBlurb', $ReviewBlurb);
		$query->bindParam(':ReviewText', $ReviewText);
		$query->bindParam(':ReviewID', $ReviewID);
	
		$query->execute();

	}

	function ChangeReviewFlag($ReviewID, $Flag){

		global $db;

		$query = $db->prepare("UPDATE tblreviews SET Flagged = :Flag
					WHERE ReviewID = :ReviewID");

		$query->bindParam(':ReviewID', $ReviewID);
		$query->bindParam(':Flag', $Flag);

		$query->execute();

	}

	function DeleteReview($ReviewID){

		global $db;

		$query = $db->prepare("DELETE FROM tblreviews 
					WHERE ReviewID = :ReviewID");

		$query->bindParam(':ReviewID', $ReviewID);

		$query->execute();

	}

	function DeleteReviewsPhotos($ReviewID){

		global $db;

		$query = $db->prepare("DELETE FROM tblreviewsphotos 
					WHERE aReview = :ReviewID");

		$query->bindParam(':ReviewID', $ReviewID);

		$query->execute();

	}

	function DeleteReviewsPhotosByPhoto($PhotoID){

		global $db;

		$query = $db->prepare("DELETE FROM tblreviewsphotos 
					WHERE aPhoto = :PhotoID");

		$query->bindParam(':PhotoID', $PhotoID);

		$query->execute();

	}

	function DeletePhoto($PhotoID){

		global $db;

		$query = $db->prepare("DELETE FROM tblphotos 
					WHERE photoID = :PhotoID");

		$query->bindParam(':PhotoID', $PhotoID);

		$query->execute();

	}

?>