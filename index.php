<?php
	print_r('shit started in index.php<br/>');
	/*List of shit to include:

	Require Content management system filesize
		Connect to MYSQL
		User Config
		Initialize Template

	Path and Parameters

	Load Plugins for homepage

	Buildpage
	*/
	error_reporting(E_ALL);

    global $start;

    require_once("cms/system.php");

    //create the CMS variable
    $_CMS = CMS::initialize();
	print_r('<pre>');
	var_dump($_CMS);
	print_r('</pre>');
	var_dump('shit');


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
