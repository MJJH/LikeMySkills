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
	
	protected $util;
	
	protected $close = array();
	
	
	function __construct($util, $template, $title, $header = true, $footer = true, $permissions = array()) {
		$this->templatePath = __DIR__ . "/../../{$util->getSetting("dirName")}/pages/templates/{$template}.php";
		$this->title = $title;
		$this->permissions = $permissions;
		$this->header = $header;
		$this->footer = $footer;
		$this->util = $util;

		$this->addStylesheet("css/style.css");
		$this->addStylesheet("https://fonts.googleapis.com/css?family=Open+Sans");

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
	
	protected function getNavigation() {
		
	}
	
	protected function getLink($args) {
		if($this->util->pageExist($args[0]))
			return "<a href=\"{$_SERVER['PHP_SELF']}?page={$args[0]}\">{$this->util->getString($args[1])}</a>";
		else
			return "<strike>{$this->util->getString($args[1])}</strike> \n";
	}
	
	protected function getForm($args) {
		$this->close[] = "form";
		return "<form method=\"post\" action=\"index.php?page={$args[0]}\"> \n";
	}
	
	protected function getInput($args) {
		$field 	= count($args) >= 1 ? $args[0] : "input";
		$type 	= count($args) >= 2 ? $args[1] : "text";
		$name 	= count($args) >= 3 ? $this->util->getString($args[2]) : $field;
		$label 	= count($args) >= 4 ? $args[3] : true;

		return "<div class=\"inputCombo\">\n".
			($label===true ? "<label for=\"{$field}\">{$name}</label> \n" : "").
			"<input ".($type==="submit"?"value=\"{$name}\" " : "")."type=\"{$type}\" name=\"{$name}\" /></div>";
	}
	
	protected function prepare($html) {
		preg_match_all('/([$])?%(.*)(=?%(\S*)|)%/U', $html, $output);
		if($output)
			foreach($output[0] as $index => $found) {
				$html = preg_replace_callback(
					'/'. ($found[0]==="$" ? "\\".$found : $found) . '/', 
					function($m) use ($output, $index) { 
						if($output[1][$index] === "$") {
							
						if($output[2][$index] === "!") {
							if(count($this->close) > 0)
								
								return "</".end($this->close).">";
							else
								return "";
						}
								
								
							$func = "get".ucfirst($output[2][$index]);
							return $this->$func(
								!empty($output[4][$index]) ? explode(",", $output[4][$index]) : null
							);
							
						} else {
							return $this->util->getString($output[2][$index]);
						} 
					}, 
					$html
				);
			}
			
		return $html;
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