<?php
global $product, $woocommerce_loop, $yith_woocompare;
$class = 'item product';
if ( isset($col) && $col > 0) :
	// Medium device class
	if (12%$col == 0) $class .= ' col-md-'.(12/$col);
	// Small device class
	if ($col >= 2 && 12%($col-1) == 0) $class .= ' col-sm-'.(12/($col-1));
	// XSmall device class
	if ($col >= 3 && 12%($col-2) == 0) $class .= ' col-xs-'.(12/($col-2));
	// Phone device class
	$class .= ' col-phone-12';
endif;
if ( isset($animate) && $animate) :
	$class .= ' item-animate';
endif;
if ( isset($eclass) && $eclass) :
	$class .= ' '.$eclass;
endif;
?>
<li class="<?php echo esc_attr($class); ?>">
	<div class="block-product-inner grid-view">
		<div class="item-inner">
			<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
			<div class="item-img have-iconew have-additional clearfix">
				<div class="cart-wrap">
					<?php
						/**
						 * woocommerce_after_shop_loop_item hook
						 *
						 * @hooked woocommerce_template_loop_add_to_cart - 10
						 */
						if ( class_exists('YITH_WCQV_Frontend') ) {
							remove_action('woocommerce_after_shop_loop_item',  array( YITH_WCQV_Frontend::get_instance(), 'yith_add_quick_view_button'), 15);
						}
						if ( isset($yith_woocompare) ) {
						    remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
						}
						do_action( 'woocommerce_after_shop_loop_item' ); 
					?>
				</div>
				<div class="item-img-info">
					<a class="product-image" href="<?php the_permalink(); ?>">
						<?php
							/**
							 * woocommerce_before_shop_loop_item_title hook
							 *
							 * @hooked woocommerce_show_product_loop_sale_flash - 10
							 * @hooked woocommerce_template_loop_product_thumbnail - 10
							 */
							do_action( 'woocommerce_before_shop_loop_item_title' );
						?>
					</a>
					<?php if ( class_exists( 'YITH_WCWL' ) || class_exists( 'YITH_Woocompare' ) || class_exists('YITH_WCQV_Frontend') ) : ?>
					<div class="item-box-hover">
						<div class="box-inner">
							<?php
				            if( class_exists( 'YITH_WCWL' ) ) {
				                echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
				            }
				            ?>

				            <?php if( class_exists( 'YITH_Woocompare' ) ) { ?>
				                <?php
				                $action_add = 'yith-woocompare-add-product';
				                $url_args = array(
				                    'action' => $action_add,
				                    'id' => $product->id
				                );
				                ?>
				                <a data-original-title="Add to compare" data-toggle="tooltip" href="<?php echo esc_url( wp_nonce_url( add_query_arg( $url_args ), $action_add ) ); ?>" class="compare btn btn-primary-outline" data-product_id="<?php echo esc_attr( $product->id ); ?>">
				                </a>
				            <?php } ?>
				            <?php if ( class_exists('YITH_WCQV_Frontend') ) { ?>
				            	<a data-original-title="<?php echo esc_html__('Quick view', 'snsnova'); ?>" data-toggle="tooltip" data-product_id="<?php echo esc_attr($product->id) ?>" class="button yith-wcqv-button" href="#"></a>
				            <?php } ?>
						</div>
					</div>
				<?php endif; ?>
				</div>
			</div>
			<div class="item-info">
				<div class="info-inner">
					<h3 class="item-title"><a class="product-name" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="item-content">
						<?php
							/**
							 * woocommerce_after_shop_loop_item_title hook
							 *
							 * @hooked woocommerce_template_loop_rating - 5
							 * @hooked woocommerce_template_loop_price - 10
							 */
							remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
							remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
							// Re-order
							add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 5);
							add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 10);
							do_action( 'woocommerce_after_shop_loop_item_title' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</li>