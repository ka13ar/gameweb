<?php global $snsnova_opt, $snsnova_obj, $woocommerce ;
?>
<!-- Top Header -->
<div class="wrap" id="sns_topheader">
	<div class="container">
		<div class="topheader-left">
			<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Topleft Widgets') ) :
			endif; ?>
		</div>
		<div class="topheader-right">
			<!-- Top Menu -->
			<?php
            if(has_nav_menu('top_navigation')): ?>
            <div class="sns-quickaccess">
				<div class="quickaccess-inner">
			<?php
	           wp_nav_menu( array(
	           				'theme_location' => 'top_navigation',
	           				'container' => false, 
	           				'menu_id' => 'top_navigation',
	           				'menu_class' => 'links'
	           			));
	        ?>
	        	</div>
	        </div>
	        <?php endif; ?>
			<!-- Settings -->
			<div class="sns-switch">
				<div class="switch-inner">
					<div class="currency-switcher">
						<div class="tongle">EUR</div>
						<ul id="select-currency">
							<li><a title="Dollar" href="#">USD</a></li>
							<li><span>EUR</span></li>
						</ul>
					</div>
					<div class="language-switcher">
						<div class="tongle"> <img alt="en" src="<?php echo THEME_URI.'/assets/img/en.jpg'?>"> <span>EN</span></div>
						<ul class="list-lang">
							<li><span class="current"><img src="<?php echo THEME_URI.'/assets/img/en.jpg'?>" alt="en"><span>EN</span></span></li>
							<li><a title="French" href="#"><img src="<?php echo THEME_URI.'/assets/img/fr.jpg'?>" alt="fr"><span>FR</span></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Header -->
<div class="wrap" id="sns_header">
	<div class="container">
		<div class="row">
			<div id="logo" class="col-sm-4">
				<?php 
				$logourl = THEME_URI.'/assets/img/logo.png';
				if ( $snsnova_opt['header_logo'] && $snsnova_opt['header_logo']['url'] !='' ){
					$logourl = $snsnova_opt['header_logo']['url'];
				}
				?>
				<a href="<?php echo esc_url( home_url('/') ) ?>" title="<?php bloginfo( 'sitename' ); ?>">
					<img src="<?php echo esc_attr($logourl); ?>" alt="<?php bloginfo( 'sitename' ); ?>"/>
				</a>
			</div>
			<div class="header-right col-sm-8">
				<div class="header-right-inner">
					<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Header Right') ) :
					endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Menu  -->
<?php 
$menu_bg = '';
if ( isset($snsnova_opt['menu_bg']['url']) && $snsnova_opt['menu_bg']['url'] !='' ) $menu_bg = $snsnova_opt['menu_bg']['url'];
//global $post; isset($post) &&
if ( $snsnova_obj->snsnova_metabox('snsnova_menubg') !='' ) :
	$m_imgs = $snsnova_obj->snsnova_metabox('snsnova_menubg', 'type=image_advanced');
	foreach ( $m_imgs as $m_img ) :
		$menu_bg = $m_img['full_url'];
	endforeach;
endif;
if ( $menu_bg != '') $menu_bg = ' style="background-image: url('.esc_attr($menu_bg).');"';
?>
<div<?php echo $menu_bg;?> id="sns_menu_wrap">
	<div class="wrap" id="sns_menu">
		<div class="container">
			<div class="inner">
				<div class="row">
					<div class="col-sm-10" id="sns_mainnav">
						<div class="visible-md visible-lg" id="sns_mainmenu">
							<?php
			                if(has_nav_menu('main_navigation')):
					           wp_nav_menu( array(
					           				'theme_location' => 'main_navigation',
					           				'container' => false, 
					           				'menu_id' => 'main_navigation',
					           				'walker' => new SnsNova_Megamenu_Front,
					           				'menu_class' => 'nav navbar-nav'
					           	) ); 
							else:
								echo '<p>'.esc_html__('Please sellect menu for Main navigation', 'snsnova').'</p>';
							endif;
							?>
						</div>
						<?php get_template_part('tpl-respmenu'); ?>
					</div>
					<div class="nav-right col-sm-2">
						<div class="header-right-inner">
							<?php 
							global $yith_woocompare;
							if ( isset($yith_woocompare) ) :
							?>
							<div class="block-compare yith-woocompare-widget">
								<a class="compare-toggle bt_compare compare" href="<?php echo esc_url( add_query_arg( array( 'iframe' => 'true' ), $yith_woocompare->obj->view_table_url() ) )?>">
									<span class="total-compare-val"><?php echo count($yith_woocompare->obj->get_products_list()); ?></span>
								</a>
								<div class="content"><div class="block-inner">
									<?php the_widget( 'SnsNova_Woocompare_Widget', 'title= ', array('before_title' => '', 'after_title' => '') ); ?>
								</div></div>
							</div>
							<?php endif; ?>
							<?php if ( class_exists('WooCommerce') ) : ?>
							<div class="mini-cart sns-ajaxcart">
								<div class="mycart mini-cart">
									<a title="View my shopping cart" class="tongle" href="<?php echo esc_url( $woocommerce->cart->get_cart_url() );?>">
										<i class="fa fa-shopping-cart"></i>
										<span class="number-item ajax_cart_quantity">
											<?php echo sizeof( WC()->cart->get_cart() ) ?>
										</span>
									</a>
									<?php if ( !is_cart() && !is_checkout() ) : ?>
									<div class="content"><div class="block-inner">
										<?php the_widget( 'WC_Widget_Cart', 'title= ', array('before_title' => '', 'after_title' => '') ); ?>
									</div></div>
									<?php endif; ?>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	$showbreadcrumbs = 1;
	if ( get_post_type( get_the_ID() ) == 'page' || get_post_type( get_the_ID() ) == 'product' ) :
		if ( is_home() || is_front_page() || ( $snsnova_obj->snsnova_metabox('snsnova_showbreadcrump') == '0' ) ) :
			$showbreadcrumbs = 0;
		endif;
	elseif ( get_post_type( get_the_ID() ) == 'post' ) :
		$showbreadcrumbs = 1;
	endif;
	?>
	<?php if ( $showbreadcrumbs == 1 && ( !is_front_page() || !is_home() ) ) : ?>
	<div id="sns_breadcrumbs" class="wrap">
		<div class="container">
			<?php if( !($snsnova_obj->snsnova_metabox('snsnova_showtitle') == '0') ): ?>
			<div id="sns_titlepage"></div>
			<?php endif; ?>
			<div id="sns_pathway" class="clearfix">
				<?php snsnova_breadcrumbs(); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php if ( ( is_page() || (function_exists('is_shop') && is_shop()) ) && $snsnova_obj->snsnova_metabox('snsnova_useslideshow') == 1 ): ?>
	<div id="sns_slideshow" class="wrap"><div class="container">
		<?php
			echo do_shortcode('[rev_slider '.esc_attr($snsnova_obj->snsnova_metabox('snsnova_revolutionslider')).' ]');
		?>
	</div></div>
	<?php endif; ?>
</div>


