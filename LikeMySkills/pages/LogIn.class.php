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
		if($this->form->validate()) {
			die("<h3> Login attempt, valid! </h3> <hr> <b> Username: </b> {$_POST['username']} <br> <b> Password: </b> {$_POST['password']} <br> ");
		}
	}
	
	public function getForm() {
		return $this->form->createForm();
	}
}