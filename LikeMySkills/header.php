<!DOCTYPE html>
<html lang="en">
<head>
	<title>%title% - LikeMySkills</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
	<header>
		<nav>
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="#">Preferences</a></li>
				<li><a href="#">Login</a></li>
			</ul>
		</nav>
	</header>
	<?php 
		if(count($_error) > 0) {
			?>
				<div id="error">
					<?php 
						foreach($_error as $e) { 
							echo $e . "<br>";
						}
					?>
				</div>
			<?php
		}
	?>
	
	<content>