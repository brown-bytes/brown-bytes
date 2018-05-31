<?php
require_once($_SERVER['DOCUMENT_ROOT']."/cms/actions.php");


if (isset($_REQUEST['a'])) {
	try {
		$return = ActionManager::start($_REQUEST['a']);
		if (!is_null($return)) die(@json_encode($return));

	} catch (Exception $e) {
		//This is were to put global AJAX
	}
}
?>