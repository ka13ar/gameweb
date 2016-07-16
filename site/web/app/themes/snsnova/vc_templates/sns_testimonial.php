<?php
/*
* SNS Testmonial
*/

$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$class = 'sns-testimonial';
$class .= ( trim(esc_attr($extra_class))!='' )?' '.esc_attr($extra_class):'';
$class .= esc_attr($this->getCSSAnimation( $css_animation ));
$uq = rand().time();
$args = array(
	'post_type' => 'testimonial',
	'posts_per_page' => -1
);
$brand = new WP_Query($args);

if ( $brand->have_posts() ) :
	ob_start();
?>
	<div id="sns_testimonial<?php echo $uq; ?>" class="<?php echo $class; ?>">
		<div class="icon">
			<span>
			<?php if( $icon_type == 'image') :
			$icon_image = wp_get_attachment_image_src( preg_replace( '/[^\d]/', '', $icon_image ), 'full' );
			if ( ! empty( $icon_image[0] ) ) {
				$icon_image = $icon_image[0];
			}
			?>
			<img src="<?php echo esc_attr($icon_image); ?>" alt=""/>
			<?php else : ?>
			<i class="<?php echo esc_attr($icon_fontawesome); ?>"></i>
			<?php endif; ?>
			</span>
		</div>
		<?php if ( $title !='' ): ?>
		<h2 class="wpb_heading"><?php echo esc_attr($title); ?></h2>
		<?php endif; ?>
		<!-- <div class="navslider" style="display:none">
			<span class="prev"><i class="fa fa-arrow-left"></i></span>
			<span class="next"><i class="fa fa-arrow-right"></i></span>
		</div> -->
		<div class="testimonial-content">
			<ul class="clearfix">
				<?php 
				while ( $brand->have_posts() ) : $brand->the_post(); ?>
				<li>
					<div class="quote-content"><?php the_content(); ?></div>
					<div class="name">-- <?php the_title(); ?> --</div>
				</li>
				<?php 
				endwhile;?>
			</ul>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('#sns_testimonial<?php echo $uq;?> ul').owlCarousel({
					items: 1,
					loop:true,
		            dots: true,
		            nava: false,
		            // animateOut: 'flipInY',
				    //animateIn: 'pulse',
				    autoplay: true,
		            // onInitialized: callback,
		            slideSpeed : 800
				});
			});
		</script>
	</div>
<?php
$output .= ob_get_clean();
echo $output;
wp_reset_postdata();
endif; 

?>