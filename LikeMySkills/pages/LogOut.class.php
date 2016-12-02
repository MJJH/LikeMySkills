<?php namespace Essentials\pages;

class LogOut extends \Essentials\Page {
	
	function __construct() {
		parent::__construct("index", "Home");
	}
	
	protected function onLoad() {
		global $util;
		
		if (isset($_COOKIE['userLogin']) && !empty($_COOKIE['userLogin'])) {
		
			$cookie = $_COOKIE['userLogin'];
			setcookie("userLogin", "", time()-3600);
			$util->getDatabase()->doQuery($util->getQuery("removeCookie"), "s", array(&$cookie));
			$util->logOut();
		}
		
		return "index";
	}
}