<?php namespace Addons\HTMLHandler;

class HTMLHandler {
	
	static function createHTML($type, $attributes = array()) {
		$html = "<{$type}";
		
		ksort($attributes);
		
		foreach($attributes as $attribute => $value) {
			if($value === true)
				$html.= " {$attribute}";
			else
				$html .= " {$attribute}=\"{$value}\"";
		}
		
		return array(
			"noContent" => $html . "/> \n",
			"open" 		=> $html . "> \n",
			"close" 	=> "</{$type}> \n"
		);
	}
	
}