<?php
/** Read files and return them as an array
*/

function read($path, $config = array()) {
	// Open file
	$file = file($path . ".config");
	
	// Read all lines 
	foreach($file as $line_num => $line) {
		// Ignore command- and empty  lines
		if(substr($line, 0, 1) === "#" || strlen($line) <= 0)
			continue;
		
		// Read config items key=value
		$config_line = preg_split("/\s* *= *\s*/", $line);
		
		// If not a valid item, skip
		if(count($config_line) !== 2) 
			continue;
		
		// Add item to return value
		$config[rtrim($config_line[0])] = rtrim($config_line[1]);
	}
	
	return $config;
	
}