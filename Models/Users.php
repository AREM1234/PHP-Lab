<?php

	function CreateUser($UserName, $Email, $Password, $UsersType){

		global $db;

		$hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

		$query = $db->prepare("INSERT INTO tblusers (UserName, Email, Password, UsersType) 
								VALUES(:UserName, :Email, :Password, :UsersType)");

		$query->bindParam(':UserName', $UserName);
		$query->bindParam(':Email', $Email);
		$query->bindParam(':Password', $hashedPassword);
		$query->bindParam(':UsersType', $UsersType);

		$query->execute();

	}

	function GetUsers(){

		global $db;

		$query = "SELECT UserName, Email, Type, UserID FROM tblusers INNER JOIN tblusertypes ON tblusers.UsersType = tblusertypes.TypeID ORDER BY UsersType";

		$result = $db->query($query);
		return $result;

	}

	function LogIn($UserName, $Password){

		global $db;

		$query = $db->prepare("SELECT UserName, Password, UserID FROM tblusers WHERE UserName = :UserName");

		$query->bindParam(':UserName', $UserName);

		$query->execute();

		$result = $query->fetch();

		if(password_verify($Password, $result["Password"])){
			
			return $result['UserID'];
			
		}
		else{
			return -1;

		}	

	}

	function GetUserByID($UserID){

		global $db;

		$query = $db->prepare("SELECT UserName, Email, Type, UserID FROM tblusers INNER JOIN tblusertypes ON tblusers.UsersType = tblusertypes.TypeID
					WHERE UserID = :UserID");

		$query->bindParam(':UserID', $UserID);

		$query->execute();

		$result = $query->fetch(PDO::FETCH_ASSOC);
		return $result;

	}

	function GetUserByName($UserName){

		global $db;

		$query = $db->prepare("SELECT UserName, Email, Type, UserID FROM tblusers INNER JOIN tblusertypes ON tblusers.UsersType = tblusertypes.TypeID
					WHERE UserName = :UserName");

		$query->bindParam(':UserName', $UserName);

		$query->execute();

		$result = $query->fetch(PDO::FETCH_ASSOC);
		return $result;

	}

	function UpdateUser($UserName, $Email, $UserID){

		global $db;

		$query = $db->prepare("UPDATE tblusers SET UserName = :UserName, Email = :Email
					WHERE UserID = :UserID");

		$query->bindParam(':UserID', $UserID);
		$query->bindParam(':UserName', $UserName);
		$query->bindParam(':Email', $Email);

		$query->execute();

	}

	function ChangeUserType($Type, $UserID){

		global $db;

		$query = $db->prepare("UPDATE tblusers SET UsersType = :Type
					WHERE UserID = :UserID");

		$query->bindParam(':UserID', $UserID);
		$query->bindParam(':Type', $Type);

		$query->execute();

	}

	function DeleteUser($UserID){

		global $db;

		$query = $db->prepare($query = "DELETE FROM tblusers 
					WHERE UserID = :UserID");

		$query->bindParam(':UserID', $UserID);

		$query->execute();

	}


?>