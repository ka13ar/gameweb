<?php
if ( ! class_exists( 'SnsNova_Class' ) ) {
	class SnsNova_Class {
		public function __construct() {
			// Set cookie theme option
			add_action( 'wp_ajax_snsnova_setcookies', array($this,'snsnova_setcookies') );
			add_action( 'wp_ajax_nopriv_snsnova_setcookies', array($this,'snsnova_setcookies') );
			// Reset cookie theme option
			add_action( 'wp_ajax_snsnova_resetcookies', array($this,'snsnova_resetcookies') );
			add_action( 'wp_ajax_nopriv_snsnova_resetcookies', array($this,'snsnova_resetcookies') );
		}
		public function snsnova_setcookies(){
			setcookie('snsnova_'.$_POST['key'], $_POST['value'], time()+3600*24*1, '/'); // 1 day
		}
		public function snsnova_resetcookies(){
			setcookie('snsnova_theme_color', '', 0, '/');
			setcookie('snsnova_use_boxedlayout', '', 0, '/');
			setcookie('snsnova_use_stickmenu', '', 0, '/');
		}
		function snsnova_getStyle($compile = 2, $scss = array('dir' => '', 'name' => ''), $css = array('dir' => '', 'name' => ''), $format = 'scss_formatter_compressed', $variables = array() ) {
			if($css['name'] == '') $css['name'] = $scss['name'];
			$scss_variables = '';
			if($variables) {
				//$css['name'] .= '-';
				foreach($variables as $propety => $value) {
					$scss_variables .= $propety . ':' . $value . ';';
					$css['name'] .= '-'.strtolower(preg_replace('/\W/i', '', $value));
				}
			}
			if( $compile == 2 || !file_exists(get_template_directory() . '/assets/css/' . $css['name'] . '.css') ) 
				$this->snsnova_compileScss($scss, $css, $format, $scss_variables);
			return $css['name'] . '.css';
		}
		function snsnova_compileScss($scss, $css, $format, $scss_variables) {
			global $wp_filesystem;
			if (empty($wp_filesystem)) {
			    require_once ABSPATH . '/wp-admin/includes/file.php';
			    WP_Filesystem();
			}
			require "scssphp/scss.inc.php";
			require "scssphp/compass/compass.inc.php";
			$sass = new scssc();
			new scss_compass($sass);
			$format = ($format == NULL) ? 'scss_formatter_compressed' : $format;
			$sass->setFormatter($format);
			$sass->addImportPath($scss['dir']);
			$string_sass = $scss_variables . $wp_filesystem->get_contents($scss['dir'] . $scss['name'] . '.scss');
			$string_css = $sass->compile($string_sass);
			//$string_css = preg_replace('/\/\*[\s\S]*?\*\//', '', $string_css); // remove mutiple comments
			$wp_filesystem->put_contents(
				$css['dir'] . $css['name'] . '.css',
				$string_css,
			  	FS_CHMOD_FILE
			);
		}
		function snsnova_getOption($param){
			global $snsnova_opt;
			$value = '';
			// Get config via theme option
			if ( isset($snsnova_opt[$param]) && $snsnova_opt[$param] ) $value = $snsnova_opt[$param];
			// Get config via cookie
			if ( isset($_COOKIE['snsnova_'.$param]) && $_COOKIE['snsnova_'.$param] != '' ) {
				$value = $_COOKIE['snsnova_'.$param];
			}
			// Get config via page config
			if ( function_exists('rwmb_meta') && rwmb_meta('snsnova_enablelayoutconfig') == '0' ) return $value;
			if ( function_exists('rwmb_meta') && rwmb_meta('snsnova_'.$param) ) $value = rwmb_meta('snsnova_'.$param);
			return $value;
		}
		function snsnova_metabox($field_id, $args = array()){
			global $post;
			if( !function_exists('rwmb_meta') || !isset($post) ){
				return '';
			}
			if( function_exists('is_shop') && is_shop() ) {
		        return rwmb_meta($field_id, $args, get_option('woocommerce_shop_page_id'));
		    }
		    return rwmb_meta($field_id, $args);
		}
	}
}
?>