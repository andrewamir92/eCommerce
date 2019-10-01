<?php
// function to get getTitl

	function getTitle(){
		global $pageTitle;
if (isset($pageTitle)) {
	echo "$pageTitle";
}else{
	echo "Default";
}

	}


// function to redirect

	function redirectHome($errorMsg,$url = null, $seconds = 3){
		if ($url === null) {
			$url = 'index.php';
		
		}else{
			if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
				$url = $_SERVER['HTTP_REFERER'];
			}else{
				$url = 'index.php';
			}
		}
		echo "<div class='alert alert-danger'>$errorMsg</div>";
		echo "<div class='alert alert-info'>You will be Redirected after $seconds seconds </div>";
		header("refresh:$seconds;url=$url");
	}


// function to check items in database

	function checkIteam($select, $from, $value){
		global $con;
		$statment = $con->prepare("SELECT $select FROM $from Where $select = ? ");
		$statment->execute(array($value));
		$count = $statment->rowCount();
		return $count;
	}

/**
	function used to get all data
**/
	function getData($select, $from, $key , $value){
		global $con;
		$statment = $con->prepare("SELECT $select FROM $from Where $key = ? ");
		$statment->execute(array($value));
		return $statment->fetchAll();
	}

	// function to count items

	function countItems($item,$table){
		global $con;
		$statment = $con->prepare("SELECT COUNT($item) FROM $table ");
		$statment->execute();
		return $statment->fetchColumn();
	}


	// function to get latest records

	function getLatest($select, $table, $order, $limit){
		global $con;
		$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC  LIMIT $limit ");
		$getStmt->execute();
		$rows = $getStmt->fetchAll();
		return $rows;
	}