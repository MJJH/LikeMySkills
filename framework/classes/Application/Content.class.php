<?php namespace Application;

/**
	A content is a piece of content that is shared on the website.
	This content contains an author and data,
	and might also contain:
		- A video
		- Pictures
		- A sound file
		- Text
		- Link
		
	@todo Finish class documentation and functions
*/
class Content {
	/** * @var integer 		Identifier from database */
	private $id;
	
	/** * @var User 		User that created this content */
	private $author;
	/** * @var Timestamp 	Timestamp of creation of this Content */
	private $date;
	
	// The title
	private $title;
	private $content;
	//
	private $media;
	
	private $likes;
	
	/**
		* Constructor for a content
		* @param id		identifier for content
		* @param author 	user object of uploader
		* @param content	content of this content
		* @param date		timestamp object when object was send
	*/
	public function __construct($id, $author, $title, $content, $media = array(), $likes = array(), $date = null) {
		$this->id = $id;
		$this->title = $title;
		$this->author = $author;
		$this->title = $title;
		$this->content = $content;
		$this->media = $media;
		$this->likes = $likes;
		
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
		*
		* @example ../../../likemyskills/index.php 19 3 Upload some content
		* @global array $_queries
		* @global Database $_database
	*/
	public function upload() {
		global $util;
		
		$authorId = $this->author->getId();
		$util->getDatabase()->doQuery($util->getQuery('addcontent'), 'sssi', array(&$this->title, &$this->date, &$this->content, &$authorId));
		
		foreach($this->media as $media) {
			$media->upload();
		}
	}
	
	public function addMedia($media) {
		$this->media[] = $media;
	}
	
	/**
		Remove content from database
	*/
	public function delete() {
		
	}
	
	/**
	* Load a content by the id
	*
	* @static
	* @return Content return the found content object or false
	*/
	public static function loadContent($id) {
		global $util;
		
		$content = $util->getDatabase()->getQuery($util->getQuery("loadContent"), "i", array(&$id));
		
		if($content) {
			$contentObject = new Content($content["contentid"], User::loadUser($content["author"]), $content["title"], $content["content"], Media::loadByContent($id), Content::loadContentLikes($id), $content['date']);
		}
	}
	
	public static function loadContentLikes($id) {
		global $util;
		
		$likes = array();
		
		$_likes = $util->getDatabase()->getQuery($util->getQuery("loadLikes"), "i", array(&$id));
		
		if($_likes) {
			if(count($_likes) > 1) {
				foreach($_likes as $like) {
					$likes[] = User::LoadUser($like['userid']);
				}
			} else {
				$likes[] = User::loadUser($_likes['userid']);
			}
		}
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