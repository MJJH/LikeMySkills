<?php namespace Essentials\pages;

class LogOut extends \Essentials\Page {
	
	function __construct() {
		parent::__construct("index", "Home");
	}
	
	protected function onLoad() {
		setcookie("userLogin", "", time()-3600);
		// TODO remove cookie from database
		header("location: {$_SERVER['PHP_SELF']}");
	}
}