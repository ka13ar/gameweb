<?php
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

global $post;
if( $template == '2' ){
	$limit = $number_limit;
}else{
	$limit = $num_display;
}
$args = array(
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => (int)$limit
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
$uq = rand().time();

$lp_query = new WP_Query( $args );
$class = 'sns-blog-list';
$class .= ($type == '0')?' show-img-ontop':'';
$class .= ( trim($extra_class)!='' )?' '.esc_attr($extra_class):'';
$class .= esc_attr($this->getCSSAnimation( $css_animation ));
//query_posts($args);
if( $lp_query->have_posts() ) :
	$output .= '<div id="sns_bloglist'.$uq.'" class="'.$class.'">';
	$output .= '<h2 class="wpb_heading">'.esc_attr( $title ).'</h2>';
	if( $template == '2' ) $output .= '<div class="navslider" style="display:none"><span class="prev"><i class="fa fa-arrow-left"></i></span><span class="next"><i class="fa fa-arrow-right"></i></span></div>';
	if( $type == 0 ) $output .= '<div class="pretext"><span>'. esc_html($pretext) .'</span></div>';
	$output .= '<div class="blist-content"><ul class="latest-posts style'.esc_attr( $type ).' image-size-'.esc_attr( $img_size ).' clearfix">';
	$i = 0;

	while ( $lp_query->have_posts() ) : $lp_query->the_post();
 		$num_comments = get_comments_number(); // get_comments_number returns only a numeric value
 		$comments ='';
  		if ( comments_open() ) {
  			if ( $num_comments == 0 ) {
     			$comments = esc_html__('No Comment','snsnova');
  			} elseif ( $num_comments > 1 ) {
     			$comments = $num_comments . esc_html__(' Comments','snsnova');
     		} else {
    			$comments = esc_html__('1 Comment','snsnova');
  			} 
  		}
  		if( $template == '2' ){
  			if( $type != 0 ){
  				if( $i % $num_display == 0 ) $output .= '<li>';
  				$output .= '<div class="latest-posts-item clearfix">';
  			}else{
  				$output .= '<li class="latest-posts-item clearfix">';
  			}
  			
  		}else{
  			$output .= '<li class="latest-posts-item clearfix">';
  		}
		if( $type == 0){ // Images on top
  			if( has_post_thumbnail() ){
  				$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID) , 'snsnova_bloglist_thumb' );
  				$output .= '<div class="image"><a href="'. esc_url( get_permalink() ) . '"><img src="'.$src[0].'" alt="'.esc_attr(get_the_title()).'" /></a></div>';
  			}
  			$output .= '<div class="latest-posts-content"><h3><a class="title" href="'. esc_url( get_permalink() ) .'">'.esc_html(get_the_title()).'</a></h3>';
  			if( intval($excerpt_length) > 0){
  				$output .= '<p class="excerpt">'.snsnova_limitwords(get_the_excerpt(),intval($excerpt_length)). '...</p>';
  			}
  			
  			$output .= '<div class="meta-info clearfix">';
  			$output .= '<div class="date"><i class="fa fa-calendar"></i><span>'. date_i18n(get_option('date_format') ,get_the_time('U')) .'</span></div>';
  			$output .= '<div class="blist-readmore"><a href="'.esc_url(get_permalink()).'" title="'.esc_attr(get_the_title()).'">'.esc_html__('Read more', 'snsnova' ).'</a></div>';
  			$output .= '</div>'; // .meta-info
  			
  			$output .= '</div>';
  		}
		elseif( $type == 1){
	  		if( has_post_thumbnail() ){
				
	  			$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID) , 'thumbnail' );
	  			$src2= wp_get_attachment_image_src( get_post_thumbnail_id($post->ID) , '');
		  		$output .= '<div class="image"><a href="'. esc_url( $src2[0] ) . '" rel="prettyPhoto[slides]" class="block prettyPhoto"><img src="'.esc_attr($src[0]).'" alt="'.esc_attr(get_the_title()).'" /></a></div>';
			}
	  		$output .= '<div class="latest-posts-content"><div><h5><a class="title" href="'. esc_url( get_permalink() ) .'">'.esc_html(get_the_title()).'</a></h5>';
	  		if( intval($excerpt_length) > 0){
		  		$output .= '<p class="excerpt">'.snsnova_limitwords(get_the_excerpt(),intval($excerpt_length)). '</p>';
	  		}	
	  		$output .= '</div></div>';			
		}else{
	  		$output .= '<div class="date"> <span class="month">'.get_the_time('M').'</span> <span class="day">'. get_the_time('d') .'</span> </div>';
	  		$output .= '<div class="latest-posts-content"><div><h5><a class="title" href="'. esc_url( get_permalink() ) .'">'.esc_html(get_the_title()).'</a></h5>';
		  	if( intval($excerpt_length) > 0){
				$output .= '<p class="excerpt">'.snsnova_limitwords(get_the_excerpt(),intval($excerpt_length)). '</p>';
		  	}			 
			$output .= '</div></div>';
		}
		if( $template == '2' && $type != 0){
			$output .= '</div>';
			if( ($i+1) % $num_display == 0 || ($i+1) == $lp_query->post_count ) $output .= '</li>';
  		}else{
  			$output .= '</li>';
  		}
  		$i++;
	endwhile;
	
	$output .= '</ul></div></div>'.$this->endBlockComment('Blog List')."\n";
	if( $template == '2' &&  $lp_query->post_count > $num_display){
		ob_start();
		?>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#sns_bloglist<?php echo $uq;?> ul').owlCarousel({
				<?php if( $type == '0' ){ ?>
				items: <?php echo $num_display; ?>,
				responsive : {
				    0 : { items: 1},
				    480 : { items:2 },
				    768 : { items: <?php echo ( ($num_display-2) > 2 ) ? $num_display-2 : 2 ; ?> },
				    992 : { items: <?php echo $num_display-1; ?> },
				    1200 : { items: <?php echo $num_display; ?> }
				},
				<?php }else{ ?>
				items: 1,
				<?php } ?>
				loop:true,
	            dots: false,
	            onInitialized: callback,
	            slideSpeed : 800
			});
			function callback(event) {
	   			if(this._items.length > this.options.items){
			        jQuery('#sns_bloglist<?php echo $uq; ?> .navslider').show();
			    }else{
			        jQuery('#sns_bloglist<?php echo $uq; ?> .navslider').hide();
			    }
			}
			jQuery('#sns_bloglist<?php echo $uq; ?> .navslider .prev').on('click', function(e){
				e.preventDefault();
				jQuery('#sns_bloglist<?php echo $uq; ?> ul').trigger('prev.owl.carousel');
			});
			jQuery('#sns_bloglist<?php echo $uq; ?> .navslider .next').on('click', function(e){
				e.preventDefault();
				jQuery('#sns_bloglist<?php echo $uq; ?> ul').trigger('next.owl.carousel');
			});
		});
		</script>
		<?php
		$output .= ob_get_clean();
	}
endif;
wp_reset_postdata();
echo $output;
