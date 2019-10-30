<?php include("Views/header.php"); ?>

	<div class="mainBody">
		<h3>Users</h3>
		<div class="row">
			<?php foreach($users as $user): ?>
				<div class="col s12 m3">
				    <div class="card userCard">
						<h5 class="reviewTitle"><a href="./?action=UserProfile&UserID=<?php echo $user['UserID']?>">Username: <?php echo $user['UserName']; ?></a></h5>
						<p>Email: <?php echo $user['Email']; ?></p>
						<p>Type: <?php echo $user['Type']; ?></p>
						<p class="cardEnd">
							<?php if($user["Type"] == "User"): ?>
								<button><a href="./?action=MakeAdmin&UserID=<?php echo $user['UserID']; ?>">Make Admin</a></button>
								<button><a href="./?action=DeleteConfirm&RecordID=<?php echo $user['UserID']; ?>&Type=User">Delete User</a></button>
							<?php elseif($user["Type"] == "Admin" && $loggedInUser["Type"] == "Master"): ?>
								<button><a href="./?action=MakeMaster&UserID=<?php echo $user['UserID']; ?>">Make Master</a></button>
								<button><a href="./?action=DeleteConfirm&RecordID=<?php echo $user['UserID']; ?>&Type=User">Delete User</a></button>
							<?php endif; ?>
						</p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

</main>

<?php include("Views/footer.php"); ?>
