<?php

class ActionManager {

	static function start($action) {
		$instance = self::initialize($action);

		return $instance->start();
	}

	static function initialize($action) {
		// make sure no funnybusiness
		$action = self::cleanName($action);

		// now include the file
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/cms/actions/'.strtolower($action).'.php')) {
			throw new Exception("File you requested doesn't exist ...");
		}
		require_once($_SERVER['DOCUMENT_ROOT'].'/cms/actions/'.strtolower($action).'.php');

		// initialize Action
		$class = new ReflectionClass(ucfirst($action));
		$instance = $class->newInstance($_REQUEST);

		return $instance;
	}

	static function cleanName($action) {
		return @eregi_replace("[^[:alnum:]_]","",$action);
	}
}

?>