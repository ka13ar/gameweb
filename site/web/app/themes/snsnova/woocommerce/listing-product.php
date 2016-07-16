<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

	get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		add_action( 'woocommerce_before_main_content', 'wc_print_notices', 10 );
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

			<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

		<?php endif; ?>
		<?php 
		// add category image http://docs.woothemes.com/document/woocommerce-display-category-image-on-category-archive/
		?>
		<?php do_action( 'woocommerce_archive_description' ); ?>

		<?php if ( have_posts() ) : ?>
			<ul class="woo-sub-cat row">
				<?php woocommerce_product_subcategories(); ?>
			</ul>
			<?php if ( have_posts() ) : ?>
			<div class="toolbar toolbar-top">
			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
				add_action( 'woocommerce_before_shop_loop', 'snsnova_woo_modeview', 1 );
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
				add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 41 );
				add_action( 'woocommerce_before_shop_loop', 'woocommerce_pagination', 40 );
				do_action( 'woocommerce_before_shop_loop' );
			?>
			</div>
			<?php woocommerce_product_loop_start(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>
			<div class="toolbar toolbar-bottom">
			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				add_action( 'woocommerce_after_shop_loop', 'snsnova_woo_modeview', 1 );
				add_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 11 );
				add_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 2 );
				do_action( 'woocommerce_after_shop_loop' );
			?>
			</div>
			<?php endif; ?>
		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

<?php //get_footer( 'shop' ); ?>
