<?php namespace Essentials\pages;

class SignUp extends \Essentials\Page {
	function __construct($util) {
		parent::__construct($util, "signup", "Home");
	}
	
	protected function onLoad() {
		$emptyform = false;
	}
	
	protected function onPost() {
		if (!isset($_POST["username"]) || empty($_POST["username"])){
				$emptyform = true;
				$this->util->addError($this->util->getString("EmptyUsername"));
		}
		if (!isset($_POST["password"]) || empty($_POST["password"])){
				$emptyform = true;
				$this->util->addError($this->util->getString("EmptyPassword"));
		}
		if (!isset($_POST["email"]) || empty($_POST["email"])){
				$emptyform = true;
				$this->util->addError($this->util->getString("EmptyEmail"));
		}
		if ($emptyform = false){
			User::register($_POST["username"],$_POST["password"],$_POST["email"]);
		}
	}
}