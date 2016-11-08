<?php
	session_start();
	
	// Include framework
	include '../framework/utils.inc.php';
	// Get content
	$page = "index";
	$path = "templates/";
	
	if(isSet($_GET["page"]) && !empty($_GET["page"])) {
		if(preg_match('/^[a-zA-Z0-9-]+$/', $_GET["page"]) && file_exists($path.$_GET["page"].".php")) {
			$page = $_GET["page"];
		}
		// REGISTER
		//User::register("username", "password", "email");
		
		// Post content
		/*
		$user = new User(1, 'username', 'email', 'user', array());
		$post = new Content(-1, $user, "Test", "Test content!", "text");
		$post->upload();*/
	} 

	ob_start();
	
	include("header.php");
	include($path.$page.".php");
	include("footer.php");
	
	$webpage = ob_get_contents();
	//var_dump($_error);
	ob_end_clean();
	// Prepare webpage to translate and place all texts
	// Also place all content
	echo prepare($webpage, $_prep);
?>