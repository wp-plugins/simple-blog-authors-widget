jQuery( document ).ready( function( $ ) {
		
	// Handle the AJAX field on change action
	$( '#sbaw-select').on( 'change', function( e ) {
		e.preventDefault();
		
		var author_url = $( '#sbaw-select' ).val();
		console.log( author_url );
		
		$.post(myAjax.ajaxurl, {
	 		data: { 'author_url': author_url },
        	     //action: 'sbaw_dropdown_ajax_call'
			 }, function(status) {
			 	 window.location.href = author_url;
           }
         );

	});
});