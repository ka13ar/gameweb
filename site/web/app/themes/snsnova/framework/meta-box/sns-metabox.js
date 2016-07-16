jQuery(document).ready(function($){
	$('.img-layout').each(function(){
		// Add active class
		if($(this).attr('data-value') == $('#snsnova_layouttype').attr('value')){
			$(this).addClass('active');
		}
		showHideDependLayout($('#snsnova_layouttype').attr('value'));
		// Click img select
		$(this).unbind('click').click(function(){
			// Change active class
			$val = $(this).attr('data-value'); $('.img-layout').removeClass('active'); $(this).addClass('active');
			// Set value for select
			$('#post-body #snsnova_layouttype').attr('value', $val);
			// Hide or show field codition
			showHideDependLayout($val);
		});
	});
	//
	if ( $('#page_template').attr('value') == 'fullwidth.php' || $('#page_template').attr('value') == 'sidepage.php' ){
		$('#sns_layout').fadeOut(500);
	}
	$('#page_template').change(function(){
		if( $(this).attr('value') == 'fullwidth.php' || $('#page_template').attr('value') == 'sidepage.php' ){
			$('#sns_layout').fadeOut(500);
		}else{
			$('#sns_layout').fadeIn(500);
		}
	})
	function showHideDependLayout($val){
		if( $val == 'm' ){
			$('#post-body #snsnova_leftsidebar').parents('.rwmb-select-wrapper').stop(true,true).fadeOut(500);
			$('#post-body #snsnova_rightsidebar').parents('.rwmb-select-wrapper').stop(true,true).fadeOut(500);
		}else if( $val == 'l-m' ){
			$('#post-body #snsnova_leftsidebar').parents('.rwmb-select-wrapper').stop(true,true).fadeIn(500);
			$('#post-body #snsnova_rightsidebar').parents('.rwmb-select-wrapper').stop(true,true).fadeOut(500);
		}else if( $val == 'm-r' ){
			$('#post-body #snsnova_rightsidebar').parents('.rwmb-select-wrapper').stop(true,true).fadeIn(500);
			$('#post-body #snsnova_leftsidebar').parents('.rwmb-select-wrapper').stop(true,true).fadeOut(500);
		}else if( $val == 'l-m-r' ){
			$('#post-body #snsnova_leftsidebar').parents('.rwmb-select-wrapper').stop(true,true).fadeIn(500);
			$('#post-body #snsnova_rightsidebar').parents('.rwmb-select-wrapper').stop(true,true).fadeIn(500);
		}
	}
	// Enable layout config
	$('#post-body input[name="snsnova_enablelayoutconfig"]').each(function(){
		if( $(this).attr('checked') == 'checked' ) enableLayoutConfig( $(this).attr('value') );
		$(this).change(function(){
			enableLayoutConfig( $(this).attr('value') );
		})
	})
	// $('#post-body input[name="snsnova_enablelayoutconfig"]').change(function(){
	// 	enableLayoutConfig( $(this).attr('value') );
	// })
	function enableLayoutConfig($val){
		if ( $val == '0' ){
			$('#post-body #snsnova_leftsidebar').parents('.rwmb-select-wrapper').stop(true,true).fadeOut(500);
			$('#post-body #snsnova_rightsidebar').parents('.rwmb-select-wrapper').stop(true,true).fadeOut(500);
			$('#post-body #snsnova_layouttype').parents('.rwmb-layouttype-wrapper').stop(true,true).fadeOut(500);
		}else if( $val == '1' ){
			$('#post-body #snsnova_layouttype').parents('.rwmb-layouttype-wrapper').stop(true,true).fadeIn(500);
			showHideDependLayout($('#snsnova_layouttype').attr('value'));
		}
	}
	//
	showHideDependPostFormat($('#post-format-gallery').attr('checked'), '#sns-post-gallery' );
	showHideDependPostFormat($('#post-format-video').attr('checked'), '#sns-post-video' );
	showHideDependPostFormat($('#post-format-quote').attr('checked'), '#sns-post-quote' );
	showHideDependPostFormat($('#post-format-link').attr('checked'), '#sns-post-link' );
	$('#post-formats-select input').each(function(){
		$(this).unbind('click').click(function(){
			showHideDependPostFormat($('#post-format-gallery').attr('checked'), '#sns-post-gallery' );
			showHideDependPostFormat($('#post-format-video').attr('checked'), '#sns-post-video' );
			showHideDependPostFormat($('#post-format-quote').attr('checked'), '#sns-post-quote' );
			showHideDependPostFormat($('#post-format-link').attr('checked'), '#sns-post-link' );
		});
	});
	function showHideDependPostFormat($checked, $id){
		if( $checked == 'checked' ){
			$($id).stop(true,true).fadeIn(500);
		}else {
			$($id).stop(true,true).fadeOut(500);
		}
	}
	// Revolution
	$('#post-body input[name=snsnova_useslideshow]').each(function(){
		if( $(this).attr('checked') == 'checked' ){
			if ( $(this).attr('value') == 1 ) {
				$('#post-body select#snsnova_revolutionslider').parents('.rwmb-select-wrapper').stop(true,true).fadeIn(500);
			}else {
				$('#post-body select#snsnova_revolutionslider').parents('.rwmb-select-wrapper').stop(true,true).fadeOut(500);
			}
		}
		$(this).change(function(){
			if ( $(this).attr('value') == 1 ) {
				$('#post-body select#snsnova_revolutionslider').parents('.rwmb-select-wrapper').stop(true,true).fadeIn(500);
			}else {
				$('#post-body select#snsnova_revolutionslider').parents('.rwmb-select-wrapper').stop(true,true).fadeOut(500);
			}
		})
	})
	// Theme color
	$('#post-body input[name=snsnova_page_themecolor]').each(function(){
		if( $(this).attr('checked') == 'checked' ){
			if ( $(this).attr('value') == 1 ) {
				$('#post-body input#snsnova_themecolor').parents('.rwmb-color-wrapper').stop(true,true).fadeIn(500);
			}else {
				$('#post-body input#snsnova_themecolor').parents('.rwmb-color-wrapper').stop(true,true).fadeOut(500);
			}
		}
		$(this).change(function(){
			if ( $(this).attr('value') == 1 ) {
				$('#post-body input#snsnova_themecolor').parents('.rwmb-color-wrapper').stop(true,true).fadeIn(500);
			}else {
				$('#post-body input#snsnova_themecolor').parents('.rwmb-color-wrapper').stop(true,true).fadeOut(500);
			}
		})
	})
	
})
