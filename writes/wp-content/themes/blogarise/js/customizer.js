/**
 * customizer.js
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	var myCustomizer = window.parent.window.wp.customize;

	console.log(php_obj.current_theme);
	
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	
	// Header text hide and show and text color.
	wp.customize( 'header_textcolor', function( value ) {
		if(value() == 'blank'){
			myCustomizer.control(`${php_obj.current_theme}_title_font_size`).container.hide();
		}else{
			myCustomizer.control(`${php_obj.current_theme}_title_font_size`).container.show();
		}
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
				$( '.site-branding-text ' ).addClass('d-none');
				myCustomizer.control(`${php_obj.current_theme}_title_font_size`).container.hide();
			} else {
				$('.site-title').css('position', 'unset');
				$( '.site-title a, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-branding-text ' ).removeClass('d-none');
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
				myCustomizer.control(`${php_obj.current_theme}_title_font_size`).container.show();
			}
		} );
	} );
	
	// Site Title Font Size.
	wp.customize( `${php_obj.current_theme}_title_font_size`, function( value ) {
		value.bind( function( newVal ) {
			$( '.site-title a' ).css( {
				'font-size': newVal+'px',
			} );
		} );
	} );

	// Header Banner, Site Title and Site Tagline Center Alignment.
	wp.customize( 'blogarise_center_logo_title', function( value ) {
		value.bind( function( newVal ) {
			var firstChild = $('.bs-header-main .row.align-items-center').children(':nth-child(1)');
			var secondChild = $('.bs-header-main .row.align-items-center').children(':nth-child(2)');	
			if(newVal == true){
				if(firstChild.hasClass('text-md-start d-lg-block col-md-4')){
					firstChild.removeClass('text-md-start d-lg-block col-md-4');
				} 
				firstChild.addClass('d-lg-block col-md-12 text-center mx-auto');

				if(secondChild.hasClass('col-lg-8')){
					secondChild.removeClass('col-lg-8');
				} 
				secondChild.addClass('col text-center mx-auto');
				
				if(secondChild.children(':nth-child(1)').hasClass('text-md-end')){
					secondChild.children(':nth-child(1)').removeClass('text-md-end');
				} 
				secondChild.children(':nth-child(1)').addClass('text-center');
			}else{
				if(firstChild.hasClass('d-lg-block col-md-12 text-center mx-auto')){
					firstChild.removeClass('d-lg-block col-md-12 text-center mx-auto');
				} 
				firstChild.addClass('text-md-start d-lg-block col-md-4');

				if(secondChild.hasClass('col text-center mx-auto')){
					secondChild.removeClass('col text-center mx-auto');
				} 
				secondChild.addClass('col-lg-8');
				
				if(secondChild.children(':nth-child(1)').hasClass('text-center')){
					secondChild.children(':nth-child(1)').removeClass('text-center');
				} 
				secondChild.children(':nth-child(1)').addClass('text-md-end');
			}
			console.log(newVal);
		} );
	} );

	// Footer Widget Area color.
	wp.customize( 'blogarise_footer_column_layout', function( value ) {
		var colum = 12 / value();
		var wclass = $('.animated.bs-widget');
		if(wclass.hasClass('col-md-12')){
			wclass.removeClass('col-md-12');
		}else if(wclass.hasClass('col-md-6')){
			wclass.removeClass('col-md-6');
		}else if(wclass.hasClass('col-md-4')){
			wclass.removeClass('col-md-4');
		}else if(wclass.hasClass('col-md-3')){
			wclass.removeClass('col-md-3');
		}
		wclass.addClass(`col-md-${colum}`);

		value.bind( function( newVal ) {
			colum = 12 / newVal;
			wclass = $('.animated.bs-widget');
			if(wclass.hasClass('col-md-12')){
				wclass.removeClass('col-md-12');
			}else if(wclass.hasClass('col-md-6')){
				wclass.removeClass('col-md-6');
			}else if(wclass.hasClass('col-md-4')){
				wclass.removeClass('col-md-4');
			}else if(wclass.hasClass('col-md-3')){
				wclass.removeClass('col-md-3');
			}
			wclass.addClass(`col-md-${colum}`);
			console.log(wclass);
		} );
	} );
} )( jQuery );
