<?php 
global $snsnova_opt;
?>
<div id="sns_footer" class="sns-footer two-col">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<?php 
				if ( has_nav_menu( 'footer_navigation' ) ) :
	                wp_nav_menu( array(
	                        'theme_location'    => 'footer_navigation',
	                        'depth'             => 1,
	                        'container'         => 'div',
	                        'container_class'   => 'sns-info clearfix',
	                        'menu_class'        => 'links',
	                        'menu_id'			=> 'footer_links'
	                ));
	            endif;
	            ?>
				<div class="sns-copyright">
					<?php echo ( isset($snsnova_opt['copyright']) && $snsnova_opt['copyright'] !='' ) ? wp_kses($snsnova_opt['copyright'], array(
											'a' => array(
												'href' => array(),
												'class' => array(),
												'data-original-title' => array(),
												'data-toggle' => array(),
												'title' => array()
											),
											)) : esc_html__('Designed by SNSTheme.Com.', 'snsnova'); ?>
				</div>
			</div>
			<?php if ( isset($snsnova_opt['payment_img']['url']) && $snsnova_opt['payment_img']['url'] != '' ) : ?>
			<div class="col-md-6">
				<div class="payment-logo">
					<div class="inner">
						<img src="<?php echo esc_attr($snsnova_opt['payment_img']['url']); ?>" alt="<?php echo esc_html__('Payment method', 'snsnova'); ?>"/>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>