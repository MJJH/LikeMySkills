<?php namespace Addons\Form;

class Submit extends Input {
	public function __construct($text, $attributes = array()) {
		parent::__construct("submit", "submit", $text, false, false, false, $attributes);
		
		global $util;
		$this->attributes["value"] = $util->getString($text);
	}
	
	public function validate() {
		return true;
	}
}