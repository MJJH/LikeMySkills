<?php namespace Application;

use \Addons\HTMLHandler\HTMLHandler;

class media {
	private $id;
	private $user;
	private $type;
	
	private $content;

	public function __construct($id, $user, $type, $content) {
		global $util;
		
		$this->id = $id;
		$this->user = $user;
		$this->content = $util->escape($content);
		$this->type = $type;
	}
	
	/**
	 *  Upload een media naar de databse.
	 *  Als de type geen text is, moet er wat informatie uit de file worden gehaald voor in de databse:
	 *  	- Extension
	 *  	- Size
	 *  	- temp Path name
	 *  Daarna moet de $file geupload worden naar de server in een of ander uploads mapje.
	 *  Als er al een bestand bestaat met dezelfde naam, moet de naam verandert worden, "_" ervoor of achter of whatever
	 *  
	 *  Als het bestand geupload is en we het upload path hebben, moet er een doQuery uitgevoerd worden,
	 *  die deze nieuwe media toevoegd aan de sr_media tabel in de database (dus query schrijven)
	 *  
	 *  Mocht er iets fout gaan met het toevoegen aan de database, moet het bestand weer verwijdert worden.
	 */
	static function upload($type, $file) {
		
	}
	
	/** 
	 *  Mag leeg blijven
	 */
	public function delete() {
		
	}
	
	/**
	 *  STATIC function => Kan aangeroepen worden zonder dat er een object wordt gemaakt
	 *  
	 *  Haal alle media op die `content` == $contentId en maak daar een Media object van en return deze in een array (!)
	 *  Kijk ook meteen even of het path nog bestaat en anders ofwel een foutmelding geven of gewoon de media niet uitladen.
	 */
	public static function loadByContent($contentId) {
		
	}
	
	/**
	 *  Functie om te kijken of het bestand met path bestaat op deze server
	 */
	private function pathExists() {
		
	}
	
	/** 
	 *  content uitladen voor HTML straks.
	 *  Heb even simpel wat dingen gemaakt, kan later uitgebreid worden waar nodig.
	 */
	public function getContent() {
		switch ($this->type) {
			case "video":
				return $this->videoContent();
			break;
			case "audio":
				return $this->audioContent();
			break;
			case "image":
				return $this->imageContent();
			break;
			default:
				return $this->textContent();
			break;
		}
	}
	
	private function textContent() {
		$span = HTMLHandler::createHTML("span", array("class" => "mediaText media", "name" => "_{$this->id}"));
		
		return $span['open'] . $this->content . $span['close'];
	}
	
	private function videoContent() {
		$video = HTMLHandler::createHTML("video", array("controls" => true, "class" => "mediaVideo media", "name" => "_{$this->id}"));
		$source = HTMLHandler::createHTML("source", array("src" => $this->content, "type" => "video/mp4"));
		
		return $video['open'] . $source['open'] . $video['close'];
	}
	
	private function audioContent() {
		$audio = HTMLHandler::createHTML("audio", array("controls" => true, "class" => "mediaAudio media", "name" => "_{$this->id}"));
		$source = HTMLHandler::createHTML("source", array("src" => $this->content, "type" => "audio/mpeg"));
		
		return $audio['open'] . $source['open'] . $audio['open'];
	}
	
	private function imageContent() {
		$image = HTMLHandler::createHTML("img", array("src" => $this->content, "class" => "mediaAudio media", "name" => "_{$this->id}"));
	
		return $image['noContent'];
	}
}