<?php namespace Application;

class media {
	private $mediaid;
	private $uploader;
	private $content;
	private $type;
	private $path;
}

public function __construct($mediaid, $uploader, $content, $type, $path) {
	$this->mediaid = $mediaid;
	$this->uploader = $uploader;
	$this->content = $content;
	$this->type = $type;
	$this->path = $path;
}