<?php
/**
 * Plugin Name: WooCommerce JNE Shipping ( Free Version )
 * Plugin URI: http://www.agenwebsite.com/products/woocommerce-pos-shipping
 * Description: Plugin untuk WooCommerce dengan penambahan metode shipping JNE.
 * Version: 8.1.03
 * Author: AgenWebsite
 * Author URI: http://agenwebsite.com
 *
 *
 * Copyright 2015 AgenWebsite. All Rights Reserved.
 * This Software should not be used or changed without the permission
 * of AgenWebsite.
 *
 */

if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'WooCommerce_JNE' ) ) :

/**
 * Initiliase Class
 *
 * @since 8.0.0
 **/
class WooCommerce_JNE{

	/**
	 * @var string
	 */
	public $version = '8.1.03';

	/**
	 * @var string
	 */
	public $db_version = '8.0.0';

	/**
	 * @var woocommerce jne main class
	 * @since 8.0.0
	 */
	protected static $_instance = null;

	/**
	 * @var WC_JNE_Shipping $shipping
	 * @since 8.0.0
	 */
	public $shipping = null;

	/**
	 * @var string
	 * @since 8.0.0
	 */
	private $nama_datakota = 'agenwebsite_datakota_jne.txt';

	/**
	 * @var string
	 * @since 8.0.0
	 */
	private $nonce = '_woocommerce_jne__nonce';

	/**
	 * Various Links
	 * @var string
	 * @since 8.0.0
	 */
	public $url_dokumen = 'http://docs.agenwebsite.com/products/woocommerce-jne-shipping';
	public $url_support = 'http://www.agenwebsite.com/support';

	/**
	 * WooCommerce JNE Instance
	 *
	 * @access public
	 * @return Main Instance
	 * @since 8.0.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @return self
	 * @since 8.0.0
	 */
	public function __construct(){
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Define JNE Constant
	 *
	 * @access private
	 * @return void
	 * @since 8.0.0
	 */
	private function define_constants(){
		register_activation_hook( __FILE__, array( 'WooCommerce_JNE', 'install' ) );
		define( 'WOOCOMMERCE_JNE', TRUE );
		define( 'WOOCOMMERCE_JNE_VERSION', $this->version );
		define( 'WOOCOMMERCE_JNE_DATAKOTA_DIR', $this->path_datakota( 'dir' ) );
	}

	/**
	 * Install
	 *
	 * @access public
	 * @return void
	 * @since 8.0.0
	 */
	public static function install(){

		// Check is under version 8
		if( get_option( 'woocommerce_jne_version' ) != '' ){
			delete_option( 'woocommerce_jne_shipping_data_save' );
			delete_option( 'woocommerce_jne_shipping_settings' );
		}

		delete_option( 'woocommerce_jne_version' );
		add_option( 'woocommerce_jne_version', WC_JNE()->version );

		delete_option( 'woocommerce_jne_db_version' );
		add_option( 'woocommerce_jne_db_version', WC_JNE()->db_version );
	}

	/**
	 * Hooks action and filter
	 *
	 * @access private
	 * @return void
	 * @since 8.0.0
	 */
	private function init_hooks(){
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( &$this, 'add_settings_link' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'load_scripts') );
		add_action( 'admin_enqueue_scripts', array( &$this, 'load_scripts_admin') );
        add_action( 'admin_notices', array( &$this, 'notice_to_upgrade' ) );
	}

	/**
	 * Inititialise Includes
	 *
	 * @access private
	 * @return void
	 * @since 8.0.0
	 */
	private function includes(){
		$this->shipping = WooCommerce_JNE::shipping();
		WooCommerce_JNE::ajax_includes();
	}

	/**
	 * Inititialise JNE Shipping module
	 *
	 * @access private
	 * @return WC_JNE_Shipping
	 * @since 8.0.0
	 */
	private static function shipping(){
	 	// Load files yang untuk modul shipping
		WooCommerce_JNE::load_file( 'shipping' );

		return new WC_JNE_Shipping();
	}

