<?php
/**
 * Main AJAX Handles
 *
 * Handles for all request file
 *
 * @author AgenWebsite
 * @package WooCommerce JNE Shipping
 * @since 8.0.0
 */

if ( !defined( 'WOOCOMMERCE_JNE' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'WC_JNE_AJAX' ) ):

class WC_JNE_AJAX{
	
	/* @var string */
	private static $nonce_admin = 'woocommerce_jne_admin';
	
	/**
	 * Hook in methods
	 */
	public static function init(){
		
		// ajax_event => nopriv
		$ajax_event = array(
			'shipping_get_kota'				=> true,
			'shipping_get_kecamatan'			=> true,
		);
			
		foreach( $ajax_event as $ajax_event => $nopriv ){
			add_action( 'wp_ajax_woocommerce_jne_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			
			if( $nopriv ){
				add_action( 'wp_ajax_nopriv_woocommerce_jne_' . $ajax_event, array( __CLASS__, $ajax_event ) );	
			}
		}
			
	}		
	
	/**
	 * AJAX Get data kota
	 *
	 * @access public
	 * @return json
	 * @since 8.0.0
	 **/
	public static function shipping_get_kota(){
		
		check_ajax_referer( WC_JNE()->get_nonce() );
		
		$datakota = WC_JNE()->shipping->get_datakota();		
		$result = array();
		
		if( count( $datakota ) ){
			if( is_array( $datakota ) ){
				foreach( $datakota as $nama_provinsi => $data_kota ){
					if( $_POST['provinsi'] === $nama_provinsi ){
						foreach( $data_kota as $nama_kota => $data_kecamatan ){
							$result[] = $nama_kota;
						}
					}
				}
			}
		}
		
		wp_send_json( $result );
		
		wp_die();
		
	}
		
	/**
	 * AJAX Get data kecamatan
	 *
	 * @access public
	 * @return json
	 * @since 8.0.0
	 **/
	public static function shipping_get_kecamatan(){

		check_ajax_referer( WC_JNE()->get_nonce() );
		
		$datakota = WC_JNE()->shipping->get_datakota();
		$result = array();

		if( count( $datakota ) ){
			if( is_array( $datakota ) ){
				foreach( $datakota as $nama_provinsi => $data_kota ){
					if( $_POST['provinsi'] === $nama_provinsi ){
						foreach( $data_kota as $nama_kota => $data_kecamatan ){
							if( $_POST['kota'] === $nama_kota ){
								foreach( $data_kecamatan as $nama_kecamatan => $data_harga ){
									$result[] = $nama_kecamatan;
								}
							}
						}
					}
				}
			}
		}
			
		wp_send_json( $result );

		wp_die();			
	}
		
}
	
WC_JNE_AJAX::init();
	
endif;
