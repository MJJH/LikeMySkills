<?php namespace Essentials\pages;

use \Addons\Form\Form;
use \Addons\Form\TextInput;
use \Addons\Form\Password;
use \Addons\Form\EmailInput;
use \Addons\Form\Submit;

class LogIn extends \Essentials\Page {
	
	private $form;
	
	function __construct() {
		parent::__construct("login", "Home");
		
		$this->form = new Form($_SERVER['PHP_SELF'] . "?page=logIn", "login", "post", null, array("autocomplete" => "off"));
		
		$this->form->addChild(new TextInput("username", "formUsername", true, true, false, true, 55, 3));
		$this->form->addChild(new Password("password", "formPassword"));
		$this->form->addChild(new Submit("formSubmit"));
	}
	
	protected function onLoad() {
		$emptyform = false;
	}
	
	protected function onPost() {
		global $util;
		if($this->form->validate()) {
			\Application\User::signInUser($_POST['username'], $_POST['password']);
		}
	}
	
	public function getForm() {
		return $this->form->createForm();
	}
}