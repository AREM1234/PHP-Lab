<?php

	session_start();

	require("Models/DBConnect.php");
	require("Models/Users.php");
	require("Models/Reviews.php");


	if(isset($_POST['action'])){
		$action = trim(filter_input(INPUT_POST, "action", FILTER_SANITIZE_STRING));
	}
	else if(isset($_GET['action'])){
		$action = trim(filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING));
	}
	else{
		$action = 'home';
	}


	if($action == 'home'){

		try{

			$reviews = GetReviews();
			
			include("Views/home.php");

		}catch(Throwable $t){

			include("Views/errors.php");
		}		
	
	}
	else if($action == 'Signup'){

		try{

			if(isset($_GET['formError'])){
				$errorText;
				$formError = $_GET['formError'];
				switch ($formError) {
					case 'PasswordMismatch':
						$errorText = "The passwords must match please try again.";
						break;
					case 'UserNameTaken':
						$errorText = "That username is already being used please try again.";
						break;
					case 'Empty':
						$errorText = "Please fill out all fields.";
						break;
					case 'InvalidInput':
						$errorText = "Special characters are not allowed.";
						break;
					default:
						$errorText = "Something went wrong please try again.";
						break;
				}
			}

			include("Views/signup.php");

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}
	
	}
	else if($action == 'AddUser'){

		try{
			$UserName = trim(filter_input(INPUT_POST, "UserName", FILTER_SANITIZE_STRING));
			$Email = trim(filter_input(INPUT_POST, "Email", FILTER_SANITIZE_STRING));
			$Password = trim(filter_input(INPUT_POST, "Password", FILTER_SANITIZE_STRING));
			$PasswordConfirm = trim(filter_input(INPUT_POST, "PasswordConfirm", FILTER_SANITIZE_STRING));

			$reg = "/[^a-zA-Z0-9\s]/";

			if (preg_match($reg,$UserName) || preg_match($reg,$Password)) {
			    header("Location: .?action=Signup&formError=InvalidInput");
			    die;
			}

			$reg = "/[^a-zA-Z0-9.@\s]/";

			if (preg_match($reg,$Email)) {
			    header("Location: .?action=Signup&formError=InvalidInput");
			    die;
			}

			if(empty($UserName) || empty($Email) || empty($Password) || empty($PasswordConfirm) ){
				header("Location: .?action=Signup&formError=Empty");
				die;
			}

			$users = GetUsers();

			if($Password != $PasswordConfirm){
				header("Location: .?action=Signup&formError=PasswordMismatch");
				die;
			}

			foreach($users as $user){
				if($UserName == $user["UserName"]){
					header("Location: .?action=Signup&formError=UserNameTaken");
					die;
				}
			}

			CreateUser($UserName, $Email, $Password, 1);

			header("Location: .?action=Login");

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}

	}
	else if($action == "Login"){

		try{
			if(isset($_GET['formError'])){
				$errorText;
				switch ($_GET['formError']) {
					case 'Invalid':
						$errorText  = "Invalid password or username.";
						break;
					case 'Empty':
						$errorText = "Please fill out all fields.";
						break;
					default:
						$errorText = "Something went wrong please try again.";
						break;
				}
			}
			

			include("Views/login.php");

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}
		
	}
	else if($action == "AuthenticateUser"){

		try{

			$UserName = trim(filter_input(INPUT_POST, "UserName", FILTER_SANITIZE_STRING));
			$Password = trim(filter_input(INPUT_POST, "Password", FILTER_SANITIZE_STRING));

			$reg = "/[^a-zA-Z0-9\s]/";

			if (preg_match($reg,$UserName) || preg_match($reg,$Password)) {
			    header("Location: .?action=Login&formError=InvalidInput");
			    die;
			}

			if(empty($UserName) || empty($Password)){
				header("Location: .?action=Login&formError=Empty");
				die;
			}

			$users = GetUsers();

			$check = false;
			foreach($users as $user){
				if($UserName == $user["UserName"]){
					$check = true;
				}
			}

			if(!$check){
				header("Location: .?action=Login&formError=Invalid");
				die;
			}

			if(LogIn($UserName, $Password) == -1){
				header("Location: .?action=Login&formError=Invalid");
				die;
			}
			else{
				$_SESSION["User"] = $UserName;
				header("Location: .?action=home");
			}

		}	
		catch(Throwable $t){
			include("Views/errors.php");
		}		
		
	}
	else if($action == "Logout"){

		try{
			$_SESSION["User"] = null;
			header("Location: .?action=home");
		}
		catch(Throwable $t){
			include("Views/errors.php");
		}

	}
	else if($action == "ViewReview"){
		
		try{

			if(isset($_GET['ReviewID'])){
				$ReviewID = trim(filter_input(INPUT_GET, "ReviewID", FILTER_SANITIZE_STRING));
			}

			if(!isset($ReviewID)){
				header("Location: .?action=home");
				die;
			}
			if(!is_numeric($ReviewID)){
				header("Location: .?action=home");
				die;
			}

			$review = GetReviewByID($ReviewID);

			if($review == null){
				header("Location: .?action=home");
				die;
			}	

			$reviewTitle = $review['ReviewTitle'];
			$reviewDate = $review['ReviewDate'];
			$reviewText = $review['ReviewText'];
			$reviewCreator = $review['UserName'];
			$flagged = $review['Flagged'];
			$images = GetReviewsImages($ReviewID);

			if(isset($_SESSION["User"])){
				$user = GetUserByName($_SESSION["User"]);	
				$userType = $user['Type'];
				if($_SESSION["User"] == $reviewCreator || $userType == "Admin" || $userType == "Master"){
					$editRights = true;
				}
			}
				
			include("Views/viewReview.php");

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}

	}
	else if($action == "AddReview"){

		try{
			if(!isset($_SESSION["User"])){
				header("Location: .?action=home");
				die;
			}

			if(isset($_GET['formError'])){
				$errorText;
				$formError = $_GET['formError'];
				switch ($formError) {
					case 'Empty':
						$errorText = "Please fill out all fields.";
						break;
					case 'InvalidInput':
						$errorText = "No special characters allowed.";
						break;
					case 'InvalidFile':
						$errorText = "Invalid File Type";
						break;
					default:
						$errorText = "Something went wrong please try again.";
						break;
				}
			}

			include("Views/addReviewForm.php");

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}

	}
	else if($action == "CreateReview"){

		try{
			if(!isset($_SESSION["User"])){
				header("Location: .?action=home");
				die;
			}

			$ReviewTitle = trim(filter_input(INPUT_POST, "ReviewTitle", FILTER_SANITIZE_STRING));
			$ReviewBlurb = trim(filter_input(INPUT_POST, "ReviewBlurb", FILTER_SANITIZE_STRING));
			$ReviewText = trim(filter_input(INPUT_POST, "ReviewText", FILTER_SANITIZE_STRING));
			$ReviewCreator = trim(filter_input(INPUT_POST, "ReviewCreator", FILTER_SANITIZE_STRING));

			$reg = "/[^a-zA-Z0-9.,\s]/";

			if (preg_match($reg,$ReviewTitle) || preg_match($reg,$ReviewBlurb) || preg_match($reg,$ReviewText)) {
			    header("Location: .?action=AddReview&formError=InvalidInput");
			    die;
			}

			if(empty($ReviewTitle) || empty($ReviewBlurb) || empty($ReviewText) || empty($ReviewCreator)){
				header("Location: .?action=AddReview&formError=Empty");
				die;
			}

			$user = GetUserByName($ReviewCreator);
			$ReviewDate=date("Y-m-d", time());

	
			$imagename=$_FILES["myimage"]["name"];

			$allowed =  array('jpg', 'png', 'gif', 'jpeg');
			
			if($imagename != ""){
				$ext = pathinfo($imagename, PATHINFO_EXTENSION);
				if(!in_array($ext,$allowed) ) {
				    header("Location: .?action=AddReview&formError=InvalidFile");
			    	die;
				}
				$imagetmp=file_get_contents($_FILES['myimage']['tmp_name']);
			}

			$reviewID = CreateReview($ReviewTitle, $ReviewDate, $ReviewBlurb, $ReviewText, $user["UserID"]);

			if($imagename != ""){
				$imageID = CreateImage($imagetmp, $imagename);

				CreateReviewsImages($reviewID, $imageID);

			}
			

			header("Location: .?action=home");
		}
		catch(Throwable $t){
			include("Views/errors.php");
		}

	}
	else if($action == "EditReview"){

		try{
			if(!isset($_SESSION["User"])){
				header("Location: .?action=home");
				die;
			}

			if(isset($_GET['ReviewID'])){
				$ReviewID = trim(filter_input(INPUT_GET, "ReviewID", FILTER_SANITIZE_STRING));
			}

			if(!isset($ReviewID)){
				header("Location: .?action=home");
				die;
			}
			if(!is_numeric($ReviewID)){
				header("Location: .?action=home");
				die;
			}

			$review = GetReviewByID($ReviewID);

			if($review == null){
				header("Location: .?action=home");
				die;
			}	

			$reviewTitle = $review['ReviewTitle'];
			$reviewText = $review['ReviewText'];
			$reviewBlurb = $review["ReviewBlurb"];

			if(isset($_GET['formError'])){
				$errorText;
				$formError = $_GET['formError'];
				switch ($formError) {
					case 'Empty':
						$errorText = "Please fill out all fields.";
						break;
					case 'InvalidInput':
						$errorText = "Special characters are not allowed.";
						break;
					default:
						$errorText = "Something went wrong please try again.";
						break;
				}
			}

			include("Views/editReviewForm.php");

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}

	}
	else if($action == "UpdateReview"){

		try{
			if(!isset($_SESSION["User"])){
				header("Location: .?action=home");
				die;
			}

			$ReviewTitle = trim(filter_input(INPUT_POST, "ReviewTitle", FILTER_SANITIZE_STRING));
			$ReviewBlurb = trim(filter_input(INPUT_POST, "ReviewBlurb", FILTER_SANITIZE_STRING));
			$ReviewText = trim(filter_input(INPUT_POST, "ReviewText", FILTER_SANITIZE_STRING));
			$ReviewID = trim(filter_input(INPUT_POST, "ReviewID", FILTER_SANITIZE_STRING));

			$reg ="/[^a-zA-Z0-9.,\s]/";

			if (preg_match($reg,$ReviewTitle) || preg_match($reg,$ReviewBlurb) || preg_match($reg,$ReviewText)) {
			    header("Location: .?action=EditReview&formError=InvalidInput");
			    die;
			}

			if(empty($ReviewTitle) || empty($ReviewBlurb) || empty($ReviewText) || empty($ReviewID)){
				header("Location: .?action=EditReview&formError=Empty");
				die;
			}

			$review = GetReviewByID($ReviewID);
			$user = GetUserByName($_SESSION["User"]);

			$imagename=$_FILES["myimage"]["name"];

			$allowed =  array('jpg', 'png', 'gif', 'jpeg');
			
			if($imagename != ""){
				$ext = pathinfo($imagename, PATHINFO_EXTENSION);
				if(!in_array($ext,$allowed) ) {
				    header("Location: .?action=EditReview&formError=InvalidFile");
			    	die;
				}
				$imagetmp=file_get_contents($_FILES['myimage']['tmp_name']);
			}

			if($user['UserID'] == $review["ReviewCreator"] || $user["Type"] == "Admin" || $user["Type"] == "Master"){
				UpdateReview($ReviewTitle, $ReviewBlurb, $ReviewText, $ReviewID);
				if($imagename != ""){
					$imageID = CreateImage($imagetmp, $imagename);
					CreateReviewsImages($ReviewID, $imageID);
				}
			}

			header("Location: .?action=ViewReview&ReviewID=" . $ReviewID);

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}
		
	}
	else if($action == "DeleteConfirm"){

		try{
			if(!isset($_SESSION["User"])){
				header("Location: .?action=home");
				die;
			}

			if(!isset($_GET["RecordID"])){
				header("Location: .?action=home");
				die;
			}

			if(!isset($_GET["Type"])){
				header("Location: .?action=home");
				die;
			}

			$RecordID = trim(filter_input(INPUT_GET, "RecordID", FILTER_SANITIZE_STRING));
			$Type = trim(filter_input(INPUT_GET, "Type", FILTER_SANITIZE_STRING));

			switch ($Type) {
				case 'Review':
					$Action = "DeleteReview";
					$ReviewTitle = GetReviewByID($RecordID)["ReviewTitle"];
					break;
				case 'User':
					$Action = "DeleteUser";
					$UserName = GetUserByID($RecordID)["UserName"];
					break;
				case 'Photo':
					$Action = "DeletePhoto";
					break;
				default:
					$Action = "home";
					break;
			}

			include("Views/deleteconfirm.php");

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}
		
	}
	else if($action == "DeleteReview"){

		try{
			if(!isset($_SESSION["User"])){
				header("Location: .?action=home");
				die;
			}

			if(!isset($_GET["RecordID"])){
				header("Location: .?action=home");
				die;
			}

			$RecordID = trim(filter_input(INPUT_GET, "RecordID", FILTER_SANITIZE_STRING));

			$review = GetReviewByID($RecordID);
			$user = GetUserByName($_SESSION["User"]);


			if($user['UserID'] == $review["ReviewCreator"] || $user["Type"] == "Admin" || $user["Type"] == "Master"){
				DeleteReviewsPhotos($RecordID);
				DeleteReview($RecordID);
			}		

			header("Location: .?action=home");

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}
		
	}
	else if($action == "FlagReview"){

		try{
			if(isset($_GET['ReviewID'])){
				$ReviewID = trim(filter_input(INPUT_GET, "ReviewID", FILTER_SANITIZE_STRING));
			}
			else{
				header("Location: .?action=home");
				die;
			}

			if(!is_numeric($ReviewID)){
				header("Location: .?action=home");
				die;
			}

			ChangeReviewFlag($ReviewID, 1);

			header("Location: .?action=ViewReview&ReviewID=" . $ReviewID);
		}
		catch(Throwable $t){
			include("Views/errors.php");
		}

	}
	else if($action == "UnflagReview"){

		try{
			$user = CheckAuthorization();		

			if(isset($_GET['ReviewID'])){
				$ReviewID = trim(filter_input(INPUT_GET, "ReviewID", FILTER_SANITIZE_STRING));
			}
			else{
				header("Location: .?action=home");
				die;
			}

			if(!is_numeric($ReviewID)){
				header("Location: .?action=home");
				die;
			}

			ChangeReviewFlag($ReviewID, 0);

			header("Location: .?action=ViewReview&ReviewID=" . $ReviewID);

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}

	}
	else if($action == "ViewFlaggedReviews"){

		try{
			$user = CheckAuthorization();

			$loggedInUser = GetUserByName($_SESSION["User"]);

			if($loggedInUser["Type"] == "Admin" || $loggedInUser["Type"] == "Master"){
				
			}
			else {
				header("Location: .?action=home");
				die;
			}

			$reviews = GetFlaggedReviews();

			include("Views/viewFlaggedReviews.php");

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}

	}
	else if($action == "ViewUsers"){

		try{
			$user = CheckAuthorization();

			$loggedInUser = GetUserByName($_SESSION["User"]);

			if($loggedInUser["Type"] == "Admin" || $loggedInUser["Type"] == "Master"){
				
			}
			else {
				header("Location: .?action=home");
				die;
			}

			$users = GetUsers();

			include("Views/viewUsers.php");

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}

	}
	else if($action == "MakeAdmin"){

		try{
			$user = CheckAuthorization();

			if(isset($_GET['UserID'])){
				$UserID = trim(filter_input(INPUT_GET, "UserID", FILTER_SANITIZE_STRING));
			}
			else{
				header("Location: .?action=home");
				die;
			}

			ChangeUserType(2, $UserID);

			header("Location: .?action=ViewUsers");

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}		

	}
	else if($action == "DeleteUser"){

		try{
			$user = CheckAuthorization();

			if(isset($_GET['RecordID'])){
				$UserID = trim(filter_input(INPUT_GET, "RecordID", FILTER_SANITIZE_STRING));
			}
			else{
				header("Location: .?action=home");
				die;
			}

			$user = GetUserByID($UserID);

			$reviews = GetReviewsByUserName($user["UserName"]);

			if($reviews != null){
				foreach($reviews as $review){
				
					DeleteReview($review["ReviewID"]);

				}
			}	

			DeleteUser($UserID);

			header("Location: .?action=ViewUsers");

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}
		
	}
	else if($action == "UserProfile"){

		try{
			if(isset($_GET['UserID'])){
				$UserID = trim(filter_input(INPUT_GET, "UserID", FILTER_SANITIZE_STRING));
			}
			else{
				header("Location: .?action=home");
				die;
			}

			$user = GetUserByID($UserID);
			$reviews = GetReviewsByUserName($user["UserName"]);

			include("Views/userProfile.php");

		}
		catch(Throwable $t){
			include("Views/errors.php");
		}

	}
	else if($action == "DeletePhoto"){

		try{

			if(isset($_GET['RecordID'])){
				$PhotoID = trim(filter_input(INPUT_GET, "RecordID", FILTER_SANITIZE_STRING));
			}
			else{
				header("Location: .?action=home");
				die;
			}

			DeleteReviewsPhotosByPhoto($PhotoID);
			DeletePhoto($PhotoID);

			header("Location: .?action=home");

			}
		catch(Throwable $t){
			include("Views/errors.php");
		}

	}
	else{
		include("Views/errors.php");
	}



	function CheckAuthorization(){

		if(!isset($_SESSION["User"])){
			header("Location: .?action=home");
			die;
		}

		$user = GetUserByName($_SESSION["User"]);

		if($user["Type"] == "Admin" || $user["Type"] == "Master"){
			
		}
		else {
			header("Location: .?action=home");
			die;
		}	

		return $user;

	}




?>