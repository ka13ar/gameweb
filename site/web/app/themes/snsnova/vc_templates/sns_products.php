<?php
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
if( class_exists('WooCommerce') ){
	global $woocommerce;
	if( $data_type == 'orderby'){
		$loop = snsnova_woo_query($type, $number_limit, $list_cat);
	}else{
		$loop = snsnova_woo_query($type, $number_limit, $cat);
		if($use_cat_is_title == '1'){
			$title = $cat;
		}
	}
	$uq = rand().time();
	$class = 'sns-products woocommerce';
	$class .= ( trim(esc_attr($extra_class))!='' )?' '.esc_attr($extra_class):'';
	$class .= esc_attr($this->getCSSAnimation( $css_animation ));
	if( $loop->have_posts() ) :
		if(trim($paddingtop_productcontainer) != ''){
			$output .= '<style scoped>#sns_products'.$uq.' .product_list{padding-top: '.esc_attr($paddingtop_productcontainer).'}</style>';
		}
		$output .= '<div id="sns_products'.$uq.'" class="'.$class.'">';
		if ( $title != '' ) $output .= '<h2 class="wpb_heading"><span>'.esc_attr($title).'</span></h2>';
		$output .= '<div class="navslider" style="display:none"><span class="prev"><i class="fa fa-arrow-left"></i></span><span class="next"><i class="fa fa-arrow-right"></i></span></div>';
		if( $pretext !='' ) $output .= '<div class="pretext"><div>'.esc_attr($pretext).'</div></div>';
		ob_start();
		wc_get_template( 'vc/carousel.php', array('loop' => $loop, 'number_display' => $number_display, 'id' => 'sns_products'.$uq) );
		$output .= ob_get_clean();
		$output .= '</div>';
	endif;
	wp_reset_postdata();
}
echo $output;
