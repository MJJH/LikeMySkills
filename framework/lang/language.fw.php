<?php
/**
	Translate all texts on the webpage
*/
	
	// Get language cookie, default language or else English (en)
	//$lan = $_COOKIE['lan'] ?: $_settings['deflan'] ?: "en";
	$lan = "nl";
	
	if(isSet($_GET['lan'])) {
		$reqLan = $_GET['lan'];
		
		if(strlen($reqLan) == 2 && ctype_alpha($reqLan)) {
			$lan = $reqLan;
		}
	}
	
	
	function text($key) {
		global $_prep;
		return isset($data[$key]) ? $_prep[$key] : $key;
	}