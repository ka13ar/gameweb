<?php
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

global $woocommerce;
// Array tabs title
$tab_titles = $this->snsnova_getListTabTitle();

$uq = rand().time();
$class = 'sns-product-tabs woocommerce template-'.esc_attr($template);
if( $template == 'carousel' ) $class .= ' pre-load';
$class .= ( trim(esc_attr($extra_class))!='' )?' '.esc_attr($extra_class):'';
$class .= esc_attr($this->getCSSAnimation( $css_animation ));
ob_start();
?>
<div id="sns_product_tabs<?php echo $uq;?>" class="<?php echo $class; ?>">
<?php if( class_exists('WooCommerce') ){ ?>
	<?php if ($title !='' ) : ?>
	<h2 class="wpb_heading"><span><?php echo esc_attr($title); ?></span></h2>
	<?php endif; ?>
	<ul class="nav-tabs gfont">
		<?php
		$i = 0;
		foreach ($tab_titles as $tab) { 
			$i++;
			if ( $i == 1) $class = 'nav-item first';
			else $class = 'nav-item';
		?>
		<li class="<?php echo $class; ?>"><a href="#producttabs_<?php echo esc_attr($tab['name']); ?>" title="<?php echo esc_attr($tab['title']); ?>"><?php echo esc_html($tab['short_title']); ?></a></li>
		<?php
		}
		?>
	</ul>
	<ul>
	    <li class="dropdown pull-left tabdrop" style="display:none;" >
	        <a href="#" data-toggle="dropdown" class="dropdown-toggle">
	            <span class="display-tab"><i class="fa fa-align-justify"></i></span>
	        </a>
	        <ul class="dropdown-menu gfont">
	            <?php
	            foreach ($tab_titles as $tab) { 
	                ?>
	                <li class="nav-item">
	                    <a href="#drop_producttabs_<?php echo esc_attr($tab['name']); ?>" title="<?php echo esc_attr($tab['title']); ?>"><?php echo esc_html($tab['short_title']); ?></a>
	                </li>
	            <?php } ?>
	        </ul>
	    </li>
	</ul>
	<?php if( $pretext !='' ) ?>
	<div class="pretext"><div><?php echo esc_attr($pretext) ?></div></div>
	<?php ?>
	<div class="tab-content">
	<?php
	if ($template == 'grid') :
		$number_query = $row*$col;
	else:
		$number_query = $number_limit;
	endif;
	if ($tab_types == 'category') :
		if( empty($list_cat) ){
			$cats = $this->snsnova_getCats();
		}else{
			$cats = explode(',', $list_cat);
		}
		$ii = 0;
		foreach ($cats as $cat) { ?>
			<div id="producttabs_<?php echo esc_attr($cat); ?>" class="tab-pan fade">
			<?php
				//if( $ii == 0):
					$loop = snsnova_woo_query($orderby, $number_query, $cat);
					if ($template == 'grid') :
						?>
						<ul class="products product_list grid <?php echo esc_attr($effect_load); ?>">
						<?php
						while ( $loop->have_posts() ) : $loop->the_post();
				        	wc_get_template( 'vc/item-grid.php', array('col' => $col) );
				    	endwhile;
				    	?>
				    	</ul>
				    	<div class="sns-woo-loadmore-wrap">
				    		<div id="sns_woo_loadmore_<?php echo $cat.'_'.$uq; ?>" class="sns-woo-loadmore btn gfont"
				    			data-numberquery="<?php echo esc_attr($number_load);?>"
				    			data-start="<?php echo esc_attr($number_query); ?>"
				    			data-order="<?php echo esc_attr($orderby); ?>"
				    			data-cat="<?php echo esc_attr($cat); ?>"
				    			data-col="<?php echo esc_attr($col); ?>"
				    			data-type='cat'
				    			data-loadtext="<?php echo esc_html__('Load more', 'snsnova'); ?>"
				    			data-loadingtext="<?php echo esc_html__('Loading...', 'snsnova'); ?>"
				    			data-loadedtext="<?php echo esc_html__('All ready', 'snsnova'); ?>">
				    			<?php echo esc_html__('Load more', 'snsnova'); ?>
				    		</div>
				    	</div>
				    	<?php
				    else:
				    	wc_get_template( 'vc/carousel.php', array('loop' => $loop, 'number_display' => intval($number_display), 'number_limit' => intval($number_limit), 'effect_load' => esc_attr($effect_load), 'id' => 'producttabs_'.esc_attr($cat)) );
				    endif;
				// endif;
				// $ii++;
		    ?>
			</div>
	    <?php
		}
	else:
		$orderbys = explode(',', $list_orderby);
		$ii = 0;
		foreach ($orderbys as $orderby) { ?>
			<div id="producttabs_<?php echo $orderby; ?>" class="tab-pan fade">
			<?php
				//if( $ii == 0):
					$loop = snsnova_woo_query($orderby, $number_query, $list_cat);
					if ($template == 'grid') :
						?>
						<ul class="products product_list grid <?php echo esc_attr($effect_load); ?>">
						<?php
						while ( $loop->have_posts() ) : $loop->the_post();
				        	wc_get_template( 'vc/item-grid.php', array('col' => $col) );
				    	endwhile;
				    	?>
				    	</ul>
				    	<div class="sns-woo-loadmore-wrap">
				    		<div id="sns_woo_loadmore_<?php echo $orderby.'_'.$uq; ?>" class="sns-woo-loadmore btn gfont"
				    			data-numberquery="<?php echo esc_attr($number_load);?>"
				    			data-start="<?php echo esc_attr($number_query); ?>"
				    			data-order="<?php echo esc_attr($orderby); ?>"
				    			data-cat="<?php echo esc_attr($list_cat); ?>"
				    			data-col="<?php echo esc_attr($col); ?>"
				    			data-type='order'
				    			data-loadtext="<?php echo esc_html__('Load more', 'snsnova'); ?>"
				    			data-loadingtext="<?php echo esc_html__('Loading...', 'snsnova'); ?>"
				    			data-loadedtext="<?php echo esc_html__('All ready', 'snsnova'); ?>">
				    			<?php echo esc_html__('Load more', 'snsnova'); ?>
				    		</div>
				    	</div>
				    	<?php
			    	else:
			    	?>
			    	<div class="navslider" style="display:none"><span class="prev"><i class="fa fa-arrow-left"></i></span><span class="next"><i class="fa fa-arrow-right"></i></span></div>
			    	<?php
				    	wc_get_template( 'vc/carousel.php', array('loop' => $loop, 'number_display' => intval($number_display), 'number_limit' => intval($number_limit), 'effect_load' => esc_attr($effect_load), 'id' => 'producttabs_'.esc_attr($orderby)) );
				 	endif;
				//endif;
				//$ii++;
		    ?>
			</div>
	    <?php
		}
	endif; 
	?>
	</div>
	<script>
		jQuery(document).ready(function($){
			// Only handle preload for template is carousel
			$('#sns_product_tabs<?php echo $uq;?>.template-carousel').removeClass('pre-load');
			// Tab
			$('#sns_product_tabs<?php echo $uq;?> .nav-tabs').find("li").first().addClass("active");
			$('#sns_product_tabs<?php echo $uq;?> ul.dropdown-menu').find("li").first().addClass("active");
			// Tab content
			$('#sns_product_tabs<?php echo $uq;?> .tab-content .tab-pan').css({'overflow':'hidden', 'height':'0'});
			$('#sns_product_tabs<?php echo $uq;?> .tab-content').find(".tab-pan").first().addClass("active in").css({'overflow':'', 'height':''});
			// Handle click
			$('#sns_product_tabs<?php echo $uq;?> .nav-tabs > li, '+ '#sns_product_tabs<?php echo $uq;?> ul.dropdown-menu > li').click(function(e){
				e.preventDefault();
				if( !$(this).hasClass('active') ){
					id = $(this).find('a').attr('href');
					// lazyload
					if( $('body').hasClass('use_lazyload') ){
						var timeout = setTimeout(function() {
					        $(id + " img.lazy:not(.loaded)").trigger("appear")
					    }, 2000);
					}
					// Tab
					$('#sns_product_tabs<?php echo $uq;?> .nav-tabs li').removeClass('active');
					$('#sns_product_tabs<?php echo $uq;?> ul.dropdown-menu li').removeClass('active');
					$(this).addClass('active');
					if( id.indexOf('drop_') == 1){
						id = id.replace('drop_', '');
						$('#sns_product_tabs<?php echo $uq;?> .nav-tabs li').each(function(){
							if ( $(this).find('a').attr('href') == id ) $(this).addClass('active');
						})	
					}else{
						$('#sns_product_tabs<?php echo $uq;?> ul.dropdown-menu li').each(function(){
							if ( $(this).find('a').attr('href').replace('drop_', '') == id ) $(this).addClass('active');
						})
					}
					// Tab content
					$('#sns_product_tabs<?php echo $uq;?> .tab-pan').removeClass('active').removeClass('in').css({'overflow':'hidden', 'height':'0'});
					$('#sns_product_tabs<?php echo $uq;?>').find(id).addClass('active').addClass('in').css({'overflow':'', 'height':''});

					// Reset effect
	            	SnsJsWoo.resetAnimate($(this));
					return false;
				}

			});
	   	});
	</script>
<?php } ?>
</div>
<?php
$output .= ob_get_clean();
wp_reset_postdata();
echo $output;
