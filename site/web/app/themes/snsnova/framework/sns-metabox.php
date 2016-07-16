<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://metabox.io/docs/registering-meta-boxes/
 */
add_filter( 'rwmb_meta_boxes', 'snsnova_register_meta_boxes' );
/**
 * Register meta boxes
 *
 * Remember to change "your_prefix" to actual prefix in your project
 *
 * @param array $meta_boxes List of meta boxes
 *
 * @return array
 */
function snsnova_register_meta_boxes( $meta_boxes ){
	/**
	 * prefix of meta keys (optional)
	 * Use underscore (_) at the beginning to make keys hidden
	 * Alt.: You also can make prefix empty to disable it
	 */
	// Better has an underscore as last sign
	wp_enqueue_script('sns-imgselect', THEME_URI . '/framework/meta-box/sns-metabox.js');
	$prefix = 'snsnova_';
	global $wpdb, $snsnova_opt;
	$revsliders =array();
	$revsliders[0] = 'Select a slider';
	if (is_plugin_active('revslider/revslider.php')) {
		$query = $wpdb->prepare("
			SELECT * 
			FROM {$wpdb->prefix}revslider_sliders 
			ORDER BY %s"
			, "ASC");
	    $get_sliders = $wpdb->get_results($query); //var_dump($get_sliders);
	    if($get_sliders) {
		    foreach($get_sliders as $slider) {
			   $revsliders[$slider->alias] = $slider->title;
		   }
	    }
	}
	//
	$default_layout = 'l-m';
	if ( isset($snsnova_opt['blog_layout']) ) $default_layout = $snsnova_opt['blog_layout'];
	//
	$siderbars = array();
	foreach ($GLOBALS['wp_registered_sidebars'] as $sidebars) {
		$siderbars[ $sidebars['id']] = $sidebars['name'];
	}
	// Layout config
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id'         => 'sns_layout',
		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title'      => esc_html__( 'Layout Config', 'snsnova' ),
		// Post types, accept custom post types as well - DEFAULT is 'post'. Can be array (multiple post types) or string (1 post type). Optional.
		'post_types' => array( 'page' ),
		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context'    => 'normal',
		// Order of meta box: high (default), low. Optional.
		'priority'   => 'high',
		// Auto save: true, false (default). Optional.
		// 'autosave'   => true,
		// List of meta fields

		'fields'     => array(
			
			array(
				'name'        => esc_html__( 'Config layout for this page', 'snsnova' ),
				'id'          => "{$prefix}enablelayoutconfig",
				'type'    => 'radio',
				'options' => array(
					'1' => esc_html__( 'Yes', 'snsnova' ),
					'0' => esc_html__( 'No', 'snsnova' ),
				),
				'std'         => '0',
				'desc'		  => esc_html__( 'Sellect Yes if you want config layout for this page', 'snsnova' ),
			),
			// Layout Type
			array(
				'name'        => esc_html__( 'Layout Type', 'snsnova' ),
				'id'          => "{$prefix}layouttype",
				'type'        => 'layouttype',
				// Array of 'value' => 'Label' pairs for select box
				'options'     => array(
					'm' => esc_html__( 'Without Sidebar', 'snsnova' ),
					'l-m' => esc_html__( 'Use Left Sidebar', 'snsnova' ),
					'm-r' => esc_html__( 'Use Right Sidebar', 'snsnova' ),
					'l-m-r' => esc_html__( 'Use Left & Right Sidebar', 'snsnova' ),
				),
				// Select multiple values, optional. Default is false.
				'multiple'    => false,
				'std'         => $default_layout,
				'placeholder' => esc_html__( '--- Select a layout type ---', 'snsnova' ),
			),
			// Left Sidebar
			array(
				'name'  => esc_html__( 'Left Sidebar', 'snsnova' ),
				'id'    => "{$prefix}leftsidebar",
				//'desc'  => esc_html__( 'Text description', 'snsnova' ),
				'type'  => 'select',
				'options'	=> $siderbars,
				'multiple'	=> false,
				'std'		=> 'left-sidebar',
				'placeholder' => esc_html__( '--- Select a sidebar ---', 'snsnova' ),
			),
			// Right Sidebar
			array(
				'name'  => esc_html__( 'Right Sidebar', 'snsnova' ),
				'id'    => "{$prefix}rightsidebar",
				//'desc'  => esc_html__( 'Text description', 'snsnova' ),
				'type'  => 'select',
				'options'	=> $siderbars,
				'multiple'	=> false,
				'std'		=> 'right-sidebar',
				'placeholder' => esc_html__( '--- Select a sidebar ---', 'snsnova' ),
			),
		)
	);
	// Page config
	$meta_boxes[] = array(
		// Meta box id, UNIQUE per meta box. Optional since 4.1.5
		'id'         => 'sns_pageconfig',
		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title'      => esc_html__( 'Page Config', 'snsnova' ),
		// Post types, accept custom post types as well - DEFAULT is 'post'. Can be array (multiple post types) or string (1 post type). Optional.
		'post_types' => array( 'page' ),
		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context'    => 'normal',
		// Order of meta box: high (default), low. Optional.
		'priority'   => 'high',
		// Auto save: true, false (default). Optional.
		// 'autosave'   => true,
		// List of meta fields

		'fields'     => array(
			array(
				'name'    => esc_html__( 'Show Title', 'snsnova' ),
				'id'      => "{$prefix}showtitle",
				'type'    => 'radio',
				'options' => array(
					'1' => esc_html__( 'Yes', 'snsnova' ),
					'0' => esc_html__( 'No', 'snsnova' ),
				),
				'std'         => '1',
			),
			array(
				'name'    => esc_html__( 'Use Slideshow', 'snsnova' ),
				'id'      => "{$prefix}useslideshow",
				'type'    => 'radio',
				'options' => array(
					'1' => esc_html__( 'Yes', 'snsnova' ),
					'0' => esc_html__( 'No', 'snsnova' ),
				),
				'std'         => '0',
			),
			array(
				'name'    => esc_html__( 'Select Slideshow', 'snsnova' ),
				'id'      => "{$prefix}revolutionslider",
				'type'    => 'select',
				'options' =>  $revsliders ,
				'std'         => '',
			),
			array(
				'name'    => esc_html__( 'Show Breadcrumbs', 'snsnova' ),
				'id'      => "{$prefix}showbreadcrump",
				'type'    => 'radio',
				'options' => array(
					'1' => esc_html__( 'Yes', 'snsnova' ),
					'0' => esc_html__( 'No', 'snsnova' ),
				),
				'std'         => '1',
				'desc' => esc_html__( 'Dont apply for homepage. Because breadcrumbs dont sense in homepage', 'snsnova' ),
			),
			array(
				'name' => esc_html__( 'Image background for menu wrapper', 'snsnova' ),
				'id'   => "{$prefix}menubg",
				'type' => 'image_advanced',
				'max_file_uploads' => 1,
				'desc' => esc_html__( 'Default value in theme option - SNS Nova', 'snsnova' ),
			),
			array(
				'name'    => esc_html__( 'Config Theme Color for this page?', 'snsnova' ),
				'id'      => "{$prefix}page_themecolor",
				'type'    => 'radio',
				'options' => array(
					'1' => esc_html__( 'Yes', 'snsnova' ),
					'0' => esc_html__( 'No', 'snsnova' ),
				),
				'std'         => '0',
			),
			array(
				'name' => esc_html__( 'Sellect Theme Color', 'snsnova' ),
				'id'   => "{$prefix}themecolor",
				'type' => 'color',
				'desc' => esc_html__( 'It will priority than Theme Color in Theme Option panel', 'snsnova' ),
			),
			array(
				'name'    => esc_html__( 'Footer Layout', 'snsnova' ),
				'id'      => "{$prefix}footer_layout",
				'type'    => 'select',
				'options' =>  array(
					'full'	=> esc_html__( 'Full Width', 'snsnova' ),
					'two-col'	=> esc_html__( 'Two Columns', 'snsnova' ),
				) ,
				'std'         => 'full',
				'desc' => esc_html__( 'Select Default to use setting in Theme Option', 'snsnova' ),
			),
		)
	);
	// Post format - Gallery
	$meta_boxes[] = array(
	    	'id' => 'sns-post-gallery',
		    'title' =>  esc_html__('Gallery Settings','snsnova'),
	    	'description' => '',
    		'pages'      => array( 'post' ), // Post type
	    	'context'    => 'normal',
		    'priority'   => 'high',
	    	'fields' => array(
			     array(
			        'name'		=> 'Gallery Images',
			        'desc'	    => 'Upload Images for post Gallery ( Limit is 15 Images ).',
			        'type'      => 'image_advanced',
			        'id'	    => "{$prefix}post_gallery",
	         		'max_file_uploads' => 15 
	        	)
			)
	);
	// Post format - Video
    $meta_boxes[] = array(
		'id' => 'sns-post-video',
		'title' => esc_html__('Video Settings','snsnova'),
		'description' => '',
		'pages'      => array( 'post' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'fields' => array( 
		    array(
				'id'    => "{$prefix}post_video",
				'name'  => esc_html__( 'Video', 'snsnova' ),
				'type'  => 'oembed',
				// Allow to clone? Default is false
				'clone' => false,
				// Input size
				'size'  => 50,
			)
		)
	);
	// Post format - Video
    $meta_boxes[] = array(
		'id' => 'sns-post-quote',
		'title' => esc_html__('Quote Settings','snsnova'),
		'description' => '',
		'pages'      => array( 'post' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'fields' => array( 
		    array(
				'id'    => "{$prefix}post_quotecontent",
				'name'  => esc_html__( 'Video', 'snsnova' ),
				'type'  => 'textarea',
				// Allow to clone? Default is false
				'clone' => false,
			),
			array(
				'id'      => "{$prefix}post_quoteauthor",
				'name'    => esc_html__( 'Quote author', 'snsnova' ),
				'type'    => 'text',
				'clone' => false,
			),
		)
	);
	// Post format - Link
    $meta_boxes[] = array(
		'id' => 'sns-post-link',
		'title' => esc_html__('Link Settings','snsnova'),
		'description' => '',
		'pages'      => array( 'post' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'fields' => array( 
		    array(
				'id'    => "{$prefix}post_linkurl",
				'name'  => esc_html__( 'Link URL', 'snsnova' ),
				'type'  => 'text',
				// Allow to clone? Default is false
				'clone' => false,
			),
			array(
				'id'      => "{$prefix}post_linktitle",
				'name'    => esc_html__( 'Link Title', 'snsnova' ),
				'type'    => 'text',
				'clone' => false,
			),
		)
	);
	// Brand config
	$meta_boxes[] = array(
		'id'         => 'sns_brandconfig',
		'title'      => esc_html__( 'Brand Config', 'snsnova' ),
		'post_types' => array( 'brand' ),
		'context'    => 'normal',
		'priority'   => 'high',
		'fields'     => array(
			array(
				'name'    => esc_html__( 'Link for brand', 'snsnova' ),
				'id'      => "{$prefix}brandlink",
				'type'    => 'text'
			),
		)
	);
	return $meta_boxes;
}


if ( class_exists( 'RWMB_Field' ) ) {
	class RWMB_Layouttype_Field extends RWMB_Select_Field {

		static function admin_enqueue_scripts(){
			wp_enqueue_style( 'snsnova-imgselect', THEME_URI . '/framework/meta-box/img-select.css' );
		}
		public static function walk( $options, $db_fields, $meta, $field ){
			$attributes = call_user_func( array( RW_Meta_Box::get_class_name( $field ), 'get_attributes' ), $field, $meta );
			$walker     = new RWMB_Select_Walker( $db_fields, $field, $meta );
			$output = sprintf(
				'<select class="rwmb-select img-select" name="%s" id="%s" size="%s"%s>',
				$field['field_name'],
				$field['id'],
				$field['size'],
				$field['multiple'] ? ' multiple' : ''
			);
			$output .= $walker->walk( $options, $field['flatten'] ? - 1 : 0 );
			$output .= '</select>';
			$output .= self::get_select_all_html( $field );
			$output .= self::img_select( $field, $meta );
			return $output;
		}
		
		static function img_select( $field, $meta ){
			$html = '';
			$img = '<span class="img-layout %s" data-value="%s" title="%s"></span>';

			foreach ( $field['options'] as $value => $label )
			{
				$html .= sprintf( $img, $value, $value, $label);
			}
			return $html;
		}
	}
}
