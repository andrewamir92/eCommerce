<?php
	function lang ($phrase){

		static $lang  = array(
			'message' => '' ,
			'admin'		=> ''		

		 );
		return $lang[$phrase];
	}


?>