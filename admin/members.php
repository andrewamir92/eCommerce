<?php
session_start();
if (isset($_SESSION['username'])) {
$pageTitle = 'members';
include 'init.php';
$do = '';

	if(isset($_GET['do'])){
		$do = $_GET['do'];
	}else{
		$do = 'manage';
	}
	// start managa page
	if($do == 'manage'){
		$query = '';
		if (isset($_GET['page']) && $_GET['page'] == 'pending') {
		 	$query = ' AND RegStatus = 0';
		 } 
		$stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		?>
		<h1 class="text-center">Manage Page</h1>
		<div class="container table-responsive">
			<table class="table  table-bordered table-striped">
  <thead class="thead-dark">
    <tr class="text-center">
      <th scope="col">#</th>
      <th scope="col">UserName</th>
      <th scope="col">Email</th>
      <th scope="col">FullName</th>
      <th scope="col">Registered Date</th>
      <th scope="col">Control</th>
    </tr>
  </thead>
  <tbody>
  	<?php foreach ($rows as $row): ?>

  		<tr class='text-center'>
	  		<td><?= $row['UserID']; ?></td>
	  		<td><?= $row['UserName']; ?></td>
	  		<td><?= $row['Email']; ?></td>
	  		<td><?= $row['FullName']; ?></td>
	  		<td><?= $row['Date']; ?></td>

	  		<td>
	  			<a href="<?= '?do=edit&userid=' . $row['UserID']; ?>" 
	  				class='border border-success border-left-0 border-right-0 btn btn-outline-success m-1'>
	  				<i class='fa fa-edit m-1'></i>Edit</a>

	  			<a href="<?= '?do=delete&userid=' . $row['UserID']; ?>" 
	  				class='border border-danger border-left-0 border-right-0 btn btn-outline-danger ml-3 delete'>
	  				<i class='fa fa-close m-1'></i>Delete</a>

	  				<?php if( $row['RegStatus'] == 0): ?>

					<a href="<?= '?do=activate&userid=' . $row['UserID']; ?>" 
	  				class='border border-info border-left-0 border-right-0 btn btn-outline-info ml-3 '>
	  				<i class='fa fa-check m-1'></i>Active</a>

	  				<?php endif; ?>

	  		</td>
  		</tr>

  	<?php endforeach; ?>
    
  </tbody>
</table>


		<a href="?do=add" class=" mb-5 border border-primary border-left-0 border-right-0 btn btn-outline-primary"><i class="fa fa-plus"></i> Add New Member</a>
		</div>

	<?php
}
	// start of add page
	if($do == 'add'){  
		?>
		<h1 class="text-center mt-5 ">Add New Member</h1>
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
					<form class="mt-5" action="?do=insert" method="POST" >
						<div class=" form-group">
							<label for="user"><b><h3>UserName</h3></b></label>
							<input type="text" id="user" name="username"  class="w-75" placeholder="Insert User Name" required>
						</div>
						<div class=" form-group">
							<label for="pass"><b><h3>Password </h3></b></label>
							
							<input type="password" id="pass" name="password"  class=" password w-75" required placeholder="Insert Your Password">
							<i class="show_password fa fa-eye-slash"></i>
						</div>
						<div class=" form-group">
							<label for="email"><b><h3>Email</h3></b></label>
							<input type="email" id="email" name="email" class="w-75 ml-5" required placeholder="Insert Your Email">
						</div>
						<div class=" form-group">
							<label for="fullname"><b><h3>FullName</h3></b></label>
							<input type="text" id="fullname" name="fullname"  class="w-75" required placeholder="Insert Your Full Name">
						</div>
						<div class="">
							<input type="submit" value="add member"  class="btn btn-primary btn-lg w-100 text-center">
						</div>

					</form>
				</div>
			</div>
				</div>
<?php

	}
	
	if ($do == 'insert') {
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
		 $user = $_POST['username'];
		 $pass = sha1($_POST['password']);
		 $email = $_POST['email'];
		 $fullname = $_POST['fullname'];
		 $errors = [];
		 //start of Validation
		$errors = [];
		 if(empty($user)){
		 	$errors[] = "User feild cant be empty";
		 }
		 if (strlen($user) < 4 ) {
		 	$errors[] = "User Name Feild cant be less than 4 chars";
		 }
		 if (strlen($user) > 20 ) {
		 	$errors[] = "User Name Feild cant be more than 20 chars";
		 }
		 if (empty($pass)) {
		 	$errors[] = "password Feild cant be empty";
		 }
		 
		 if(empty($email)){
		 	$errors[] = "Email feild cant be empty";
		 }
		 if (strlen($email) < 4 ) {
		 	$errors[] = "email Feild cant be less than 4 chars";
		 }
		 if (strlen($email) > 20 ) {
		 	$errors[] = "email Feild cant be more than 20 chars";
		 }
		 if(empty($fullname)){
		 	$errors[] = "Full Name Feild cant be empty";
		 }
		 foreach ($errors as $error) {
		 	echo "<div class='alert alert-danger'>" . $error . "<br>" . "</div>";
		 	// echo $error . "<br>";
		 }
		
			
		if(empty($errors)){
			// check if user exist in database
			$check = checkIteam("UserName","users",$user);
			if ($check == 1) {
				echo "This User is Exist";
			}else{
				($checkUserType = getData("*" , "users" , "UserName" , $_SESSION['username']));
				$regStatus = 0;
				if(count($checkUserType)> 0){
					if($checkUserType[0]['GroupID']  == "1"){
						$regStatus = 1;
					}
				}
				// insert new member into Database from admin 
			$stmt = $con->prepare("INSERT INTO users(UserName, Password, Email, FullName,RegStatus, Date)
			VALUES(:user, :pass, :email, :fullname,:regStatus , now())
			");
			$stmt->execute(array(
				'user' 	=> $user,
				'pass' 	=> $pass,
				'email'	=> $email,
				'fullname'	=> $fullname,
				'regStatus' => $regStatus
			));
			echo  "<div class='alert alert-success'>Member has been inserted</div>";
			
			}
		}

		}else{
			$errorMsg = "Sorry You Cant Brows This Page Directly";
			redirectHome($errorMsg ,'members.php',10);
		}
	}

	// end of add page

	// start of edit page

	if ($do == 'edit') { //edit page
		$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;
		$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
		$stmt->execute(array($userid));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();
	
	if ($count > 0) {
		?>
			<h1 class="text-center mt-5 ">Edit Member</h1>
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
					<form class="mt-5" action="?do=update" method="POST" >
						<div class=" form-group">
							<input type="hidden" name="userid" value="<?php echo $userid ?>">
							<label for="user"><b><h3>UserName</h3></b></label>
							<input type="text" id="user" name="username" value="<?php echo $row['UserName'] ?>" class="w-75" required>
						</div>
						<div class=" form-group">
							<label for="pass"><b><h3>Password </h3></b></label>
							<input type="hidden" id="pass" name="oldpassword" value="<?php echo $row['Password'] ?>" >
							<input type="password" id="pass" name="newpassword"  class="w-75">
						</div>
						<div class=" form-group">
							<label for="email"><b><h3>Email</h3></b></label>
							<input type="text" id="email" name="email" value="<?php echo $row['Email'] ?>" class="w-75 ml-5" required>
						</div>
						<div class=" form-group">
							<label for="fullname"><b><h3>FullName</h3></b></label>
							<input type="text" id="fullname" name="fullname" value="<?php echo $row['FullName'] ?>" class="w-75" required>
						</div>
						<div class="">
							<input type="submit" value="save"  class="btn btn-primary btn-lg w-100 text-center">
						</div>

					</form>
				</div>
			</div>
				</div>

		<?php
	}else{
		$errorMsg = "This id isnt exits";
		
			redirectHome($errorMsg );
	}
} 
// end of edit page

// start of update page
if( $do == 'update'){
	echo "<h1 class='text-center'>Update Member </h1>";
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$id = $_POST['userid'];
		$user = $_POST['username'];
		$email = $_POST['email'];
		$name = $_POST['fullname'];
		$newPassword = $_POST['newpassword'];
		$oldPassword = $_POST['oldpassword'];

		$pass = '';
		if(empty($newPassword)){
			$pass = $oldPassword;
		}else{
			$pass = sha1($newPassword);
		}

		//start of Validation
		$errors = [];
		 if(empty($user)){
		 	$errors[] = "User feild cant be empty";
		 }
		 if (strlen($user) < 4 ) {
		 	$errors[] = "User Name Feild cant be less than 4 chars";
		 }
		 if (strlen($user) > 20 ) {
		 	$errors[] = "User Name Feild cant be more than 20 chars";
		 }
		 if(empty($email)){
		 	$errors[] = "Email feild cant be empty";
		 }
		 if(empty($name)){
		 	$errors[] = "Full Name Feild cant be empty";
		 }
		 foreach ($errors as $error) {
		 	echo "<div class='alert alert-danger'>" . $error . "<br>" . "</div>";
		 	// echo $error . "<br>";
		 }
		 if(empty($errors)){
			$stmt = $con->prepare("UPDATE users SET UserName = ? , Email = ? , FullName = ? , Password = ? WHERE UserID = ?");
			$stmt->execute(array($user, $email, $name, $pass, $id));
			$errorMsg =   $stmt->rowCount() . "record updated" ;
			redirectHome($errorMsg);

		 }
		 // end of validation

		 
	}else{
		$errorMsg =  "you cant brows here !!!";
		redirectHome($errorMsg,'back');
	}
}

// end of update page

// start of Delete page
if ($do == 'delete') {
	echo "<h1 class='text-center'>delete Page </h1>";
	$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;
		$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
		$stmt->execute(array($userid));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();
	if ($count > 0) {
		$stmt = $con->prepare("DELETE FROM users WHERE UserID = :user AND GroupID !=1");
		$stmt->bindParam(':user',$userid);
		$stmt ->execute();
		$errorMsg =  $stmt->rowCount() . "record Deleted" ;
		redirectHome($errorMsg );
}else{
	$errorMsg = "this user isnt exist";
	redirectHome($errorMsg, 'back' );
}
}


// end of Delete Page

 // start of activate page
if ($do == 'activate') {
	echo "<h1 class='text-center'>activate Page </h1>";
	$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;
		$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?  LIMIT 1");
		$stmt->execute(array($userid));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();
	if ($count > 0) {
		$stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
		$stmt ->execute(array($userid));
		$errorMsg =  $stmt->rowCount() . "record Activated" ;
		redirectHome($errorMsg );
}else{
	$errorMsg = "this user isnt exist";
	redirectHome($errorMsg);
}
}

 // end of activate page

include $tpl . "footer.php";
}else{

}