	/**
	 * Include AJAX file
	 *
	 * @access private
	 * @return void
	 * @since 8.0.0
	 */
	private function ajax_includes(){
		require_once( 'includes/wc-jne-ajax.php' );
	}

	/**
	 * Load Requires Files by modules
	 *
	 * @access private
	 * @return void
	 * @since 8.0.0
	 */
	private static function load_file( $modules ){
		switch( $modules ){

			case 'shipping':
				require_once( 'includes/shipping/shipping.php' );
				require_once( 'includes/shipping/shipping-frontend.php' );
			break;

		}
	}

	/**
	 * Load JS & CSS FrontEnd
	 *
	 * @access public
	 * @return void
	 * @since 8.0.0
	 */
	public function load_scripts(){
        $suffix			= defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        $assets_path	= str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';

        // select2
        $select2_js_path = $assets_path . 'js/select2/select2' . $suffix . '.js';
        $select2_css_path = $assets_path . 'css/select2.css';
        if( ! wp_script_is( 'select2', 'registered' ) ) wp_register_script( 'select2', $select2_js_path, array( 'jquery' ), '3.5.2' );
        if( ! wp_style_is( 'select2', 'registered' ) ) wp_register_style( 'select2', $select2_css_path );

        // chosen
        $chosen_js_path = $assets_path . 'js/chosen/chosen.jquery' . $suffix . '.js';
        $chosen_css_path = $assets_path . 'css/chosen.css';
        if( ! wp_script_is( 'chosen', 'registered' ) ) wp_register_script( 'chosen', $chosen_js_path, array( 'jquery' ), '1.0.0', true );
        if( ! wp_style_is( 'chosen', 'registered' ) ) wp_enqueue_style( 'woocommerce_chosen_styles', $chosen_css_path );

        wp_register_script( 'woocommerce-jne-shipping',		$this->plugin_url() . '/assets/js/shipping' . $suffix . '.js', 	array( 'jquery' ),	'1.0.12', true );

        // shipping
        if( $this->shipping->is_enable() ){
            if( is_checkout() || is_wc_endpoint_url( 'edit-address' ) ) {
                wp_enqueue_script( 'woocommerce-jne-shipping');
                wp_localize_script( 'woocommerce-jne-shipping', 'agenwebsite_woocommerce_jne_params', $this->localize_script( 'shipping' ) );
            }
        }

    }

	/**
	 * Load JS dan CSS admin
	 *
	 * @access public
	 * @return void
	 * @since 8.0.0
	 */
	public function load_scripts_admin(){
        global $pagenow;

        $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

        if( $pagenow == 'admin.php' && ( isset( $_GET['page'] ) && $_GET['page'] == 'wc-settings' ) && ( isset( $_GET['tab'] ) && $_GET['tab'] == 'shipping' ) && ( isset( $_GET['section'] ) && $_GET['section'] == 'wc_jne' ) ) {
            // Load for admin common JS & CSS
            wp_enqueue_script( 'woocommerce-jne-js-admin', WC_JNE()->plugin_url() . '/assets/js/admin' . $suffix . '.js', array( 'jquery' ), '1.0.2', true );
            wp_enqueue_style( 'woocommerce-jne-admin', WC_JNE()->plugin_url() . '/assets/css/admin.css' );

            // Load localize admin params
            wp_localize_script( 'woocommerce-jne-js-admin', 'agenwebsite_jne_admin_params', $this->localize_script( 'admin' ) );
        }
    }

