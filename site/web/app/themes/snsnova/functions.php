<?php
add_filter('https_ssl_verify', '__return_false');
define( 'THEME_DIR', get_template_directory() );
define( 'THEME_URI', get_template_directory_uri() );
// Require framework
require_once( THEME_DIR.'/framework/init.php' );
/**
	Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
 **/
add_action( 'vc_before_init', 'snsnova_vcSetAsTheme' );
function snsnova_vcSetAsTheme() {
	vc_set_as_theme(true);
}
// Initialising Visual shortcode editor
 if (class_exists('WPBakeryVisualComposerAbstract')) {
 	function requireVcExtend(){
 		include_once( get_template_directory().'/vc_extend/extend-vc.php');
 	}
 	add_action('init', 'requireVcExtend', 2);
 }
/**
	Width of content, it's max width of content without sidebar.
 **/
if ( ! isset( $content_width ) ) { $content_width = 660; }

/**
	Set base function for theme.
 **/
if ( ! function_exists( 'snsnova_setup' ) ) {
    function snsnova_setup() {
        global $snsnova_opt;
    	// Load default theme textdomain.
        load_theme_textdomain( 'snsnova', THEME_DIR . '/languages' );
		// Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );
		// Enable support for Post Thumbnails on posts and pages.
        add_theme_support( 'post-thumbnails' );
        // Add title-tag, it auto title of head
        add_theme_support( 'title-tag' );
        // Enable support for Post Formats.
        add_theme_support( 'post-formats',
            array(
                'video', 'quote', 'link', 'gallery'
            )
        );
		//Setup the WordPress core custom background & custom header feature.
         $default_background = array(
            'default-color' => '#FFF',
        );
        add_theme_support( 'custom-background', $default_background );
        $default_header = array();
        add_theme_support( 'custom-header', $default_header );
        // Register navigations
	    register_nav_menus( array(
			'main_navigation' => esc_html__( 'Main navigation', 'snsnova' ),
			//'left_navigation' => esc_html__( 'Left navigation', 'snsnova' ),
			'top_navigation'  => esc_html__( 'Top navigation', 'snsnova' ),
			'footer_navigation'  => esc_html__( 'Footer navigation', 'snsnova' ),
		) );
    }
    add_action ( 'init', 'snsnova_setup' ); // or add_action( 'after_setup_theme', 'snsnova_setup' );
}

add_filter( 'body_class', 'snsnova_bodyclass' );
function snsnova_bodyclass( $classes ) {
    global $snsnova_opt, $snsnova_obj;
    if ($snsnova_obj->snsnova_getOption('use_boxedlayout') == 1) {
        $classes[] = 'boxed-layout';
    }
    if( $snsnova_opt['advance_tooltip'] ){
        $classes[] = 'use-tooltip';
    }
    if( $snsnova_obj->snsnova_getOption('use_stickmenu') == 1){
        $classes[] = 'use_stickmenu';
    }
    if( $snsnova_obj->snsnova_getOption('use_logocolor') == 1){
        $classes[] = 'use_logocolor';
    }
    if ( $snsnova_opt['woo_uselazyload'] == 1 ){
        $classes[] = 'use_lazyload';
    }
    return $classes;
}
//add_filter('widget_text', 'do_shortcode');
function snsnova_widgetlocations(){
    // Register widgetized locations
    if(function_exists('register_sidebar')) {
        register_sidebar(array(
           'name' => 'Widget Area',
           'id'   => 'widget-area',
            'description'   => esc_html__( 'These are widgets for the Widget Area.','snsnova' ),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ));
        register_sidebar(array(
           'name' => 'Topleft Widgets',
           'id'   => 'topleft-widgets',
            'description'   => esc_html__( 'These are widgets for the Topleft.','snsnova' ),
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '<h4>',
            'after_title'   => '</h4>'
        ));
        register_sidebar(array(
           'name' => 'Header Right',
           'id'   => 'header-right',
            'description'   => esc_html__( 'These are widgets for the Header Right.','snsnova' ),
            'before_widget' => '<div class="header-right-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4>',
            'after_title'   => '</h4>'
        ));
        register_sidebar(array(
           'name' => 'Footer Widgets',
           'id'   => 'footer-widgets',
            'description'   => esc_html__( 'These are widgets for the Footer.','snsnova' ),
            'before_widget' => '<div id="%1$s" class="widget widget-footer %2$s col-sm-3">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4>',
            'after_title'   => '</h4>'
        ));

        register_sidebar(
            array(
            'name' => 'Right Sidebar',
            'id' => 'right-sidebar',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ));

        register_sidebar(
            array(
            'name' => 'Right2 Sidebar',
            'id' => 'right2-sidebar',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ));

        register_sidebar(
            array(
            'name' => 'Left Sidebar',
            'id' => 'left-sidebar',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ));

        register_sidebar(
            array(
            'name' => 'Woo Sidebar',
            'id' => 'woo-sidebar',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ));

        register_sidebar(
            array(
            'name' => 'Product Sidebar',
            'id' => 'product-sidebar',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ));
        register_sidebar(
            array(
            'name' => 'Product Tab Sidebar',
            'id' => 'product-tab-sidebar',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ));
    }
}
add_action( 'widgets_init', 'snsnova_widgetlocations' );
/**
	Add styles & scripts
 **/
