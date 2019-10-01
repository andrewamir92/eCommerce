<?php
	$do = '';

	if(isset($_GET['do'])){
		$do = $_GET['do'];
	}else{
		$do = 'manage';
	}

	if ($do == 'manage'){
		echo "Welcome You are in manage page";
	}elseif ($do == 'add') {
		echo "Welcome you are in add page";
	}else{
		echo "there is no page name like this";
	}


?>