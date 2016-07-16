(function ($) {
	"use strict";
	$(document).ready(function() {
		// Click mode view
		$('.mode-view a').click(function(){
            var mode = $(this).data('mode');
            if(!$(this).hasClass('active')){
	            $.ajax({
	                url: ajaxurl,
	                data:{
	                	action : 'sns_setmodeview',
	                	mode : mode
	                },
	                type: 'POST'
	            });
	        }else{
	        	return false;
	        }
	        $('.mode-view a').removeClass('active');
            $('.mode-view a').each(function(){
            	if ( $(this).hasClass(mode) ) $(this).addClass('active');
            })
            
            //
            if( $('#sns_woo_list.product_list').hasClass('grid') ){
            	$('#sns_woo_list.product_list').removeClass('grid');
            	$('#sns_woo_list.product_list').addClass('list');
            }else if( $('#sns_woo_list.product_list').hasClass('list') ){
            	$('#sns_woo_list.product_list').removeClass('list');
            	$('#sns_woo_list.product_list').addClass('grid');
            }
            return false;
        });

		// Click loadmore from shortcode SNS Product Tabs
		$('.sns-woo-loadmore').each(function() {
			$(this).click(function(){
				if(!$(this).hasClass('loaded')){
					var btnid, numberquery, start, order, col, cat, loadtext, loadingtext, loadedtext, type, wrapid, eclass;
					btnid       = $(this).attr('id');
					numberquery = $(this).attr('data-numberquery');
					start       = $(this).attr('data-start');
	            	order       = $(this).attr('data-order');
	            	col         = $(this).attr('data-col');
	            	cat         = $(this).attr('data-cat');
	            	loadtext    = $(this).attr('data-loadtext');
	            	loadingtext = $(this).attr('data-loadingtext');
	            	loadedtext  = $(this).attr('data-loadedtext');
	            	type        = $(this).attr('data-type');

	            	wrapid = $('#'+btnid).parents('.sns-product-tabs').attr('id');

	            	eclass = 'animate-'+Math.floor((Math.random() * 1000000000));

	            	$('#'+btnid).html(loadingtext); $('#'+btnid).addClass('loading');

		            $.ajax({
		                url: ajaxurl,
		                data:{
		                	action 		: 'sns_wooloadmore',
		                	numberquery : numberquery,
		                	start       : start,
		                	order       : order,
		                	col         : col,
		                	cat         : cat,
		                	eclass      : eclass,
		                },
		                type: 'POST',
		                success: function(data){
		                	if( data!='' ){
			                	if(type == 'order'){
			                		$('#'+wrapid+' #producttabs_'+order+' .product_list').append(data);
			                		SnsJsWoo.setAnimate( '#'+wrapid+' #producttabs_'+order, eclass );
			                	}else if(type == 'cat'){
			                		$('#'+wrapid+' #producttabs_'+cat+' .product_list').append(data);
			                		SnsJsWoo.setAnimate('#'+wrapid+' #producttabs_'+cat, eclass );
			                	}
			                	
			                	$('#'+btnid).removeClass('loading');
			                	if( (parseInt(start) + parseInt(numberquery)) > $('.sns-product-tabs #producttabs_'+order+' .product_list li').size() ){
			                		$('#'+btnid).html(loadedtext);
			                		$('#'+btnid).addClass('loaded');
			                	}else{
			                		$('#'+btnid).html(loadtext);
			                	}
			                	$('#'+btnid).attr('data-start', parseInt(start) + parseInt(numberquery));
			                	// Callback quickview, wishlist
			                	$.fn.yith_quick_view();
			                }else{
			                	$('#'+btnid).html(loadedtext);
			                	$('#'+btnid).addClass('loaded');
			                }
		                }
		            });
		        }else{
		         	return false;
		        }
		    });
		});

        // Accordion for category
		$('.product-categories').SnsAccordion({
			btn_open: '<span class="ac-tongle open"></span>',
			btn_close: '<span class="ac-tongle close"></span>',
		});
		// Click add to cart
		$('.grid-view a.add_to_cart_button.product_type_simple').click(function(e) {
			var $this = $(this);
		});
		$('.list-view a.add_to_cart_button.product_type_simple').each(function() {
		});

		// Wishlist & compare
		$('.type-product .summary .compare, .yith-wcwl-add-to-wishlist .add_to_wishlist, .yith-wcwl-wishlistaddedbrowse a, .yith-wcwl-wishlistexistsbrowse a').each(function(){
			$(this).attr('data-toggle', 'tooltip').attr('data-original-title', $(this).text().trim());
		});

		jQuery(document).ajaxComplete(function(){
			// Wishlist & compare
			$('.type-product .summary .compare, .yith-wcwl-add-to-wishlist .add_to_wishlist, .yith-wcwl-wishlistaddedbrowse a, .yith-wcwl-wishlistexistsbrowse a').each(function(){
				$(this).attr('data-toggle', 'tooltip').attr('data-original-title', $(this).text().trim());
			});
			// View cart
			$('.products .added_to_cart').each(function(){
				if( $(this).text().trim() != '') $(this).attr('data-toggle', 'tooltip').attr('data-original-title', $(this).text().trim());
			});
			// Compare
			$('.woocommerce .compare.button').each(function(){
				if( $(this).text().trim() != '') $(this).attr('data-original-title', $(this).text().trim());
			});
			// Need check clear all .clear-all
			// Mini cart number
			$('.sns-ajaxcart .tongle .number-item').html($('.sns-ajaxcart .sns-cart-number').html());
			// Compare number
			var countC = 0;
			$('.block-compare .products-list > li').each(function(){
				if( $(this).find('a').length ) countC ++;
			});
			$('.block-compare .compare-toggle .total-compare-val').html(countC);
		});
	});
})(jQuery);

