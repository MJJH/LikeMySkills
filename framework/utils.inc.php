<?php
/**
 *  The overview php file. 
 *  Include this file to start working with the framework.
 *  
 *  <p> 
 *  Includes the configReader.fw.php.
 *  The configReader makes it possible to read any .config file created by standard.
 *  </p>
 *  
 *  <p>
 *  Includes the language.fw.php
 *  This makes the webpage bilangual, giving you the option to setup multiple languages.
 *  </p>
 *  
 *  <p>
 *  Includes the database.fw.php
 *  Custom database connection and query handler.
 *  </p>
 *  
 *  @author Martijn Vriens
 *  @version 0.1
 *  @copyright 2016
 *  @see https://github.com/MJJH/LikeMySkills
 *  @license https://github.com/MJJH/LikeMySkills
*/


// Include all framework files
include 'configReader.fw.php';
include 'database/database.fw.php';

// Get all classes
function classLoader($class) {
    $classes = stream_resolve_include_path("classes/{$class}.class.php");
	$addons = stream_resolve_include_path("{$class}.class.php");
	
	if($classes !== false) include $classes;
	elseif($addons !== false) include $addons;
}

spl_autoload_register('classLoader');


// Set globals
/**
 *  @var array<string, mixed> $_settings Global settings variable, containing all setting keys with values.
 */
//$_settings = read('settings.config');
/**
 *  Contains all queries, stored in one place to use in 
 *  @see /classes/Database
 *  
 *  @var array<string, string> $_queries
 */
/*$_queries = read('database/queries.config');
$_prep = read('lang/' . getLan() . '.config');
$_prep["lan"] = getLan();
$_database = new Database();
$_error = array();*/

// HTML features
/**
 *  Transforms BB code to HTML
 *  
 *  <p> 
 *  Allowed BB code types should be configured in 
 *  </p>
 *  
 *  <p>
 *  BB codes is used as [code] text [/code]
 *  </p>
 *  
 *  @param string 	$code	BB keyword. Something like b, i, h3
 *  
 *  @param string 	$text	Content of the bb codes, The code that is placed between the bb code. [bb] Text [/bb]
 *  
 *  @return string	Created html code for given bb code and text
 */
/*function bb($code, $text) {
	global $_settings;
	
		// todo: ESCAPE %..%[%]
	
	$allowed = explode(",", $_settings["allowedBB"]);
	
	if(in_array( $code, $allowed )) {
		return "<" . $code . ">" . $text . "</" . $code . ">";
	}
}*/

/**
 *  Prepares a piece of HTML with needed string inputs.
 *  To keep PHP and HTML seperate.
 *  
 *  Usage, where register get's replaced by the given value of the word:
 *  	<div id="Content">
 *  		<input type="submit" value="%register%" />
 *  	</div>
 * 
 *  @param string 	$html	The input html
 *  
 *  @return string	Prepared html
 */
/*function prepare($html) {
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

/**
 *  Escape a piece of html, to disallow XSS.
 *  
 *  All HTML tags will be escaped and all BB code will be replace with HTML
 *  
 *  @param string	$content	Input text	that gets escaped and replaced
 *  
 *  @return string	Escaped HTML, ready to be placed on a webpage
 */
/*function escape($content) {
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
}*/