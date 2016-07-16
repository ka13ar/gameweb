<?php
$vc_add_css_animation = array(
	'type' => 'dropdown',
	'heading' => esc_html__( 'CSS Animation', 'js_composer' ),
	'param_name' => 'css_animation',
	'admin_label' => true,
	'value' => array(
		esc_html__( 'No', 'js_composer' ) => '',
		esc_html__( 'Top to bottom', 'js_composer' ) => 'top-to-bottom',
		esc_html__( 'Bottom to top', 'js_composer' ) => 'bottom-to-top',
		esc_html__( 'Left to right', 'js_composer' ) => 'left-to-right',
		esc_html__( 'Right to left', 'js_composer' ) => 'right-to-left',
		esc_html__( 'Appear from center', 'js_composer' ) => 'appear'
	),
	'description' => esc_html__( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'js_composer' )
);
$sns_extra_class =array(
			"type" => "textfield",
			"heading" => esc_html__("Extra class name", "snsnova"),
			"param_name" => "extra_class"
		);

global $wpdb;
// Get category name
$sql = $wpdb->prepare( "
	SELECT a.name,a.slug,a.term_id 
	FROM {$wpdb->terms} a JOIN  {$wpdb->term_taxonomy} b ON (a.term_id= b.term_id ) 
	WHERE b.count> %d and b.taxonomy = %s",
	0,'category' );
$results = $wpdb->get_results($sql);
$cat_value = array();
foreach ($results as $cat) {
	$cat_value[$cat->name] = $cat->slug;
}
// Get woo category name
$sql = $wpdb->prepare( "
	SELECT a.name,a.slug,a.term_id 
	FROM {$wpdb->terms} a JOIN  {$wpdb->term_taxonomy} b ON (a.term_id= b.term_id ) 
	WHERE b.count> %d and b.taxonomy = %s",
	0,'product_cat' );
$results = $wpdb->get_results($sql);
$woocat_value = array();
foreach ($results as $cat) {
	$woocat_value[$cat->name] = $cat->slug;
}

