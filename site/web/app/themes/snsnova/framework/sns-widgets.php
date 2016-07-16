<?php
class SnsNova_Widget_Facebook extends WP_Widget {
	public function __construct() {
		$widget_ops = array('description' => esc_html__( 'Display your facebook like box', 'snsnova' ) );
		parent::__construct('sns_facebook', esc_html__('SNS Facebook', 'snsnova'), $widget_ops);
	}
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		echo wp_kses( $args['before_widget'], array(
				                                'aside' => array(
				                                    'id' => array(),
				                                    'class' => array()
				                                ),
				                            ) );
		if ( $title ) echo wp_kses( $args['before_title'] . $title . $args['after_title'], array(
				                                'h3' => array(
				                                    'class' => array()
				                                ),
				                                'h4' => array(
				                                    'class' => array()
				                                ),
				                                'span' => array(
				                                    'class' => array()
				                                ),
				                            ) );

		$fanpageName = empty( $instance['fanpageName'] ) ? 'snstheme' : esc_html($instance['fanpageName']);
		?>
		<div class="content">
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.4";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-page" 
			data-href="https://www.facebook.com/<?php echo esc_html($fanpageName); ?>" 
			data-small-header="true" 
			data-adapt-container-width="true" 
			data-hide-cover="false" 
			data-show-facepile="true" 
			data-show-posts="false">
				<div class="fb-xfbml-parse-ignore">
					<blockquote cite="https://www.facebook.com/<?php echo esc_html($fanpageName); ?>">
						<a href="https://www.facebook.com/<?php echo esc_html($fanpageName); ?>"><?php echo esc_html($fanpageName); ?></a>
					</blockquote>
				</div>
			</div>
		</div>
		<?php
		echo wp_kses( $args['after_widget'], array(
				                                'aside' => array()
				                            ) );
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 
			'title' => 'SNS Facebook',
			'fanpageName' => 'snstheme',
			'numberDisplay' => '12',
		));
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['fanpageName'] = strip_tags($new_instance['fanpageName']);
		$instance['numberDisplay'] = strip_tags($new_instance['numberDisplay']);
		return $instance;
	}
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => 'SNS Facebook',
			'fanpageName' => 'snstheme',
			'numberDisplay' => '12',
		) );
		$title = $instance['title'];
		$fanpageName = $instance['fanpageName'];
		$numberDisplay = $instance['numberDisplay'];
?>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
			<?php esc_html_e('Title:', 'snsnova'); ?>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</label>
	</p>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('fanpageName')); ?>">
			<?php esc_html_e('Fanpage Name:', 'snsnova'); ?>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('fanpageName')); ?>" name="<?php echo esc_attr($this->get_field_name('fanpageName')); ?>" type="text" value="<?php echo esc_attr($fanpageName); ?>" />
		</label>
	</p>
<?php
	}
}

class SnsNova_Widget_Recent_Post extends WP_Widget {
	
	function __construct(){
		$widget_ops = array('description' => esc_html__( 'Display recent posts', 'snsnova' ) );
		parent::__construct('sns_recentpost', esc_html__('SNS Recent Post', 'snsnova'), $widget_ops);
	}
	
	function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$number = esc_attr($instance['number']);
		
		echo wp_kses( $before_widget, array(
				                                'aside' => array(
				                                	'class' => array()
				                                )
				                            ) );

		if($title) {
			echo wp_kses( $before_title . $title . $after_title, array(
				                                'h3' => array(
				                                    'class' => array()
				                                ),
				                                'h4' => array(
				                                    'class' => array()
				                                ),
				                                'span' => array(
				                                    'class' => array()
				                                ),
				                            ) );
		}
		?>
		<?php
		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => $number ,
			'order'          => 'DESC',
		    'orderby'        => 'date',
		    'ignore_sticky_posts' => true,
		    'post_status'    => 'publish'
		);
		$recent_posts = new WP_Query($args);
		if($recent_posts->have_posts()):
		?>
        <ul class="widget-posts">
		<?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
	        <li class="post-item">
			<?php if(has_post_thumbnail()): ?>
			<a class="post-img" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		    <?php the_post_thumbnail('thumbnail'); ?>
			</a>
			<?php endif; ?>
	        <div><a class="post-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title() ?></a></div>
	        <span class="post-date"><?php echo get_the_date();?></span>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
        </ul>
		<?php endif; ?>
     
		<?php echo wp_kses( $after_widget, array(
				                                'aside' => array()
				                            ) );
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;

		$instance['title'] = esc_attr($new_instance['title']);
		$instance['number'] = esc_attr($new_instance['number']);
		
		return $instance;
	}

	function form($instance){
		$defaults = array('title' => 'Recent posts', 'number' => 4);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'snsnova'); ?></label>
			<input class="widefat" type="text"  id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of items to show:', 'snsnova'); ?></label>
			<input class="widefat" type="text"  id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($instance['number']); ?>" />
		</p>
	<?php
	}
}

