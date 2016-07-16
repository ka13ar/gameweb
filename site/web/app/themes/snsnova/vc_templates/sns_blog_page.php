<?php
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

global $post, $snsnova_opt;
$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
if(!$page) $page = 1;
$args = array(
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => (int)$posts_per_page,
	'paged'          => $page,
);
 
if(!empty($category)){
	$cat_id = explode(',', $category );
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'category',
			'field' => 'slug',
			'terms' => $cat_id
		)
	);
}
$extra_class = (trim($extra_class) != '') ? ' '.esc_attr($extra_class) : '';
query_posts($args);
if( have_posts() ) :
	$snsnova_opt['blog_class'] = ' sns-blog-page'.$extra_class;
	$snsnova_opt['excerpt_length'] = $excerpt_length;
	$snsnova_opt['enable_readmore'] = $enable_readmore;
	$snsnova_opt['show_categories'] = $show_categories ;
	$snsnova_opt['show_author'] = $show_author;
	$snsnova_opt['show_date'] = $show_date;
	$snsnova_opt['img_size'] = $img_size;

	ob_start();
	get_template_part( 'framework/tpl/blog/blog', $blog_type );
	$output .= ob_get_clean();
endif;
wp_reset_query();
echo $output;
