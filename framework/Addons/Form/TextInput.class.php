<?php namespace Addons\Form;

class TextInput extends Input {
	private $minLength;
	private $maxLength;
	private $regex;
	
	public function __construct($name, $text, $label = true, $required = true, $placeholder = false, $reFillValue = false, $maxLength = 100, $minLength = 0, $regex = false, $attributes = array()) {
		parent::__construct($name, "text", $text, $label, $required, $reFillValue, $attributes);
		global $util;
		
		if($placeholder) {
			$this->attributes["placeholder"] = $util->getString($text);
		}
		
		$this->attributes["maxlength"] = $maxLength;
		
		$this->minLength = $minLength;
		$this->maxLength = $maxLength;
		$this->regex = $regex;
	}
	
	public function validate() {
		$value = $_POST[$this->name];
		if(!isset($value) || empty($value) && $this->required) {
			$this->errors[] = "formEmpty";
			return false;
		}
		
		if(strlen($value) < $this->minLength || strlen($value) > $this->maxLength) {
			$this->errors[] = "formStringLength";
			return false;
		}
		
		if($this->regex && !preg_match($this->regex, $value)) {
			$this->errors[] = "formRegex";
			return false;
		}
		
		return true;
	}
}