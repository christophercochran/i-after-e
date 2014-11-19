jQuery(function( $ ){

	$(".nav-primary").addClass("responsive-menu").before('<div id="menu-icon"></div>');

	$("#menu-icon").click(function(){
		$(".nav-primary").slideToggle();
	});

	$(window).resize(function(){
		if ( window.innerWidth > 600 ) {
			$(".nav-primary").removeAttr("style");
		}
	});

});