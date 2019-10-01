<?php
$dsn = 'mysql:host=localhost;dbname=shop';
$user = 'root';
$pass = '123123';
$option = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => 'set NAMES utf8'
);
try{
	$con = new PDO($dsn, $user, $pass, $option);
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// echo "you are Connected";
}
catch(PDOException $e){
	echo "faild to Connect to DataBase " . $e->getMessage();
}


?>
