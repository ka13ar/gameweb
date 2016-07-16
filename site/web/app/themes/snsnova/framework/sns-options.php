<?php
if ( ! class_exists( 'SnsNova_Options' ) ) {
class SnsNova_Options {
	public $args = array();
	public $sections = array();
	public $theme;
	public $ReduxFramework;

	public function __construct() {
		if ( ! class_exists( 'ReduxFramework' ) ) {
		 return;
		}
		if ( true == Redux_Helpers::isTheme( THEME_DIR . '/framework/sns-options.php' ) ) {
			$this->snsnova_initSettings();
		} else {
			add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
		}
	}

	public function snsnova_initSettings() {
	    // Set the default arguments
	    $this->snsnova_setArguments();
	    // Set a few help tabs so you can see how it's done
	    $this->snsnova_setHelpTabs();
	    // Create the sections and fields
	    $this->snsnova_setSections();
	    if ( ! isset( $this->args['opt_name'] ) ) {
	        return;
	    }
	    $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
	}

	public function snsnova_setArguments() {
	    $theme = wp_get_theme();
	    $this->args = array(
            'opt_name'  			=> 'snsnova_themeoptions',
            'display_name' 			=> $theme->get( 'Name' ),
            'menu_type'          	=> 'menu',
	        'allow_sub_menu'     	=> true,
	        'menu_title'         	=> esc_html__( 'SNS Nova', 'snsnova' ),
	        'page_title'         	=> esc_html__( 'SNS Nova', 'snsnova' ),
	        'customizer' 			=> true,
	        'page_priority'      	=> 50,
	        'menu_icon' 			=> '',
	        'hints'              	=> array(
	            'icon'          => 'icon-question-sign',
	            'icon_position' => 'right',
	            'icon_color'    => 'lightgray',
	            'icon_size'     => 'normal',
	            'tip_style'     => array(
	                'color'   	=> 'light',
	                'shadow'  	=> true,
	                'rounded' 	=> false,
	                'style'   	=> '',
	            ),
	            'tip_position'  => array(
	                'my' => 'top left',
	                'at' => 'bottom right',
	            ),
	            'tip_effect'    => array(
	                'show' => array(
	                    'effect'   => 'slide',
	                    'duration' => '500',
	                    'event'    => 'mouseover',
	                ),
	                'hide' => array(
	                    'effect'   => 'slide',
	                    'duration' => '500',
	                    'event'    => 'click mouseleave',
	                ),
	            ),
	        ),
	        'dev_mode' 				=> false,
	        //'forced_dev_mode_off'	=> false,
	        'show_options_object'   => false
	    );
	}

	public function snsnova_setHelpTabs() {
	    $this->args['help_tabs'][] = array(
	        'id'      => 'redux-help-tab-1',
	        'title'   => esc_html__( 'Theme Information 1', 'snsnova' ),
	        'content' => wp_kses(__( '<p>This is the tab content, HTML is allowed.</p>', 'snsnova' ), array(
							'p' => array()
						 ))
	    );
	    $this->args['help_tabs'][] = array(
	        'id'      => 'redux-help-tab-2',
	        'title'   => esc_html__( 'Theme Information 2', 'snsnova' ),
	        'content' => wp_kses(__( '<p>This is the tab content, HTML is allowed.</p>', 'snsnova' ), array(
							'p' => array()
						 ))
	    );
	    $this->args['help_sidebar'] = wp_kses(__( '<p>This is the sidebar content, HTML is allowed.</p>', 'snsnova' ), array(
										'p' => array()
									  ));
	}
	public function snsnova_setSections() {
		$this->sections[] = $this->snsnova_importSampleData();
	    $this->sections[] = $this->snsnova_getGeneralCfg();
	    $this->sections[] = $this->snsnova_getHeaderCfg();
	    $this->sections[] = $this->snsnova_getFooterCfg();
	    $this->sections[] = $this->snsnova_getBlogCfg();
	    $this->sections[] = $this->snsnova_getWooCfg();
	    $this->sections[] = $this->snsnova_getAdvanceCfg();
	}
	public function snsnova_getPatterns(){
		$patterns = array();
		$path = THEME_DIR . '/assets/img/patterns';
		$regex = '/(\.gif)|(.jpg)|(.png)|(.bmp)$/i';
		if( !is_dir($path) ) return;
		
		$dk =  opendir ( $path );
		$files = array();
		while ( false !== ($filename = readdir ( $dk )) ) {
			if (preg_match ( $regex, $filename )) {
				$files[] = $filename;
			}
		}
		foreach( $files as $p ) $patterns[] = $p;
		return $patterns;
	}
	public function snsnova_importSampleData(){
		$desc = '';
		if( is_plugin_active('wordpress-importer/wordpress-importer.php') ){
			$desc .= esc_html__('Note: Please Deactivate plugin Wordpress Importer before click Import!', 'snsnova');
		}
    	$subtitle = '
	    	<input type=\'button\' class=\'button\' name=\'btn_sampledata\' id=\'btn_sampledata\' value=\'Import\' />
	    	<div class=\'sns-importprocess\'>
	    		<div  class=\'sns-importprocess-width\'></div>
	    	</div>
	    	<span id=\'sns-importmsg\'><span class=\'status\'></span></span>
	    	<div id="sns-import-tablecontent">
				<label>List contents will import:</label>
				<ul>
				  <li class="theme-cfg"><i class="fa fa-hand-pointer-o"></i>Theme config</li>
				  <li class="revslider-cfg"><i class="fa fa-hand-pointer-o"></i>Revolution Slider config</li>
				  <li class="all-content"><i class="fa fa-hand-pointer-o"></i>All contents</li>
				  <li class="widget-cfg"><i class="fa fa-hand-pointer-o"></i>Widget config</li>
				</ul>
			</div>
    	';
		return  array(
		    'icon' => 'el-icon-briefcase',
		    'title' => esc_html__('Demo content', 'snsnova'),
		    'subsection' => true,
		    'fields' => array(
		        array(
		        	'title' => '',
		            'subtitle' => $subtitle,
		            'desc'	=> $desc,
		            'id' => 'theme_data',
		            'icon' => true,
		            'type' => 'image_select',
		            'default' => 'sns_nova',
		            'options' => array(
		                'sns_nova' => get_template_directory_uri().'/assets/img/logo.png',
		            ),
		        )
		    )
		);
	}
	public function snsnova_getGeneralCfg() {
		$patterns = $this->snsnova_getPatterns();
		$pattern_opt = array();
		foreach($patterns as $pattern)
			$pattern_opt[$pattern] = array('img' => THEME_URI . '/assets/img/patterns/' . $pattern, 'alt' => $pattern);
		
	    return array(
	        'title'		=> esc_html__( 'General', 'snsnova' ),
	        'icon'		=> 'el-icon-cog',
	        'fields'	=> array(
	            array(
	                'id'       => 'theme_color',
	                'type'     => 'color',
	                'output'   => array( '.site-title' ),
	                'title'    => esc_html__( 'Theme Color', 'snsnova' ),
	                'default'  => '#3cabda',
	        		'transparent'	=> false
	            ),
	            array(
	                'id'       => 'use_boxedlayout',
	                'type'     => 'switch',
	                'title'    => 'Use Boxed Layout',
	                'default'  => 0,
	                'on'       => 'Yes',
                    'off'      => 'No',
	            ),
	            array(
	                'id'       => 'body_bg',
	                'type'     => 'background',
	                'output'   => array( 'body' ),
	                'title'    => esc_html__( 'Body Background', 'snsnova' ),
	                'background-image' => false,
	        		'preview'	=> false,
	        		'required' => array( 'use_boxedlayout', '=', '1' )
	            ),
	            array(
	                'id'       => 'body_bg_type',
	                'type'     => 'select',
	                'title'    => 'Body Background Image',
	                'options'  => array(
	                    'none'   	=> 'No image',
	                    'pantern'   => 'Pantern',
	                    'img'  		=> 'Image',
	                ),
	                'default'  => 'pantern',
	                'select2'  => array( 'allowClear' => false ),
	                'required' => array( 'use_boxedlayout', '=', '1' )
	            ),
	            array(
	                'id'       => 'body_bg_type_pantern',
	                'type'     => 'image_select',
	                'options'  => $pattern_opt,
	                'width'		=>  '50px !important',
	                'height'	=> 50,
              	  	'required' => array( 'body_bg_type', '=', array( 'pantern' ) )
	            ),
			    array(
			        'id'		=> 'body_bg_type_img',
			        'type'		=> 'media',
              	 	'required' => array( 'body_bg_type', '=', array( 'img' ) ),
			    ),
	            array(
	                'id'          => 'body_font',
	                'type'        => 'typography',
	                'title'       => esc_html__( 'Body font', 'snsnova' ),
	                'line-height'   => false,
	                'text-align'   => false,
	                'color'         => true,
	                'all_styles'  => true,
	                'units'       => 'px',
	                // 'subsets'       => true,
	                'default'     => array(
	                    'font-size'   => '12px',
	                    'font-family' => 'Arial,Helvetica,sans-serif',
	                    'font-weight' => '400',
	                    'color'		  => '#909090'
	                ),
	            ),
	            array(
	                'id'          => 'secondary_font',
	                'type'        => 'typography',
	                'title'       => esc_html__( 'Secondary font', 'snsnova' ),
	                'line-height'   => false,
	                'text-align'   => false,
	                'color'         => false,
	                'all_styles'  => true,
	                'units'       => 'px',
                	'font-size'     => false,
	                // 'subsets'       => true,
	                'default'     => array(
	                    'font-family' => 'Open Sans',
	                    'font-weight' => '300',
	                ),
	            ),
	            array(
	                'id'       => 'secondary_font_target',
	                'type'     => 'textarea',
	                'title'    => esc_html__( 'Secondary font target', 'snsnova' ),
	                'default'  => 'h1, h2, h3, h4, h5, h6,
input[type="submit"],
input[type="button"],
.button,
button,
blockquote,
#wp-calendar tfoot td a,
.gfont,
.onsale,
.price,
.widget a.title,
.widget .product-title,
.widget .post-title,
#sns_titlepage,
#sns_mainmenu > ul > li.menu-item > a',
	                'validate' => 'no_html'
	            ),
	        )
	    );
	}
	public function snsnova_getHeaderCfg() {
	    return array(
	        'title'		=> esc_html__( 'Header', 'snsnova' ),
	        'icon'		=> 'el el-brush',
	        'fields'	=> array(
			    array(
			        'id'		=> 'header_logo',
			        'type'		=> 'media',
			        'default'	=> '',
			        'title'		=> esc_html__( 'Logo', 'snsnova' ),
                	'subtitle' => esc_html__( 'If this is not selected, This theme will be display logo with "theme/sns_nova/img/logo.png"', 'snsnova' ),
			        'desc'		=> esc_html__( 'Image that you want to use as logo', 'snsnova' ),
			    ),
			    array(
	                'id'       => 'use_logocolor',
	                'type'     => 'switch',
	                'title'    => 'Use background-color for logo',
	                'subtitle' => esc_html__( 'Some logo image is transparent. Maybe it need background-color', 'snsnova' ),
	                'default'  => false,
	                'on'       => 'Yes',
                    'off'      => 'No',
	            ),
			    array(
	                'id'       => 'use_stickmenu',
	                'type'     => 'switch',
	                'title'    => 'Enable Sticky Menu',
	                'subtitle' => esc_html__( 'Keep menu on top when scroll down/up', 'snsnova' ),
	                'default'  => false,
	                'on'       => 'Yes',
                    'off'      => 'No',
	            ),
	            array(
			        'id'		=> 'menu_bg',
			        'type'		=> 'media',
			        'title'		=> esc_html__( 'Background Image for menu wrapper', 'snsnova' ),
                	'subtitle' => esc_html__( 'Menu wrapper contain: Slideshow, Page title, Breadcrumbs', 'snsnova' ),
			        'desc'		=> esc_html__( 'This is default vaule fo all page', 'snsnova' ),
			    ),
	        )
	    );
	}
	public function snsnova_getFooterCfg(){
		return array(
	        'title'		=> esc_html__( 'Footer', 'snsnova' ),
	        'icon'		=> 'el el-link',
	        'fields'	=> array(
	          	array(
	        		'id'		=> 'footer_layout',
	        		'type'		=> 'select',
	        		'title'		=> esc_html__( "Footer Layout", "snsnova" ),
	        		'default'   => 'full',
	        		'options'   => array(
			        			'full' => esc_html__( "Full Width", "snsnova" ),
			        			'two-col' => esc_html__( "Two Columns", "snsnova" ),
	        		),
	        	),
			    array(
			        'id'		=> 'payment_img',
			        'type'		=> 'media',
			        'title'		=> esc_html__( "Payment method's image", "snsnova" )
			    ),
			    array(
			        'id'		=> 'copyright',
			        'type'		=> 'textarea',
			        'title'		=> esc_html__( "Copyright", "snsnova" ),
			        'default' => esc_html__( "Designed by SNSTheme.Com", "snsnova" ),
			    ),
	        )
	    );
	}
	public function snsnova_getBlogCfg() {
		$siderbars = array(
		    'right-sidebar' => esc_html__( 'Right Sidebar', 'snsnova' ),
			'left-sidebar' => esc_html__( 'Left Sidebar', 'snsnova' ),
			'woo-sidebar' => esc_html__( 'Woo Sidebar', 'snsnova' ),
		);
		//if( is_admin() ) wp_enqueue_style('snsnova-admincss', THEME_URI . '/assets/css/admin-theme-option.css');
	    return array(
	        'title'		=> esc_html__( 'Blog', 'snsnova' ),
	        'icon'		=> 'el el-file-edit',
	        'fields'	=> array(
				array(
				    'id'       => 'layouttype',
				    'type'     => 'image_select',
				    'title'    => esc_html__('Default Blog Layout', 'snsnova'), 
				    'options'  => array(
				        'm'      => array(
				            'alt'   => esc_html__( 'Without Sidebar', 'snsnova' ), 
				            'img'   => THEME_URI.'/assets/img/admin/m.jpg'
				        ),
				        'l-m'      => array(
				            'alt'   => esc_html__( 'Use Left Sidebar', 'snsnova' ), 
				            'img'   => THEME_URI.'/assets/img/admin/l-m.jpg'
				        ),
				        'm-r'      => array(
				            'alt'  => esc_html__( 'Use Right Sidebar', 'snsnova' ), 
				            'img'  => THEME_URI.'/assets/img/admin/m-r.jpg'
				        ),
				        'l-m-r'      => array(
				            'alt'   => esc_html__( 'Use Left & Right Sidebar', 'snsnova' ), 
				            'img'   => THEME_URI.'/assets/img/admin/l-m-r.jpg'
				        )
				    ),
				    'default' => 'l-m'
				),
				// Left Sidebar
				array(
					'title'  => esc_html__( 'Left Sidebar', 'snsnova' ),
					'id'    => "leftsidebar",
					//'desc'  => esc_html__( 'Text description', 'snsnova' ),
					'type'  => 'select',
					'options'	=> $siderbars,
					'multiselect'	=> false,
					'required' => array( 'layouttype', '=', array( 'l-m', 'l-m-r' ) )
				),
				// Right Sidebar
				array(
					'title'  => esc_html__( 'Right Sidebar', 'snsnova' ),
					'id'    => "rightsidebar",
					//'desc'  => esc_html__( 'Text description', 'snsnova' ),
					'type'  => 'select',
					'options'	=> $siderbars,
					'multiselect'	=> false,
					'required' => array( 'layouttype', '=', array( 'm-r', 'l-m-r' ) )
				),
				array( 
		        	'title' => esc_html__( 'Blog Style', 'snsnova'),
					'id' => 'blog_type',
					'default' => '',
					'type' => 'select',
					'multiselect' => false ,
					'options' => array(
						'' => 'Standard Blog',
						// 'grid' =>  'Grid Blog',
						'list' =>  'List Blog'
					)
				),
				// array(
	   //              'id'       => 'blog_gridcol',
	   //              'type'     => 'select',
	   //              'title'    => esc_html__( 'Grid columns', 'snsnova' ),
	   //              'subtitle'  => esc_html__( 'We are using grid bootstap - 12 cols layout', 'snsnova' ),
	   //              'default'  => '3',
	   //              'options'  => array(
	   //                  '2' => '2',
	   //                  '3' => '3',
	   //                  '4' => '4',
	   //                  '6' => '6',
	   //              ),
	   //              'required' => array( 'blog_type', '=', array( 'grid' ) )
	   //          ),
	   //          array(
	   //              'id'       => 'blog_enablemasonry',
	   //              'type'     => 'switch',
	   //              'title'    => esc_html__( 'Enable Masonry', 'snsnova' ),
	   //              'default'  => true,
	   //              'on'       => 'Yes',
	   //              'off'      => 'No',
	   //              'required' => array( 'blog_type', '=', array( 'grid' ) )
	   //          ),
	   //          array(
	   //              'id'       => 'blog_pagination',
	   //              'type'     => 'select',
	   //              'title'    => esc_html__( 'Blog Pagination', 'snsnova' ),
	   //              'default'  => '',
	   //              'options'  => array(
	   //                  '' => 'Standard',
	   //                  'infinite' => 'Infinite Scroll',
	   //                  'loadmore' => 'Load More Button'
	   //              ),
	   //              'required' => array( 'blog_type', '=', array( 'grid' ) )
	   //          ),
				array(
				    'id'   =>'divider_blog',
				    'type' => 'divide'
				),
				array(
	                'id'       => 'img_size',
	                'type'     => 'select',
	                'title'    => esc_html__( 'Image Size', 'snsnova' ),
	                'default'  => 'full',
	                'options'  => array(
	                    "full" => "Full",
						"large" => "Large",
						"medium" =>  "Medium",
						"snsnova_bloglist_thumb" => "Blog List Thumb"
	                ),
	            ),
	            array(
	                'id'       => 'excerpt_length',
	                'type'     => 'text',
	                'title'    => esc_html__( 'Blog Excerpt Length', 'snsnova' ),
	                'default'  => '35',
	            ),
				array(
	                'id'       => 'enable_readmore',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Enable Read More Button', 'snsnova' ),
	                'subtitle' => esc_html__( 'Apply for post has Excerpt', 'snsnova' ),
	                'default'  => false,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
	                'id'       => 'show_categories',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Show Categories for Blog Entries Page', 'snsnova' ),
	                'default'  => true,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
	                'id'       => 'show_author',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Show Author for Blog Entries Page', 'snsnova' ),
	                'default'  => true,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
	                'id'       => 'show_tags',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Show Tags for Blog Entries Page', 'snsnova' ),
	                'default'  => true,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
	                'id'       => 'show_date',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Show Date for Blog Entries Page', 'snsnova' ),
	                'default'  => true,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
				    'id'   =>'divider_post',
				    //'desc' => esc_html__('Options for blog page', 'snsnova'),
				    'type' => 'divide'
				),
	            array(
	                'id'       => 'show_postauthor',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Enable Author Info on Post Detail', 'snsnova' ),
	                'default'  => true,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
	                'id'       => 'enalble_related',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Enable Related Posts on Post Detail', 'snsnova' ),
	                'default'  => false,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
	                'id'       => 'related_posts_by',
	                'type'     => 'select',
	                'title'    => esc_html__( 'Related Posts By', 'snsnova' ),
	                'default'  => 'cat',
	                'options'  => array(
	                    "cat" => "Categories",
						"tag" => "Tags",
	                ),
	                'desc'		=> 'Get related posts by Categories or Tags.',
	                'required' => array( 'enalble_related', '=', true )
	            ),
	            array(
	                'id'       => 'related_num',
	                'type'     => 'text',
	                'title'    => esc_html__( 'Related Posts Number', 'snsnova' ),
	                'default'  => '5',
	                'required' => array( 'enalble_related', '=', true )
	            ),
	            array(
	                'id'       => 'show_postsharebox',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Enable Share Box on Post Detail', 'snsnova' ),
	                'default'  => false,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
				    'id'       => 'show_facebook_sharebox',
				    'type'     => 'checkbox',
				    'title'    => esc_html__('Show Facebook in Sharing Box', 'snsnova'),
				    'required' => array( 'show_postsharebox', '=', true ),
				    'default'  => '1'// 1 = on | 0 = off
				),
	            array(
				    'id'       => 'show_twitter_sharebox',
				    'type'     => 'checkbox',
				    'title'    => esc_html__('Show Twitter in Sharing Box', 'snsnova'), 
				    'required' => array( 'show_postsharebox', '=', true ),
				    'default'  => '1'// 1 = on | 0 = off
				),
				array(
				    'id'       => 'show_gplus_sharebox',
				    'type'     => 'checkbox',
				    'title'    => esc_html__('Show G + in Sharing Box', 'snsnova'),
				    'required' => array( 'show_postsharebox', '=', true ), 
				    'default'  => '1'// 1 = on | 0 = off
				),
				array(
				    'id'       => 'show_linkedin_sharebox',
				    'type'     => 'checkbox',
				    'title'    => esc_html__('Show Linkedin in Sharing Box', 'snsnova'), 
				    'required' => array( 'show_postsharebox', '=', true ),
				    'default'  => '1'// 1 = on | 0 = off
				),
				array(
				    'id'       => 'show_pinterest_sharebox',
				    'type'     => 'checkbox',
				    'title'    => esc_html__('Show Pinterest in Sharing Box', 'snsnova'), 
				    'required' => array( 'show_postsharebox', '=', true ),
				    'default'  => '1'// 1 = on | 0 = off
				),
				array(
				    'id'       => 'show_tumblr_sharebox',
				    'type'     => 'checkbox',
				    'title'    => esc_html__('Show Tumblr in Sharing Box', 'snsnova'), 
				    'required' => array( 'show_postsharebox', '=', true ),
				    'default'  => '1'// 1 = on | 0 = off
				),
				array(
				    'id'       => 'show_email_sharebox',
				    'type'     => 'checkbox',
				    'title'    => esc_html__('Show Send Email in Sharing Box', 'snsnova'), 
				    'required' => array( 'show_postsharebox', '=', true ),
				    'default'  => '1'// 1 = on | 0 = off
				),

	            
	        )
	    );
	}
	public function snsnova_getWooCfg() {
		return array(
	        'title'		=> esc_html__( 'WooCommerce', 'snsnova' ),
	        'icon'		=> 'el el-shopping-cart',
	        'fields'	=> array(
	        	array(
	                'id'       => 'woo_uselazyload',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Use lazyload for Product Image', 'snsnova' ),
	                'default'  => false,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
	                'id'		=> 'woo_list_modeview',
	                'type'		=> 'select',
	                'title'		=> esc_html__( 'Default mode view for listing page', 'snsnova' ),
	                'options'  => array(
	                    'grid' => 'Grid',
	                    'list' => 'List',
	                ),
	                'default'  => 'grid'
			    ),
	            array(
	                'id'       => 'woo_grid_col',
	                'type'     => 'select',
	                'title'    => esc_html__( 'Grid columns', 'snsnova' ),
	                'subtitle'  => esc_html__( 'We are using grid bootstap - 12 cols layout', 'snsnova' ),
	                'default'  => '3',
	                'options'  => array(
	                    '1' => '1',
	                    '2' => '2',
	                    '3' => '3',
	                    '4' => '4',
	                    '6' => '6',
	                ),
	            ),
	            array(
	                'id'       => 'woo_number_perpage',
	                'type'     => 'text',
	                'title'    => esc_html__( 'Number products per listing page', 'snsnova' ),
	                'default'  => '9',
	            ),
	            array(
				    'id'   =>'divider_blog',
				    'type' => 'divide'
				),
	            array(
	                'id'       => 'woo_sharebox',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Enable Share box', 'snsnova' ),
	                'default'  => true,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
	                'id'       => 'woo_related',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Enable Related Products', 'snsnova' ),
	                'default'  => true,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
	            	'id'       => 'woo_related_num',
                 	'type'     => 'text',
                 	'title'    => esc_html__( 'Number Related Products to display', 'snsnova' ),
                 	'required' => array( 'woo_related', '=', '1' ),
                 	'default'  => '6',
                )
	        )
	    );
	}
	public function snsnova_getAdvanceCfg() {
	    return array(
	        'title'		=> esc_html__( 'Advance', 'snsnova' ),
	        'icon'		=> 'el el-wrench',
	        'fields'	=> array(
	            array(
	                'id'       => 'advance_tooltip',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Enable Tooltip', 'snsnova' ),
	                'default'  => true,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
	                'id'       => 'advance_cpanel',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Enable Cpanel', 'snsnova' ),
	                'default'  => true,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
	                'id'       => 'advance_scrolltotop',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Enable Button Scroll To Top', 'snsnova' ),
	                'default'  => true,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
	                'id'       => 'disable_adminbar',
	                'type'     => 'switch',
	                'title'    => esc_html__( 'Disable Admin Bar on frontend', 'snsnova' ),
	                'default'  => false,
	                'on'       => 'Yes',
	                'off'      => 'No',
	            ),
	            array(
	                'id'		=> 'advance_scss_compile',
	                'type'		=> 'select',
	                'title'		=> esc_html__( 'SCSS Compile', 'snsnova' ),
	                'options'  => array(
	                    '1' => 'Only compile when don\'t have the css file',
	                    '2' => 'Alway compile'
	                ),
	                'default'  => '2'
			    ),
	            array(
	                'id'		=> 'advance_scss_format',
	                'type'		=> 'select',
	                'title'		=> esc_html__( 'CSS Format', 'snsnova' ),
	                'options'  => array(
	                    'scss_formatter' => 'scss_formatter',
	                    'scss_formatter_nested' => 'scss_formatter_nested',
	                    'scss_formatter_compressed' => 'scss_formatter_compressed',
	                ),
	                'default'  => 'scss_formatter_nested'
			    ),
	            array(
	                'id'       => 'advance_customcss',
	                'type'     => 'ace_editor',
	                'title'    => esc_html__( 'Custom CSS', 'snsnova' ),
	                'subtitle' => esc_html__( 'Enter your CSS code here.', 'snsnova' ),
	                'mode'     => 'css',
	                'theme'    => 'monokai',
	                'default'  => ""
	            ),
	            array(
	                'id'       => 'advance_customjs',
	                'type'     => 'ace_editor',
	                'title'    => esc_html__( 'Custom JS', 'snsnova' ),
	                'subtitle' => esc_html__( 'Enter your JS code here.', 'snsnova' ),
	                'mode'     => 'javascript',
	                'theme'    => 'chrome',
	                'default'  => ""
	            ),
	        )
	    );
	}
}
}

?>