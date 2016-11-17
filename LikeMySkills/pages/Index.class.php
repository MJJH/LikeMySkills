<?php namespace Essentials\pages;

class Index extends \Essentials\Page {
	
	private $testArray;
	
	function __construct() {
		parent::__construct("index", "Home");
		
		$this->testArray = array(
			new Test("Martijn", "Software"),
			new Test("Pim", "Media"),
			new Test("Jarno", "Business"),
			new Test("Noureddine", "Business")
		);
		
		$this->addScript("js/vendor/modernizr-2.6.2.min.js");
		$this->addScript("js/loader.js");
	}
	
	public function getTest() {
		return $this->testArray;
	}
}

class Test {
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