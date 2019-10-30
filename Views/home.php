<?php include("Views/header.php"); ?>
	
	<div class="banner_container">
	    <img src="Images/banner.jpg" class="banner">
	    <div class="text_div">
	    	<h4 class="black-text">Vacation Reviews!</h4>
	    </div>
	</div>
   
    <div class="mainBody">
		<h3>Reviews</h3>
		<?php if(isset($_SESSION["User"])): ?>
			<a href="./?action=AddReview" class="waves-effect waves-light btn orange lighten-3 black-text">Create New Review</a>
		<?php endif ?>
		<div class="row">
			<?php foreach($reviews as $review): ?>
				<?php $images = GetReviewsImages($review['ReviewID']); ?>
			    <div class="col s12 m4">
			      <div class="card">
			      		<span class="card-title">
			      			<h5><a href="./?action=ViewReview&ReviewID=<?php echo $review['ReviewID'] ?>"><?php echo $review['ReviewTitle']; ?></a></h5>
			      		</span>
						<p>
							Created by: <a href="./?action=UserProfile&UserID=<?php echo $review['ReviewCreator']?>"><?php echo $review['UserName']; ?></a>; 
							On: <?php echo $review['ReviewDate']; ?>
						</p>
						<p>Summary: <?php echo $review['ReviewBlurb']; ?></p>
						<?php if(isset($images[0])): ?>
							<img src=Models/source.php?id=<?php echo $images[0]['aPhoto'];	?> class="review-small-img" />
						<?php endif;?>
						<p class="cardEnd"><a href="./?action=ViewReview&ReviewID=<?php echo $review['ReviewID'] ?>">Read Full Review -></a></p>
			      </div>
			      <hr />
			    </div>
			<?php endforeach; ?>
		</div>

		
		<br />
		<br />
	</div>
</main>

<?php include("Views/footer.php"); ?>
