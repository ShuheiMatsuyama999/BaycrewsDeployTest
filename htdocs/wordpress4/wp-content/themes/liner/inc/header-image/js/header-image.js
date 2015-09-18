/**
 * @author NetBusinessAgen
 * @version 1.0.1
 */
jQuery( function( $ ) {
	var custom_uploader;
	$( '#liner-header-image-media' ).click( function( e ) {
		e.preventDefault();
		if ( custom_uploader ) {
			custom_uploader.open();
			return;
		}
		custom_uploader = wp.media( {
			title: liner_header_image.title,
			library: {
				type: 'image'
			},
			button: {
				text: liner_header_image.title
			},
			multiple: false
		} );

		custom_uploader.on( 'select', function() {
			var images = custom_uploader.state().get( 'selection' );
			images.each( function( file ){
				$( '#liner-header-image' ).append( '<img src="' + file.toJSON().url + '" />' );
				$( '#liner-header-image-hidden' ).val( file.toJSON().id );
				$( '#liner-header-image-media' ).removeClass().addClass( 'liner-header-image-hide' );
				$( '#liner-header-image-delete' ).removeClass().addClass( 'liner-header-image-show' );
			} );
		} );

		custom_uploader.open();
	} );

	$( '#liner-header-image-delete' ).click( function() {
		$( '#liner-header-image' ).text( '' );
		$( '#liner-header-image-hidden' ).val( '' );
		$( '#liner-header-image-media' ).removeClass().addClass( 'liner-header-image-show' );
		$( this ).removeClass().addClass( 'liner-header-image-hide' );
	} );
} );