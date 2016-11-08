<?php
/**
	Translate all texts on the webpage
*/
	function getLan() {
		global $_settings;
		
		$lan = $_settings["deflan"];
		
		if(isSet($_COOKIE['lan']) && !empty($_COOKIE['lan']) && lanExists($_COOKIE['lan'])) {
			$lan = $_COOKIE['lan'];
		}
		
		
		if(isSet($_GET['lan']) && lanExists($_GET['lan'])) {
			if(!isSet($_COOKIE['lan']) || $_COOKIE['lan'] !== $_GET['lan'])
				setcookie("lan", $_GET['lan'], time() + (10 * 365 * 24 * 60 * 60));
			
			$lan = $_GET['lan'];
		}
	
		return $lan;
	}
	
	function lanExists($lan) {
		return preg_match('/^[a-z]{2}$/', $lan) && file_exists(dirname(__FILE__) . "/" . $lan . '.config');
	}
	
	function text($key) {
		global $_prep;
		return isset($_prep[$key]) ? $_prep[$key] : $key;
	}