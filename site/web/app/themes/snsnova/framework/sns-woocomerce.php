<?php

add_action( 'after_setup_theme', 'snsnova_woocommerce_support' );
function snsnova_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
// Override thumbnail
function snsnova_post_thumbnail_html($html, $post_id, $post_thumbnail_id, $size, $attr) {
    global $snsnova_opt;
    if($snsnova_opt['woo_uselazyload'] == 1){
        $id = get_post_thumbnail_id();
        $src = wp_get_attachment_image_src($id, $size);
        $alt = get_the_title($id);
        $class = ( isset($attr['class']) ) ? $attr['class'] : '';
        if (strpos($class, 'lazy') !== false) {
            $html = '<img src="'.THEME_URI.'/assets/img/prod_loading.gif" alt="'.$alt.'" data-original="' . $src[0] . '" class="' . $class . '" />';
        }
    }
    return $html;
}
add_filter('post_thumbnail_html', 'snsnova_post_thumbnail_html', 99, 5);

if(class_exists('WooCommerce')){
    class SnsNova_Woocommerce{
        public static function snsnova_getInstance(){
            static $_instance;
            if( !$_instance ){
                $_instance = new SnsNova_Woocommerce();
            }
            return $_instance;
        }
        public function __construct(){
            // Add Scripts
            add_action('wp_head', array($this, 'snsnova_renderurlajax'), 15);
            add_action( 'wp_enqueue_scripts', array($this,'snsnova_getscripts') );
            // Number product per page
            add_filter( 'loop_shop_columns', array($this, 'snsnova_woo_shop_columns') );
            // Grid cols per row
            add_filter( 'loop_shop_per_page', array($this, 'snsnova_woo_shop_perpage') );
            // Remove each style one by one
            add_filter( 'woocommerce_enqueue_styles', array($this, 'snsnova_dequeue_woostyles') );
            // Set modeview
            add_action( 'wp_ajax_sns_setmodeview', array($this,'snsnova_set_modeview') );
            add_action( 'wp_ajax_nopriv_sns_setmodeview', array($this,'snsnova_set_modeview') );
            // Ajax Load more
            add_action( 'wp_ajax_sns_wooloadmore', array($this,'snsnova_woo_loadmore') );
            add_action( 'wp_ajax_nopriv_sns_wooloadmore', array($this,'snsnova_woo_loadmore') );
            //
            remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
            add_action( 'woocommerce_before_shop_loop_item_title', 'snsnova_product_thumbnail', 11 );

            remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
        }
        public function snsnova_getscripts(){
            $optimize = '.min';
            wp_enqueue_script('snsnova-woocommerce', THEME_URI.'/assets/js/sns-woocommerce'.$optimize.'.js', array(), '', true);
        }
        public function snsnova_renderurlajax() {
        ?>
            <script type="text/javascript">
                var ajaxurl = '<?php echo esc_js( admin_url('admin-ajax.php') ); ?>';
            </script>
            <?php
        }
        public function snsnova_woo_loadmore(){
            $orderby        = $_POST['order'];
            $number_query   = $_POST['numberquery'];
            $start          = $_POST['start'];
            $list_cat       = $_POST['cat'];
            $col            = $_POST['col'];
            $eclass         = $_POST['eclass'];
            $loop = snsnova_woo_query($orderby, $number_query, $list_cat, $start);
            while ( $loop->have_posts() ) : $loop->the_post();
                wc_get_template( 'vc/item-grid.php', array('col' => $col, 'animate' => 1, 'eclass' => $eclass) );
            endwhile;
            wp_die();
        }
        public function snsnova_set_modeview(){
            setcookie('snsnova_woo_list_modeview', $_POST['mode'] , time()+3600*24*100, '/');
        }
        public function snsnova_woo_shop_columns( $columns ) {
            global $snsnova_opt;
            return $snsnova_opt['woo_grid_col'];
        }
        public function snsnova_woo_shop_perpage( $columns ) {
            global $snsnova_opt;
            return $snsnova_opt['woo_number_perpage'];
        }
        
        public function snsnova_dequeue_woostyles( $enqueue_styles ) {
            unset( $enqueue_styles['woocommerce-general'] );    // Remove the gloss
            unset( $enqueue_styles['woocommerce-layout'] );     // Remove the layout
            unset( $enqueue_styles['woocommerce-smallscreen'] );    // Remove the smallscreen optimisation
            return $enqueue_styles;
        }
    }
    SnsNova_Woocommerce::snsnova_getInstance();
    // Re-render rating
    add_filter( 'woocommerce_product_get_rating_html', 'snsnova_get_rating_html', 100, 2 );
    function snsnova_get_rating_html(){
        global $woocommerce, $product;
        if( $product && $product->get_average_rating() ) $rating = $product->get_average_rating();
        if( isset($rating) && $rating > 0){
            $rating_html  = '<div class="star-rating" title="' . sprintf( esc_html__( 'Rated %s out of 5', 'woocommerce' ), $rating ) . '">';
            $rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong> ' . esc_html__( 'out of 5', 'woocommerce' ) . '</span>';
        }else{
            $rating_html  = '<div class="star-rating">';
        }
        $rating_html .= '</div>';
        return $rating_html;
    }

    // Set template view mode
    function snsnova_woo_modeview() {
        wc_get_template( 'loop/modeview.php' );
    }
    function snsnova_product_thumbnail(){
        global $post, $snsnova_opt;
        $size = 'shop_catalog';
        if ( has_post_thumbnail() ) {
            if( $snsnova_opt['woo_uselazyload'] == 1 ){
                echo get_the_post_thumbnail( $post->ID, $size, array('class' => "lazy attachment-$size") );
            }else{
                echo get_the_post_thumbnail( $post->ID, $size );
            }
        } elseif ( wc_placeholder_img_src() ) {
            echo wc_placeholder_img( $size );
        }
    }
    
    // Override quickview
    function snsnova_woo_images_quickview() {
        wc_get_template( 'single-product/product-image-quickview.php' );
    }

    function snsnova_woo_query($type, $post_per_page=-1, $cat='', $offset=0, $paged=1){
        global $woocommerce;
        $args = array(
            'post_type'         => 'product',
            'posts_per_page'    => $post_per_page,
            'post_status'       => 'publish',
            'offset'            => $offset,
            'paged'             => $paged
        );
        switch ($type) {
            case 'best_selling':
                $args['meta_key']='total_sales';
                $args['orderby']='meta_value_num';
                $args['ignore_sticky_posts']   = 1;
                $args['meta_query'] = array();
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'featured_product':
                $args['ignore_sticky_posts']=1;
                $args['meta_query'] = array();
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = array(
                             'key' => '_featured',
                             'value' => 'yes'
                         );
                $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'top_rate':
                add_filter( 'posts_clauses',  array( $woocommerce->query, 'order_by_rating_post_clauses' ) );
                $args['meta_query'] = array();
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                break;
            case 'recent':
                $args['meta_query'] = array();
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                break;
            case 'on_sale':
                $args['meta_query'] = array();
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $args['post__in'] = wc_get_product_ids_on_sale();
                break;
            case 'recent_review':
                if($post_per_page == -1) $_limit = 4;
                else $_limit = $post_per_page;
                global $wpdb;
                $query = $wpdb->prepare( "
                    SELECT c.comment_post_ID 
                    FROM {$wpdb->prefix}posts p, {$wpdb->prefix}comments c 
                    WHERE p.ID = c.comment_post_ID AND c.comment_approved > %d 
                    AND p.post_type = %s AND p.post_status = %s
                    AND p.comment_count > %d ORDER BY c.comment_date ASC" ,
                    0, 'product', 'publish', 0 );
                $results = $wpdb->get_results($query, OBJECT);
                $_pids = array();
                foreach ($results as $re) {
                    if(!in_array($re->comment_post_ID, $_pids))
                        $_pids[] = $re->comment_post_ID;
                    if(count($_pids) == $_limit)
                        break;
                }

                $args['meta_query'] = array();
                $args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
                $args['meta_query'][] = $woocommerce->query->visibility_meta_query();
                $args['post__in'] = $_pids;
                break;
        }

        if($cat!=''){
            $args['product_cat']= $cat;
        }
        return new WP_Query($args);
    }

    add_filter( 'body_class', 'snsnova_woo_class' );
    function snsnova_woo_class( $classes ) {
        $classes[] = 'woocommerce';
        return $classes;
    }
    //
    add_action('woocommerce_single_product_summary', 'snsnova_woo_addthis', 22);
    function snsnova_woo_addthis(){
        global $snsnova_opt;
        $html = '';
        if ( $snsnova_opt['woo_sharebox'] == 1 ) {
            $html .= '
            <div class="addthis_toolbox addthis_default_style ">
            <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
            <a class="addthis_button_tweet"></a>
            <a class="addthis_button_pinterest_pinit" pi:pinit:layout="horizontal"></a>
            <a class="addthis_counter addthis_pill_style"></a>
            </div>
            <script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-507b2455057cfd5f"></script>
            ';
        }
        echo wp_kses($html, array(
                                'div' => array(
                                    'class' => array(),
                                ),
                                'a' => array(
                                    'href' => array(),
                                    'class' => array(),
                                    'fb:like:layout' => array()
                                ),
                                'script' => array(
                                    'type' => array(),
                                    'src' => array()
                                ),
                            ) );
    }
    //
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    add_action('woocommerce_after_single_product_summary', 'snsnova_relatedproducts', 20);
    function snsnova_relatedproducts() {
        global $snsnova_opt;
        if ( $snsnova_opt['woo_related'] == '1' ) {
            $args = array(
                'posts_per_page' => $snsnova_opt['woo_related_num'],
                'orderby' => 'rand'
            );
            woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );
        }else{
            return;
        }
    }
    add_action( 'woocommerce_archive_description', 'snsnova_woo_category_image', 2 );
    function snsnova_woo_category_image() {
        if ( is_product_category() ){
            global $wp_query;
            $cat = $wp_query->get_queried_object();
            $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
            $image = wp_get_attachment_url( $thumbnail_id );
            if ( $image ) {
                echo '<p class="cate-img"><img src="' . esc_attr($image) . '" alt="" /></p>';
            }
        }
    }
}

?>