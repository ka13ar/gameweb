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

if ( !class_exists( 'WC_JNE' ) ) :

/**
 * Class WooCommerce JNE
 *
 * @since 8.0.0
 **/
class WC_JNE extends WC_Shipping_Method{

	/**
	 * Option name for save the settings
	 *
	 * @access private
	 * @var string
	 * @since 8.0.0
	 **/
	private $option_layanan;
	private $option_nama_datakota;
	private $option_versi_datakota;

	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 * @since 8.0.0
	 **/
	public function __construct(){
		$this->id                     = 'jne_shipping';
		$this->method_title           = __('JNE Shipping', 'agenwebsite');
		$this->method_description     = __( 'Plugin JNE Shipping mengintegrasikan ongkos kirim dengan total belanja pelanggan Anda.', 'agenwebsite' );

		$this->option_layanan         = $this->plugin_id . $this->id . '_layanan';
		$this->option_nama_datakota   = $this->plugin_id . $this->id . '_nama_datakota';
		$this->option_versi_datakota  = $this->plugin_id . $this->id . '_versi_datakota';

		$this->init();
	}

	/**
	 * Init JNE settings
	 *
	 * @access public
	 * @return void
	 * @since 8.0.0
	 **/
	public function init(){
		// Load the settings API
		// Override the method to add JNE Shipping settings
		$this->form_fields = WC_JNE()->shipping->form_fields();
		// Loads settings you previously init.
		$this->init_settings();

		// Load default services options
		$this->load_default_services();

		// Define user set variables
		$this->enabled                    = $this->settings['enabled'];
		$this->title                      = $this->settings['title'];
		$this->default_weight             = $this->settings['default_weight'];

		// Save settings in admin
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( &$this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( &$this, 'process_admin_jne' ) );

	}

	/**
	 * Load default JNE services
	 *
	 * @access private
	 * @return void
	 * @since 8.0.0
	 **/
	private function load_default_services(){

		$servives_options = get_option( $this->option_layanan );
		if( empty ( $servives_options ) ) {

			$data_to_save = WC_JNE()->shipping->default_service();

			update_option( $this->option_layanan, $data_to_save );
		}
	}

	/**
	 * calculate_shipping function.
	 *
	 * @access public
	 * @param mixed $package
	 * @return void
	 * @since 8.0.0
	 **/
	public function calculate_shipping( $package = array() ){
        $layanan_jne = get_option( $this->option_layanan );

        $country= WC()->customer->get_shipping_country();
        $state	= WC()->customer->get_shipping_state();
        $city	= WC()->customer->get_shipping_city();

        if( $country != 'ID' ) return false;

        $cost = $this->_getCost( $state, $city );

        $total_weight = $this->calculate_weight( $package['contents'] );
        $jne_weight = WC_JNE()->shipping->calculate_jne_weight( $total_weight );
        $weight = $jne_weight;

        $totalamount = floatval( preg_replace( '#[^\d.]#', '', WC()->cart->get_cart_total() ) );

        if( empty( $cost ) ) return false;

        if( sizeof( $package ) == 0 ) return;

        foreach( $layanan_jne as $service ){
            $service_id = $service['id'];
            $service_name = $service['name'];
            $service_enable = $service['enable'];
            $service_extra_cost = $service['extra_cost'];

            $etd = ( array_key_exists( $service_id, $cost ) ) ? $cost[$service_id]['etd'] : '';
            $tarif = ( array_key_exists( $service_id, $cost ) ) ? $cost[$service_id]['harga'] : 0;
            $label = sprintf( __( '%s ( %s hari )' ), $this->set_label($this->title, $service), $etd );

            if( ! empty($tarif) && $tarif != 0 && $service_enable == 1) {

                $tarif = $tarif * $weight;

                if( ! empty( $service_extra_cost ) ) $tarif += $service_extra_cost;

                $rate = array(
                    'id'	=> $this->id . '_' . $service_id,
                    'label'	=> $label,
                    'cost'	=> $tarif
                );

                $this->add_rate( $rate );

            }

        }//end foreach layanan

    }

	/**
	 * Calculate Total Weight
	 * This function will calculated total weight for all product
	 *
	 * @access private
	 * @param mixed $products
	 * @return integer Total Weight in Kilograms
	 * @since 8.0.0
	 **/
	private function calculate_weight( $products ){
        $weight = 0;
        $weight_unit = WC_JNE()->get_woocommerce_weight_unit();
        $default_weight = $this->default_weight;

        // Default weight JNE settings is Kilogram
        // Change default weight settings to gram if woocommerce unit is gram
        if( $weight_unit == 'g' )
            $default_weight = $default_weight * 1000;

        foreach( $products as $item_id => $item ){
            $product = $item['data'];

            if( $product->is_downloadable() == false && $product->is_virtual() == false ) {
                $product_weight = $product->get_weight() ? $product->get_weight() : $default_weight;
                $product_weight = ( $product_weight == 0 ) ? $default_weight : $product_weight;

                $product_weight = $product_weight * $item['quantity'];

                // Change product weight to kilograms
                if ($weight_unit == 'g')
                    $product_weight = $product_weight / 1000;

                $weight += $product_weight;

            }
        }

        $weight = number_format((float)$weight, 2, '.', '');

        return $weight;
    }