// SNS Custom Box
class WPBakeryShortCode_SNS_Custom_Box extends WPBakeryShortCode {}
vc_map( array(
	"name"  => esc_html__("SNS Custom Box", "snsnova"),
	"base" => "sns_custom_box",
	"show_settings_on_create" => true ,
	"is_container" => false ,
	"icon" => "vc_icon_snstheme",
	"class" => "vc_icon_snstheme",
	"content_element" => true ,
	"category" => esc_html__('Content', "snsnova"),
	'description' => esc_html__( 'Box contain: icon, title, description', 'snsnova' ),
	"params" => array(
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Icon library', 'js_composer' ),
			'value' => array(
				esc_html__( 'Font Awesome', 'js_composer' ) => 'fontawesome',
				// esc_html__( 'Open Iconic', 'js_composer' ) => 'openiconic',
				// esc_html__( 'Typicons', 'js_composer' ) => 'typicons',
				// esc_html__( 'Entypo', 'js_composer' ) => 'entypo',
				esc_html__( 'Linecons', 'js_composer' ) => 'linecons',
			),
			'param_name' => 'icon_type',
			'description' => esc_html__( 'Select icon library.', 'js_composer' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_fontawesome',
			'value' => 'fa fa-adjust', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => false,
				// default true, display an "EMPTY" icon?
				'iconsPerPage' => 4000,
				// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'fontawesome',
			),
			'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_openiconic',
			'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => false, // default true, display an "EMPTY" icon?
				'type' => 'openiconic',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'openiconic',
			),
			'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_typicons',
			'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => false, // default true, display an "EMPTY" icon?
				'type' => 'typicons',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'typicons',
			),
			'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_entypo',
			'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => false, // default true, display an "EMPTY" icon?
				'type' => 'entypo',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'entypo',
			),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_linecons',
			'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => false, // default true, display an "EMPTY" icon?
				'type' => 'linecons',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'linecons',
			),
			'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
		),
		array(
			"type" => "colorpicker",
			"value" => "",
			"heading" => esc_html__("Color for icon", "snsnova"),
			"param_name" => "icon_color"
	    ),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Font size for icon", "snsnova"),
			"param_name" => "icon_font_size" ,
			"description" => esc_html__("It's font-size for icon you sellected, example: 24px", "snsnova")
		),
		array(
			"type" => "dropdown",
			"value" => Array( 
						"No border" => "" ,
						"1px" => "1px" ,
						"2px" => "2px" ,
						"3px" => "3px" ,
						"4px" => "4px" ,
						"5px" => "5px" ,
						"6px" => "6px" ,
						"7px" => "7px" ,
						"8px" => "8px" 
			), 	
			"heading" => esc_html__("Border size for icon", "snsnova"),
			"param_name" => "icon_border_size" ,
			"description" => esc_html__("It's border size for icon box", "snsnova")
		),
		array(
			"type" => "textfield",	
			"heading" => esc_html__("Border radius for icon box", "snsnova"),
			"param_name" => "icon_border_radius" ,
			'dependency' => array(
				'element' => 'icon_border_size',
				'value' => array('1px', '2px', '3px', '4px', '5px', '6px', '7px', '8px')
			),
			"description" => esc_html__("It's border radius for icon box, example: 10px, or 50%, or none ", "snsnova")
		),
		array(
			"type" => "colorpicker",
			"value" => "",
			"heading" => esc_html__("Border color", "snsnova"),
			"param_name" => "border_color",
			'dependency' => array(
				'element' => 'icon_border_size',
				'value' => array('1px', '2px', '3px', '4px', '5px', '6px', '7px', '8px')
			)

	    ),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Custom Link", "snsnova"),
			"param_name" => "link" ,
			"description" => esc_html__("Enter the  link. Do't forget to include http:// ", "snsnova")
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Title", "snsnova"),
			"param_name" => "title",
			"value" => esc_html__("Your Title Here ...","snsnova"),
			"admin_label" => true 
		),
		array(
			"type" => "dropdown",
			"value" => Array( 
						"Sellect text align" => "" ,
						"left" => "left" ,
						"right" => "right" ,
						"center" => "center"
			), 	
			"heading" => esc_html__("Text align for box", "snsnova"),
			"param_name" => "text_align" 
		),
		array(
			"type" => "textarea",
			"heading" => esc_html__("Description", "snsnova"),
			"param_name" => "desc"
		),
		$vc_add_css_animation,
		$sns_extra_class,
	)
));

// SNS Twitter
class WPBakeryShortCode_SNS_Twitter extends WPBakeryShortCode {}
vc_map( array(
	"name"  => esc_html__("SNS Twitter", "snsnova"),
	"base" => "sns_twitter",
	"show_settings_on_create" => true ,
	"is_container" => false ,
	"icon" => "",
	"class" => "sns_twitter",
	"content_element" => true ,
	"category" => esc_html__('Content', "snsnova"),
	'description' => esc_html__( 'Show your list tweets', 'snsnova' ),
	"params" => array(
		array(
			"type" => "textfield",
			"heading" => esc_html__("Title", "snsnova"),
			"param_name" => "title",
			"value" => esc_html__("Latest Tweets","snsnova"),
			"admin_label" => true 
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Widget ID", "snsnova"),
			"param_name" => "widget_id",
			"value" => "420187988887212033",
			"admin_label" => true 
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Twitter Account", "snsnova"),
			"param_name" => "account_name",
			"value" => "snstheme",
			"admin_label" => true 
		),
		array(
			"type" => "dropdown",
			"value" => Array( 
						"List" => "list" ,
						"Carousel" => "carousel" 
			), 	
			"heading" => esc_html__("Template", "snsnova"),
			"param_name" => "template" 
		),
		array(
			"type" => "dropdown",
			"value" => Array( 
						"Yes" => "1" ,
						"No" => "2" ,
			), 	
			"heading" => esc_html__("Show Navigation", "snsnova"),
			"param_name" => "show_navigation",
			'dependency' => array(
				'element' => 'template',
				'value' => 'carousel'
			)
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Tweets number display", "snsnova"),
			"param_name" => "tweets_num_display",
			"value" => "2",
			"admin_label" => true 
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Tweets number limit", "snsnova"),
			"param_name" => "tweets_num_limit",
			"value" => "6",
			'dependency' => array(
				'element' => 'template',
				'value' => 'carousel'
			),
			"admin_label" => true 
		),
		array(
			"type" => "dropdown",
			"value" => Array( 
						"Yes" => "1" ,
						"No" => "2" ,
			), 	
			"heading" => esc_html__("Show Avartar", "snsnova"),
			"param_name" => "show_avartar",
		),
		array(
			"type" => "dropdown",
			"value" => Array( 
						"Yes" => "1" ,
						"No" => "2" ,
			), 	
			"heading" => esc_html__("Show Follow Link", "snsnova"),
			"param_name" => "show_follow_link",
		),
		array(
			"type" => "dropdown",
			"value" => Array( 
						"Yes" => "1" ,
						"No" => "2" ,
			), 	
			"heading" => esc_html__("Show Interact Link", "snsnova"),
			"param_name" => "show_interact_link",
		),
		array(
			"type" => "dropdown",
			"value" => Array( 
						"Yes" => "1" ,
						"No" => "2" ,
			), 	
			"heading" => esc_html__("Show Date", "snsnova"),
			"param_name" => "show_date",
		),
		$vc_add_css_animation,
		$sns_extra_class,
	)
));

