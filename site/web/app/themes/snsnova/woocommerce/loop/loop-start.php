<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
global $snsnova_opt;

$class = 'grid';
$id = 'sns_woo_list';
if ( !is_product() && !is_cart() ) :
	//$class .=' row';
	$viewmode = $snsnova_opt['woo_list_modeview'];
	if (isset($_COOKIE['snsnova_woo_list_modeview']) && $_COOKIE['snsnova_woo_list_modeview']== 'grid') {
		$class = $class;
	}elseif (isset($_COOKIE['snsnova_woo_list_modeview']) && $_COOKIE['snsnova_woo_list_modeview']== 'list') {
	    $class = 'list';
	}else{
	    if ( $viewmode == 'grid' ){
	    	$class = $class;
	    }else{
	    	$class = 'list';
	    }
	}
else:
	$id .= rand();
endif;
$class .= ' row';
?>
<div class="prdlist-content">
	<ul id="<?php echo $id; ?>" class="products product_list <?php echo $class; ?>">