<?php
	function lang ($phrase){

		static $lang  = array(
			// navbar links
			'home' 				=> 'HOME' ,
			'categories'		=> 'Categories'	,
			'ITEMS'				=> 'Items',
			'MEMBERS'			=> 'Members',
			'STATISTICS'		=> 'Statistics',
			'LOGS'				=> 'Logs'	

		 );
		return $lang[$phrase];
	}


?>