class WPBakeryShortCode_SNS_Blog_list extends WPBakeryShortCode {}

vc_map( array(
	"name" => esc_html__("SNS Blog List","snsnova"),
	"base" => "sns_blog_list",
	"icon" => "sns_icon_bloglist",
	"class" => "sns_bloglist",
	"category" => esc_html__("Content","snsnova"),
	"description" => esc_html__( "Show list post with image/title/excerpt/date/...", "snsnova" ),
	"params" => array(
		array(
			"type" => "checkbox",
			"value" => $cat_value,
			"class" => "",
			"heading" => esc_html__("Categories","snsnova"),
			"description" => "If you dont sellect category, the default is sellected all category",
			"param_name" => "category"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Title","snsnova"),
			"param_name" => "title",
			"value" => "Latest Post"
		),
		array(
			"heading" => esc_html__("Pre text", "snsnova"),
			"param_name" => "pretext",
			"type" => "textarea",
			"dependency" => array("element" => "template" , "value" => array("2") )
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Template","snsnova"),
			"param_name" => "template",
			"value" => array(
				"List" => "1",
				"Carousel" =>  "2"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("List type","snsnova"),
			"param_name" => "type",
			"value" => array(
				"Show Images on Top" =>  "0",
				"Show Images on Left" => "1",
				"Show Date on Left" =>  "2"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Image Size","snsnova"),
			"param_name" => "img_size",
			"value" => array(
				"Default ( Medium )" => "default",
				"Small" =>  "small",
				"Large" => "large"
			),
			"description" => "" ,
			"dependency" => array("element" => "type" , "value" => array("1") )
		),
		// array(
		// 	"type" => "checkbox",
		// 	"class" => "",
		// 	"heading" => esc_html__("Show Comments","snsnova"),
		// 	"param_name" => "show_comments",
		// 	"value" => array(esc_html__("Yes","snsnova") => "yes")
		// ),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Excerpt Length","snsnova"),
			"param_name" => "excerpt_length",
			"value" => "20"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Posts number display","snsnova"),
			"param_name" => "num_display",
			"value" => "4"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Posts number limit","snsnova"),
			"param_name" => "number_limit",
			"dependency" => array("element" => "template" , "value" => "2" ),
			"value" => "12"
		),
		$vc_add_css_animation,
		$sns_extra_class,
	)
) );

class WPBakeryShortCode_SNS_Blog_Page extends WPBakeryShortCode {}

vc_map( array(
	"name" => esc_html__("SNS Blog Page","snsnova"),
	"base" => "sns_blog_page",
	"icon" => "sns_icon_blogpage",
	"class" => "sns_blogpage",
	"category" => esc_html__("Content","snsnova"),
	"description" => esc_html__( "To create blog page with some style", "snsnova" ),
	"params" => array(
		array(
			"type" => "checkbox",
			"value" => $cat_value,
			"class" => "",
			"heading" => esc_html__("Categories","snsnova"),
			"description" => "If you dont sellect category, the default is sellected all category",
			"param_name" => "category"
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Blog Style","snsnova"),
			"param_name" => "blog_type",
			"value" => array(
				'Standard Blog' => '',
				'List Blog' =>  'list'			
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Post per pages","snsnova"),
			"param_name" => "posts_per_page",
			"value" => "6"
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Image Size","snsnova"),
			"param_name" => "img_size",
			"value" => array(
				"Full" => "full",
				"Large" => "large",
				"Medium" =>  "medium",
				"Blog List Thumb" => "snsnova_bloglist_thumb",
			),
			"description" => "" ,
		),
		// array(
		// 	"type" => "checkbox",
		// 	"class" => "",
		// 	"heading" => esc_html__("Show Comments","snsnova"),
		// 	"param_name" => "show_comments",
		// 	"value" => array(esc_html__("Yes","snsnova") => "yes")
		// ),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Excerpt Length","snsnova"),
			"param_name" => "excerpt_length",
			"value" => "35"
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Enable Read More Button","snsnova"),
			"param_name" => "enable_readmore",
			"value" => array(
				"Yes" => true,
				"No" =>  false
			),
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Show Categories","snsnova"),
			"param_name" => "show_categories",
			"value" => array(
				"Yes" => true,
				"No" =>  false
			),
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Show Author","snsnova"),
			"param_name" => "show_author",
			"value" => array(
				"Yes" => true,
				"No" =>  false
			),
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Show Date","snsnova"),
			"param_name" => "show_date",
			"value" => array(
				"Yes" => true,
				"No" =>  false
			),
		),
		// array(
		// 	"type" => "textfield",
		// 	"class" => "",
		// 	"heading" => esc_html__("Posts number display","snsnova"),
		// 	"param_name" => "num_display",
		// 	"value" => "4"
		// ),
		// array(
		// 	"type" => "textfield",
		// 	"class" => "",
		// 	"heading" => esc_html__("Posts number limit","snsnova"),
		// 	"param_name" => "number_limit",
		// 	"dependency" => array("element" => "template" , "value" => "2" ),
		// 	"value" => "12"
		// ),
		$vc_add_css_animation,
		$sns_extra_class,
	)
) );


class WPBakeryShortCode_SNS_Our_Brand extends WPBakeryShortCode {}

vc_map( array(
	"name" => esc_html__("SNS Our Brand","snsnova"),
	"base" => "sns_our_brand",
	"icon" => "sns_icon_ourbrand",
	"class" => "sns_ourbrand",
	"category" => esc_html__("Content","snsnova"),
	"description" => esc_html__( "Carousel list brands(image, link)", "snsnova" ),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Title","snsnova"),
			"param_name" => "title",
			"value" => "Our brands"
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Style","snsnova"),
			"param_name" => "style",
			"value" => array(
				"Default" => "default",
				"Hover show navigation" =>  "hover-show-nav"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Link Target","snsnova"),
			"param_name" => "link_target",
			"value" => array(
				"New Windown" => "blank",
				"Same Windown" =>  "_self"
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Brands number display","snsnova"),
			"param_name" => "num_display",
			"value" => "4",
			"description" => "Numbers display with each page carousel"
		),
		$vc_add_css_animation,
		$sns_extra_class,
	)
) );

class WPBakeryShortCode_SNS_Testimonial extends WPBakeryShortCode {}

vc_map( array(
	"name" => esc_html__("SNS Testimonial","snsnova"),
	"base" => "sns_testimonial",
	"icon" => "sns_icon_testimonial",
	"class" => "sns_testimonial",
	"category" => esc_html__("Content","snsnova"),
	"description" => esc_html__( "Carousel list testimonial", "snsnova" ),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Icon type","snsnova"),
			"param_name" => "icon_type",
			"value" => array(
				"Image" => "image",
				"FontAwesome" =>  "fontawesome"
			),
			"description" => ""
		),
		array(
			"type" => "attach_image",
			"class" => "",
			"heading" => esc_html__("Icon","snsnova"),
			"param_name" => "icon_image",
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'image',
			),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_fontawesome',
			'value' => 'fa fa-adjust', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => false,
				// default true, display an "EMPTY" icon?
				'iconsPerPage' => 4000,
				// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'fontawesome',
			),
			'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Title","snsnova"),
			"param_name" => "title",
			"value" => "What client say"
		),
		$vc_add_css_animation,
		$sns_extra_class,
		
	)
) );

