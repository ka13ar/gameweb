<?php
/**
 * WooCommerce JNE Shipping
 *
 * Main file for the calculation and settings shipping
 *
 * @author AgenWebsite
 * @package WooCommerce JNE Shipping
 * @since 8.0.0
 */

if ( !defined( 'WOOCOMMERCE_JNE' ) ) { exit; } // Exit if accessed directly

if ( !class_exists( 'WC_JNE_Shipping' ) ) :

/**
 * Class WooCommerce JNE
 *
 * @since 8.0.0
 **/
class WC_JNE_Shipping{

	/**
	 * Constructor
	 *
	 * @return void
	 * @since 8.0.0
	 **/
	public function __construct(){
        /**
         * Initialise JNE shipping method.
         *
         * @since 8.0.0
         **/
        add_action( 'woocommerce_shipping_init', array( &$this, 'shipping_method' ) );

        /**
         * Add Shipping Method
         *
         * Tell method JNE shipping to woocommerce. Hey Woo AgenWebsite JNE is Here !! :D
         *
         * @since 8.0.0
         **/
        add_filter( 'woocommerce_shipping_methods', array( &$this, 'add_jne_shipping_method' ) );

        // filter default chosen shipping
        add_filter( 'woocommerce_shipping_chosen_method', array( &$this, 'get_default_method' ), 10, 2 );

        // Release the frontend
        if( $this->is_enable() ) new WC_JNE_Frontend();

    }

	/**
	 * Init Shipping method
	 *
	 * @access public
	 * @return void
	 * @since 8.0.0
	 **/
	public function shipping_method(){
		include_once( 'shipping-method.php' );
	}

	/**
	 * Add JNE shipping method
	 *
	 * @access public
	 * @return void
	 * @since 8.0.0
	 **/
	public function add_jne_shipping_method( $methods ) {
		$methods[] = 'WC_JNE';
		return $methods;
	}

	/**
	 * Get the default method
	 * @param  array  $available_methods
	 * @param  boolean $current_chosen_method
	 * @return string
     * @since 8.1.02
	 */
	public function get_default_method( $default_method, $available_methods ) {
		$selection_priority = get_option( 'woocommerce_shipping_method_selection_priority', array() );

		if ( ! empty( $available_methods ) ) {

			// Is a method already chosen?
			if ( ! empty( $current_chosen_method ) && ! isset( $available_methods[ $current_chosen_method ] ) ) {
				foreach ( $available_methods as $method_id => $method ) {
					if ( strpos( $method->id, $current_chosen_method ) === 0 ) {
						return $method->id;
					}
				}
			}

			// Order by priorities and costs
			$prioritized_methods = array();

			foreach ( $available_methods as $method_id => $method ) {
				$priority                         = isset( $selection_priority[ $method_id ] ) ? absint( $selection_priority[ $method_id ] ) : 1;
				if ( empty( $prioritized_methods[ $priority ] ) ) {
					$prioritized_methods[ $priority ] = array();
				}
				$prioritized_methods[ $priority ][ $method_id ] = $method->cost;
			}

			$prioritized_methods = current( $prioritized_methods );

			return current( array_keys( $prioritized_methods ) );
		}

		return false;
	}

	/**
	 * Get total weight
	 *
	 * @access public
	 * @return integer Total weight
	 * @since 8.0.0
	 **/
	public function get_total_weight_checkout(){
        $settings = get_option( 'woocommerce_jne_shipping_settings' );
        $default_weight = $settings['default_weight'];
        $weight = 0;
        $weight_unit = WC_JNE()->get_woocommerce_weight_unit();

        foreach ( WC()->cart->cart_contents as $cart_item_key => $values ) {
            $_product = $values['data'];
            if( $_product->is_downloadable() == false && $_product->is_virtual() == false ) {
                $_product_weight = $_product->get_weight();

                if( $_product_weight == '' ){
                    if( $weight_unit == 'g' ){
                        $default_weight *= 1000;
                    }
                    $_product_weight = $default_weight;
                }

                $weight += $_product_weight * $values['quantity'];

            }
        }

        if( $weight_unit == 'g' ){
            if( $weight > 1000 ){
                $weight = $weight / 1000;
                $weight = number_format((float)$weight, 2, '.', '');
                add_filter( 'weight_unit_total_weight', array( &$this, 'change_to_kg' ) );
            }
        }

        return $weight;
    }

