<?php include("Views/header.php"); ?>

	<div class="mainBody">
		<br />
		<div class="row">
		    <div class="col s12 m8 offset-m2">
		    	<div class="card">
		    		<h5 class="reviewTitle">Sign up</h5>
					<?php if(isset($errorText)): ?>

						<h4><?php echo $errorText ?></h4>
						<br />
					<?php endif; ?>
					<div class="row">
					    <div class="col s12 m10 offset-m1">
							<form action="." method="post">

								<input type="hidden" name="action" value="AddUser" />

								<label class="black-text" for="UserName"><h5>User Name</h5></label>
								<input name="UserName" type="text" id="UserName" maxlength="45" required/>

								<label class="black-text" for="Email"><h5>Email</h5></label>
								<input type="email" name="Email" id="Email" maxlength="45" required/>

								<label class="black-text" for="Password"><h5>Password</h5></label>
								<input type="password" name="Password" id="Password" maxlength="45" required/>

								<label class="black-text" for="PasswordConfirm"><h5>Confirm Password</h5></label>
								<input type="password" name="PasswordConfirm" id="PasswordConfirm" maxlength="45" required/>

								<button class="btn waves-effect waves-light orange lighten-3 black-text right" type="submit">Sign Up</button>

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
