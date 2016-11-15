<?php namespace Addons

class Addon {
	static function handleArguments($linesIn) {
		preg_match_all("/((\w+) \s* = \s*)? (\[(.+)\]|\{(.+)\}|[\w ]+) ,?/xms", $args, $out);
		$array = array();
		
		foreach($out[2] as $key => $val) {
			if(empty($out[4][$key]) && !empty($out[5][$key]))
				$out[4][$key] = $out[5][$key];
			
			if(!empty($val))
				$array[$val] =  (empty($out[4][$key]) ? $out[3][$key] : handleArguments($out[4][$key]));
			else
				$array[] =  (empty($out[4][$key]) ? $out[3][$key] : handleArguments($out[4][$key]));
		}

		return $array;
	}
}