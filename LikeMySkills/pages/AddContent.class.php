<?php namespace Essentials\pages;
use \Addons\Form\Form;
use \Addons\Form\TextInput;
use \Addons\Form\Password;
use \Addons\Form\EmailInput;
use \Addons\Form\Submit;
class AddContent extends \Essentials\Page {
	
	private $form;
	
	function __construct() {
		parent::__construct("addcontent", "Home");
		
		$this->form = new Form($_SERVER['PHP_SELF'] . "?page=AddContent", "addcontent", "post", null, array("autocomplete" => "off"));
		
		$this->form->addChild(new TextInput("title", "formTitle", true, true, false, true, 30, 3, "/^[a-zA-Z0-9._-]*$/"));
		$this->form->addChild(new ContentInput("title", "formContent", true, true, false, true, 30, 3, "/^[a-zA-Z0-9._-]*$/"));
		$this->form->addChild(new Submit("submitAddContent"));
	}
	
	protected function onLoad() {
		$emptyform = false;
	}
	
	protected function onPost() {
		if($this->form->validate()) {
	}
	
	public function getForm() {
		return $this->form->createForm();
	}
}