	/**
	 * Change to kilograms
	 *
	 * @access public
	 * @return string
	 * @since 8.1.0
	 **/
	public function change_to_kg(){
        return 'kg';
    }

	/**
	 * Return the number of decimals after the decimal point.
	 *
	 * @access public
	 * @return int
	 * @since 8.0.1
	 **/
	public function get_price_decimals(){
		if( function_exists( 'wc_get_price_decimals' ) )
            return wc_get_price_decimals();
        else
            return absint( get_option( 'woocommerce_price_num_decimals', 2 ) );
	}

	/**
	 * Check plugin is active
	 *
	 * @access public
	 * @return bool
	 * @since 8.0.0
	 **/
	public function is_enable(){
		$settings = get_option( 'woocommerce_jne_shipping_settings' );
		return ( is_array( $settings ) && array_key_exists('enabled', $settings) && $settings['enabled'] == 'yes' ) ? TRUE : FALSE;
	}

	/**
	 * Calculate JNE Weight
	 * To calculate weight tolerance from jne.
	 *
	 * @link http://jne.co.id/share_article.php?id=2013020404274222
	 * @access public
	 * @param integer $weight
	 * @return integer Total Weight in Kilograms
	 * @since 8.0.0
	 **/
	public function calculate_jne_weight( $weight ){
        if( WC_JNE_Shipping::is_decimal( $weight ) ){
            $desimal = explode( '.', $weight );
            $jne_weight = ( $desimal[0] == 0 || substr($desimal[1], 0, 1) > 3 || substr($desimal[1], 0, 2) > 30) ? ceil($weight) : floor($weight);
            $weight = ( $jne_weight == 0 ) ? 1 : $jne_weight;
        }

        return $weight;
	}

	/**
	 * Is Decimal
	 * For check the number is decimal.
	 *
	 * @access public
	 * @param integer
	 * @return bool
	 * @since 8.0.0
	 **/
	private static function is_decimal( $num ){
		return is_numeric( $num ) && floor( $num ) != $num;
	}

	/**
	 * JNE get data kota
	 * Mendapatkan data kota yang sudah diupload
	 *
	 * @access public
	 * @return array
	 * @since 8.0.0
	 **/
	public function get_datakota() {
		$path = WOOCOMMERCE_JNE_DATAKOTA_DIR;

		if(file_exists($path)) {
			$jne_data = unserialize(file_get_contents($path));
		}

		if(isset($jne_data) && !empty($jne_data)) { asort($jne_data); }
			return $jne_data;
	}

	/**
	 * Shipping service option default
	 *
	 * @access public
	 * @return array
	 * @since 8.0.0
	 **/
	public function default_service(){

		return array(
			array(
                'id'        => 'reg',
                'enable'    => 1,
                'name'      => 'REG',
                'extra_cost'=> 0
			),
			array(
                'id'        => 'oke',
                'enable'    => 1,
                'name'      => 'OKE',
                'extra_cost'=> 0
			),
			array(
                'id'        => 'yes',
                'enable'    => 1,
                'name'      => 'YES',
                'extra_cost'=> 0
			)
		);
	}

	/**
	 * Shipping form fields settings
	 *
	 * @access public
	 * @return array
	 * @since 8.0.0
	 **/
	public function form_fields(){
		return array(
			'enabled' => array(
				'title'         => __( 'Aktifkan JNE Shipping', 'agenwebsite' ),
				'type'          => 'checkbox',
				'label'         => __( 'Aktifkan  JNE Shipping', 'agenwebsite' ),
				'default'       => 'no',
			),
			'title' => array(
				'title'         => __( 'Label', 'agenwebsite' ),
				'description' 	=> __( 'Ubah label untuk fitur pengiriman kamu.', 'agenwebsite' ),
				'type'          => 'text',
				'default'       => __( 'JNE Shipping', 'agenwebsite' ),
			),
			'default_weight' => array(
				'title'         => __( 'Berat default ( kg )', 'agenwebsite' ),
				'description' 	=> __( 'Otomatis setting berat produk jika kamu tidak setting pada masing-masing produk.', 'agenwebsite' ),
				'type'          => 'number',
				'custom_attributes' => array(
					'step'	=>	'any',
					'min'	=> '0'
				),
				'placeholder'	=> '0.00',
				'default'		=> '1',
			),
			'jne_service' => array(
				'type'          => 'jne_service',
				'default'		=> 'yes',
			),
			'jne_import' => array(
				'type'          => 'jne_import',
				'default'		=> 'yes',
			),
		);
	}

}

endif;
