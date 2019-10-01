<?php

include 'connection.php';

// Template folder for admin
$tpl = 'includes/templates/';

// functions folder for admin
$func = 'includes/functions/';

// css folder for admin
$css = 'layout/css/';


// js folder for admin
$js = 'layout/js/';


$lang = 'includes/languages/';



// include important files
include $lang . 'english.php';
include $func . 'function.php';
include $tpl . "header.php";
if(!isset($noNavBar)){
include $tpl . "navbar.php";
	
}



?>