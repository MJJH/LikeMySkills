<?php namespace Essentials;

/**
 *  Util class
 *  Utilities that run the main framework
 *  
 *  <p> Holds lists with settings, errors, pages, language keys and queries </p>
 */

class Util {
	/** @var array All settings loaded from settings.config */
	private $settings = array();
	/** @var array All queries loaded from database/queries.config */
	private $queries = array();
	/** @var array All language strings for current language. Loaded from __application__/lang/{language}.config */
	private $language = array();
	/** @var array Global errors (ie Failed loading or connections) */
	private $error = array();
	/** @var array All page links to \Essentials\Page page classes */
	private $pages = array();
	
	/** @var \Essentials\Database\Database The database connection used on the application */
	private $database;
	/** @var array|null Data about logged in user as \Application\User */
	private $loginData;
	
	/** @var \Essentials\Page Current page */
	private $page;
	
	/**
	 *  Util constructor
	 *  Initializes all essential data for the page to load.
	 */
	public function __construct() {
		$this->settings = read(__DIR__ . "/../../" . "settings");
		$this->queries = read(__DIR__ . "/../../" . "database/queries");
		$this->pages = read(__DIR__ . "/../../../{$this->getSetting("dirName")}/pages/pages");
		
		$this->database = new Database($this); 
		
		$this->language = $this->getLanguage();
		$this->user = $this->logIn();
	}
	
	/**
	 *  Get the current logged in User
	 *  
	 *  @return \Application\User|null Logged in User object, if logged in
	 */
	public function getLoggedIn() {
		if(!empty($this->user) && !empty($this->user['userObject']))
			return $this->user['userObject'];
		return null;
	}
	
	/**
	 *  Login via Cookie
	 *  
	 *  Looks for a userLogin cookie and creates array with all user data
	 *  
	 *  @return array|null Login array. <p>Device: Browser and Device data</p><p>createIP: IP from where cookie created</p><p>currentIP: IP of current visit</p><p>createDate: Date the cookie was created</p><p>loadDate: Date of array creation</p><p>userObject: Object of type \Application\User</p><p>userId: ID of user</p>
	 */
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
			
			return null;
		}
	}
	
	/**
	 *  Get language from cookie or request (get) and load the language strings
	 *  
	 *  @return array Array with all language strings
	 */
	private function getLanguage() {
		$lan = $this->settings["deflan"];
		
		if(isSet($_COOKIE['lan']) && !empty($_COOKIE['lan']) && $this->lanExists($_COOKIE['lan'])) {
			$lan = $_COOKIE['lan'];
		}
		
		if(isSet($_GET['lan']) && $this->lanExists($_GET['lan'])) {
			if(!isSet($_COOKIE['lan']) || $_COOKIE['lan'] !== $_GET['lan'])
				setcookie("lan", $_GET['lan'], time() + (10 * 365 * 24 * 60 * 60));
			
			$lan = $_GET['lan'];
		} elseif(isset($_GET['lan'])) {
			$this->addError("Language <b>".$_GET['lan']."</b> not found!");
		}
		return read(__DIR__ . "/../../../{$this->getSetting("dirName")}/lang/{$lan}");
	}
	
	/**
	 *  Check if a language config with code exists in the language folder
	 *  
	 *  @param string $lan 	Language code (two lowercase letters)
	 *  
	 *  @return boolean True if language config was found
	 */
	public function lanExists($lan) {
		return preg_match('/^[a-z]{2}$/', $lan) && file_exists(__DIR__ . "/../../../{$this->getSetting("dirName")}/lang/{$lan}". '.config');
	}
	
	/**
	 *  Create page object for current visited page
	 *  
	 *  @param string $page name of page, has to be defined in the pages/pages.config !
	 *  
	 *  @todo Auto include for page classes
	 */
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
	
	/**
	 *  Create HTML code from BB code
	 *  
	 *  @param string $bb The bb code to be changed into HTML (only if allowed by settings.config)
	 *  @param string $text Content of the bb code, will be placed in HTML
	 *  
	 *  @return string|null Returns the created html, but only if bb in allowedBB in Settings
	 */
	private function bb($bb, $text) {
		$allowed = explode(",", $this->getSetting("allowedBB"));
		
		if(in_array( $code, $allowed )) {
			return "<" . $code . ">" . $text . "</" . $code . ">";
		}
	}
	
	/**
	 *  Escape user input to deny HTML (and javascript) and Framework syntax
	 *  
	 *  @param string $content Input content, to be escaped
	 *  
	 *  @return string Escaped input, save to use on webpage.
	 */
	private function escape($content) {
		// Remove all html
		$content = htmlentities($content);
		
		// escape %..% || $%..%
		
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
	
	/**
	 *  Get all permissions for role
	 *  
	 *  @param string @role The role to look for in database
	 *  
	 *  @return array Array with the allowed permission keys
	 */
	public function getPermissions($role) {
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
	
	/**
	 *  Get setting value
	 *  
	 *  @param string $key Setting key to look for
	 *  
	 *  @return string|null The found value
	 */
	function getSetting($key) {
		return array_key_exists($key, $this->settings) ? 
			$this->settings[$key] : 
			null; 
	}
	
	/**
	 *  Get query
	 *  
	 *  @param string $key Query key to look for
	 *  
	 *  @return string|null The found query
	 */
	function getQuery($key) {
		return array_key_exists($key, $this->queries) ?
			$this->queries[$key] :
			null;
	}
	
	/**
	 *  Get language string, for currently loaded language for user
	 *  
	 *  @param string $key The key to look for
	 *  
	 *  @param string Found translation / definition; If not found return key back
	 */
	function getString($key) {
		return array_key_exists($key, $this->language) ?
			$this->language[$key] :
			$key;
	}
	
	/**
	 *  Check if page exists according to pages/pages.config
	 *  
	 *  @param string $key The page name to look for
	 *  
	 *  @return boolean True if page defined in pages.config
	 */
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