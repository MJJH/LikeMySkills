<?php namespace Essentials\pages;

class LogOut extends \Essentials\Page {
	
	function __construct() {
		parent::__construct("index", "Home");
	}
	
	protected function onLoad() {
		global $util;
		
		$cookie = $_COOKIE['userLogin'];
		
		if(!empty($cookie)) {
			setcookie("userLogin", "", time()-3600);
			$util->getDatabase()->doQuery($util->getQuery("removeCookie"), "s", array(&$cookie));
		}
		
		header("location: {$_SERVER['PHP_SELF']}");
	}
}