function snsnova_scripts() {
	global $snsnova_opt, $snsnova_obj;
    $optimize = '.min';
    $theme_color = $snsnova_obj->snsnova_getOption('theme_color');
    if ( is_page() && function_exists('rwmb_meta') && rwmb_meta('snsnova_page_themecolor') == 1 && rwmb_meta('snsnova_themecolor') !='' ) {
        $theme_color = rwmb_meta('snsnova_themecolor');
    }
    if( empty($theme_color) ) $theme_color = '#3cabda';
    $theme_color = ( strpos($theme_color, '#') === false ) ? '#'.$theme_color : $theme_color;
    $body_color = $snsnova_opt['body_font']['color'];
    if( empty($body_color) ) $body_color = '#999999';
    if( $snsnova_opt ){
        $scss_compile = $snsnova_opt["advance_scss_compile"];
        $scss_format = $snsnova_opt['advance_scss_format'];
    }else{
        $scss_compile = 2;
        $scss_format = 'scss_formatter_nested';
    }
	// Compile scss and get css file name
	$css_file = $snsnova_obj->snsnova_getStyle(
        $scss_compile,
        array('dir' => THEME_DIR . '/assets/scss/', 'name' => 'theme'),
        array('dir' => THEME_DIR . '/assets/css/', 'name' => 'theme'),
        $scss_format,
        array('$color1' => $theme_color, '$color' => $body_color)
    );
	// Enqueue style
    wp_enqueue_style('snsnova-owlcarousel', THEME_URI . '/assets/css/owl.carousel.css');
	wp_enqueue_style('snsnova-fontawesome', THEME_URI . '/assets/fonts/awesome/css/font-awesome.min.css');
    wp_enqueue_style('snsnova-bootstrap', THEME_URI . '/assets/css/bootstrap.min.css');
	wp_enqueue_style('snsnova-themestyle', THEME_URI . '/assets/css/' . $css_file);
	// Enqueue script
    wp_enqueue_script('jquery-core', '/wp-includes/js/jquery/jquery.js', array(), '' );
    wp_enqueue_script('jquery-migrate', "/wp-includes/js/jquery/jquery-migrate.min.js", array(), '' );

    wp_enqueue_script('snsnova-bootstrap', THEME_URI . '/assets/js/bootstrap'.$optimize.'.js', array(), '', true);
    wp_enqueue_script('snsnova-owlcarousel', THEME_URI . '/assets/js/owl.carousel'.$optimize.'.js', array(), '', true);
    wp_enqueue_script('snsnova-bootstraptabdrop', THEME_URI . '/assets/js/bootstrap-tabdrop'.$optimize.'.js', array(), '', true);
    if( $snsnova_opt['woo_uselazyload'] == 1 ) wp_enqueue_script('snsnova-lazyload', THEME_URI . '/assets/js/jquery.lazyload'.$optimize.'.js', array(), '', true);
    wp_enqueue_script('snsnova-script', THEME_URI . '/assets/js/sns-script'.$optimize.'.js', array(), '', true);
    wp_enqueue_script('snsnova-html5shiv', 'https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js', array(), '');
    wp_script_add_data('snsnova-html5shiv', 'conditional', 'lt IE 9');
    wp_enqueue_script('snsnova-respond', 'https://oss.maxcdn.com/respond/1.4.2/respond.min.js', array(), '');
    wp_script_add_data('snsnova-respond', 'conditional', 'lt IE 9');
    // Add style inline with option in admin theme option
    wp_add_inline_style('snsnova-themestyle', snsnova_cssinline());
}
add_action( 'wp_enqueue_scripts', 'snsnova_scripts' );

// Editor style
add_editor_style('assets/css/editor-style.css');
/**
	Remove admin bar
 **/
