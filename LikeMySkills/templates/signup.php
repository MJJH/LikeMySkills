<?php
$emptyform = false;
if (isset($_POST) && !empty($_POST)){
	if (!isset($_POST["username"]) || empty($_POST["username"])){
			$emptyform = true;
			$GLOBALS["_error"][] = "%EmptyUsername%";
	}
	if (!isset($_POST["password"]) || empty($_POST["password"])){
			$emptyform = true;
			$_error[] = "%EmptyPassword%";
	}
	if (!isset($_POST["email"]) || empty($_POST["email"])){
			$emptyform = true;
			$_error[] = "%EmptyEmail%";
	}
	if ($emptyform = false){
		User::register($_POST["username"],$_POST["password"],$_POST["email"]);
	}
}
?>
<form method="post" action="index.php?page=signup">
	
	<label for="username">Username:</label>
	<input type="text" name="username" /><br>
	
	<label for="password">Password:</label>
	<input type="password" name="password" /><br>
	
	<label for="email">E-mail:</label>
	<input type="text" name="email" /><br>
	<input type="submit" value="Registreer">
</form>