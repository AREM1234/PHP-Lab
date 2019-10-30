<?php include("Views/header.php"); ?>

	<div class="mainBody">
		<br />
		<div class="row">
		    <div class="col s12 m8 offset-m2">
		    	<div class="card">
		    		<h4 class="reviewTitle">Add a review</h4>
					<?php if(isset($errorText)): ?>

						<h4><?php echo $errorText; ?></h4>
						<br />
					<?php endif; ?>
					<div class="row">
					    <div class="col s12 m10 offset-m1">
							<form action="." method="post" enctype="multipart/form-data">

								<input type="hidden" name="action" value="CreateReview" />
								<input type="hidden" name="ReviewCreator" value="<?php echo $_SESSION['User'] ?>">

								<label class="black-text" for="ReviewTitle"><h5>Review Title</h5></label>
								<input name="ReviewTitle" type="text" id="ReviewTitle" maxlength="45" required/>

								<label class="black-text" for="ReviewBlurb"><h5>Review Blurb</h5></label>
								<input type="text" name="ReviewBlurb" id="ReviewBlurb" maxlength="45" required/>

								<label class="black-text" for="ReviewText"><h5>Review Text</h5></label>
								<textarea rows="4" cols="50" maxlength="65535" name="ReviewText" id="ReviewText" required></textarea>

								<p>Add an image to your review. (Optional) You can add more later</p>
								<input type="file" name="myimage" />
								<br />
								<br />
								<button class="btn waves-effect waves-light right orange lighten-3 black-text" type="submit">Add</button>

							</form>
							<br />
							<br />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</main>

<?php include("Views/footer.php"); ?>
