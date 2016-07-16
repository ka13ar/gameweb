<?php
class SnsNova_Megamenu_Front extends Walker_Nav_Menu {
    var $columns = 0;
    var $enablemega = 0;

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu {replace_class}\">\n";
    }
    function end_lvl(&$output, $depth = 0, $args = array()) { 
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
        if ($depth === 0) {
            if($this->enablemega && $this->columns > 0){
                $output = str_replace("{replace_class}", "enable-megamenu row-fluid col-".$this->columns."", $output);
                $this->columns = 0;
            }
            else{
                $output = str_replace("{replace_class}", "", $output);
            }
        }
    }    
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        global $wp_query;
        $item_output = $li_text_block_class = $column_class = "";
        if($depth === 0){   
            $this->enablemega = get_post_meta( $item->ID, '_sns_megamenu_item_enable', true);
        }
        if($depth === 1 && $this->enablemega) {
            $this->columns ++;
			if( $item->hidetitlemega != true ){
                 $title = apply_filters( 'the_title', $item->title, $item->ID );
                if($title != "-" && $title != '"-"'){
                   $attributes = ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';      
            
                   $item_output .= $args->before;
                   $item_output .= '<h4 class="megamenu-title"'. $attributes .'>';
                   $item_output .= (get_post_meta( $item->ID, '_sns_megamenu_item_icon', true) != '')?'<i class="'.get_post_meta( $item->ID, '_sns_megamenu_item_icon', true).'"></i>':'';
                   $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
                   $item_output .= '</h4>';
                   $item_output .= $args->after;
               }
			}
        }else{
            $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
            $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
            $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
            $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';            
        
            $item_output .= $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= (get_post_meta( $item->ID, '_sns_megamenu_item_icon', true) != '')?'<i class="'.get_post_meta( $item->ID, '_sns_megamenu_item_icon', true).'"></i>':'';
            $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;
        }
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        if( $depth == 0 && $this->enablemega ) $class_names .= ' enable-mega';
        if ( get_post_meta( $item->ID, '_sns_megamenu_item_icon', true) != '' ) $class_names .= ' have-icon';
        $class_names = ' class="'.$li_text_block_class. esc_attr( $class_names ) . $column_class.'"';
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
        $output .= $indent . '<li '.$id . $value . $class_names .'>'; 
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}
?>