global $snsnova_opt;
if ($snsnova_opt['disable_adminbar'] == 1) add_action('after_setup_theme', function(){show_admin_bar(false);});
/**
CSS inline
**/
function snsnova_cssinline(){
    global $snsnova_opt, $snsnova_obj;
    $inline_css = '';
    // Body style
    $bodycss = '';
    if ($snsnova_obj->snsnova_getOption('use_boxedlayout') == 1) {
        if ($snsnova_opt['body_bg_type'] == 'pantern') {
            $bodycss .= 'background-image: url('.THEME_URI.'/assets/img/patterns/'.$snsnova_opt['body_bg_type_pantern'].');';
        }elseif( $snsnova_opt['body_bg_type'] == 'img' ){
            $bodycss .= 'background-image: url('.$snsnova_opt['body_bg_type_img']['url'].');';
        }
    }
    if(isset($snsnova_opt['body_font']) && is_array($snsnova_opt['body_font'])) {
        $body_font = '';
        foreach($snsnova_opt['body_font'] as $propety => $value)
            if($value != 'true' && $value != 'false' && $value != '' && $propety != 'subsets')
                $body_font .= $propety . ':' . $value . ';';

        if($body_font != '') $bodycss .= $body_font;
    }
    $inline_css .= 'body {'.$bodycss.'}';
    // Selectors use google font
    if(isset($snsnova_opt['secondary_font_target']) && $snsnova_opt['secondary_font_target']) {
        if(isset($snsnova_opt['secondary_font']) && is_array($snsnova_opt['secondary_font'])) {
            $secondary_font = '';
            foreach($snsnova_opt['secondary_font'] as $propety => $value)
                if($value != 'true' && $value != 'false' && $value != '' && $propety != 'subsets')
                    $secondary_font .= $propety . ':' . $value . ';';

            if($secondary_font != '') $inline_css .= $snsnova_opt['secondary_font_target'] . ' {'.$secondary_font.'}';
        }
    }
    if(isset($snsnova_opt['advance_customcss']) && $snsnova_opt['advance_customcss']) {
        $inline_css .= $snsnova_opt['advance_customcss'];
    }
    return $inline_css;
}

/**
Custom JS
**/
function snsnova_customjs(){
    global $snsnova_opt;
    if(isset($snsnova_opt['advance_customjs']) && $snsnova_opt['advance_customjs']) {
        echo '<script type="text/javascript">';
        echo $snsnova_opt['advance_customjs'];
        echo '</script>';
    }
    return '';
}
add_action( 'wp_head', 'snsnova_customjs' );
/**
    Post thumnails
**/
add_theme_support('post-thumbnails');
if ( function_exists( 'add_image_size' ) ) {
       add_image_size( 'snsnova_bloglist_thumb', 350 , 200 , true );
       add_image_size( 'snsnova_relatedthumb', 300, 150, true ); // (cropped)
}
/**
	Tile for page, post
 **/
