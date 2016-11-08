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
</content>
	<footer>
		<div class="footer-container">
			<p>&copy; 2016 LikeMySkills - <a href="#" title="%chooseLan%">%lan%</a></p>
		</div>
	</footer>
</body>
</html>