class WPBakeryShortCode_SNS_Member extends WPBakeryShortCode {}

vc_map( array(
	"name" => esc_html__("SNS Member","snsnova"),
	"base" => "sns_member",
	"icon" => "sns_icon_member",
	"class" => "sns_member",
	"category" => esc_html__("Content","snsnova"),
	"description" => esc_html__( "Display team member", "snsnova" ),
	"params" => array(
		array(
	      "type" => "attach_image",
	      "heading" => esc_html__("Avartar", "snsnova"),
	      "param_name" => "avartar" 
	    ),
	    array(
			"type" => "dropdown",
			"heading" => esc_html__("Avartar style","snsnova"),
			"param_name" => "avartar_style",
			"value" => array(
				"Default" => "",
				"Rounded" =>  "rounded",
				"Circle" =>  "circle"
			),
			"description" => ""
		),
		array(
			'type' => 'vc_link',
			'heading' => esc_html__( 'Link to member', 'snsnova' ),
			'param_name' => 'link',
		),
	    array(
	      "type" => "textfield",
	      "heading" => esc_html__("Member name", "snsnova"),
	      "param_name" => "name",
		  "admin_label" => true
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => esc_html__("Member role", "snsnova"),
	      "param_name" => "role",
		  "admin_label" => true
	    ),
	    array(
	      "type" => "textarea_html",
	      "heading" => esc_html__("Short description", "snsnova"),
	      "param_name" => "short_desc",
	    ),
	   //  array(
	   //    "type" => "checkbox",
	   //    "heading" => esc_html__("Social Links", "snsnova"),
	   //    "param_name" => "social_links",
		  // "value" => Array('Twitter'=>'twitter' ,'Facebook'=>'facebook','Linkedin'=>'linkedin','Youtube'=>'youtube','Google plus'=>'google','Behance'=>'behance','Dribbble'=>'dribbble','Pinterest'=>'pinterest')
	   //  ),
		array(
	      "type" => "textfield",
	      "heading" => esc_html__("Twitter link", "snsnova"),
	      "param_name" => "twitter",
		  //"dependency" => Array('element' => "social_links", 'value' => 'twitter')
	    ),
		array(
	      "type" => "textfield",
	      "heading" => esc_html__("Facebook link", "snsnova"),
	      "param_name" => "facebook",
		  //"dependency" => Array('element' => "social_links", 'value' => 'facebook')
	    ),
		array(
	      "type" => "textfield",
	      "heading" => esc_html__("linkedin link", "snsnova"),
	      "param_name" => "linkedin",
		  //"dependency" => Array('element' => "social_links", 'value' => 'linkedin')
	    ),
		array(
	      "type" => "textfield",
	      "heading" => esc_html__("youtube link", "snsnova"),
	      "param_name" => "youtube",
		  //"dependency" => Array('element' => "social_links", 'value' => 'youtube')
	    ),
		array(
	      "type" => "textfield",
	      "heading" => esc_html__("google link", "snsnova"),
	      "param_name" => "google",
		  //"dependency" => Array('element' => "social_links", 'value' => 'google')
	    ),
		array(
	      "type" => "textfield",
	      "heading" => esc_html__("behance link", "snsnova"),
	      "param_name" => "behance",
		  //"dependency" => Array('element' => "social_links", 'value' => 'behance')
	    ),
		array(
	      "type" => "textfield",
	      "heading" => esc_html__("dribbble link", "snsnova"),
	      "param_name" => "dribbble",
		  //"dependency" => Array('element' => "social_links", 'value' => 'dribbble')
	    ),
		array(
	      "type" => "textfield",
	      "heading" => esc_html__("pinterest link", "snsnova"),
	      "param_name" => "pinterest",
		  //"dependency" => Array('element' => "social_links", 'value' => 'pinterest')
	    ),
	    $vc_add_css_animation,
	    $sns_extra_class,
	)
) );

