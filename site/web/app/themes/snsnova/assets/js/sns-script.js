(function ($) {
	"use strict";
	$(document).ready(function($){
		// 
		if( $('body').hasClass('use_lazyload') ){
			$("img.lazy").lazyload({
			    effect : "fadeIn"
			});
		}

		// handle-preload
		$('.handle-preload').removeClass('handle-preload');
		// Set color, border-color for social icon
		$('.connect-us [data-color]').hover(function(){
			$(this).css({
				'color':$(this).attr('data-color'), 
				'border-color':$(this).attr('data-color')
			});
		},function(){
			$(this).css({
				'color':'',
				'border-color':''
			})
		});
		// Make page title
		$('#sns_content h1.product_title, #sns_content h1.page-title, #sns_content h1.entry-title, #sns_content h1.page-header').each(function (){
			if( $('#sns_titlepage').length && $(this).length ){
				$('#sns_titlepage').html( $(this)[0].outerHTML ); // or $('selector').clone().wrap('<p>').parent().html();
				$(this).remove();
			}
		});
		// Count to
		$('.counter-value').each(function(){
			$(this).waypoint(function() {
	        	var element = $(this).find(' > span');
		    	element.countTo({
		    		from: element.data('from'), 
		    		to: element.data('to'),
		    		efreshInterval: element.data('interval'),
		    		speed: element.data('speed')
		    	});
	        },{
			triggerOnce : true ,
			     offset : '100%'
	    	});
		});
		// Sticky menu
		if($('#sns_menu').length && $('body').hasClass('use_stickmenu')){
		    var headerOrgOffset = $('#sns_menu').offset().top;
		    $(window).scroll(function() {
		        var currentScroll = $(this).scrollTop();
		        if(currentScroll > headerOrgOffset) {
		        	$('#sns_menu').addClass('keep-menu');
		        } else {
		        	$('#sns_menu').removeClass('keep-menu');
		        }
		    });
		}
	});
		
	$(window).load(function(){
		//
		// $("img.lazy").each(function(){
		// 	$(this).attr("src", $(this).attr('data-original')); //.attr('src', $(this).attr('data-original'));
		// 	$(this).addClass('loaded');
		// })
		// Tooltip
	    $("body.use-tooltip *[data-toggle='tooltip']").each(function(){
			$(this).tooltip({
	    		container: 'body'
	    	});
		})
		$(document).ajaxComplete(function(){
			$("body.use-tooltip *[data-toggle='tooltip']").each(function(){
				$(this).tooltip({
		    		container: 'body'
		    	});
			})
			if( $('body').hasClass('use_lazyload') ){
				var timeout = setTimeout(function() {
					$("img.lazy:not(.loaded)").lazyload({
					    effect : "fadeIn"
					});
				}, 1000);
			}
			// if( $('body').hasClass('use_lazyload') ){
			// 	$("img.lazy:not(.loaded)").lazyload({
			// 	    effect : "fadeIn"
			// 	});
			// }
		})
	});
	$.fn.SnsAccordion = function(options) {
		var $el    = $(this);
		var defaults = {
			active: 'open',
			active_default: 'nav-2',
			el_wrap: 'li',
			el_content: 'ul',
			accordion: true,
			expand: true,
			btn_open: '<i class="fa fa-plus-square-o"></i>',
			btn_close: '<i class="fa fa-minus-square-o"></i>'
		};
		var options = $.extend({}, defaults, options);
		
		
		$(document).ready(function() {
			$el.find(options.el_wrap).each(function(){
				$(this).find('> a, > span, > h4').wrap('<div class="accr_header"></div>');
				if(($(this).find(options.el_content)).length){
					$(this).find('> .accr_header').append('<span class="btn_accor">' + options.btn_open + '</span>');
					$(this).find('> '+options.el_content+':not(".accr_header")').wrap('<div class="accr_content"></div>');
				}
			});
			if(options.accordion){
				$('.accr_content').hide();
				$el.find(options.el_wrap).each(function(){
					if(options.active_default!==''){
						if( $(this).hasClass(options.active_default) ){
							$(this).addClass(options.active);
						}
					}
					if($(this).hasClass(options.active)) {
						$(this).find('> .accr_content')
							   .addClass(options.active)
							   .slideDown();
						$(this).find('> .accr_header')
							   .addClass(options.active);
					}
				});
			} else {
				$el.find(options.el_wrap).each(function(){
					if(!options.expand){
						$('.accr_content').hide();
					} else {
						$(this).find('> .accr_content').addClass(options.active);
						$(this).find('> .accr_header').addClass(options.active);
						$(this).find('> .accr_header .btn_accor').html(options.btn_close);
					}
				});
			}

	    });
	    $(window).load(function() {
			$el.find(options.el_wrap).each(function(){
				var $wrap = $(this);
				var $accrhead = $wrap.find('> .accr_header');
				var btn_accor = '.btn_accor';
				
				$accrhead.find(btn_accor).on('click', function(event) {
					event.preventDefault();
					var obj = $(this);
					var slide = true;
					if($accrhead.hasClass(options.active)) {
						slide = false;
					}
					if(options.accordion){
						$wrap.siblings(options.el_wrap).find('> .accr_content').slideUp().removeClass(options.active);
						$wrap.siblings(options.el_wrap).find('> .accr_header').removeClass(options.active);
						$wrap.siblings(options.el_wrap).find('> .accr_header ' + btn_accor).html(options.btn_open);
					}
					if(slide) {
						$accrhead.addClass(options.active);
						obj.html(options.btn_close);
						$accrhead.siblings('.accr_content').addClass(options.active).stop(true, true).slideDown();
					} else {
						$accrhead.removeClass(options.active);
						obj.html(options.btn_open);
						$accrhead.siblings('.accr_content').removeClass(options.active).stop(true, true).slideUp();
					}
					return false;
				});
			});
		});
	};
})(jQuery);