<?php
class SnsNova_MegaMenu {
    function __construct() {
        add_filter( 'wp_setup_nav_menu_item', array( $this, 'snsnova_megamenuset' ) );
		add_action( 'wp_update_nav_menu_item', array( $this, 'snsnova_megamenusave'), 10, 3 );
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'snsnova_megamenuedit'), 10, 2 );
		add_action( 'admin_print_footer_scripts', array( $this, 'snsnova_megamenujs' ), 99 );
		add_action( 'admin_print_styles', array( $this , 'snsnova_loadadmincss'));
		add_action( 'admin_print_scripts', array( $this , 'snsnova_loadadminjs'));
	
    }
    // Set value
	function snsnova_megamenuset ( $item ) {
		// For 1st level
		$item->enablemega = get_post_meta( $item->ID, '_sns_megamenu_item_enable', true );
		// For 2nd level
		$item->hidetitlemega = get_post_meta( $item->ID, '_sns_megamenu_item_hidetitle', true );
		// All level
		$item->iconmega = get_post_meta( $item->ID, '_sns_megamenu_item_icon', true );

		return $item;
	}
	
	// Save option to db	
    function snsnova_megamenusave( $menu_id, $menu_item_db_id, $args ) {
		// Enable
		if ( isset( $_REQUEST['sns-mega-mitem-enable'][$menu_item_db_id]) ) {
		    update_post_meta( $menu_item_db_id, '_sns_megamenu_item_enable', 1 );
		} else {
		    update_post_meta( $menu_item_db_id, '_sns_megamenu_item_enable', 0 );
		}
		// Hide title
		if ( isset( $_REQUEST['sns-mega-mitem-hidetitle'][$menu_item_db_id]) ) {
		    update_post_meta( $menu_item_db_id, '_sns_megamenu_item_hidetitle', 1 );
		} else {
		    update_post_meta( $menu_item_db_id, '_sns_megamenu_item_hidetitle', 0 );
		}
		// Icon
		if ( isset( $_REQUEST['sns-mega-mitem-icon'][$menu_item_db_id]) ) {
		    $icon_value = $_REQUEST['sns-mega-mitem-icon'][$menu_item_db_id];
		    update_post_meta( $menu_item_db_id, '_sns_megamenu_item_icon', $icon_value );
		}
		    
    }
	// Edit form
    function snsnova_megamenuedit($walker, $menu_id) {
	    return 'SnsNova_Megamenu_Admin'; 
	}
	// Load css file
	function snsnova_loadadmincss(){
		wp_enqueue_style('snsnova-thickbox');
		wp_enqueue_style( 'snsnova-iconpicker', THEME_URI.'/assets/css/admin_megamenu.css', false, '1.0', 'all' );
		wp_enqueue_style('snsnova-fontawesome', THEME_URI . '/assets/fonts/awesome/css/font-awesome.min.css');
		
	}
	// Load js file
	function snsnova_loadadminjs(){
		wp_enqueue_script('snsnova-thickbox');
		
	}
	// Load js inline
    function snsnova_megamenujs() {
    	global $wp_filesystem;
        // Initialize the Wordpress filesystem, no more using file_put_contents function
        if ( empty( $wp_filesystem ) ) {
            require_once ABSPATH . '/wp-admin/includes/file.php';
            WP_Filesystem();
        }
	    $icon_fa = array();
		$content_fa = '';
	    if( file_exists( get_template_directory().'/assets/fonts/awesome/css/font-awesome.css' ) ) {
			$content_fa = $wp_filesystem->get_contents(get_template_directory().'/assets/fonts/awesome/css/font-awesome.css');
	    }
	    preg_match_all('/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/', $content_fa , $matches_fa, PREG_SET_ORDER);
	    foreach($matches_fa as $k => $v){
		   $icon_fa[$k] = $v[1];
	    }
	    ?>
		<div id="sns_iconmegapicker" style="display:none">
		    <div class="mega-icon-option wpb-icon-prefix">
		    <?php		
		    if( is_array($icon_fa ) && !empty($icon_fa)) {
		    	echo '<i class=""></i>';
		        foreach( $icon_fa as $k => $v) { 
		            echo '<i class="fa '.esc_attr($v).'"></i>';
		        }
	     	}
			?>
		    </div>
		</div> 
		<script type="text/javascript">
			jQuery(function(){
			  	var snsmegamenu = {
					timeout : false ,  
					init : function(){
						var iconfield = null ;
						var megaicon = null;
						jQuery('.sns-iconpicker').on('click',function(e){
								iconfield = jQuery('#sns-mega-mitem-icon-'+ jQuery(this).attr('data-pickerid') );
								megaicon = iconfield.parent().find('i');
							    tb_show( jQuery(this).attr('title') , '#TB_inline?width=580&height=450&inlineId=sns_iconmegapicker');
							    jQuery('.mega-icon-option i').each(function(){
							    	if( jQuery(this).attr('class') == megaicon.attr('class') ){
							    		jQuery('.mega-icon-option i').removeClass('selected'); jQuery(this).addClass('selected');
							    	}
							    })
							    return false;
						});
					    jQuery('.mega-icon-option i').live('click', function(e) {
					       e.preventDefault();
					       iclass = jQuery(this).attr('class').replace('selected', '').trim();
						   iconfield.attr('value', iclass);
						   megaicon.attr('class', iclass).attr('style', 'display:inline-block');
						   if( megaicon.attr('class') == '' ) megaicon.attr('style', 'display:none');
						   window.parent.tb_remove();
						});	
					}
			    }
			
				snsmegamenu.init();
				jQuery( ".menu-item-bar" ).live( "mouseup", function(event, ui) {
			        if ( !jQuery(event.target).is('a') ) {
					     clearTimeout(snsmegamenu.timeout);
				         snsmegamenu.timeout = setTimeout(snsmegamenu.init() , 700);
					}
			    });
			});
		</script>
	<?php
	}		
}

// Init SnsNova_MegaMenu
$sns_mega = new SnsNova_MegaMenu();
require_once THEME_DIR . '/framework/mega-menu/admin.php';
require_once THEME_DIR . '/framework/mega-menu/frontend.php';

?>
