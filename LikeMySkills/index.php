<?php
	session_start();
	// Include framework
	include '../framework/utils.inc.php';
	// include '../framework/classes/essentials/Util.class.php';
	global $util;	
	$util = new \Essentials\Util();
	$util->createPage(isSet($_GET["page"]) ?  $_GET["page"] : "index");

	
	// Get content
	if(!empty($util->getPage())) {
		echo $util->getPage()->getPageContent();
	} else {
		echo "Page not found";
	}
		// REGISTER
		//User::register("username", "password", "email");
		
		// Post content
		/*
		$user = new User(1, 'username', 'email', 'user', array());
		$post = new Content(-1, $user, "Test", "Test content!", "text");
		$post->upload();*/
	 

	// Prepare webpage to translate and place all texts
	// Also place all content

?>