<?php namespace Essentials;

class Util {
	private $settings = array();
	private $queries = array();
	private $language = array();
	private $error = array();
	private $pages = array();
	
	private $database;
	private $user;
	
	private $page;
	
	
	function __construct() {
		$this->settings = read(__DIR__ . "/../../" . "settings");
		$this->queries = read(__DIR__ . "/../../" . "database/queries");
		$this->pages = read(__DIR__ . "/../../../{$this->getSetting("dirName")}/pages/pages");
		
		$this->language = $this->getLanguage();
		$this->user = $this->getLoggedIn();
		
		$this->database = new Database($this); 
	}
	
	private function getLoggedIn() {
		
	}
	
	private function getLanguage() {
		$lan = $this->settings["deflan"];
		
		if(isSet($_COOKIE['lan']) && !empty($_COOKIE['lan']) && $this->lanExists($_COOKIE['lan'])) {
			$lan = $_COOKIE['lan'];
		}
		
		
		if(isSet($_GET['lan']) && lanExists($_GET['lan'])) {
			if(!isSet($_COOKIE['lan']) || $_COOKIE['lan'] !== $_GET['lan'])
				setcookie("lan", $_GET['lan'], time() + (10 * 365 * 24 * 60 * 60));
			
			$lan = $_GET['lan'];
		}
		
		return read(__DIR__ . "/../../../{$this->getSetting("dirName")}/lang/{$lan}");
	}
	
	public function lanExists($lan) {
		return preg_match('/^[a-z]{2}$/', $lan) && file_exists(__DIR__ . "/../../../{$this->getSetting("dirName")}/lang/{$lan} ". '.config');
	}
	
	function checkPermission($task) {
		
	}
	
	public function createPage($page) {
		if(!array_key_exists($page, $this->pages))
			return;
		
		$path = __DIR__ . "/../../../{$this->getSetting("dirName")}/pages/{$this->pages[$page]}.class.php";
		if( $path && file_exists($path)) {
			include_once $path;
			
		$class = "\Essentials\pages\\" . $this->pages[$page];
		$this->page = new $class();
		}
	}
	
	private function bb($bb, $text) {
		$allowed = explode(",", $this->getSetting("allowedBB"));
		
		if(in_array( $code, $allowed )) {
			return "<" . $code . ">" . $text . "</" . $code . ">";
		}
	}
	
	private function escape($content) {
		// Remove all html
		$content = htmlentities($content);
		
		// escape %..% || $%..%%
		
		// Get all opening tags []
		preg_match_all('/\[(\w+)\]/U', $content, $output);
		
		if($output)
			foreach($output[1] as $key) {
				$content = preg_replace_callback(
					"/\[(".$key.")\](.*)\[\/".$key."\]/iU", 
					function($match) {
						return bb($match[1], $match[2]); 
					}, 
					$content
				);
			}
			
		return $content;
	}
	
	function getSetting($key) {
		return array_key_exists($key, $this->settings) ? 
			$this->settings[$key] : 
			"Unknown"; 
	}
	
	function getQuery($key) {
		return array_key_exists($key, $this->queries) ?
			$this->queries[$key] :
			"Not found";
	}
	
	function getString($key) {
		return array_key_exists($key, $this->language) ?
			$this->language[$key] :
			$key;
	}
	
	function pageExist($key) {
		return array_key_exists($key, $this->pages);
	}
	
	public function getPage() {
		return $this->page;
	}
	
	public function getErrors() {
		return $this->error;
	}
	
	public function addError($error) {
		$this->error[] = $error;
	}
}