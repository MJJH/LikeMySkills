<?php namespace Addons\Form;

class EmailInput extends TextInput {
	public function __construct($name, $text, $label = true, $required = true, $placeholder = false, $reFillValue = false, $attributes = array()) {
		parent::__construct($name, $text, $label, $required, $placeholder, $reFillValue, 100, 3, false, $attributes);
		
		$this->setType("email");
	}
	
	public function validate() {
		if(parent::validate()) 
			if (filter_var($_POST[$this->name], FILTER_VALIDATE_EMAIL)) {
				return true;
			} else {
				$this->errors[] = "formEmail";
			}
			
		return false;
	}
}