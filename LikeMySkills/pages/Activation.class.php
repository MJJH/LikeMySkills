<?php namespace Essentials\pages;

class Activation extends \Essentials\Page {
	
	function __construct() {
		parent::__construct("index", "Activation");
			
		if(!empty($_GET['code'])) {
			// Activate
			die($_GET['code']);
		}
		global $util;
		header('location: '.$util->getSetting("path"));
	}
}