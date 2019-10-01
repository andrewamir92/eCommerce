<?php
session_start();
$noNavBar = '';
$pageTitle = 'login';

if (isset($_SESSION['username'])) {
		header('location:dashboard.php');
}

include 'init.php';

// print_r($_SESSION);
// check if it send POST from form 
if($_SERVER['REQUEST_METHOD'] === 'POST'){

	$username = $_POST['user'];
	$password = $_POST['pass'];
	$hashedPass = sha1($password);

// check if user exist in database
	$stmt = $con->prepare("SELECT UserID, UserName, Password FROM users WHERE UserName = ? AND Password = ? AND GroupID = 1 LIMIT 1");
	$stmt->execute(array($username,$hashedPass));
	$row = $stmt->fetch();
	$count = $stmt->rowCount();

	// if count not equal 0 then database has this user and password
	if($count > 0 ){
		$_SESSION['username'] = $username;
		$_SESSION['ID'] = $row['UserID'];
		header('location:dashboard.php');
		exit();
	}




}

?>
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<h4 class="text-center">Admin Login</h4>
		<input class="form-control" type="text"  name="user" placeholder="UserName" autocomplete="off">
		<input class="form-control" type="password"  name="pass" placeholder="password">
		<input class="btn btn-primary btn-block" type="submit" value="login">
	</form>
<?php
include $tpl . "footer.php";
?>