	/**
	 * Localize Scripts
	 *
	 * @access public
	 * @return void
	 * @since 8.0.0
	 */
	public function localize_script( $handle ){
		switch( $handle ){
			case 'admin':
				return array(
					'i18n_is_available'            => __( 'sudah tersedia', 'agenwebsite' ),
                    'i18n_reset_default'           => __( 'Kembalikan ke settingan awal ?', 'agenwebsite' ),
					'ajax_url'                     => self::ajax_url(),
					'jne_admin_wpnonce'            => wp_create_nonce( 'woocommerce_jne_admin' )
				);
			break;
			case 'shipping':
				return array(
                    'i18n_placeholder_kota'         => __( 'Pilih Kota / Kabupaten', 'agenwebsite' ),
                    'i18n_placeholder_kecamatan'    => __( 'Pilih Kecamatan', 'agenwebsite' ),
                    'i18n_label_kecamatan'          => __( 'Kecamatan', 'agenwebsite' ),
                    'i18n_no_matches'               => __( 'Data tidak ditemukan', 'agenwebsite' ),
                    'i18n_required_text'            => __( 'required', 'agenwebsite' ),
                    'i18n_loading_data'             => __( 'Meminta data...', 'agenwebsite' ),
                    'wc_version'                    => self::get_woocommerce_version(),
                    'ajax_url'                      => self::ajax_url(),
                    'page'                          => self::get_page(),
                    '_wpnonce'                      => wp_create_nonce( $this->nonce )
				);
			break;
		}
	}

	/**
	 * Add setting link to plugin list table
	 *
	 * @access public
	 * @param  array $links Existing links
	 * @return array		Modified links
	 * @since 8.0.0
	 */
	public function add_settings_link( $links ){
        $plugin_links = array(
            '<a href="http://www.agenwebsite.com/products/woocommerce-jne-shipping" target="new">' . __( 'Full Version', 'agenwebsite' ) . '</a>',
            '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=wc_jne' ) . '">' . __( 'Settings', 'agenwebsite' ) . '</a>',
            '<a href="' . $this->url_dokumen . '" target="new">' . __( 'Docs', 'agenwebsite' ) . '</a>',
        );

