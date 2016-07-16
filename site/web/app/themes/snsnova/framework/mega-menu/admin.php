<?php
// Extends /wp-includes/nav-menu-template.php
class SnsNova_Megamenu_Admin extends Walker_Nav_Menu  {
	function start_lvl(&$output, $depth = 0, $args = array()) {	
	}
	function end_lvl(&$output, $depth = 0, $args = array()) {
	}
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) { 
	
	    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	    $item_id = esc_attr( $item->ID );
	    $removed_args = array(
	        'action',
	        'customlink-tab',
	        'edit-menu-item',
	        'menu-item',
	        'page-tab',
	        '_wpnonce',
	    );
	
	    $classes = array(
	        'menu-item menu-item-depth-' . $depth,
	        'menu-item-' . esc_attr( $item->object ),
	        'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
	    );
	    $title = empty( $item->label ) ? $item->title: $item->label;
		// Vaule for megamenu option
		$item->iconmega = (isset($item->iconmega)) ? $item->iconmega : '' ;
		$item->enablemega = (isset($item->enablemega)) ? $item->enablemega : 0 ;
		$item->hidetitlemega = (isset($item->hidetitlemega)) ? $item->hidetitlemega : 0 ;

		ob_start();
	    ?>
	    <li id="menu-item-<?php echo esc_attr($item_id); ?>" class="<?php echo esc_attr(implode(' ', $classes )); ?>">
	        <dl class="menu-item-bar">
	            <dt class="menu-item-handle">
	                <span class="item-title">
	                	<span class="menu-item-title"><?php echo esc_html( $title ); ?></span>
	                	<span class="is-submenu" <?php if($depth == 0) echo 'style="display:none"'; ?>><?php echo esc_html__('sub item', 'snsnova'); ?></span>
	                <span class="item-controls">
	                    <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
	                    <span class="item-order hide-if-js">
	                        <a href="<?php
	                            echo esc_url(
								         wp_nonce_url(
										  add_query_arg(
											  array(
												  'action' => 'move-up-menu-item',
												  'menu-item' => $item_id,
											  ),
											  remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
										  ),
										  'move-menu_item'
	                            ));
	                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'snsnova'); ?>">&#8593;</abbr></a>
	                        |
	                        <a href="<?php
	                            echo  esc_url(
										wp_nonce_url(
										   add_query_arg(
												array(
													'action' => 'move-down-menu-item',
													'menu-item' => $item_id,
												),
												remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
											),
											'move-menu_item'
										)
							);
	                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down', 'snsnova'); ?>">&#8595;</abbr></a>
	                    </span>
	                    <a class="item-edit" id="edit-<?php echo esc_attr($item_id); ?>" title="<?php esc_attr_e('Edit Menu Item', 'snsnova'); ?>" href="<?php
	                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : esc_url(add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) )) );
	                    ?>"><?php esc_html_e( 'Edit Menu Item', 'snsnova' ); ?></a>
	                </span>
	            </dt>
	        </dl>
	
	        <div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo esc_attr($item_id); ?>">
	            <?php if( 'custom' == $item->type ) : ?>
	                <p class="field-url description description-wide">
	                    <label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
	                        <?php esc_html_e( 'URL', 'snsnova' ); ?><br />
	                        <input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
	                    </label>
	                </p>
	            <?php endif; ?>
	            <p class="description description-thin">
	                <label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( 'Navigation Label', 'snsnova' ); ?><br />
	                    <input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
	                </label>
	            </p>
	            <p class="description description-thin">
	                <label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( 'Title Attribute', 'snsnova' ); ?><br />
	                    <input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
	                </label>
	            </p>
	            <p class="field-link-target description">
	                <label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
	                    <input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
	                    <?php esc_html_e( 'Open link in a new window/tab', 'snsnova' ); ?>
	                </label>
	            </p>
	            <p class="field-css-classes description description-thin">
	                <label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( 'CSS Classes (optional)', 'snsnova' ); ?><br />
	                    <input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
	                </label>
	            </p>
	            <p class="field-xfn description description-thin">
	                <label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( 'Link Relationship (XFN)', 'snsnova' ); ?><br />
	                    <input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
	                </label>
	            </p>
	            <p class="field-description description description-wide">
	                <label for="edit-menu-item-description-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( 'Description', 'snsnova' ); ?><br />
	                    <textarea id="edit-menu-item-description-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr($item_id); ?>]"><?php echo esc_html( $item->description );?></textarea>
	                    <span class="description"><?php esc_html_e('The description will be displayed in the menu if the current theme supports it.', 'snsnova'); ?></span>
	                </label>
	            </p>        
	            <!-- Begin: Option Megamenu -->
               	<div class="sns-megamenu-options">   
	           	<p class="field-megamenu-icon description description-wide">
	                <label for="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( "Menu Item's Icon", "snsnova" ); ?><br />
	                    <?php 
	                    $style = ( $item->iconmega !='' )?'style="display:inline-block"':'style="display:none"'; 
	                    echo '<i '.$style.' class="'.esc_attr($item->iconmega).'"></i>';
	                    ?>
	                    <input type="hidden" id="sns-mega-mitem-icon-<?php echo esc_attr($item_id); ?>" class="edit-sns-mega-mitem-icon" name="sns-mega-mitem-icon[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->iconmega ); ?>"  />
                        <a data-pickerid="<?php echo esc_attr($item_id); ?>" class="button-primary sns-iconpicker" title="<?php echo esc_html__('Select Icon','snsnova');?>" href="#" ><?php echo esc_html__('Select Icon','snsnova');?></a>
	                </label>
	            </p>
	            <p class="field-megamenu-enable description description-wide">
	                <label for="sns-mega-mitem-enable-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( 'Enable Mega Menu(Apply for level 1)', 'snsnova' ); ?><br />
	                    <input type="checkbox" id="sns-mega-mitem-enable-<?php echo esc_attr($item_id); ?>" class="edit-sns-mega-mitem-enable" name="sns-mega-mitem-enable[<?php echo esc_attr($item_id); ?>]" value="1" <?php echo checked( !empty( $item->enablemega ), 1, false ); ?> />
	                </label>
	            </p>
                
                <p class="field-megamenu-hidetitle description description-wide">
	                <label for="sns-mega-mitem-hidetitle-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( 'Hide Title(Apply for level 2)', 'snsnova' ); ?><br />
	                    <input type="checkbox" id="sns-mega-mitem-hidetitle-<?php echo esc_attr($item_id); ?>" class="edit-sns-mega-mitem-hidetitle" id="edit-menu-item-hide-title[<?php echo esc_attr($item_id); ?>]" name="sns-mega-mitem-hidetitle[<?php echo esc_attr($item_id); ?>]" value="1" <?php echo checked( !empty( $item->hidetitlemega ), 1, false ); ?> />
	                </label>
	            </p>
                </div>
	            <!-- End: Option Megamenu -->
	            <div class="menu-item-actions description-wide submitbox">
	                <a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
	                echo esc_url(
						   wp_nonce_url( 
								add_query_arg(
									array(
										'action' => 'delete-menu-item',
										'menu-item' => $item_id,
									),
									remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
								),
								'delete-menu_item_' . $item_id
							   ) 
							  ); ?>"><?php esc_html_e('Remove', 'snsnova'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr($item_id); ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
	                    ?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php esc_html_e('Cancel', 'snsnova'); ?></a>
	            </div>
	
	            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item_id); ?>" />
	            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
	            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
	            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
	            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
	            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
	        </div>
	        <ul class="menu-item-transport"></ul>

    	<?php
    	$output .= ob_get_clean();
	}
} 

?>