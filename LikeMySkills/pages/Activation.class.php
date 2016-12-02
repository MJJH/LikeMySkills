<?php namespace Essentials\pages;

class Activation extends \Essentials\Page {
	
	function __construct() {
		parent::__construct("index", "Activation");
	}
	
	protected function onLoad() {
		global $util;
			
		if(!empty($_GET['code'])) {
			$code = $_GET['code'];
			$ids = $util->getDatabase()->getQuery($util->getQuery("findActivation"), "s", array(&$code));
			
			if($ids) {
				// Activation found
				$id = $ids['id'];
				$userid = $ids['userid'];
				$util->getDatabase()->doQuery($util->getQuery("doActivation"), "i", array(&$id));
				$util->getDatabase()->doQuery($util->getQuery("userActivate"), "i", array(&$userid));
				
				return "signUpSuccess";
			}
		}
					
		return "index";
	}
}