class WPBakeryShortCode_SNS_Counter extends WPBakeryShortCode {

}

vc_map( array(
	"name" => esc_html__("SNS Counter", "snsnova"),
	"base" => "sns_counter",
	"class" => "sns_counter",
	"icon" => "sns_icon_counter",
	"description" => esc_html__( "Display box count to", "snsnova" ),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Use icon?","snsnova"),
			"param_name" => "enable_icon",
			"value" => array(
				esc_html__('Yes', 'snsnova') => "1",
				esc_html__('No', 'snsnova') => "0"
			),
			"description" => ""
		),
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Icon library', 'js_composer' ),
			'value' => array(
				esc_html__( 'Font Awesome', 'js_composer' ) => 'fontawesome',
				esc_html__( 'Linecons', 'js_composer' ) => 'linecons',
			),
			'param_name' => 'icon_type',
			'description' => esc_html__( 'Select icon library.', 'js_composer' ),
			'dependency' => array(
				'element' => 'enable_icon',
				'value' => '1',
			),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_fontawesome',
			'value' => 'fa fa-adjust', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => false,
				// default true, display an "EMPTY" icon?
				'iconsPerPage' => 4000,
				// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'fontawesome',
			),
			'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'js_composer' ),
			'param_name' => 'icon_linecons',
			'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => false, // default true, display an "EMPTY" icon?
				'type' => 'linecons',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_type',
				'value' => 'linecons',
			),
			'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
		),
		array(
			"type" => "colorpicker",
			"value" => "",
			"heading" => esc_html__("Color for icon", "snsnova"),
			"param_name" => "icon_color",
			'dependency' => array(
				'element' => 'enable_icon',
				'value' => '1',
			),
	    ),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Font size for icon", "snsnova"),
			"param_name" => "icon_font_size" ,
			"description" => esc_html__("It's font-size for icon you sellected, example: 24px", "snsnova"),
			'dependency' => array(
				'element' => 'enable_icon',
				'value' => '1',
			),
		),
  
	  	array(
	      "type" => "textfield",
	      "heading" => esc_html__("Value to Count", "snsnova"),
	      "param_name" => "value" ,
		  "description" => "This value must be an integer", 
		  "admin_label" => true
	    ),
	    array(
			"type" => "colorpicker",
			"value" => "",
			"heading" => esc_html__("Color for Value", "snsnova"),
			"param_name" => "value_color"
	    ),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Font size for Value", "snsnova"),
			"param_name" => "value_font_size" ,
			"description" => esc_html__("It's font-size for Value, example: 18px", "snsnova")
		),
	    array(
	      "type" => "textfield",
	      "heading" => esc_html__("Unit", "snsnova"),
	      "param_name" => "unit",
		  "description" => 'You can use any text such as % , cm or any other . Leave Blank if you do not want to display any unit value'
	    ),
	    array(
	      "type" => "textfield",
	      "heading" => esc_html__("Counter Title", "snsnova"),
	      "param_name" => "title" ,
		  "value" => esc_html__("Your Title Goes Here...","snsnova"),
	    ),
	    array(
			"type" => "colorpicker",
			"value" => "",
			"heading" => esc_html__("Color for Title", "snsnova"),
			"param_name" => "title_color"
	    ),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Font size for Title", "snsnova"),
			"param_name" => "title_font_size" ,
			"description" => esc_html__("It's font-size for Title, example: 12px", "snsnova")
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("From to count", "snsnova"),
			"param_name" => "from" ,
			"value"		=> "0",
			"description" => esc_html__("The number the element should start at, example: 0", "snsnova")
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Speed to count", "snsnova"),
			"param_name" => "speed",
			"value"		=> "900",
			"description" => esc_html__("How long it should take to count between the target numbers, example: 900", "snsnova")
		),
		array(
			"type" => "textfield",
			"heading" => esc_html__("Interval to count", "snsnova"),
			"param_name" => "interval",
			"value"		=> "10",
			"description" => esc_html__("How often the element should be updated, example: 10", "snsnova")
		),
		$vc_add_css_animation,
		$sns_extra_class,
  	)

));


