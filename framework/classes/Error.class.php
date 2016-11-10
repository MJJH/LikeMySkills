<?php namespace Essentials\html

class Error {

	private $type;
	private $types = array("GLOBAL", "FORM");
	private $title;
	private $source;
	
	private $description;
	
	function __construct($title, $description, $type=$types[0], $source = null ) {
		
	}
}