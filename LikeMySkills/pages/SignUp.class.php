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
		
		$this->form->addChild(new TextInput("username", "formUsername", true, true, false, true, 30, 3, "/^[a-zA-Z0-9._-]*$/"));
		$this->form->addChild(new Password("password", "formPassword"));
		$this->form->addChild(new EmailInput("email", "formEmail", true, true, false, true));
		$this->form->addChild(new Submit("submitSignUp"));
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
				if($code = \Application\User::register($_POST['username'], $_POST['password'], $_POST['email'])) {
					$this->sendActivationMail($_POST['email'], $_POST['username'], $code);
					return "signUpSuccess";
				}
			}
		}
	}
	
	private function sendActivationMail($to, $name, $activationCode) {
		global $util;
		$mailHandler = new \Addons\MailHandler\MailHandler();
		
		$activationString = $util->getString("activationMail");
		$activationString = str_replace("%username%", $name, $activationString);
		
		$activationLink = \Addons\HTMLHandler\HTMLHandler::createHTML("a", array("href"=>$util->getSetting("path") . "?page=activation&code={$activationCode}"));
		$activationString = str_replace("%activation%", "{$activationLink['open']} Sport Report {$activationLink['close']}", $activationString);
		
		$mailHandler->send($to, "Sport Report", $activationString);
	}
	
	public function getForm() {
		return $this->form->createForm();
	}
}