class WPBakeryShortCode_SNS_Products extends WPBakeryShortCode {}

vc_map( array(
	"name" => esc_html__("SNS Products","snsnova"),
	"base" => "sns_products",
	"icon" => "sns_icon_products",
	"class" => "sns_products",
	"category" => esc_html__("WooCommerce","snsnova"),
	"description" => esc_html__( "WooCommerce's products","snsnova" ),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Title","snsnova"),
			"param_name" => "title",
			"value" => "New Products"
		),
		array(
			"type" => "textarea",
			"heading" => esc_html__("Pre text", "snsnova"),
			"param_name" => "pretext"
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Data types","snsnova"),
			"param_name" => "data_type",
			"value" => array(
				esc_html__('Order by', 'snsnova') => "orderby",
				esc_html__('Category', 'snsnova') => "category",
			),
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"value" => $woocat_value,
			"heading" => esc_html__("Select Category","snsnova"),
			"param_name" => "list_cat",
			"dependency" => array("element" => "data_type" , "value" => "orderby" ),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"value" => $woocat_value,
			"heading" => esc_html__("Select Category","snsnova"),
			"param_name" => "cat",
			"dependency" => array("element" => "data_type" , "value" => "category" ),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Sellect Order By","snsnova"),
			"param_name" => "type",
			"value" => array(
				esc_html__('Latest Products', 'snsnova') => "recent",
				esc_html__('BestSeller Products', 'snsnova') => "best_selling",
				esc_html__('Top Rated Products', 'snsnova') => "top_rate",
				esc_html__('Special Products', 'snsnova') => "on_sale",
				esc_html__('Featured Products', 'snsnova') => "featured_product",
				esc_html__('Recent Review', 'snsnova') => "recent_review",
			),
			"description" => "",
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"value" =>  array(
				esc_html__('Yes', 'snsnova') => "1",
			),
			"heading" => esc_html__("Use category name is Title","snsnova"),
			"param_name" => "use_cat_is_title",
			"dependency" => array("element" => "data_type" , "value" => "category" ),
			"description" => ""
		),

		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Padding top for products container","snsnova"),
			"param_name" => "paddingtop_productcontainer",
			//"dependency" => array("element" => "template" , "value" => "2" ),
			"value" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Product number limit","snsnova"),
			"param_name" => "number_limit",
			//"dependency" => array("element" => "template" , "value" => "2" ),
			"value" => "10"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Product number display","snsnova"),
			"param_name" => "number_display",
			//"dependency" => array("element" => "template" , "value" => "2" ),
			"value" => "4"
		),
		$vc_add_css_animation,
		$sns_extra_class,
		
	)
) );

