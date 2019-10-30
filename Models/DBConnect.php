<?php 

	//Sets necessary stuff
	$dsn = "mysql:host=localhost;port=3308;dbname=vacation_site";
	$username = 'root';
	$password = '';

	try{

		//Creates connection
		$db = new PDO($dsn, $username, $password);


	}
	catch(PDOException $e){

		//Print out error if issue occurs
		$errorMessage = $e->getMessage();
		echo $errorMessage;
		exit();

	}
 

?>