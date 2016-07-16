<?php
global $snsnova_obj;
$wclass = '';
if ( $snsnova_obj->snsnova_getOption('blog_class') ) {
	$wclass = $snsnova_obj->snsnova_getOption('blog_class');
}
// Page title
snsnova_pagetitle();
?>
<div class="blog-standard posts<?php echo esc_attr($wclass);?>">
<?php 
// Theloop
while ( have_posts() ) : the_post();
    get_template_part( 'framework/tpl/posts/post', get_post_format() );
endwhile;
// Paging
get_template_part('tpl-paging');
?>
</div>