	/**
	 * Process admin JNE shipping
	 *
	 * @access public
	 * @return void
	 * @since 8.0.0
	 **/
	public function process_admin_jne(){

		// Upload data kota button
		if( isset( $_POST['upload_datakota'] ) && ! empty( $_POST['upload_datakota'] ) ){
			self::_process_datakota();
		}

		// If click button reset option
		if( isset( $_POST['reset_default'] ) && ! empty( $_POST['reset_default'] ) ){
			$default = $this->_reset_option();

			$save_layanan = $default['save_layanan'];

            // Update the option setting for layanan
            update_option( $this->option_layanan, $save_layanan );

			update_option( $this->plugin_id . $this->id . '_settings', $default['save_settings'] );
		}

	}

	/**
	 * Reset option to default
	 * Fungsi untuk tombol reset option pada halaman setting jne shipping
	 *
	 * @access private
	 * @return array
	 * @since 8.0.0
	 **/
	private function _reset_option(){
		$jne_settings = array();

		foreach( WC_JNE()->shipping->form_fields() as $key => $value ){
			$jne_settings[$key] = $value['default'];
		}

		$data['save_layanan'] = WC_JNE()->shipping->default_service();
		$data['save_settings'] = $jne_settings;

		return $data;
	}

	/**
	 * Process upload data kota
	 *
	 * @access private
	 * @return void
	 * @since 8.0.0
	 **/
	private function _process_datakota(){
		if( $_FILES['woocommerce_jne_import_city']['error'] !== UPLOAD_ERR_NO_FILE ) {
			$datakota		= array();
			$upload_path	= $_FILES['woocommerce_jne_import_city']['tmp_name'];

			if( ! empty( $upload_path ) ){
				$name = explode( '.', $_FILES['woocommerce_jne_import_city']['name'] );
				$file_type = strtolower( end( $name ) );

				switch( $file_type ):
					case 'csv':
						$datakota = $this->_processCSVdatakota( $upload_path );
					break;
					case 'txt':
						$aKota = unserialize( file_get_contents( $upload_path ) );
						$datakota = $aKota;
					break;
				endswitch;

				$this->_update_datakota( $datakota );
			}
		}
	}

	/**
	 * Process file CSV datakota v2.0.0
	 *
	 * @access private
	 * @param string of path $upload_path
	 * @return array
	 * @since 8.0.0
	 **/
	private function _processCSVdatakota( $upload_path ){
		$fd = fopen( $upload_path, 'r' );
		$heading = true;
		$data = array();
        $datakota = array();

		while( !feof( $fd ) ){
			$buffer = fgetcsv( $fd, filesize( $upload_path ) );

            if( ! empty( $buffer ) ) {

                if( ! $heading ){

                    $provinsi	= $buffer[0];
                    $kota		= $buffer[1];
                    $kecamatan	= $buffer[2];
                    $price_REG	= $buffer[3];
                    $ETD_REG	= $buffer[4];

                    $datakota[ $provinsi ][ $kota ][ $kecamatan ] = array(
                        'reg'	=> array(
                            'harga' => $price_REG,
                            'etd'	=> $ETD_REG
                        ),
                    );

                }

                if( $heading ) {

                    $data['nama_datakota'] = $buffer[0];
                    $data['versi_datakota'] = $buffer[1];

                    $versi_datakota = $this->check_versi_datakota( $data['versi_datakota'] );

                    if( ! is_numeric( $versi_datakota ) && $versi_datakota < 200 ){
                        add_action( 'jne_admin_notices', array( &$this, 'jne_error_data_kota_notice' ) );
                        break;
                    }

                    $heading = false;
                }

            }

		}

		fclose( $fd );

        $data['datakota'] = $datakota;
        $versi_datakota = $this->check_versi_datakota( $data['versi_datakota'] );

        if( ! empty( $datakota ) && is_numeric( $versi_datakota ) && $versi_datakota >= 200 ){
			add_action( 'jne_admin_notices', array( &$this, 'jne_success_data_kota_notice' ) );
		}

		return $data;
	}

