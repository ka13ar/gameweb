<?php 
global $product;
?>
<ul class="products product_list grid<?php echo ( isset($effect_load) ) ? ' ' . esc_attr($effect_load) : ''; ?>">
<?php
while ( $loop->have_posts() ) : $loop->the_post(); ?>
    <?php
    wc_get_template( 'vc/item-grid.php' );
    ?>
<?php endwhile; ?>
</ul>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('#<?php echo esc_attr($id);?> ul').owlCarousel({
		items: <?php echo intval($number_display) ?>,
		responsive : {
		    0 : { items: 1 },
		    480 : { items: 2 },
		    768 : { items: <?php echo intval($number_display)-1 ?> },
		    992 : { items: <?php echo intval($number_display) ?> },
		    1200 : { items: <?php echo intval($number_display) ?> }
		},
		loop:true,
        dots: false,
	    // autoplay: true,
        onInitialized: callback,
        slideSpeed : 800
	});
	function callback(event) {
			if(this._items.length > this.options.items){
	        jQuery('#<?php echo esc_attr($id);?> .navslider').show();
	    }else{
	        jQuery('#<?php echo esc_attr($id);?> .navslider').hide();
	    }
	}
	jQuery('#<?php echo esc_attr($id);?> .navslider .prev').on('click', function(e){
		if( jQuery('body').hasClass('use_lazyload') ){
			var timeout = setTimeout(function() {
		        jQuery("#<?php echo esc_attr($id);?> img.lazy:not(.loaded)").trigger("appear")
		    }, 1000);
		}
		e.preventDefault();
		jQuery('#<?php echo esc_attr($id);?> ul').trigger('prev.owl.carousel');
		
	});
	jQuery('#<?php echo esc_attr($id);?> .navslider .next').on('click', function(e){
		if( jQuery('body').hasClass('use_lazyload') ){
			var timeout = setTimeout(function() {
		        jQuery("#<?php echo esc_attr($id);?> img.lazy:not(.loaded)").trigger("appear")
		    }, 1000);
		}
		e.preventDefault();
		jQuery('#<?php echo esc_attr($id);?> ul').trigger('next.owl.carousel');
	});
});
</script>