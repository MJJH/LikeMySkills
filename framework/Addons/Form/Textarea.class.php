<?php namespace Addons\Form;

use \Addons\HTMLHandler\HTMLHandler;
class Textarea extends TextInput {
	public function createInput() {
		global $util;
		$binder = HTMLHandler::createHTML("div", array("class" => "textareaBinder"));
		$textarea = HTMLHandler::createHTML("textarea", $this->attributes);
		$label = HTMLHandler::createHTML("label", array("class" => "formLabel input_{$this->name}", "for" => $this->name));
		
		$errorHolder = HTMLHandler::createHTML("div", array("class" => "formErrorHolder"));
		
		$errors = $errorHolder["open"];
		foreach($this->errors as $error) {
			$htmlError = HTMLHandler::createHTML("span", array("class" => "formError"));
			$errors .= $htmlError["open"] . $util->getString($error) . $htmlError["close"];
		}
		$errors .= $errorHolder["close"];

		return  $binder["open"] . 
				(count($this->errors) > 0 ? $errors : "") .
				($this->label ? $label["open"] . $util->getString($this->text) . "\n" . $label["close"] : "") .
				$textarea["open"] .
				$textarea["close"] .
				$binder["close"];
	}
}