<?php namespace Addons\Form;

use \Addons\HTMLHandler\HTMLHandler;

class MediaInput extends Input {
	private $audio;
	private $video;
	private $image;
	private $fileExtensions;
	
	private $file;

	private maxWidth; // KB
	
	public function __construct($name, $text, $label = false, $required = true, $audio = false, $video = false, $image = false, $fileExtensions = array(), $maxWidth = 1000, $attributes = array()) {
		parent::__construct($name, "file", $text, $label, $required, false, $attributes);
		
		$this->upload = $upload;
		$this->audio = $audio;
		$this->video = $video;
		$this->image = $image;
		$this->fileExtensions = $fileExtensions;
		
		$accept = array();
		if($this->audio) $accept[] = "audio/*";
		if($this->video) $accept[] = "video/*";
		if($this->image) $accept[] = "image/*";
		
		if(count($accept) <= 0) $accept = $this->fileExtensions;
		
		$this->attributes["accept"] = implode(",", $accept);
		
		$this->maxWidth = $maxWidth;
	}
	
	public function validate() {
		return true;
	}
	
	public function getFile() {
		return $this->file;
	}
	
	public function upload($path, $name = false) {
		
	}
}