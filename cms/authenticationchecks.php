<?php
require_once($_SERVER['DOCUMENT_ROOT']."/cms/session.php");

class AuthenticationChecks extends CMSPlugin {
	public static function adminOnly() {
		 return ((isset($_SESSION['admin']) && $_SESSION['admin'] == true));
	}

	public static function hasRight() {

		if(parent::hasRights("active") == true)
			return true;
		else
			return false;
	}

	public static function checkRights() {

		$hasrights = self::adminOnly();
		if(!$hasrights)
			$hasrights = self::hasRight();

		return $hasrights;
	}

}

?>