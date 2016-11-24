<?php namespace Addons\Form;

use \Addons\HTMLHandler\HTMLHandler;

abstract class Input {
	protected $name;
	private $type;
	
	private $label;
	private $text;
	private $required;
	
	protected $attributes;
	
	protected $errors;
	
	public function __construct($name, $type, $text, $label, $required = true, $reFillValue = false, $attributes = array()) {
		$this->attributes = $attributes;
		
		$this->setName($name);
		$this->setType($type);
		$this->label = $label;
		$this->text = $text;
		$this->required = $required;
		
		if($required)
			$this->attributes["required"] = true;
		
		if($reFillValue && isSet($_POST) && !empty($_POST[$name])) 
			$this->attributes["value"] = $_POST[$name];
		
		$this->errors = array();
	}
	
	abstract public function validate();
	
	public function createInput() {
		global $util;
		
		$binder = HTMLHandler::createHTML("div", array("class" => "inputBinder"));
		$input = HTMLHandler::createHTML("input", $this->attributes);
		$label = HTMLHandler::createHTML("label", array("class" => "formLabel input_{$this->name}", "for" => $this->name));
		
		$errorHolder = HTMLHandler::createHTML("div", array("class" => "formErrorHolder"));
		
		$errors = $errorHolder["open"];
		foreach($this->errors as $error) {
			$htmlError = HTMLHandler::createHTML("span", array("class" => "formError"));
			$errors .= $htmlError["open"] . $util->getString("{$error}_{$this->name}") . $htmlError["close"];
		}
		$errors .= $errorHolder["close"];

		return  $binder["open"] . 
				(count($this->errors) > 0 ? $errors : "") .
				($this->label ? $label["open"] . $util->getString($this->text) . "\n" . $label["close"] : "") .
				$input["noContent"] .
				$binder["close"];
	}
	
	protected function setType($type) {
		$this->type = $type;
		$this->attributes["type"] = $type;
	}
	
	protected function setName($name) {
		$this->name = $name;
		$this->attributes["name"] = $name;
	}
	
	protected function setAttribute($attribute, $value) {
		$this->attributes[$attribute] = $value;
	}
	
	public function getErrors() {
		return $this->errors;
	}
	
	public function addError($error) {
		$this->errors[] = $error;
	}
	
	public function getName() {
		return $this->name;
	}
}