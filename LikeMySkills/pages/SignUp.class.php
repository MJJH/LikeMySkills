<?php namespace Essentials\pages;

use \Addons\Form\Form;
use \Addons\Form\TextInput;
use \Addons\Form\Password;
use \Addons\Form\EmailInput;
use \Addons\Form\Submit;

class SignUp extends \Essentials\Page {
	
	private $form;
	
	function __construct() {
		parent::__construct("signup", "Home");
		
		$this->form = new Form($_SERVER['PHP_SELF'] . "?page=signUp", "signup", "post", null, array("autocomplete" => "off"));
		
		$this->form->addChild(new TextInput("username", "formUsername", true, true, false, true, 30, 4, "/^[a-zA-Z0-9._-]*$/"));
		$this->form->addChild(new Password("password", "formPassword"));
		$this->form->addChild(new EmailInput("email", "formEmail", true, true, false, true));
		$this->form->addChild(new Submit("formSubmit"));
	}
	
	protected function onLoad() {
		$emptyform = false;
	}
	
	protected function onPost() {
		if($this->form->validate()) {
			// Validate user
			$uu = !\Application\User::uniqueUsername($_POST['username']);
			$ue = !\Application\User::uniqueEmail($_POST['email']);
			
			if($uu || $ue) {
				if($uu) $this->form->getInput("username")->addError("uniqueUsername");
				if($ue) $this->form->getInput("email")->addError("uniqueEmail");
			} else {
				\Application\User::register($_POST['username'], $_POST['password'], $_POST['email']);
				header('location: index.php?page=signUpSuccess');
			}
		}
	}
	
	public function getForm() {
		return $this->form->createForm();
	}
}