<?php
global $snsnova_obj;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <h1 class="page-header">
        <?php the_title(); ?>
    </h1>
    <?php
    // Post Quote
    if ( get_post_format() == 'quote' && function_exists('rwmb_meta') && rwmb_meta('snsnova_post_quotecontent') && rwmb_meta('snsnova_post_quoteauthor') ) : ?>
        <div class="quote-info post-thumb">
            <?php if ( rwmb_meta('snsnova_post_quotecontent') ) : ?>
            <div class="quote-content gfont"><?php echo esc_html(rwmb_meta('snsnova_post_quotecontent')); ?></div>
            <?php endif; ?>
             <?php if ( rwmb_meta('snsnova_post_quoteauthor') ) : ?>
            <div class="quote-author"><?php echo esc_html(rwmb_meta('snsnova_post_quoteauthor')); ?></div>
            <?php endif; ?>
        </div>
    <?php
    // Post Link
    elseif ( get_post_format() == 'link' && function_exists('rwmb_meta') && rwmb_meta('snsnova_post_linkurl') ) : ?>
        <div class="link-info post-thumb">
            <a class="gfont" title="<?php echo esc_attr(rwmb_meta('snsnova_post_linktitle')) ?>" href="<?php echo esc_url( rwmb_meta('snsnova_post_linkurl') ) ?>"><?php echo esc_html(rwmb_meta('snsnova_post_linktitle')) ?></a>
        </div>
    <?php
    // Post Video
    elseif ( function_exists('rwmb_meta') && rwmb_meta('snsnova_post_video') ) : ?>
        <div class="video-thumb video-responsive">
            <?php
            echo wp_oembed_get(esc_attr(rwmb_meta('snsnova_post_video')));
            ?>
        </div>
    <?php
    // Post Gallery
    elseif ( function_exists('rwmb_meta') && rwmb_meta('snsnova_post_gallery') ) : ?>
        <div class="gallery-thumb">
            <div class="navslider" style="display:none"><span class="prev"><i class="fa fa-angle-left"></i></span><span class="next"><i class="fa fa-angle-right"></i></span></div>
            <div class="thumb-container">
            <?php
            foreach (rwmb_meta('snsnova_post_gallery', 'type=image') as $image) {?>
               <div class="item"><img alt="<?php echo esc_attr($image['alt']); ?>" src="<?php echo esc_attr($image['full_url']); ?>"/></div>
            <?php
            }
            ?>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery('#post-<?php the_ID() ?> .thumb-container').owlCarousel({
                    items: 1,
                    loop:true,
                    dots: false,
                    // animateOut: 'flipInY',
                    //animateIn: 'pulse',
                    //autoplay: true,
                    onInitialized: callback,
                    slideSpeed : 800
                });
                function callback(event) {
                    if(this._items.length > this.options.items){
                        jQuery('#post-<?php the_ID() ?> .navslider').show();
                    }else{
                        jQuery('#post-<?php the_ID() ?> .navslider').hide();
                    }
                }
                jQuery('#post-<?php the_ID() ?> .navslider .prev').on('click', function(e){
                    e.preventDefault();
                    jQuery('#post-<?php the_ID() ?> .thumb-container').trigger('prev.owl.carousel');
                });
                jQuery('#post-<?php the_ID() ?> .navslider .next').on('click', function(e){
                    e.preventDefault();
                    jQuery('#post-<?php the_ID() ?> .thumb-container').trigger('next.owl.carousel');
                });
            });
        </script>
    <?php
    // Post Image
    elseif ( has_post_thumbnail() ) : ?>
        <div class="post-thumb">
            <?php
            the_post_thumbnail();
            ?>
        </div>
    <?php
    endif;?>
    <div class="post-content">
        <?php 
        the_content();
        // Post Paging
        wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'snsnova' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); 
        ?>
    </div>
    <?php
    if ( is_sticky() && is_home() && ! is_page() ) {
        printf( '<span class="sticky-post">%s</span>', esc_html__( 'Featured', 'snsnova' ) );
    }
    ?>
    <div class="wp_postmeta">
        <span class="separator">|</span><span class="user-post"><i class="fa fa-user"></i> <?php the_author_posts_link(); ?></span>
        <span class="separator">|</span><span class="date-post"><i class="fa fa-calendar-o"></i> <?php the_time('F jS, Y'); ?></span>
        <?php edit_post_link(esc_html__('Edit','snsnova'), '<span class="separator">|</span><span class="edit-post"><i class="fa fa-edit"></i> ', '</span>'); ?>
        <?php
        // List categories
        $categories_list = get_the_category_list( esc_html__( ', ', 'snsnova' ) );
        if ( $categories_list ) {
            echo '<span class="separator">|</span><span class="categories-links"><i class="fa fa-folder-o"></i> ' . $categories_list . '</span>';
        }
        // List tags
        $tag_list = get_the_tag_list( '', esc_html__( ', ', 'snsnova' ) );
        if ( $tag_list ) {
            echo '<span class="separator">|</span><span class="tags-links"><i class="fa fa-tags"></i> ' . $tag_list . '</span>';
        }
        ?>
    </div>
    <?php
    // Author bio
    if ( $snsnova_obj->snsnova_getOption('show_postauthor') == '' || $snsnova_obj->snsnova_getOption('show_postauthor') ) :
        get_template_part( 'author-bio' );
    endif;
    // Related post
    if ( $snsnova_obj->snsnova_getOption('enalble_related') ) :
    ?>
    <div class="post-related">
        <?php
            snsnova_relatedpost($snsnova_obj->snsnova_getOption('related_num'), 'post', 'category');
        ?>
    </div>
    <?php
    endif;
    if ( $snsnova_obj->snsnova_getOption('show_postsharebox') ) : 
        snsnova_sharebox();
    endif;
    ?>
    <?php
    // Post Comment
    if ( comments_open() || get_comments_number() ) :
        comments_template();
    endif;
    // Prev & Next post navigation.
      the_post_navigation( array(
          'prev_text' => '<span class="prev-post screen-reader-text">' . esc_html__( 'Previous post', 'snsnova' ) . '</span>' .
          '<span class="post-title">: %title</span>',
          'next_text' => '<span class="next-post screen-reader-text">' . esc_html__( 'Next post', 'snsnova' ) . '</span>' .
          '<span class="post-title">: %title</span>',
      ) );
    ?>
</article>