<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url('/') ); ?>">
	<div>
		<input type="text" placeholder="<?php echo esc_html__('Search', 'snsnova'); ?>" value="<?php echo get_search_query(); ?>" name="s" id="s" />
		<input type="submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'snsnova' ); ?>" />
	</div>
</form>