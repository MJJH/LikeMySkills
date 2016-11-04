<?php
/**
	@author		Martijn Vriens	
	@version	0.01 2016
*/


// Include all framework files
include 'configReader.fw.php';
include 'lang/language.fw.php';
include 'database/database.fw.php';

// Get all classes
include_once 'classes/Content.class.php';
include_once 'classes/User.class.php';

// Set globals
$_settings = read('settings.config');
$_queries = read('database/queries.config');
$_prep = read('lang/' . $lan . '.config');
$_prep["lan"] = $lan;
$_database = new Database();
$_error = array();

// HTML features
function bb($code, $text) {
	global $_settings;
	$allowed = explode(",", $_settings["allowedBB"]);
	
	if(in_array( $code, $allowed )) {
		return "<" . $code . ">" . $text . "</" . $code . ">";
	}
}

function media($path, $name, $description, $type) {
	switch ($type) {
		case 'video':
		
		break;
		case 'sound':
		
		break;
		case 'image':
		
		break;
	}
}

function prepare($html) {
	// Insert text %%
	preg_match_all('/%(.*)%/U', $html, $output);
	
	if($output)
		foreach($output[1] as $key) {
			$html = preg_replace_callback(
				'/%('.$key.')%/iU', 
				function($m) { 
					return text($m[1]); 
				}, 
				$html
			);
		}
		
	return $html;
}

function escape($content) {
	// Remove all html
	$content = htmlentities($content);
	
	// Get all opening tags []
	preg_match_all('/\[(\w+)\]/U', $content, $output);
	
	if($output)
		foreach($output[1] as $key) {
			$content = preg_replace_callback(
				"/\[(".$key.")\](.*)\[\/".$key."\]/iU", 
				function($match) {
					return bb($match[1], $match[2]); 
				}, 
				$content
			);
		}
		
	return $content;
}