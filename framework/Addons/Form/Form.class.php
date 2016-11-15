<?php namespace Addons\Form;

use \Addons\HTMLHandler\HTMLHandler;

class Form {

	private $action;
	private $method;
	private $name;
	
	private $children;
	
	private $attributes;

	public function __construct($action, $name, $method = "post", $children = array(), $attributes = array()) {
		$this->children = $children;
		$this->attributes = $attributes;

		$this->setAction($action);
		$this->setName($name);
		$this->setMethod($method);
	}
	
	public function validate() {
		$validated = true;
		foreach($this->children as $child) {
			if($child instanceof Input && !$child->validate())
				$validated = false;
		}
		
		return $validated;
	}
	
	public function addChild($child) {
			$this->children[] = $child;
	}
	
	public function createForm() {
		$formHTML = HTMLHandler::createHTML("form", $this->attributes);
		
		$html = $formHTML["open"];
		foreach($this->children as $child) {
			$html .= ($child instanceof Input ? $child->createInput() : $child);
		}
		$html .= $formHTML["close"];
		
		return $html;
	}
	
	private function setAction($action) {
		$this->action = $action;
		$this->attributes["action"] = $action;
	}
	
	private function setName($name) {
		$this->name = $name;
		$this->attributes["name"] = $name;
	}
	
	private function setMethod($method) {
		$this->method = $method;
		$this->attributes["method"] = $method;
	}
}