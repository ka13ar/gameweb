<?php
global $snsnova_opt, $snsnova_obj;
$theme_color = $snsnova_obj->snsnova_getOption('theme_color');
if ( function_exists('rwmb_meta') && rwmb_meta('snsnova_themecolor') ) $theme_color = rwmb_meta('snsnova_themecolor');
$theme_color = str_replace('#', '', $theme_color);
$boxedlayout =  $snsnova_obj->snsnova_getOption('use_boxedlayout');
$stickymenu = $snsnova_obj->snsnova_getOption('use_stickmenu');
?>
<div id="sns_cpanel">
    <div class="cpanel-head">
    	<a class="button btn-buy" href="#" title="<?php echo esc_html__('Buy Theme Now', 'snsnova'); ?>"><?php echo esc_html__('Buy Theme Now', 'snsnova'); ?></a>
    </div>
    <div class="cpanel-set">
    	<div class="form-group">
    		<div class="col-xs-12">
				<label><?php echo esc_html__('Theme Color', 'snsnova'); ?></label>
				<div class="" id="cpanel_themecolor">
					<a class="<?php echo ( $theme_color == '3cabda' ) ? 'active color' : 'color'; ?>" href="<?php echo esc_url( site_url() ); ?>" data-color="3cabda">#3cabda</a>
                    <a class="<?php echo ( $theme_color == '00a988' ) ? 'active color' : 'color'; ?>" href="<?php echo ( get_page(2598) ) ? esc_url( get_page_link(2598) ) : '' ; ?>" data-color="00a988">#00a988</a>
                    <a class="<?php echo ( $theme_color == 'ff8402' ) ? 'active color' : 'color'; ?>" href="<?php echo ( get_page(2611) ) ? esc_url( get_page_link(2611) ) : '' ; ?>" data-color="ff8402">#ff8402</a>
                    <a class="<?php echo ( $theme_color == '81c77f' ) ? 'active color' : 'color'; ?>" href="<?php echo ( get_page(2609) ) ? esc_url( get_page_link(2609) ) : '' ; ?>" data-color="81c77f">#81c77f</a>
                    <a class="<?php echo ( $theme_color == 'b263a4' ) ? 'active color' : 'color'; ?>" href="<?php echo ( get_page(2613) ) ? esc_url( get_page_link(2613) ) : '' ; ?>" data-color="b263a4">#b263a4</a>
				</div>
				<p><?php echo esc_html__('You can also sellect color codes via admin theme options', 'snsnova'); ?></p>
			</div>		
		</div>
		<div class="form-group">
			<div class="col-xs-12">
				<label><?php echo esc_html__('Enable sticky menu', 'snsnova'); ?></label>
				<div class="content sticky_menu">
					<a class="<?php echo ($stickymenu == 1)?'active menu':'menu'; ?>" href="#" data-sticky="1">Yes</a>
					<a class="<?php echo ($stickymenu == 0)?'active menu':'menu'; ?>" href="#" data-sticky="0">No</a>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-12">
				<label><?php echo esc_html__('Use boxed Layout', 'snsnova'); ?></label>
				<div class="content boxed_layout">
					<a class="<?php echo ($boxedlayout == 1)?'active layout':'layout'; ?>" href="#" data-boxed="1">Yes</a>
					<a class="<?php echo ($boxedlayout == 0)?'active layout':'layout'; ?>" href="#" data-boxed="0">No</a>
				</div>
			</div>
		</div>
    </div>
    <div class="cpanel-bottom">
    	<div class="form-group">
			<div class="col-xs-12">
				<div class="button-action">
					<a class="button btn-reset" href="#"><?php echo esc_html__('Reset Options', 'snsnova'); ?></a>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-12">
				<!-- <label>Cpanel tool</label> -->
				<p>That is some options to demo for you.</p>
			</div>
		</div>
	</div>
    <div id="sns_cpanel_btn">
        <i class="fa fa-cog fa-spin"></i>
    </div>
	<script type="text/javascript">
	// <![CDATA[
	jQuery(document).ready(function($){
		$('#sns_cpanel_btn').click(function(){
			if( !$('#sns_cpanel').hasClass('open') ){
				$('#sns_cpanel').animate({right:'0px'});
				$('#sns_cpanel').addClass('open');
			}else{
				$('#sns_cpanel').animate({right:'-290px'});
				$('#sns_cpanel').removeClass('open');
			}
		});
		
		$('#cpanel_themecolor a').each(function(){
			$(this).css({
				'background-color': '#'+$(this).data('color')
			});
		})
		// Click theme color
		$('#cpanel_themecolor a').click(function(){
            var color = $(this).data('color');
            var href = $(this).attr('href');
            $('#sns_cpanel').addClass('wait');
            if ( href != '#' ) window.location.href = href;
            else window.location.href = window.location.href;
            return false;
        });
        // Click Boxed Layout
		$('#sns_cpanel .boxed_layout a').click(function(){
            var boxed = $(this).data('boxed');
            var href = $(this).attr('href');
            $('#sns_cpanel').addClass('wait');
            $.ajax({
                url: ajaxurl,
                data:{
                	action : 'snsnova_setcookies',
                	key	: 'use_boxedlayout',
                	value : boxed
                },
                type: 'POST',
                success:function(result){
                	if ( href != '#' ) window.location.href = href;
                	else window.location.href = window.location.href;
                }
            });
            return false;
        });
        // Click Sticky Menu
		$('#sns_cpanel .sticky_menu a').click(function(){
            var sticky = $(this).data('sticky');
            var href = $(this).attr('href');
            $('#sns_cpanel').addClass('wait');
            $.ajax({
                url: ajaxurl,
                data:{
                	action : 'snsnova_setcookies',
                	key	: 'use_stickmenu',
                	value : sticky
                },
                type: 'POST',
                success:function(result){
                	if ( href != '#' ) window.location.href = href;
                	else window.location.href = window.location.href;
                }
            });
            return false;
        });
        // Reset cookie
        $('#sns_cpanel .btn-reset').click(function(){
            var href = '<?php echo site_url(); ?>';
            $('#sns_cpanel').addClass('wait');
            $.ajax({
                url: ajaxurl,
                data:{
                	action : 'snsnova_resetcookies'
                },
                type: 'POST',
                success:function(result){
                	if ( href != '#' ) window.location.href = href;
                	else window.location.href = window.location.href;
                }
            });
            return false;
        });
	});
	// ]]>
	</script>
</div>