    /**
     * Set Label
     *
     * @access private
     * @param string $shipping_title
     * @param string $nama_layanan
     * @return string $label
     * @since 8.1.02
     */
    private function set_label($shipping_title, $service){

        $label = $shipping_title .' '. $service['name'] .' ';

        return $label;
    }

    /**
     * Check versi datakota
     * Change number format version to numeric
     *
     * @access private
     * @param integer of format version
     * @return interger
     * @since 8.1.0
     */
    private function check_versi_datakota( $versi ){
        $versi = explode( '.', $versi );
        $versi = implode( '', $versi );

        return $versi;
    }

	/**
	 * Update data kota JNE
	 *
	 * @access private
	 * @param mixed $datakota
	 * @return void
	 * @since 8.0.0
	 **/
	private function _update_datakota( $datakota ){

		$data = serialize( $datakota['datakota'] );
		$path_file = WOOCOMMERCE_JNE_DATAKOTA_DIR;

        // Write data kota serialize to file txt on wp uploads folder
		$file = fopen( $path_file, 'w' );
		fwrite( $file, $data );
		fclose( $file );

        // Update option nama datakota
		update_option( $this->option_nama_datakota, $datakota['nama_datakota'] );

        // Update option versi datakota
        update_option( $this->option_versi_datakota, $datakota['versi_datakota'] );
	}

	/**
	 * JNE cost
	 * Mendapatkan harga dari data kota yang sudah di save
	 *
	 * @access private
	 * @param string $state
	 * @param string $city
	 * @return array
	 * @since 8.0.0
	 **/
	private function _getCost( $state, $city ){
		$JNE_data = WC_JNE()->shipping->get_datakota();
		$provinsi = $this->_getProvinceName($state);

		if( ! empty( $city ) ){
			$array_data = explode(', ', $city);
			$kecamatan = $array_data[0];
			$kota = $array_data[1];
			return $JNE_data[ $provinsi ][ $kota ][ $kecamatan ];
		}

	}

	/**
	 * Get nama provinsi Indonesia
	 * Mendapatkan nama provinsi berdasarkan id provinsi dari woocommerce
	 *
	 * @access private
	 * @param string $id of provinsi
	 * @return string
	 * @since 8.0.0
	 **/
	private function _getProvinceName( $id ){
		$provinsi = '';
		$states = WC()->countries->get_states( 'ID' );

		foreach( $states as $id_provinsi => $nama_provinsi ){
			if( $id_provinsi == $id ){
				$provinsi = $nama_provinsi;
			}
		}

		return $provinsi;
	}

	/**
	 * Notice error upload data kota
	 *
	 * @access public
	 * @return HTML
	 * @since 8.1.0
	 */
	public function jne_error_data_kota_notice(){
		$message = sprintf( __( 'Gagal Upload Data kota! Data kota yang Anda upload tidak sesuai dengan versi plugin saat ini. Baca artikel %s', 'agenwebsite' ), '<a href="http://www.agenwebsite.com/faq/gagal-upload-data-kota" target="new">Gagal Upload Data kota</a>' );
		echo '<div class="error"><p><strong>' . $message . '</strong></p></div>';
	}

	/**
	 * Notice success upload data kota
	 *
	 * @access public
	 * @return HTML
	 * @since 8.1.0
	 */
	public function jne_success_data_kota_notice(){
		$message = __( 'Data kota berhasil diupload', 'agenwebsite' );
		echo '<div class="updated"><p><strong>' . $message . '</strong></p></div>';
	}

