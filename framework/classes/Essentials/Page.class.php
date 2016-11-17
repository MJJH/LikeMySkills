<?php namespace Essentials;

abstract class Page {
	
	protected $title;
	
	protected $scripts = array();
	protected $styles = array();
	
	protected $permissions;
	
	protected $unPrepared;
	protected $prepared;
	protected $templatePath;
	
	protected $header;
	protected $footer;
	
	protected $close = array();
	
	
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
		if(is_array($this->scripts)) {
			foreach($this->scripts as $uri) {
				echo "<script src=\"{$uri}\"></script> \n";
			}
		}
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
		return $this->title . " ~ LikeMySkills";
	}
	
	protected function onLoad() {
		
	}
	
	protected function onPost() {
		
	}
}