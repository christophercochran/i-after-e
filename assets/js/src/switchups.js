function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function setCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

jQuery(document).ready(function( $ ) {

	$( 'a[update-count]').click(function( e ) {
		e.preventDefault();
    	var post_id = $( this ).attr( 'update-count' );
		var nonce  = $(this).attr( 'data-nonce' )

		if ( getCookie( post_id + '_visited' ) != 1 ) {

			jQuery.ajax({
				type : 'post',
				dataType : 'json',
				url : SwitchUps.ajaxurl,
				data : { action: 'ive_switched_up', post_id : post_id, nonce: nonce },
				success: function(response) {
					console.log(response);
					if ( response.type == "success" ) {
						$( '.switchups-' + post_id ).html( response.vote_count );
						setCookie( post_id + '_visited', 1, 4200 );
					} else {
						alert( "Your vote could not be added." )
					}
				}
			})

		} else {

			alert( "You already told me this :) I know the 'I' Before 'E' Except After 'C' rule is annoying, and you may misspell this word ALL the time, but I have rules too. One vote per word. Sorry. But hey, at least I don't have a long list of exceptions to this rule. (Though this alert is pretty long)" )

  		}

  	});


});