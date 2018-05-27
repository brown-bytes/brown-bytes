<?php
	/*List of shit to include:
	
	Require Content management system filesize
		Connect to MYSQL
		User Config
		Initialize Template

	Path and Parameters

	Load Plugins for homepage

	Buildpage


	
	
	*/
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];




	//header('Location: '.$uri.'/dashboard/');
	exit;
?>
Something is wrong with the XAMPP installation :-(
