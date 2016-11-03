<?php
	session_start();
	
	// Include framework
	include '../framework/utils.inc.php';
	// Get content
	$page = "index";
	$_string["title"] = $page;
	$path = "templates/";
	if(!isSet($_GET["page"]) || isEmpty($_GET["page"])) {
		// Get page
		
		// REGISTER
		//User::register("username", "password", "email");
		
		// Post content
		$user = new User(16, 'username', 'email', 'user', array());
		$post = new Content(-1, $user, "Test", "Test content!", "text");
		$post->upload();
	}

	ob_start();
	
	include("header.php");
	include($path.$page.".php");
	include("footer.php");
	
	$webpage = ob_get_contents();
	
	ob_end_clean();
	
	// Prepare webpage to translate and place all texts
	// Also place all content
	echo prepare($webpage, $_prep);
?>