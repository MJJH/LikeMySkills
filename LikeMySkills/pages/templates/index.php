<div class="container">

	<div id="loader-wrapper">
		<div id="loader"></div>

        <div class="loader-text"><h6>SPORT REPORT</h6></div>
		<div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>

 	</div>

	<h3> Test 1: </h3>
	<ul>
	$%for:test{
		<li> 
			<i>$%i%.</i> <b>$%naam%</b> doet $%richting%
		</li>
	}%
	</ul>
	<hr>
	<h3> Test 2: </h3>
	
	$%for:test{
		<div class="__$%i%">
			$%naam% : $%richting%
		</div>
	}%
	
</div>