class WPBakeryShortCode_SNS_Product_Tabs extends WPBakeryShortCode {
	public function snsnova_getListTabTitle(){
		$this->atts = vc_map_get_attributes( $this->getShortcode(), $this->atts );
		$array_tab = array();
		if ( $this->atts['tab_types'] == 'category' ) :
			if( empty($this->atts['list_cat']) ) :
				$array_tab = $this->snsnova_getCats();
			else :
				$array_tab = explode(',', $this->atts['list_cat']);
			endif;
			//var_dump($array_tab);
		else :
			$array_tab = explode(',', $this->atts['list_orderby']);
		endif;
		foreach ($array_tab as $tab) {
			$list_tab[$tab] = $this->snsnova_tabTitle($tab, $this->atts['tab_types']);
		}
		return $list_tab;
	}

	public function snsnova_tabTitle($tab, $tab_types){
		if( $tab_types == 'category' ){
			$cat = get_term_by('slug', $tab, 'product_cat');
			return array('name'=>str_replace(' ', '_', $tab),'title'=>$cat->name,'short_title'=>$cat->name);
		}else{
			switch ($tab) {
				case 'recent':
					return array('name'=>$tab,'title'=>esc_html__('Latest Products','snsnova'),'short_title'=>esc_html__('Latest','snsnova'));
				case 'featured_product':
					return array('name'=>$tab,'title'=>esc_html__('Featured Products','snsnova'),'short_title'=>esc_html__('Featured','snsnova'));
				case 'top_rate':
					return array('name'=>$tab,'title'=> esc_html__('Top Rated Products','snsnova'),'short_title'=>esc_html__('Top Rated', 'snsnova'));
				case 'best_selling':
					return array('name'=>$tab,'title'=>esc_html__('BestSeller Products','snsnova'),'short_title'=>esc_html__('Best Seller','snsnova'));
				case 'on_sale':
					return array('name'=>$tab,'title'=>esc_html__('Special Products','snsnova'),'short_title'=>esc_html__('Special','snsnova'));
			}
		}
	}
	public function snsnova_getCats(){
		$cats = get_terms('product_cat');
		$arr = array();
		foreach ($cats as $cat) {
			$arr[] = $cat->slug;
		}
		return $arr;
	}
}

