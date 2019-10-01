<?php
session_start();
if (isset($_SESSION['username'])) {
$pageTitle = 'Dashboard';
include 'init.php';?>
			<h1 class="text-center m-3">DashBoard</h1>
	<div class="container dashboard">
		<div class="row">
			<div class="col-md-3 text-center">
				<div class="edit members">
				<h3>Total Members</h3>
				<span><a href="members.php"><?php echo countItems('UserID','users') ?></a></span>
				</div>
			</div>
			<div class="col-md-3 text-center">
				<div class="edit pending">
				<h3>Pending Items</h3>
				<span><a href="members.php?do=manage&page=pending"><?php echo checkIteam('RegStatus','users',0); ?></a></span>
				</div>
			</div>
			<div class="col-md-3  text-center">
				<div class="edit items">
				<h3>Total Items</h3>
				<span>122</span>
				</div>
			</div>
			<div class="col-md-3 text-center">
				<div class="edit comments">

					<h3>Total Comments</h3>
					<span>122</span>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class=" mt-4  p-2">
					<h4 class="border p-2"><i class="fa fa-users">Latest Registered Users</i><h4>
						<div class="white  border latest-users">
							<?php
								$latestUsers = 5;
							?>
						<div>
						<ul class="list-unstyled pl-2">
							<?php 
								$theLatest = getLatest('*', 'users', 'UserID', $latestUsers);
								foreach($theLatest as $user){
									
									echo " <li class ='p-1'>" . $user['UserName'] . "<span class='btn btn-success pull-right'><i class='fa fa-edit  mr-1'></i>
									 <a href='members.php?do=edit&userid=" . $user['UserID'] . "'>Edit</a></span>";
									 	if($user['RegStatus'] == 0){
									 		?>
									 		<a class='pull-right border border-info border-left-0 border-right-0 btn btn-info active-btn' href="members.php?do=activate&userid= <?php echo $user['UserID']; ?>">Activate</a>
									 		<?php	
									 	}
									 ?>
									    </li>
								<?php
								}
							 ?>
								 <!-- <?php if( $user['RegStatus'] == 0): ?>

						<a href="<?= 'members.php?do=activate&userid=' . $user['UserID']; ?>" 
		  				class='border border-info border-left-0 border-right-0 btn btn-outline-info ml-3  '>
		  				<i class='fa fa-close m-1'></i>Active</a>

		  					<?php endif; ?> -->
						</div>
					</ul>
						</div>
				</div>
			</div>
				<div class="col-md-6">
				<div class=" mt-4  p-2">
					
					<h4 class="border p-2"><i class="fa fa-edit">Latest Items</i><h4>
						<div class=" border white">
						
						</div>
				</div>
				</div>
			</div>
	</div>
	

<?php
include $tpl . "footer.php";
}else{

}