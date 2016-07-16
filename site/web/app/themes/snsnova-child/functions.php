<?php
/**
 * Enqueue style of child theme
 */
function snsnova_child_enqueue_styles() {
    wp_enqueue_style( 'snsnova-child-style', get_stylesheet_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'snsnova_child_enqueue_styles', 100000 );

?>