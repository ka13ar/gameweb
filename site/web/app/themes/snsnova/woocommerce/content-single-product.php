<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="primary_block row clearfix">
		<?php
		$col_prod_sidebar = 0;
		$col_pro_img = 5;
		$col_pro_summary = 7;
		if ( is_active_sidebar( 'product-sidebar' ) ) :
			$col_prod_sidebar = 3;
			$col_pro_img = 4;
			$col_pro_summary = 5;
		endif;
		?>
		<div class="entry-img col-md-<?php echo $col_pro_img; ?> col-sm-6">
			<div class="inner">
		<?php
			/**
			 * woocommerce_before_single_product_summary hook
			 *
			 * @hooked woocommerce_show_product_sale_flash - 10
			 * @hooked woocommerce_show_product_images - 20
			 */
			do_action( 'woocommerce_before_single_product_summary' );
		?>
			</div>
		</div>
		<div class="summary entry-summary col-md-<?php echo $col_pro_summary; ?> col-sm-6">

			<?php
				/**
				 * woocommerce_single_product_summary hook
				 *
				 * @hooked woocommerce_template_single_title - 5
				 * @hooked woocommerce_template_single_rating - 10
				 * @hooked woocommerce_template_single_price - 10
				 * @hooked woocommerce_template_single_excerpt - 20
				 * @hooked woocommerce_template_single_add_to_cart - 30
				 * @hooked woocommerce_template_single_meta - 40
				 * @hooked woocommerce_template_single_sharing - 50
				 */
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
				add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 6);
				do_action( 'woocommerce_single_product_summary' );
			?>

		</div><!-- .summary -->
		<?php if( $col_prod_sidebar > 0): ?>
		<div class="minner-sidebar col-md-<?php echo $col_prod_sidebar; ?> col-sm-12">
			<?php dynamic_sidebar( 'product-sidebar' ); ?>
		</div>
		<?php endif; ?>
	</div>
	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		//remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
		//remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
		//remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
		do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
