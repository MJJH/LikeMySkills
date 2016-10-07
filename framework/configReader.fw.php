<?php
/** Read files and return them as an array
*/

function read($path, $config = array()) {
	
	// Open file
	$file = file($url);
	
	// Read all lines 
	foreach($file as $line_num => $line) {
		if(substr($line, 0, 1) === "#" || strlen($line) <= 0)
			continue;
		
		$config_line = preg_split("/\s*=\s*/", $line);
		
		if(count($config_line) !== 2) 
			continue;
		
		$config[$config_line[0]] = $config_line[1];
	}
	
	return $config;
	
}