<?php
global $snsnova_obj;
$lclass = '';
$rclass = '';
$mclass = '';
$hasL = 0;
$hasR = 0;
if( is_product() ){
	$mclass = 'col-md-12';
}else{
	if ( $snsnova_obj->snsnova_metabox('snsnova_layouttype') == '' || $snsnova_obj->snsnova_metabox('snsnova_layouttype') == 'l-m'){
	    $lclass .= 'col-md-3';
	    $mclass = 'col-md-9';
	    $hasL = 1;
	}elseif( $snsnova_obj->snsnova_metabox('snsnova_layouttype') == 'm-r' ){
	    $rclass .= 'col-md-3';
	    $mclass = 'col-md-9';
	    $hasR = 1;
	}elseif( $snsnova_obj->snsnova_metabox('snsnova_layouttype') == 'l-m-r' ){
	    $lclass .= 'col-md-3';
	    $rclass .= 'col-md-3';
	    $mclass = 'col-md-6';
	    $hasL = 1;
	    $hasR = 1;
	}else{
	    $mclass = 'col-md-12';
	}
}
?>
<?php get_header(); ?>
<!-- Content -->
<div id="sns_content">
	<div class="container">
		<div class="row sns-content">
			<?php if ($hasL == 1) :?>
			<!-- left sidebar -->
			<div class="<?php echo esc_attr($lclass); ?> sns-left">
			    <?php
			    if( $snsnova_obj->snsnova_metabox('snsnova_leftsidebar')!= '' && is_active_sidebar( $snsnova_obj->snsnova_metabox('snsnova_leftsidebar') ) ){
			        dynamic_sidebar( $snsnova_obj->snsnova_metabox('snsnova_leftsidebar') );
			    }else{
			        dynamic_sidebar( 'woo-sidebar' );
			    }
			    ?>
			</div>
			<?php endif; ?>
			<!-- Main content -->
			<div class="<?php echo esc_attr($mclass); ?> sns-main">
			    <?php
		    	if( is_product() ){
					wc_get_template( 'single-product.php' );
				}else{
					wc_get_template( 'listing-product.php' );
				}
				?>
			</div>
			<?php if ($hasR == 1): ?>
			<!-- Right sidebar -->
			<div class="<?php echo esc_attr($rclass); ?> sns-right">
			    <?php 
			    if( $snsnova_obj->snsnova_metabox('snsnova_rightsidebar')!= '' && is_active_sidebar( $snsnova_obj->snsnova_metabox('snsnova_rightsidebar') ) ){
			        dynamic_sidebar( $snsnova_obj->snsnova_metabox('snsnova_rightsidebar') );
			    }else{
			        dynamic_sidebar( 'woo-sidebar' );
			    }
			    ?>
			</div>
			<?php endif ?>
		</div>
	</div>
</div>
<!-- End Content -->
<?php get_footer(); ?>