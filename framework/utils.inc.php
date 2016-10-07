<?php
//session_start;

// Include all framework files
include 'configReader.fw.php';
include 'lang/language.fw.php';
include 'database/database.fw.php';

// Set globals
$_settings 	= read('settings.config');
$_string 	= read('lang/' . $lan . '.config');

// HTML features
function javascripts() {
	
}

function stylesheets() {
	
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

function prepare($html, $data = array()) {
	// Insert text %%
	preg_match_all('/%(.*)%/U', $html, $output);
	
	if($output)
		foreach($output[1] as $key) {
			$html = preg_replace('/%'.$key.'%/iU', text($key, $data), $html);
		}
		
	return $html;
}