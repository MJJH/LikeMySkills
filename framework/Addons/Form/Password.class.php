<?php namespace Addons\Form;

class Password extends TextInput {
	private $minStrength;
	
	public function __construct($name, $text, $label = true, $required = true, $placeholder = false, $minLength = 5, $maxLength = 255, $minStrength = 0, $attributes = array()) {
		parent::__construct($name, $text, $label, $required, $placeholder, false, $maxLength, $minLength, false, $attributes);
		
		$this->minStrength = ($minStrength > 10 ? 10 : $minStrength);
		
		$this->setType("password");
	}
	
	public function validate() {
		if(parent::validate())
			if(Password::getPasswordStrength($_POST[$this->name]) >= $this->minStrength) {
				return true;
			} else {
				$this->errors[] = "formSecurity";
			}
			
		return false;
	}
	
	static function getPasswordStrength($password) {
		$strength = 0;
		return 10;
		// length
	}
}