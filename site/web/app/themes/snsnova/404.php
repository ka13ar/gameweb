<?php get_header(); ?>
<div id="sns_content">
    <div class="container">
        <div class="row sns-content">
            <div class="col-md-12 sns-main">
                <h1 class="page-header">
                    <?php echo esc_html__('Page not found','snsnova'); ?>
                </h1>
                <div class="row">
                    <div class="col-md-7 notfound-left">
                        <h2><?php echo esc_html__('404: Error', 'snsnova'); ?></h2>
                        <p><?php echo esc_html__('Oops, This Page Could Not Be Found!', 'snsnova'); ?></p>
                    </div>
                    <div class="col-md-5 notfound-right">
                        <h2><?php echo esc_html__('Search Our Website', 'snsnova'); ?></h2>
                        <p><?php echo esc_html__("Can't find what you need? Take a moment and do a search below!", "snsnova"); ?></p>
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>