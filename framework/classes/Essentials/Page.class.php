<?php namespace Essentials;

use \Addons\HTMLHandler\HTMLHandler as HTML;
/**
 *  Page is an abstract class that allows users of the framework to add and render webpages.
 *  Contains default methods to load from normal syntax
 */
abstract class Page {
	/** @var string $title Title to be shown on page */
	protected $title;
	
	/** @var array $scripts All script url's intern and extern to be loaded on specific page */
	protected $scripts = array();
	/** @var array $styles All style url's intern and extern to be loaded on specific page */
	protected $styles = array();
	
	/** @var array $permissions Permissions needed to load this page */
	protected $permissions;
	
	/** @var string $unPrepared Unprepared HTML. All content without being rendered for this Page */
	protected $unPrepared;
	/** @var string $prepared Prepared HTML version of $unPrepared. */
	protected $prepared;
	/** @var string $templatePath Path to template loaded in this Page */
	protected $templatePath;
	
	/** @var boolean $header If the header should be loaded on this Page */
	protected $header;
	/** @var boolean $footer If the footer should be loaded on this Page */
	protected $footer;
	
	/**
	 *  Initialize abstract Page
	 */
	function __construct($template, $title, $header = true, $footer = true, $permissions = array()) {
		global $util;
		$this->templatePath = __DIR__ . "/../../../{$util->getSetting("dirName")}/pages/templates/{$template}.php";
		$this->title = $title;
		$this->permissions = $permissions;
		$this->header = $header;
		$this->footer = $footer;

		// Default stylesheets
		$this->addStylesheet("css/style.css");
		$this->addStylesheet("https://fonts.googleapis.com/css?family=Open+Sans");
		$this->addScript("js/vendor/jquery-1.9.1.min.js");
		$this->addScript("js/page.js");
		$this->addScript("https://use.fontawesome.com/57c3f7ef0f.js");
	}
	
	protected function addStylesheet($uri) {
		if(is_array($this->styles) && !in_array($uri, $this->styles)) {
			$this->styles[] = $uri;
		}
	}
	
	protected function addScript($uri) {
		if(is_array($this->scripts) && !in_array($uri, $this->scripts)) {
			$this->scripts[] = $uri;
		}
	}
	
	protected function getStylesheets() {
		$ret = "";
		if(is_array($this->styles)) {
			foreach($this->styles as $uri) {
				$ret .= "<link rel=\"stylesheet\" href=\"{$uri}\"> \n";
			}
		}
		return $ret;
	}
	
	protected function getScripts() {
		$ret = "";
		if(is_array($this->scripts)) {
			foreach($this->scripts as $uri) {
				$ret .= "<script src=\"{$uri}\"></script> \n";
			}
		}
		return $ret;
	}
	
	protected function getNavigation() {
		global $util;
		$nav = array();
		
		$nav[] = $this->getLink(array("index", "home"));
		
		// Not logged in
		if(!$util->getLoggedIn()) {
			$nav[] = $this->getLink(array("signUp", "register"));
			$nav[] = $this->getLink(array("logIn", "login"));
		} else {
			$nav[] = $this->getLink(array("account", $util->getLoggedIn()->getUserName()));
		
			if($util->getLoggedIn()->hasPermission("write")) {
				$nav[] = $this->getLink(array("write", "write"));
			}
			
			$nav[] = $this->getLink(array("logOut", "logout"));
		}
		$html = "";
		foreach($nav as $link)
			$html.= "<li>{$link}</li>";
			
		return $html;
	}
	
	protected function getErrors() {
		global $util;
		if(count($util->getErrors()) > 0) {
			echo "<div id=\"errors\"> \n";
			foreach($util->getErrors() as $error) {
				echo "<span class=\"error\">{$error}</span> \n";
			}
			echo "</div>";
		}
	}
	
	protected function getLink($args) {
		global $util;
		if($util->pageExist($args[0]))
			return "<a href=\"{$_SERVER['PHP_SELF']}?page={$args[0]}\">{$util->getString($args[1])}</a>";
		else
			return "<strike>{$util->getString($args[1])}</strike>";
	}
	
	protected function prepare($html) {
		preg_match_all('~([$])?%(.*)(=?:(.*)|)(=?{(.*)}|)%~Ums', $html, $output);
		if($output)
			foreach($output[0] as $index => $found) {		
				$html = preg_replace_callback("~".preg_quote($found)."~", 
					function($m) use ($output, $index) { 
						if($output[1][$index] === "$") {
							if(strtolower($output[2][$index]) === "for") {
								try {
									if(!empty($output[4][$index])) {
										$array = "get".ucfirst($output[4][$index]);
										if(is_array($this->$array())) {
											return $this->forloop($this->$array(), $output[6][$index]);
										} else {
											return $this->$array();
										}
									}
								} catch(Exception $e) {
									return "For loop not working";
								}
							} else {
								$func = "get".ucfirst($output[2][$index]);
								try {
									return $this->$func(
										!empty($output[6][$index]) ? explode(",", $output[6][$index]) : null
									);
								} catch(Exception $e) {
									return "Method <b>{$func}</b> not found";
								}
							}
							
						} else {
							global $util;
							return $util->getString($output[2][$index]);
						} 
					}, 
					$html
				);
			}
			
		return $html;
	}
	
	public function forloop($array, $snippet) {
		$html = "";
		foreach($array as $key => $value) {
			$create = preg_replace_callback("~[$]%(\w*)%~U", 
			function($m) use ($array, $snippet, $key, $value) {
				
				if($m[1] === "i") return $key;
				
				$func = "get".ucfirst($m[1]);
				try {
					return $value->$func();
				} catch (Exception $e) {
					return $m[1];
				}
			},
			$snippet);
			$html.= $create;
		}
		return $this->prepare($html);
	}
	
	public function getPageContent() {
		ob_start();
		
		if($this->header)
			include("header.php");
		
		if(file_exists($this->templatePath))
			include($this->templatePath);
		else
			echo "<b>{$this->templatePath}</b> not found!";
		
		if($this->footer)
			include("footer.php");
		
		$this->unPrepared = ob_get_contents();
		
		ob_end_clean();
		
		$this->onLoad();
		
		if(isSet($_POST) && !empty($_POST)) {
			$this->onPost();
		}
		
		return $this->prepare($this->unPrepared);
	}
	
	public function getTitle() {
		String lng = "en";
		Locale loc = new Locale(lng);
		String name = loc.getDisplayLanguage(loc); // English
		return $this->title . " ~ LikeMySkills - {$name}";
	}
	
	public function getUserName() {
		global $util;
		
		return $util->getLoggedIn()->getUserName() ?: "unknown";
	}
	
	public function getLanSelect() {
		global $util;
		
		$toggle = HTML::createHTML("a", array("id"=>"lanSelect"));
		$divHolder = HTML::createHTML("div", array("id"=>"lanHolder", "class"=>"closed"));
		
		$languages = glob(__DIR__."/../../../{$util->getSetting("dirName")}/lang/*.{config}", GLOB_BRACE);
		ksort($languages);
		
		$html = $toggle["open"] . $util->getString("lan") . $toggle["close"];
		
		$html.= $divHolder["open"];
		
		foreach($languages as $lang) {
			$lang = basename($lang, ".config");
			$languageLink = HTML::createHTML("a", array("class"=>"lanSelector", "href"=>"?lan={$lang}"));
			$html.= $languageLink["open"] . strtoupper($lang) . $languageLink["close"];
		}
		
		$html.= $divHolder["close"];
	
		return $html;
	}
	
	protected function onLoad() {
		
	}
	
	protected function onPost() {
		
	}
}