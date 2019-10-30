<?php include("Views/header.php"); ?>

	<div class="mainBody">
		<h3>Are you sure you want to delete 
			<?php 

				if(isset($ReviewTitle)){
					echo $ReviewTitle;
				}
				else if(isset($UserName)){
					echo $UserName . ". All reviews by this user will also be deleted.";
				} 
				else{
					echo "<img src=Models/source.php?id=" . $RecordID . " class='review-img' />";
				}

			?>?
		</h3>
		<a href="./" class="orange lighten-3 black-text waves-effect waves-light btn">Cancel</a>
		<a href="./?action=<?php echo $Action ?>&RecordID=<?php echo $RecordID ?>" class="orange lighten-3 black-text waves-effect waves-light btn">Confirm</a>
	</div>
</main>

<?php include("Views/footer.php"); ?>
