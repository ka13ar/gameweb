<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<h1 class="page-header">
        <?php the_title(); ?>
    </h1>
    <?php
    while ( have_posts() ) : the_post();
        the_content();
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;
    endwhile;
    ?>
</section>