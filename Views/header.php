<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
    <title>Vacation Reviews</title>

    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="css/additionalStyles.css"  media="screen,projection"/>

</head>
    <body>
    	<nav>
    		<ul id="nav">
    			<li><a href="./" class="black-text">Home</a></li>
    		</ul>
    		<?php if(!isset($_SESSION["User"])): ?>
    			<ul id="nav" class="right">
		    		<li><a class="black-text" href="./?action=Signup">Signup</a></li>
		    		<li><a class="black-text" href="./?action=Login">Log In</a></li>
		    	</ul>
	    	<?php else: ?>
	    		<ul id="nav" class="right">
		    		<?php $userRole = GetUserByName($_SESSION["User"])["Type"]; ?>
		    		<?php if($userRole == "Admin" || $userRole == "Master"): ?>
		    			<li><a class="black-text" href="./?action=ViewFlaggedReviews">View Flagged Reviews</a></li>
		    			<li><a class="black-text" href="./?action=ViewUsers">Manage Users</a></li>
		    		<?php endif; ?>
		    		<li><a href="./?action=AddReview" class="black-text">Create New Review</a></li>
		    		<li>
		    			<a href="./?action=Logout" class="black-text">
			    			Welcome <?php echo $_SESSION["User"] ?>
			    			Log Out
			    		</a>
		    		</li>
		    	</ul>
	    	<?php endif; ?>
    	</nav>

		<main>
