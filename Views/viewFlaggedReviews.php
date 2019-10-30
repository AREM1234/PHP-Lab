<?php include("Views/header.php"); ?>

	<div class="mainBody">
		<h3>Flagged Reviews</h3>
		<div class="row">
			<?php foreach($reviews as $review): ?>
			    <div class="col s12 m4">
			    	<div class="card">

						<h5 class="reviewTitle"><a href="./?action=ViewReview&ReviewID=<?php echo $review['ReviewID'] ?>"><?php echo $review['ReviewTitle']; ?></a></h5>
						<p>
							Created by: <a href="./?action=UserProfile&UserID=<?php echo $review['ReviewCreator']?>"><?php echo $review['UserName']; ?></a>; 
							On: <?php echo $review['ReviewDate']; ?>
						</p>
						<p><?php echo $review['ReviewBlurb']; ?></p>
						<p class="cardEnd"><a href="./?action=ViewReview&ReviewID=<?php echo $review['ReviewID'] ?>">Read Full Review -></a></p>
				 	</div>
			    </div>
			<?php endforeach; ?>
		</div>
	</div>
</main>

<?php include("Views/footer.php"); ?>