class SnsNova_Widget_Twitter extends WP_Widget {
	public function __construct() {
		$widget_ops = array('description' => esc_html__( 'Display your tweets', 'snsnova' ) );
		parent::__construct('sns_twitter', esc_html__('SNS Twitter', 'snsnova'), $widget_ops);
	}
	public function widget( $args, $instance ) {
   		wp_enqueue_script('snsnova-twitterjs', THEME_URI . '/assets/js/twitterfetcher.min.js', array(), '', true );
   		
		$title 			= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$widgets_id 	= isset($instance['widgets_id']) ? $instance['widgets_id'] : '420187988887212033';
		$limit 			= isset($instance['limit']) ? $instance['limit'] : 3;
		$follow_link 	= isset($instance['follow_link']) ? $instance['follow_link'] : true;
		$account_name 	= isset($instance['account_name']) ? $instance['account_name'] : 'snstheme';
		$avartar 		= isset($instance['avartar']) ? $instance['avartar'] : true;
		$interact_link 	= isset($instance['interact_link']) ? $instance['interact_link'] : true;
		$date 			= isset($instance['date']) ? $instance['date'] : true;
		
		$uq = rand().time();
		$class  = "";
		$class .= ($avartar)?'':' no-avartar';
		$class .= ($follow_link)?'':' no-follow-link';
		$class .= ($interact_link)?'':' no-interact-link';
		$class .= ($date)?'':' no-date';
		
		echo wp_kses( $args['before_widget'], array(
				                                'aside' => array(
				                                	'class' => array(),
				                                	'id' => array()
				                                )
				                            ) );
		if ( $title ) echo wp_kses( $args['before_title'] . $title . $args['after_title'], array(
				                                'h3' => array(
				                                    'class' => array()
				                                ),
				                                'h4' => array(
				                                    'class' => array()
				                                ),
				                                'span' => array(
				                                    'class' => array()
				                                ),
				                            ) );
		
		if($follow_link && $account_name != ''){ ?>
		<a class="follow-link" href="http://twitter.com/follow?user=<?php echo esc_attr($account_name); ?>">
			<span><?php echo esc_html__("Follow", "snsnova"); ?></span>
		</a>
		<?php
		}
		?>
		<div class="content">
			<div id="sns_twitter_<?php echo $uq; ?>" class="sns-tweets <?php echo esc_attr($class); ?>"></div>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					var config = {
					  "id": '<?php echo $widgets_id; ?>',
					  "domId": '',
					  "maxTweets": <?php echo $limit; ?>,
					  "enableLinks": true,
					  "showUser": <?php echo $avartar; ?>,
					  "showTime": <?php echo $date; ?>,
					  "dateFunction": '',
					  "showRetweet": false,
					  "customCallback": handleTweets,
					  "showInteraction": <?php echo $interact_link; ?>
					};

					function handleTweets(tweets) {
					    var x = tweets.length;
					    var n = 0;
					    var element = document.getElementById('sns_twitter_<?php echo $uq; ?>');
					    var html = '<ul>';
					    while(n < x) {
					      html += '<li>' + tweets[n] + '</li>';
					      n++;
					    }
					    html += '</ul>';
					    element.innerHTML = html;
					}

					twitterFetcher.fetch(config);
				});
			</script>
		</div>
		<?php
		
		echo wp_kses( $args['after_widget'], array(
				                                'aside' => array()
				                            ) );
	}
	public function update( $new_instance, $old_instance ) {
		$new_instance = (array) $new_instance;
		$instance = array( 
			'follow_link' => 0,
			'avartar' => 0,
			'interact_link' => 0,
			'date' => 0,
		);
		foreach ( $instance as $field => $val )
			if ( isset($new_instance[$field]) )
				$instance[$field] = 1;

		$instance['title'] = ! empty( $new_instance['title'] ) ? $new_instance['title'] : 'SNS Twitter';
		$instance['widgets_id'] = ! empty( $new_instance['widgets_id'] ) ? $new_instance['widgets_id'] : '420187988887212033';
		$instance['limit'] = ! empty( $new_instance['limit'] ) ? intval( $new_instance['limit'] ) : 3;
		$instance['account_name'] = ! empty( $new_instance['account_name'] ) ? $new_instance['account_name'] : 'snstheme';

		return $instance;
	}
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => 'SNS Twitter',
			'widgets_id' => '420187988887212033',
			'limit' => 3,
			'follow_link' => true,
			'account_name' => 'snstheme',
			'avartar' => true,
			'interact_link' => true,
			'date' => true
		) );
		$title = $instance['title'];
		$widgets_id = $instance['widgets_id'];
		$limit = $instance['limit'];
		$follow_link = $instance['follow_link'];
		$account_name = $instance['account_name'];
		$avartar = $instance['avartar'];
		$interact_link = $instance['interact_link'];
		$date = $instance['date'];
