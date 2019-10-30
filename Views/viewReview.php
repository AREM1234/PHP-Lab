<?php include("Views/header.php"); ?>

	<div class="mainBody">
		<div class="row">
		    <div class="col s12">
		    	<div class="card">
					<h5 class="reviewTitle"><?php echo $reviewTitle; ?></h5>
					<?php foreach($images as $image): ?>
						<img src=Models/source.php?id=<?php echo $image['aPhoto']; 	?> class="review-img" />
						<?php if(isset($editRights)): ?>
							<a href="./?action=DeleteConfirm&RecordID=<?php echo $image['aPhoto']; ?>&Type=Photo" class='orange lighten-3 black-text waves-effect waves-light btn flagglink'>Delete</a>	
						<?php endif; ?>	
					<?php endforeach; ?>
					<p>
						Created by: <a href="./?action=UserProfile&UserID=<?php echo $review['ReviewCreator']?>"><?php echo $reviewCreator; ?></a>; 
						On: <?php echo $reviewDate; ?>
					</p>
					<p><?php echo $reviewText; ?></p>
					<br /><br />
					<?php if($flagged == 0): ?>
						<p class="cardEnd"><a href="./?action=FlagReview&ReviewID=<?php echo $ReviewID; ?>" class='orange lighten-3 black-text waves-effect waves-light btn flagglink'>Flag Review</a>
					<?php else: ?>
						<p class="red-text">This review has been flagged.</p>
						<?php if(isset($userType)): ?>
							<?php if($userType == "Admin" || $userType == "Master"): ?>
								<p class="cardEnd"><a href="./?action=UnflagReview&ReviewID=<?php echo $ReviewID; ?>" class="orange lighten-3 black-text waves-effect waves-light btn">Unflag Reivew</a>
							<?php endif; ?>
						<?php endif; ?>
						
					<?php endif; ?>

					<?php if(isset($editRights)): ?>
						<a href="./?action=EditReview&ReviewID=<?php echo $ReviewID; ?>" class="orange lighten-3 black-text waves-effect waves-light btn">Edit</a>
						<a href="./?action=DeleteConfirm&RecordID=<?php echo $ReviewID; ?>&Type=Review" class="orange lighten-3 red-text waves-effect waves-light btn">Delete</a></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</p>


</main>

<?php include("Views/footer.php"); ?>