function snsnova_pagetitle(){
	// Disable title in page
	if( is_page() && function_exists('rwmb_meta') && rwmb_meta('snsnova_showtitle') == '0' ) return;
	// Show title in page, single post
	if( is_single() || is_page() || ( is_home() && get_option( 'show_on_front' ) == 'page' ) ) : ?>
		<h1 class="page-header">
          <?php the_title(); ?>
        </h1>
    <?php
    // Show title for category page
    elseif ( is_category() ) : ?>
        <h1 class="page-header">
          <?php single_cat_title(); ?>
        </h1>
    <?php
    // Author
    elseif ( is_author() ) : ?>
        <h1 class="page-header">
        <?php
            printf( esc_html__( 'All posts by: %s', 'snsnova' ), get_the_author() );
        ?>
        </h1>
        <?php if ( get_the_author_meta( 'description' ) ) : ?>
        <header class="archive-header">
            <div class="author-description"><p><?php the_author_meta( 'description' ); ?></p></div>
        </header>
        <?php endif; ?>
    <?php
    // Tag
    elseif ( is_tag() ) : ?>
        <h1 class="page-header">
            <?php printf( esc_html__( 'Tag Archives: %s', 'snsnova' ), single_tag_title( '', false ) ); ?>
        </h1>
        <?php
        $term_description = term_description();
        if ( ! empty( $term_description ) ) : ?>
        <header class="archive-header">
            <?php printf( '<div class="taxonomy-description">%s</div>', $term_description ); ?>
        </header>
        <?php endif; ?>
    <?php
    // Search
    elseif ( is_search() ) : ?>
    <h1 class="page-header"><?php printf( esc_html__( 'Search Results for: %s', 'snsnova' ), get_search_query() ); ?></h1>
    <?php
    // Archive
    elseif ( is_archive() ) : ?>
        <?php the_archive_title( '<h1 class="page-header">', '</h1>' ); ?>
        <?php
        if( get_the_archive_description() ): ?>
        <header class="archive-header">
            <?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
        </header>
        <?php
        endif;
        ?>
    <?php
    // Default
    else : ?>
        <h1 class="page-header">
          <?php the_title(); ?>
        </h1>
    <?php
	endif;
}
// Excerpt Function
if(!function_exists('snsnova_excerpt')){
    function snsnova_excerpt($limit, $afterlimit='[...]') {
        $limit = ($limit) ? $limit : 35 ;
        $excerpt = get_the_excerpt();
        if( $excerpt != '' ){
           $excerpt = explode(' ', strip_tags( $excerpt ), intval($limit));
        }else{
            $excerpt = explode(' ', strip_tags(get_the_content( )), intval($limit));
        }
        if ( count($excerpt) >= $limit ) {
            array_pop($excerpt);
            $excerpt = implode(" ",$excerpt).' '.$afterlimit;
        } else {
            $excerpt = implode(" ",$excerpt);
        }
        $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
        return strip_shortcodes( $excerpt );
    }
}
// Word Limiter
function snsnova_limitwords($string, $word_limit) {
    $words = explode(' ', $string);
    return implode(' ', array_slice($words, 0, $word_limit));
}
//
if(!function_exists('snsnova_sharebox')){
    function snsnova_sharebox( $layout='',$args=array() ){
        $default = array(
            'position' => 'top',
            'animation' => 'true'
            );
        $args = wp_parse_args( (array) $args, $default );

        $path = THEME_DIR.'/tpl-sharebox';
        if( $layout!='' ){
            $path = $path.'-'.$layout;
        }
        $path .= '.php';

        if( is_file($path) ){
            require($path);
        }

    }
}
//
if(!function_exists('snsnova_relatedpost')){
    function snsnova_relatedpost( $relate_count = -1, $posttype = 'post', $taxonomy = 'category'){
        global $post, $snsnova_obj;
        if($post){
            $post_id = $post->ID;
        }else{
            return '';
        }
        $args = array(
            'post_status' => 'publish',
            'posts_per_page' => $relate_count,
            'orderby' => 'date',
            'ignore_sticky_posts' => 1,
            'post__not_in' => array ($post_id)
        );
        $get_related_post_by = $snsnova_obj->snsnova_getOption('related_posts_by');
        if( $get_related_post_by == 'cat' ){
            $categories = wp_get_post_categories($post_id);
            $args['category__in'] =  $categories;
        }else{ // Get post by tags
            $posttags = wp_get_post_tags($post_id);
            $array_tags = array();
            if( $posttags ){
                foreach ($posttags as $tag) {
                    $tags = $tag->term_id;
                    array_push($array_tags, $tags);
                }
            }
            $args['tag__in'] = $array_tags;
        }
        $relates = new WP_Query( $args );
        $template_name = '/framework/tpl/posts/related_'.$posttype.'.php';
        if(is_file(THEME_DIR.$template_name)) {
            include(THEME_DIR.$template_name);
        }
        wp_reset_postdata();
    }
}
function snsnova_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment; ?>
    <?php $add_below = ''; ?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
        <div class="comment-body">
            <?php echo get_avatar($comment, 60); ?>
            <h4 class="comment-user"><?php echo get_comment_author_link(); ?></h4>
            <div class="comment-meta"><?php echo esc_html__("Posted on",'snsnova');?> <?php printf(esc_html__('%1$s at %2$s', 'snsnova'), get_comment_date(),  get_comment_time()) ?></a></div>
            <div class="reply">
              <?php edit_comment_link(esc_html__('Edit', 'snsnova'),'  ','') ?>
              <?php comment_reply_link(array_merge( $args, array('reply_text' => esc_html__('Reply', 'snsnova'), 'add_below' => 'comment', 'depth' => $depth, 'max_depth' => $args['max_depth'])))?>
            </div>
            <?php if ($comment->comment_approved == '0') : ?>
            <p>
                <em><?php echo esc_html__('Your comment is awaiting moderation.', 'snsnova') ?></em><br />
            </p>
            <?php endif; ?>
             <?php comment_text() ?>
        </div>
  <?php
}
/**
	Breadcrumbs
 **/
function snsnova_breadcrumbs(){
    $template_name = '/tpl-breadcrumb.php';
	if(is_file(THEME_DIR.$template_name)) {
        include(THEME_DIR.$template_name);
    }
}



/* Sample data */
add_action( 'admin_enqueue_scripts', 'snsnova_importlib' );
function snsnova_importlib(){
    wp_enqueue_script('snsnova-sampledata', get_template_directory_uri().'/framework/sample-data/assets/script.js');
    wp_enqueue_style('snsnova-sampledata-css',get_template_directory_uri().'/framework/sample-data/assets/style.css');
}
add_action( 'wp_ajax_sampledata', 'snsnova_importsampledata' );
function snsnova_importsampledata(){
    locate_template(array('/framework/sample-data/sns-importdata.php'), true, true);
    snsnova_importdata();
}

?>
