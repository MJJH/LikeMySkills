<?php
$emptyform = false;
if (isset($_POST)){
	if (isset($_POST["username"])){
		if empty($_POST["username"]){
			$emptyform = true;
			$_error[] = "%EmptyUsername%";
		}
	}
	if (isset($_POST["password"])){
		if empty($_POST["password"]){
			$emptyform = true;
			$_error[] = "%EmptyPassword%";
		}
	}
	if (isset($_POST["email"])){
		if empty($_POST["email"]){
			$emptyform = true;
			$_error[] = "%EmptyEmail%";
		}
	}
	if (!$emptyform){
		User::register($_POST["username"],$_POST["password"],$_POST["email"]);
	}
	else{
		print"Een van de velden is leeg";
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