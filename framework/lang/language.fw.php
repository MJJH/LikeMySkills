<?php
/**
	Translate all texts on the webpage
*/
	
	// Get language cookie, default language or else English (en)
	//$lan = $_COOKIE['lan'] ?: $_settings['deflan'] ?: "en";
	$lan = "en";
	
	function text($key, $data = array()) {
		return isset($data[$key]) ? $data[$key] : $key;
	}