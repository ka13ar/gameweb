<?php
add_action( 'tgmpa_register', 'snsnova_plugin_activation' );
function snsnova_plugin_activation() {
    $plugins = array(
            // install Redux Framework, it on wordpress.org/plugins
            array(
                'name'      => 'Redux Framework',
                'slug'      => 'redux-framework', // Slug name of plugin on URL
                'required'  => true,
            ),
            array(
                'name'               => 'Meta Box',
                'slug'               => 'meta-box',
                'required'           => true,
                'force_activation'   => false,
                'force_deactivation' => false,
            ),
            array(
                'name'                  => 'Slider Revolution',
                'slug'                  => 'revslider',
                'source'                => get_template_directory_uri()  . '/framework/plugins/revslider.zip',
                'required'              => true,
                'force_activation'      => false,
                'force_deactivation'    => false,
            ),
            array(
                'name'                  => 'WPBakery Visual Composer',
                'slug'                  => 'js_composer',
                'source'                => get_template_directory_uri() . '/framework/plugins/js_composer.zip',
                'required'              => true,
                'force_activation'      => false,
                'force_deactivation'    => false,
            ),
            array(
                'name'                  => 'SNS Posttype',
                'slug'                  => 'sns-posttype',
                'source'                => get_template_directory_uri() . '/framework/plugins/sns-posttype.zip',
                'required'              => true,
                'force_activation'      => false,
                'force_deactivation'    => false,
            ),
            array(
                'name'               => 'WooCommerce - excelling eCommerce',
                'slug'               => 'woocommerce',
                'required'           => true,
                'force_activation'   => false,
                'force_deactivation' => false,
            ),
            array(
                'name'               => 'YITH WooCommerce Wishlist',
                'slug'               => 'yith-woocommerce-wishlist',
                'required'           => true,
                'force_activation'   => false,
                'force_deactivation' => false,
            ),
            array(
                'name'               => 'YITH WooCommerce Compare',
                'slug'               => 'yith-woocommerce-compare',
                'required'           => true,
                'force_activation'   => false,
                'force_deactivation' => false,
            ),
            array(
                'name'               => 'YITH WooCommerce Quick View',
                'slug'               => 'yith-woocommerce-quick-view',
                'required'           => true,
                'force_activation'   => false,
                'force_deactivation' => false,
            ),
            array(
                'name'               => 'Contact Form 7',
                'slug'               => 'contact-form-7',
                'required'           => true,
                'force_activation'   => false,
                'force_deactivation' => false,
            ),
            array(
                'name'               => 'Widget Logic',
                'slug'               => 'widget-logic',
                'required'           => true,
                'force_activation'   => false,
                'force_deactivation' => false,
            ),
        );
  
    $config = array(
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Is show notices or not?
        'dismissable'  => false,                   // If false then user cannot colose notices above.
        'is_automatic' => true,                    // If false thene plugin cannot auto active after install.
    );
    tgmpa( $plugins, $config );
}