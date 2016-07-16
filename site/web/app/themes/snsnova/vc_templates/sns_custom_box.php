<?php
/*
* SNS Custom Box
*/

$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

vc_icon_element_fonts_enqueue( $icon_type ); 
$icon_style = '';
if( $icon_color != '' ){
	$icon_style .= 'color:'.esc_attr($icon_color).';';
}
if( $icon_font_size != '' ){
	$icon_style .= 'font-size:'.esc_attr($icon_font_size).';';
}
if( $icon_border_size != '' ){
	if( $border_color == '' ){
		$border_color = '#dfdfdf';
	}
	$icon_style .= 'border:'.esc_attr($icon_border_size).' solid '.esc_attr($border_color).';';
	if( $icon_border_radius != '' ){
		$icon_style .= 'border-radius:'.esc_attr($icon_border_radius).';';
	}
}
$icon_style .= 'display: inline-block;';
$tclass = 'sns-custom-box';
$tclass .= ( trim(esc_attr($extra_class))!='' )?' '.esc_attr($extra_class):'';
$tclass .= esc_attr($this->getCSSAnimation( $css_animation ));
$icon_style .= 'width: 84px; height:84px; text-align:center; line-height: 84px;';
if( $icon_style != '' ) $icon_style = ' style="'.$icon_style.'"';
$icon = '<span'.$icon_style.' class="vc_icon_element-icon '.esc_attr( ${"icon_" . $icon_type} ).'"></span>';
$text_align = ' style="text-align:'.esc_attr($text_align).'"';
$output .= '<div'.$text_align.' class="'.$tclass .'">';
$output .= $icon;
if($title != '') $output .= '<h2 class="wpb_heading"><a href="'.esc_url( $link ).'">'.esc_attr( $title ).'</a></h2>';
if($desc != '') $output .= '<p>'.esc_html( $desc ).'</p>';
$output .= '</div>';
echo $output;

?>