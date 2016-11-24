
$("document").ready(function() {
	var lanToggle = $("#lanSelect"),
		lanHolder = $("#lanHolder");

	lanToggle.click(function() {
		lanHolder.toggleClass("closed");
	});
});