<?php
// Include all framework files
include 'configReader.fw.php';
include 'lang/language.fw.php';
include 'database/database.fw.php';

// Set globals
$_settings 	= read('settings.config');
$_prep 	= read('lang/' . $lan . '.config');
$_prep["lan"] = $lan;

// HTML features
function javascripts() {
	
}

function stylesheets() {
	
}

function bb($code, $close) {
	die($close);
	$allowed = ['b', 'i', 'u', 's', 'h1', 'h2', 'h3'];
	
	if(in_array( $code, $allowed )) {
		return "<" . ($close ? "/" : "") . $code . ">";
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

function prepare($html, $data) {
	// Insert text %%
	preg_match_all('/%(.*)%/U', $html, $output);
	
	if($output)
		foreach($output[1] as $key) {
			$html = preg_replace('/%'.$key.'%/iU', text($key, $data), $html);
		}
		
	return $html;
}

function escape($content) {
	// Remove all html
	$content = htmlentities($content);
	
	// Get all opening tags []
	preg_match_all('/\[(\/?\w+)\]/U', $content, $output);
	
	if($output)
		foreach($output[1] as $key) {
			$content = preg_replace('~\[(/)?['.$key.']\]~iUe', 'bb('.$key.', "$1")', $content);
		}
		
	die(htmlentities($content));
	return $content;
}