?>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
			<?php esc_html_e('Title:', 'snsnova'); ?>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</label>
	</p>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('widgets_id')); ?>">
			<?php esc_html_e('Widgets Id:', 'snsnova'); ?>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('widgets_id')); ?>" name="<?php echo esc_attr($this->get_field_name('widgets_id')); ?>" type="text" value="<?php echo esc_attr($widgets_id); ?>" />
		</label>
	</p>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('limit')); ?>">
			<?php esc_html_e('Tweets Count:', 'snsnova'); ?>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('limit')); ?>" name="<?php echo esc_attr($this->get_field_name('limit')); ?>" type="text" value="<?php echo esc_attr($limit); ?>" />
		</label>
	</p>
	<p>
		<input class="checkbox" type="checkbox" <?php checked($instance['follow_link'], true) ?> id="<?php echo esc_attr($this->get_field_id('follow_link')); ?>" name="<?php echo esc_attr($this->get_field_name('follow_link')); ?>" />
		<label for="<?php echo esc_attr($this->get_field_id('follow_link')); ?>"><?php esc_html_e('Show follow link', 'snsnova'); ?></label>
	</p>
	<p>
		<label for="<?php echo esc_attr($this->get_field_id('account_name')); ?>">
			<?php esc_html_e('Account Name:', 'snsnova'); ?>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('account_name')); ?>" name="<?php echo esc_attr($this->get_field_name('account_name')); ?>" type="text" value="<?php echo esc_attr($account_name); ?>" />
		</label>
	</p>
	<p style="margin: 0;">
		<input class="checkbox" type="checkbox" <?php checked($instance['avartar'], true) ?> id="<?php echo esc_attr($this->get_field_id('avartar')); ?>" name="<?php echo esc_attr($this->get_field_name('avartar')); ?>" />
		<label for="<?php echo esc_attr($this->get_field_id('avartar')); ?>"><?php esc_html_e('Show avartar', 'snsnova'); ?></label>
	</p>
	<p style="margin: 0;">
		<input class="checkbox" type="checkbox" <?php checked($instance['interact_link'], true) ?> id="<?php echo esc_attr($this->get_field_id('interact_link')); ?>" name="<?php echo esc_attr($this->get_field_name('interact_link')); ?>" />
		<label for="<?php echo esc_attr($this->get_field_id('interact_link')); ?>"><?php esc_html_e('Show interact link', 'snsnova'); ?></label>
	</p>
	<p style="margin: 0;">
		<input class="checkbox" type="checkbox" <?php checked($instance['date'], true) ?> id="<?php echo esc_attr($this->get_field_id('date')); ?>" name="<?php echo esc_attr($this->get_field_name('date')); ?>" />
		<label for="<?php echo esc_attr($this->get_field_id('date')); ?>"><?php esc_html_e('Show date', 'snsnova'); ?></label>
	</p>
	<br />

	<?php
	}
}
if ( class_exists('YITH_Woocompare_Widget') ){
	class SnsNova_Woocompare_Widget extends YITH_Woocompare_Widget {
		function widget( $args, $instance ) {
            global $yith_woocompare;

            /**
             * WPML Support
             */
            $lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : false;

            extract( $args );

            $localized_widget_title = function_exists( 'icl_translate' ) ? icl_translate( 'Widget', 'widget_yit_compare_title_text', $instance['title'] ) : $instance['title'];

            echo wp_kses( $before_widget . $before_title . $localized_widget_title . $after_title, array(
            									'div' => array(
				                                    'class' => array()
				                                ),
				                                'h3' => array(
				                                    'class' => array()
				                                ),
				                                'h4' => array(
				                                    'class' => array()
				                                ),
				                                'span' => array(
				                                    'class' => array()
				                                ),
				                            ) );
            ?>

            <ul class="products-list" data-lang="<?php echo esc_attr($lang) ?>">
                <?php echo $yith_woocompare->obj->list_products_html(); ?>
            </ul>

            <a href="<?php echo esc_url( $yith_woocompare->obj->remove_product_url('all') ) ?>" data-product_id="all" class="clear-all"><?php esc_html_e( 'Clear all', 'yith-wcmp' ) ?></a>
            <a href="<?php echo esc_url( add_query_arg( array( 'iframe' => 'true' ), $yith_woocompare->obj->view_table_url() ) ) ?>" class="compare button"><?php esc_html_e( 'Compare', 'yith-wcmp' ) ?></a>

            <?php echo wp_kses( $after_widget, array(
            									'div' => array()
				                            ) );
        }
	}
}

function snsnova_load_widgets() {
    register_widget( 'SnsNova_Widget_Facebook');
    register_widget( 'SnsNova_Widget_Twitter');
    register_widget( 'SnsNova_Widget_Recent_Post');
    if ( class_exists('WooCommerce') && class_exists('YITH_Woocompare_Widget') ) register_widget( 'SnsNova_Woocompare_Widget');
}
add_action('widgets_init', 'snsnova_load_widgets');

