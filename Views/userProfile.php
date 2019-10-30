<?php include("Views/header.php"); ?>

	<div class="mainBody">
		<h3><?php echo $user["UserName"]; ?></h3>
		<p>Email: <?php echo $user["Email"]; ?></p>

		<h3>Reviews</h3>
		<div class="row">
			<?php foreach($reviews as $review): ?>
			    <div class="col s12 m6">
			      <div class="card">
			      		<h5 class="reviewTitle"><a href="./?action=ViewReview&ReviewID=<?php echo $review['ReviewID'] ?>"><?php echo $review['ReviewTitle']; ?></a></h5>
						<p>Created by: <?php echo $review['UserName']; ?>; On: <?php echo $review['ReviewDate']; ?></p>
						<p>Summary: <?php echo $review['ReviewBlurb']; ?></p>
						<p class="cardEnd"><a href="./?action=ViewReview&ReviewID=<?php echo $review['ReviewID'] ?>">Read Full Review -></a></p>
			      </div>
			    </div>
			<?php endforeach; ?>
		</div>
	</div>

</main>

<?php include("Views/footer.php"); ?>
