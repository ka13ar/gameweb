<?php // Template Name: Side page(demo shortcodes) ?>
<?php get_header(); ?>
<!-- Content -->
<div id="sns_content">
	<div class="container">
		<div class="row sns-content">
			<!-- left sidebar -->
			<div class="col-md-3 sns-left">
				<h3 class='sidebar-shortcodes'><span><?php echo esc_html__('Shortcodes Demo','snsnova'); ?></span></h3>
			    <ul class="side-navigation">
	            <?php 	
					$post_ancestors = get_post_ancestors($post->ID);
					$post_parent = end($post_ancestors);
					if($post_parent) {
						echo wp_list_pages("title_li=&child_of=".esc_attr($post_parent)."&echo=0");
					} else {
						echo wp_list_pages("title_li=&child_of=".esc_attr($post->ID)."&echo=0");
					}
					?>
	            </ul>
			</div>
			<!-- Main content -->
			<div class="col-md-9 sns-main">
			    <?php
			    if ( have_posts() ) :
			        get_template_part( 'framework/tpl/page/content' );
			    else:
			        get_template_part( 'content', 'none' );
			    endif; ?>
			</div>
		</div>
	</div>
</div>
<!-- End Content -->
<?php get_footer(); ?>