var SnsJsWoo= {
	setAnimate: function (el, eclass){
		jQuery(el).find('.sns-woo-loadmore').fadeOut('fast');
		morec = '';
		if( jQuery(el+' .product_list').hasClass('owl-carousel') ){
		 	morec = '.owl-item.active ';
		}
		jQuery(el+' .product_list '+morec+'.'+eclass).each(function(i){
			jQuery(this).attr("style", "-webkit-animation-delay:" + i * 300 + "ms;"
	                + "-moz-animation-delay:" + i * 300 + "ms;"
	                + "-o-animation-delay:" + i * 300 + "ms;"
	                + "animation-delay:" + i * 300 + "ms;");
	        if (i == jQuery(el+' .product_list '+morec+'.'+eclass).size() -1) {
	            jQuery(el+' .product_list').addClass("play");
	            jQuery(el).find('.sns-woo-loadmore').fadeIn(i*0.3);
	            if( morec!='' ){
	            	setTimeout(function(){
	            		SnsJsWoo.delAnimate(el);
	            	}, i*300+700);
	            }
	        }
		});
	},
	resetAnimate: function (el){
		var wrapid, eclass, contentid;
		wrapid = el.parents('.sns-product-tabs').attr('id');
    	eclass = 'animate-'+Math.floor((Math.random() * 1000000000));
    	contentid = el.find('a').attr('href');
    	//
    	jQuery('#'+wrapid+' .product_list').removeClass('play');
    	jQuery('#'+wrapid+' .product_list li').removeClass('item-animate');
    	jQuery('#'+wrapid+' .product_list li').attr('style', '');
    	// Remove class with prefix animate-
    	var classNames = [];
		jQuery('#'+wrapid+' .product_list li[class*="animate-"]').each(function(i, el){
		    var name = (el.className.match(/(^|\s)(animate\-[^\s]*)/) || [,,''])[2];
		    if(name){
		        classNames.push(name);
		        jQuery(el).removeClass(name);
		    }
		});
    	//
    	jQuery('#'+wrapid+' '+contentid+' .product_list li').addClass('item-animate').addClass(eclass);
    	// Set effect
	    SnsJsWoo.setAnimate('#'+wrapid+' '+contentid, eclass );
	},
	delAnimate: function(el){
		if( jQuery(el+' .product_list li').hasClass('item-animate') ) jQuery(el+' .product_list li').removeClass('item-animate');
	}
};
