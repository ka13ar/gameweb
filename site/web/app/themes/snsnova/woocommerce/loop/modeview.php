<?php
global $snsnova_opt;

$modeview = $snsnova_opt['woo_list_modeview'];
if (isset($_COOKIE['snsnova_woo_list_modeview']) && $_COOKIE['snsnova_woo_list_modeview']== 'grid') {
    $modeview = 'grid';
}elseif (isset($_COOKIE['snsnova_woo_list_modeview']) && $_COOKIE['snsnova_woo_list_modeview']== 'list') {
    $modeview = 'list';
}
?>
<ul class="mode-view pull-left">
    <li class="grid">
    	<a class="grid<?php echo ($modeview=='grid')?' active':''; ?>" data-mode="grid" href="#" title="<?php echo esc_html__('Grid', 'snsnova'); ?>">
    		<i class="fa fa-th"></i><span><?php echo esc_html__('Grid', 'snsnova'); ?></span>
    	</a>
    </li>
    <li class="list">
    	<a class="list<?php echo ($modeview=='list')?' active':''; ?>" data-mode="list" href="#" title="<?php echo esc_html__('List', 'snsnova'); ?>">
            <i class="fa fa-th-list"></i><span><?php echo esc_html__('List', 'snsnova'); ?></span>
        </a>
    </li>
</ul>