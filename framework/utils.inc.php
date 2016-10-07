<?php
session_start;

// Include all framework files
include 'configReader.fw.php';
include 'lang/language.fw.php';
include 'database/database.fw.php';

// Set globals
global $_settings 	= read('settings.config');
global $_string 	= read('lang/' . $lan . '.config');


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
	preg_replace('/%(.*)%/U', text(${1}), $html);
}