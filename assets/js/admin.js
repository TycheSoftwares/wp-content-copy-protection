jQuery( document ).ready( function( $ ) {
	// Init Select2
	$( '#wpccp_exclude_categories' ).select2();
	$( '#wpccp_exclude_roles, #wpccp_exclude_posttypes' ).select2( { disabled: true } );

	// Init Select2 for posts
	$( '#wpccp_exclude_posts' ).select2( {
		ajax: {
			url: ajaxurl,
			dataType: 'json',
			delay: 250,
			data( params ) {
				return {
					wpccp_q: params.term,
					wpccp_type: 'post',
					action: 'wpccp_getposts',
					_ajax_nonce: wpccpNonce,
				};
			},
			processResults( data ) {
				const options = [];
				if ( data ) {
					$.each( data, function( index, text ) {
						options.push( {
							id: text[ 0 ],
							text: text[ 1 ],
						} );
					} );
				}

				return {
					results: options,
				};
			},
			cache: true,
		},
		minimumInputLength: 3,
	} );

	// Init Select2 for pages
	$( '#wpccp_exclude_pages' ).select2( {
		ajax: {
			url: ajaxurl,
			dataType: 'json',
			delay: 250,
			data( params ) {
				return {
					wpccp_q: params.term,
					wpccp_type: 'page',
					action: 'wpccp_getposts',
					_ajax_nonce: wpccpNonce,
				};
			},
			processResults( data ) {
				const options = [];
				if ( data ) {
					$.each( data, function( index, text ) {
						options.push( {
							id: text[ 0 ],
							text: text[ 1 ],
						} );
					} );
				}

				return {
					results: options,
				};
			},
			cache: true,
		},
		minimumInputLength: 3,
	} );
} );
