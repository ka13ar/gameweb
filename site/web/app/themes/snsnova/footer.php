<?php
global $snsnova_opt, $snsnova_obj;
?>
	<?php if( is_active_sidebar( 'Footer Widgets' ) ) : ?>
	<div id="sns_footer_middle" class="wrap">
		<div class="container">
			<div class="row">
	          <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Widgets')) : ?><?php endif; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
    <?php
    $flayout = '';
    if( $snsnova_opt['footer_layout'] ){
    	$flayout = $snsnova_opt['footer_layout'];
    }
    if( $snsnova_obj->snsnova_metabox('snsnova_footer_layout')!='' ){
    	$flayout = $snsnova_obj->snsnova_metabox('snsnova_footer_layout');
    }
    if( $flayout == 'two-col' ){
    	get_template_part( 'tpl-footer', 'two-col' );
    }else{
    	get_template_part( 'tpl-footer', '' );
    }
    ?>
    <?php if ( $snsnova_opt['advance_scrolltotop'] == 1 || $snsnova_opt['advance_cpanel'] == 1 ) : ?>
    <div id="sns_tools">
    	<?php 
		if ( $snsnova_opt['advance_scrolltotop'] == 1 ) : 
			get_template_part( 'tpl-scrolltotop');
		endif;
		if ( $snsnova_opt['advance_cpanel'] == 1 ) : 
			get_template_part( 'tpl-cpanel');
		endif;
		?>
	</div>
	<?php endif; ?>
</div>
<!-- end main container -->

<?php wp_footer(); ?>
</body>
</html>