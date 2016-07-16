<?php
global $snsnova_opt, $snsnova_obj;
require_once THEME_DIR . '/framework/class-tgm-plugin-activation.php'; // Plugin for installation and activation plugins.
require_once THEME_DIR . '/framework/plugins-need-active.php'; // Active somes plugins.
require_once THEME_DIR . '/framework/sns-class.php'; // Nova theme Class
require_once THEME_DIR . '/framework/sns-options.php'; // Theme Options.
require_once THEME_DIR . '/framework/sns-metabox.php'; // Metabox
require_once THEME_DIR . '/framework/sns-menu.php'; // Mega menu
require_once THEME_DIR . '/framework/sns-widgets.php'; // Widgets
if ( class_exists('WooCommerce') ) require_once THEME_DIR . '/framework/sns-woocomerce.php'; // Woo function
// Init Theme Options in admin panel
$reduxConfig = new SnsNova_Options();
// Get Theme Options's value
$snsnova_opt =  get_option('snsnova_themeoptions');
// 
$snsnova_obj = new SnsNova_Class;
?>