<?php namespace Essentials\pages;
use \Addons\Form\Form;
use \Addons\Form\TextInput;
use \Addons\Form\Textarea;
use \Addons\Form\Submit;
class AddContent extends \Essentials\Page {
	
	private $form;
	
	function __construct() {
		parent::__construct("addcontent", "Home");
		
		$this->form = new Form($_SERVER['PHP_SELF'] . "?page=addContent", "addcontent", "post", null, array("autocomplete" => "off"));
		
		$this->form->addChild(new TextInput("title", "formTitle", true, true, false, true, 30, 3, false));
		$this->form->addChild(new Textarea("content", "formContent", true, true, false, true, 1000, 3, false));
		$this->form->addChild(new Submit("submitAddContent"));
	}
	
	protected function onLoad() {
		$emptyform = false;
	}
	
	protected function onPost() {
		global $util;
		$userid = $util->getLoggedIn()->getId();

		if($this->form->validate()) {
			\Application\content::upload(NULL, $_POST['title'], $_POST['content'], $userid);
			header('location: index.php?page=addContentSuccess');
		}
	}
	
	public function getForm() {
		return $this->form->createForm();
	}
}