	/**
	 * Admin Options
	 * Setup the gateway settings screen.
	 *
	 * @access public
	 * @return HTML of the admin jne settings
	 * @since 8.0.0
	 */
	public function admin_options() {
		$html  = '<div id="agenwebsite_woocommerce">' . "\n";

			// AW head logo and links and table status
			ob_start();
			$this->aw_head();
			$html .= ob_get_clean();

			$html .= '<h3>' . $this->method_title . '</h3>' . "\n";
			$html .= '<p>' . $this->method_description . '</p>' . "\n";

			$html .= '<div id="agenwebsite_notif">';
			ob_start();
			do_action( 'jne_admin_notices' );
			$html .= ob_get_clean();
			$html .= '</div>';

			$html .= '<table class="form-table hide-data">' . "\n";

				ob_start();
				$this->generate_settings_html();
				$html .= ob_get_clean();

			$html .= '</table>' . "\n";

		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	 * AgenWebsite Head
	 *
	 * @access private static
	 * @return HTML for the admin logo branding and usefull links.
	 * @since 8.0.0
	*/
	private function aw_head(){
		$html  = '<div class="agenwebsite_head">';
		$html .= '<div class="logo">' . "\n";
		$html .= '<a href="' . esc_url( 'http://agenwebsite.com/' ) . '" target="_blank"><img id="logo" src="' . esc_url( apply_filters( 'aw_logo', WC_JNE()->plugin_url() . '/assets/images/logo.png' ) ) . '" /></a>' . "\n";
		$html .= '</div>' . "\n";
		$html .= '<ul class="useful-links">' . "\n";
			$html .= '<li class="documentation"><a href="' . esc_url( WC_JNE()->url_dokumen ) . '" target="_blank">' . __( 'Dokumentasi', 'agenwebsite' ) . '</a></li>' . "\n";
			$html .= '<li class="support"><a href="' . esc_url( WC_JNE()->url_support ) . '" target="_blank">' . __( 'Bantuan', 'agenwebsite' ) . '</a></li>' . "\n";
		$html .= '</ul>' . "\n";

		ob_start();
		include_once( WC_JNE()->plugin_path() . '/views/html-admin-jne-settings-status.php' );
		$html .= ob_get_clean();

		$html .= '</div>';
		echo $html;
	}

	/**
	 * Field type jne_service
	 *
	 * @access public
	 * @return HTML
	 * @since 8.0.0
	 **/
	public function generate_jne_service_html(){
		$html = '<tr valign="top" class="premium-version">';
			$html .= '<th scope="row" class="titledesc">' . __( 'Layanan JNE', 'agenwebsite' ) . '</th>';
			$html .= '<td class="forminp">';
				$html .= '<table class="widefat wc_input_table sortable" cellspacing="0">';
					$html .= '<thead>';
						$html .= '<tr>';
							$html .= '<th class="sort">&nbsp;</th>';
							$html .= '<th>Nama Pengiriman ' . WC_JNE()->help_tip( 'Metode pengiriman yang digunakan.' ) . '</th>';
							$html .= '<th>Tambahan Biaya ' . WC_JNE()->help_tip( 'Biaya tambahan, bisa disetting untuk tambahan biaya packing dan lain-lain.' ) . '</th>';
							$html .= '<th style="width:14%;text-align:center;">Aktifkan</th>';
						$html .= '</tr>';
					$html .= '</thead>';
					$html .= '<tbody>';

						$i = 0;
						foreach( get_option( $this->option_layanan ) as $service ) :

							$html .= '<tr class="service">';
								$html .= '<td class="sort"></td>';
								$html .= '<td><input type="text" value="' . $service['name'] . '" name="service_name[' . $i . '][' . $service['id'] . ']" disabled /></td>';
								$html .= '<td><input type="number" value="' . $service['extra_cost'] . '" name="service_extra_cost[' . $i . '][' . $service['id'] . ']" disabled /></td>';
								$html .= '<td style="text-align:center;"><input type="checkbox" value="1" ' . checked( $service['enable'], 1, FALSE ) . ' name="service_enable[' . $i . '][' . $service['id'] . ']" /><input type="hidden" value="' . $service['id'] . '" name="service_id[' . $i . ']" disabled /></td>';
							$html .= '</tr>';

							$i++;
						endforeach;

					$html .= '</tbody>';
				$html .= '</table>';
			$html .= '</td>';
		$html .= '</tr>';

		return $html;
	}

	/**
	 * Field type jne_import
	 *
	 * @access public
	 * @return HTML
	 * @since 8.0.0
	 **/
	public function generate_jne_import_html() {
		$html = '<tr valign="top">	';
			$html .= '<th scope="row" class="titledesc">'. __('Impor Kota', 'agenwebsite') . '</th>';
			$html .= '<td class="input-impor-kota">';
				$html .= '<p><input type="file" name="woocommerce_jne_import_city" id="woocommerce_jne_import_city" /> <input name="upload_datakota" class="button-primary help_tip" data-tip="Klik untuk melakukan upload data kota." class="button-primary" type="submit" value="Upload Data Kota"></p>';
				$html .= '<p class="description">' . __( 'Masukan file csv untuk menginput data kota kamu.', 'agenwebsite' ) . '</p>';

			$html .= '</td>';
		$html .= '</tr>';
		$html .= '<tr valign="top">';
			$html .= '<td></td>';
			$html .= '<td class="premium-version">';
				$html .= '<input type="submit" name="backup_datakota" class="button-primary help_tip" data-tip="Backup data ongkos kirim agar bisa Anda digunakan kembali." value="Backup Data" onclick="return false;"> ';
				$html .= '<input name="jne_delete_datakota" class="button-primary help_tip" type="submit" id="jne_delete_datakota" value="Hapus Semua Data" data-tip="Ketika Anda mengklik dan mengkonfirmasikannya Plugin ini akan menghapus semua data tarif." onclick="return false;">';
			$html .= '</td>';
		$html .= '</tr>';

		return $html;
	}

}

endif;
