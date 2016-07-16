<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $woocommerce, $product;

?>
<div class="images">

	<?php
		if ( has_post_thumbnail() ) {

			$image_title 	= esc_attr( get_the_title( get_post_thumbnail_id() ) );
			$image_caption 	= get_post( get_post_thumbnail_id() )->post_excerpt;
			$image_link  	= wp_get_attachment_url( get_post_thumbnail_id() );
			$image       	= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	=> $image_title,
				'alt'	=> $image_title
				) );

			$attachment_count = count( $product->get_gallery_attachment_ids() );

			if ( $attachment_count > 0 ) {
				$gallery = '[product-gallery]';
			} else {
				$gallery = '';
			}

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $image_caption, $image ), $post->ID );

		} else {

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );

		}
	?>
	<div class="sns-thumbnails handle-preload">
		<?php do_action( 'woocommerce_product_thumbnails' ); ?>
		<div class="navslider" style="display:none">
			<span class="prev"></span>
			<span class="next"></span>
		</div>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('.sns-thumbnails .thumbnails').owlCarousel({
				items: 4,
				responsive : {
				    0 : { items: 3 },
				    480 : { items: 4 },
				    768 : { items: 4 },
				    992 : { items: 4 },
				    1200 : { items: 4 }
				},
				loop:true,
	            dots: false,
	            // animateOut: 'flipInY',
			    //animateIn: 'pulse',
			    // autoplay: true,
	            onInitialized: callback,
	            slideSpeed : 800
			});
			function callback(event) {
	   			if(this._items.length > this.options.items){
			        jQuery('.sns-thumbnails .navslider').show();
			    }else{
			        jQuery('.sns-thumbnails .navslider').hide();
			    }
			}
			jQuery('.sns-thumbnails .prev').on('click', function(e){
				e.preventDefault();
				jQuery('.sns-thumbnails .thumbnails').trigger('prev.owl.carousel');
			});
			jQuery('.sns-thumbnails .next').on('click', function(e){
				e.preventDefault();
				jQuery('.sns-thumbnails .thumbnails').trigger('next.owl.carousel');
			});
		});
	</script>

</div>
