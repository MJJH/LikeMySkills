<?php namespace Essentials\pages;

class Index extends \Essentials\Page {
	
	private $feed;
	
	function __construct() {
		parent::__construct("index", "Home");
		
		$this->addScript("js/vendor/modernizr-2.6.2.min.js");
		$this->addScript("js/loader.js");
	}
	
	public function getTest() {
		return $this->feed;
	}
}

class feed {
	private $naam;
	private $richting;
	
	public function __construct($naam, $richting) {
		$this->naam = $naam;
		$this->richting = $richting;
	}
	
	public function getNaam() {
		return $this->naam;
	}
	
	public function getRichting() {
		return $this->richting;
	}
}