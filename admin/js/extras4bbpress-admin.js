(function( $ ) {
	'use strict';

	$(document).on( 'change', 'input[name="bbp_extra_limitado"]', function(e){

		if ( 2 === parseInt(e.target.value) ){

			$('input[name="bbp_extra_limite"]').val(0);
			$('#bbp_extra_limite_p').hide();
		} else {

			$('#bbp_extra_limite_p').show();
		}
	});

})( jQuery );