vc_map( array(
	"name" => esc_html__("SNS Product Tabs","snsnova"),
	"base" => "sns_product_tabs",
	"icon" => "sns_icon_product_tabs",
	"class" => "sns_product_tabs",
	"category" => esc_html__("WooCommerce","snsnova"),
	"description" => esc_html__( "WooCommerce's product tabs", "snsnova" ),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Title","snsnova"),
			"param_name" => "title",
			"value" => "New Products"
		),
		array(
			"type" => "textarea",
			"heading" => esc_html__("Pre text", "snsnova"),
			"param_name" => "pretext"
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Template","snsnova"),
			"param_name" => "template",
			"value" => array(
				"Grid" => "grid",
				"Carousel" =>  "carousel"
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Tab types","snsnova"),
			"param_name" => "tab_types",
			"value" => array(
				"Categories" => "category",
				"Order By" =>  "orderby"
			),
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"value" => $woocat_value,
			"heading" => esc_html__("Select Category","snsnova"),
			"param_name" => "list_cat",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Order By for all tab","snsnova"),
			"param_name" => "orderby",
			"value" => array(
				esc_html__('Latest Products', 'snsnova') => "recent",
				esc_html__('BestSeller Products', 'snsnova') => "best_selling",
				esc_html__('Top Rated Products', 'snsnova') => "top_rate",
				esc_html__('Special Products', 'snsnova') => "on_sale",
				esc_html__('Featured Products', 'snsnova') => "featured_product",
				esc_html__('Recent Review', 'snsnova') => "recent_review",
			),
			"dependency" => array("element" => "tab_types" , "value" => "category" ),
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => esc_html__("Select Order By","snsnova"),
			"param_name" => "list_orderby",
			"value" => array(
				esc_html__('Latest Products', 'snsnova') => "recent",
				esc_html__('BestSeller Products', 'snsnova') => "best_selling",
				esc_html__('Top Rated Products', 'snsnova') => "top_rate",
				esc_html__('Special Products', 'snsnova') => "on_sale",
				esc_html__('Featured Products', 'snsnova') => "featured_product",
				esc_html__('Recent Review', 'snsnova') => "recent_review",
			),
			"dependency" => array("element" => "tab_types" , "value" => "orderby" ),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Row","snsnova"),
			"param_name" => "row",
			"dependency" => array("element" => "template" , "value" => "grid" ),
			"value" => "2"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Column per Row","snsnova"),
			"param_name" => "col",
			"dependency" => array("element" => "template" , "value" => "grid" ),
			"value" => "4"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Number product with each click to Load more button","snsnova"),
			"param_name" => "number_load",
			"dependency" => array("element" => "template" , "value" => "grid" ),
			"value" => "4"
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => esc_html__("Effect for product when click to Load more button","snsnova"),
			"param_name" => "effect_load",
			"value" => array(
				esc_html__('zoomOut', 'snsnova') => "zoomOut",
				esc_html__('zoomIn', 'snsnova') => "zoomIn",
				esc_html__('pageRight', 'snsnova') => "pageRight",
				esc_html__('pageLeft', 'snsnova') => "pageLeft",
				esc_html__('pageTop', 'snsnova') => "pageTop",
				esc_html__('pageBottom', 'snsnova') => "pageBottom",
				esc_html__('starwars', 'snsnova') => "starwars",
				esc_html__('slideBottom', 'snsnova') => "slideBottom",
				esc_html__('slideLeft', 'snsnova') => "slideLeft",
				esc_html__('slideRight', 'snsnova') => "slideRight",
				esc_html__('bounceIn', 'snsnova') => "bounceIn",
			),
			"dependency" => array("element" => "template" , "value" => "grid" ),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Product number limit","snsnova"),
			"param_name" => "number_limit",
			"dependency" => array("element" => "template" , "value" => "carousel" ),
			"value" => "10"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => esc_html__("Product number display","snsnova"),
			"param_name" => "number_display",
			"dependency" => array("element" => "template" , "value" => "carousel" ),
			"value" => "4"
		),
		$vc_add_css_animation,
		$sns_extra_class,
		
	)
) );

?>