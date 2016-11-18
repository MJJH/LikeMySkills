<?php namespace Essentials;

class Util {
	private $settings = array();
	private $queries = array();
	private $language = array();
	private $error = array();
	private $pages = array();
	
	private $database;
	private $loginData;
	
	private $page;
	
	
	function __construct() {
		$this->settings = read(__DIR__ . "/../../" . "settings");
		$this->queries = read(__DIR__ . "/../../" . "database/queries");
		$this->pages = read(__DIR__ . "/../../../{$this->getSetting("dirName")}/pages/pages");
		
		$this->database = new Database($this); 
		
		$this->language = $this->getLanguage();
		$this->user = $this->logIn();
	}
	
	public function getLoggedIn() {
		if(!empty($this->user) && !empty($this->user['userObject']))
			return $this->user['userObject'];
		return null;
	}
	
	private function logIn() {
		if(isSet($_COOKIE['userLogin']) && !empty($_COOKIE['userLogin'])) {
			$cookie = $_COOKIE['userLogin'];
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
			
			$users = $this->database->getQuery($this->getQuery("findCookie"), "ss", array(&$cookie, &$userAgent));

			// User found
			if($users) {
				return array(
					'device' => $userAgent,
					
					'createIP' => $users['ip'],
					'currentIP' => $_SERVER['REMOTE_ADDR'],
					
					'createDate' => $users['date'],
					'loadDate' => date("F j, Y \a\t g:ia"),
					
					'userObject' => \Application\User::loadUser($this, $users['userid']),
					'userId' => $users['userid']
				);
			}
		}
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
	
	function getPermissions($role) {
		$permissions = $this->database->getQuery($this->getQuery("loadPermission"), "s", array(&$role));
		
		$retPerm = array();
		if($permissions) {
			foreach($permissions as $key => $value) {
				if($key == "role") continue;

				if($value == 1)
					$retPerm[] = $key;
			}
		}
		
		return $retPerm;
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
	
	public function getDatabase() {
		return $this->database;
	}
}