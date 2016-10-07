<?php
/**
	Translate all texts on the webpage
*/
	
	// Get language cookie, default language or else English (en)
	global $lan = $_COOKIE['lan'] ?: $_settings['deflan'] ?: "en";
	
	function text($key) {
		return $_string[$key] ?: "Undefined";
	}