        return array_merge( $plugin_links, $links );
    }

	/**
	 * Data kota JNE is uploadded
	 *
	 * @access public
	 * @return bool
	 * @since 8.0.0
	 **/
	public function datakota_is_uploaded(){
		$path = WOOCOMMERCE_JNE_DATAKOTA_DIR;
		$jne_data = array();
		$harga = array();
		if( file_exists( $path ) ){
			$jne_data = unserialize( file_get_contents( $path ) );

			if( count( $jne_data ) > 0 ){
				if(is_array($jne_data) && count($jne_data) > 0){
					foreach( $jne_data as $provinsi => $data_kota ){
						foreach( $data_kota as $kota => $data_kecamatan ){
							foreach( $data_kecamatan as $harga ){
								$hasil[] = $harga;
							}
						}
					}
				}
			}
		}

		return ( ! empty( $hasil ) ) ? TRUE : FALSE;
	}

	/**
	 * Get status weight
	 *
	 * @access public
	 * @return HTML
	 * @since 8.0.0
	 */
	public function get_status_weight(){
		$weight_unit = $this->get_woocommerce_weight_unit();
		$status = '';
		$status['unit']	= $weight_unit;
		if( $weight_unit == 'g' || $weight_unit == 'kg' ){
			$status['message'] = 'yes';
		}else{
			$status['message'] = 'error';
		}

		return $status;
	}

	/**
	 * Get nama datakota uploaded
	 *
	 * @access public
	 * @return string
	 * @since 8.0.0
	 **/
	public function get_nama_datakota(){
		return get_option( 'woocommerce_jne_shipping_nama_datakota' );
	}

	/**
	 * WooCommerce weight unit
	 *
	 * @access public
	 * @return string
	 * @since 8.0.0
	 **/
	public function get_woocommerce_weight_unit(){
		return get_option( 'woocommerce_weight_unit' );
	}

	/**
	 * Get nonce
	 *
	 * @access public
	 * @return string
	 * @since 8.0.0
	 */
	public function get_nonce(){
		return $this->nonce;
	}

	/**
	 * Notice to upgrade
	 *
	 * @access public
	 * @return string
	 * @since 8.0.0
	 */
    public function notice_to_upgrade(){
        global $pagenow;
        if( $pagenow == 'admin.php' && isset( $_GET['section'] ) && $_GET['section'] === 'wc_jne' ){
            printf('<div class="updated woocommerce-jne"><p><b>%s</b></p><p class="submit">%s</p></div>',
                   __( 'Saat ini kamu sedang menggunakan versi Gratis, dengan fitur yang sangat terbatas. Ingin fitur yang lebih lengkap? Lihat perbandingan fitur sekarang!', 'agenwebsite' ),
                   '<a href="http://www.agenwebsite.com/products/woocommerce-jne-shipping" target="new" class="button-primary" target="new">' . __( 'Lihat Perbandingan Versi', 'agenwebsite' ) . '</a>' );
        }

        if( $pagenow == 'admin.php' && isset( $_GET['section'] ) && $_GET['section'] === 'wc_jne' && ! $this->datakota_is_uploaded() ){
            printf('<div class="updated woocommerce-jne"><p><b>%s</b> &#8211; %s</p><p class="submit">%s</p></div>',
                   __( 'Data kota tidak ada. Upload data kota untuk mengaktifkan WooCommerce JNE Free', 'agenwebsite' ),
                   __( 'Anda bisa mendapatkan data kota di website agenwebsite.', 'agenwebsite' ),
                   '<a href="http://www.agenwebsite.com/?add-to-cart=5318" target="new" class="button-primary">' . __( 'Download Data Kota', 'agenwebsite' ) . '</a>');
        }

    }

	/**
	 * Get current page
	 *
	 * @access private
	 * @return string
	 * @since 8.0.0
	 **/
	private static function get_page(){
		// get billing or shipping
		$permalink = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		$permalinks = explode( '/', $permalink );
		end($permalinks);
		$key = key( $permalinks );
		$currentPage = $permalinks[$key-1];

		if( is_cart() )
			$page = 'cart';
		elseif( is_checkout() )
			$page = 'checkout';
		elseif( $currentPage == 'billing' )
			$page = 'billing';
		elseif( $currentPage == 'shipping' )
			$page = 'shipping';
		else
			$page = '';

		return $page;
	}

	/**
	 * JNE data kota path
	 *
	 * @access private
	 * @param dir(basedir)|url(baseurl)
	 * @return string
	 * @since 8.0.0
	 **/
	private function path_datakota( $base ){
		$dir = wp_upload_dir();
		$base = 'base' . $base;
		return $dir[$base] . '/' . $this->nama_datakota;
	}

	/**
	 * AJAX URL
	 *
	 * @access private
	 * @return string URL
	 * @since 8.0.0
	 **/
	private static function ajax_url(){
		return admin_url( 'admin-ajax.php' );
	}

	/**
	 * WooCommerce version
	 *
	 * @access public
	 * @return string
	 * @since 8.0.0
	 **/
	private function get_woocommerce_version(){
		 require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		 $data = get_plugins( '/' . plugin_basename( 'woocommerce' ) );
		 $version = explode('.',$data['woocommerce.php']['Version']);
		 return $data['woocommerce.php']['Version'];
	}

	/**
	 * Get the plugin url.
	 *
	 * @access public
	 * @return string
	 * @since 8.0.0
	 */
	public function plugin_url(){
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @access public
	 * @return string
	 * @since 8.0.0
	 */
	public function plugin_path(){
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Render help tip
	 *
	 * @access public
	 * @return HTML for the help tip image
	 * @since 8.0.0
	 **/
	public function help_tip( $tip, $float = 'none' ){
		return '<img class="help_tip" data-tip="' . $tip . '" src="' . $this->plugin_url() . '/assets/images/help.png" height="16" width="16" style="float:' . $float . ';" />';
	}

	/**
	 * Render link tip
	 *
	 * @access public
	 * @return HTML for the help tip link
	 * @since 8.0.0
	 **/
	public function link_tip( $tip, $text, $href, $target = NULL, $style = NULL ){
		return '<a href="' . $href . '" data-tip="' . $tip . '" target="' . $target . '" class="help_tip">' . $text . '</a>';
	}

}

endif;

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	/**
	 * Returns the main instance
	 *
	 * @since  8.0.0
	 * @return WooCommerce_JNE
	 */
	function WC_JNE(){
		return WooCommerce_JNE::instance();
	}

	// Let's fucking rock n roll! Yeah!
	WooCommerce_JNE::instance();

};
