<?php

/**
	A content is a piece of content that is shared on the website.
	This content contains an author and data,
	and might also contain:
		- A video
		- Pictures
		- A sound file
		- Text
		- Link
*/
class Content {
	// Id of the content, to identify in the database
	private $id;
	
	// Author of this piece of content, USER object
	private $author;
	// Date when this content was uploaded
	private $date;
	
	// The type
	private $type;
	// The title
	private $title;
	// The content, is a string (either a text or a path)
	private $content;
	
	/**
		Constructor for a content
		@param id		identifier for content
		@param author 	user object of uploader
		@param content	content of this content
		@param date		timestamp object when object was send
	*/
	public function __construct($id, $author, $title, $content, $type, $date = null) {
		$this->id = $id;
		$this->title = $title;
		$this->type = $type;
		
		if(!empty($author) && isSet($author))
			$this->author = $author;
		else return;
		
		if(!empty($content) && isSet($content) && $this->validateContent($content, $type))
			$this->content = $content;
		else return;
		
		if(empty($date) || !isSet($date))
			$this->date = date('Y-m-d H:i:s');
		else
			$this->date = $date;
	}
	
	/**
		* Upload this content to the database. 
		* For new contents or changes
		*
		* @author Martijn
		* @return string Gives an error if failed else nothing
	*/
	public function upload() {
		global $_queries;
		global $_database;
		
		$authorId = $this->author->getId();
		$_database->doQuery($_queries['addcontent'], 'ssssi', array(&$this->type, &$this->title, &$this->date, &$this->content, &$authorId));
	}
	
	/**
		Remove content from database
	*/
	public function delete() {
		
	}
	
	/**
		STATIC
		Load a content by the id
		@return Content return the found content object or false
	*/
	static public function loadContent($id) {
		
	}
	
	/**
		* Validate title
		*
		* @param string 		$title
		* @return boolean		true / false validated
	*/
	private function validateTitle($title) {
		return true;
	}
		
	
	/**
		* Validate the content
		* @param content		To validate content
		* @param string		content		Content 
	*/
	private function validateContent($content, $type) {
		return true;
	}
}