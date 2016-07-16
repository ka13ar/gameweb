<?php
global $snsnova_obj;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
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
    elseif ( get_post_format() == 'video' && function_exists('rwmb_meta') && rwmb_meta('snsnova_post_video') ) : ?>
        <div class="video-thumb video-responsive">
            <?php
            echo rwmb_meta('snsnova_post_video');
            ?>
        </div>
    <?php
    // Post Gallery
    elseif ( get_post_format() == 'gallery' && function_exists('rwmb_meta') && rwmb_meta('snsnova_post_gallery') ) : ?>
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
            the_post_thumbnail($snsnova_obj->snsnova_getOption('img_size'));
            ?>
        </div>
    <?php
    endif;?>
    <div class="post-content">
        <div class="date-and-format">
            <?php
            // Date
            if ( $snsnova_obj->snsnova_getOption('show_date') == '' || $snsnova_obj->snsnova_getOption('show_date') == true) : ?>
            <div class="date-post">
                <span class="month"><?php the_time('M'); ?></span>
                <span class="day"><?php the_time('d'); ?></span>
            </div>
            <?php endif; ?>
            <!-- Post Fotmat -->
            <div class="post-format <?php echo esc_attr(get_post_format()); ?><?php echo (esc_attr($post->post_type) == 'product') ? ' product' : '' ; ?>"></div>
        </div>
        <div class="content">
            <h2 class="page-header">
              <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permalink to %s', 'snsnova' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
            </h2>
            <div class="wp_postmeta">
                <?php
                // Is sticky or not
                if ( is_sticky() && is_home() && ! is_paged() ) { ?>
                    <span class="separator">|</span><span class="sticky-post"><i class="fa fa-thumb-tack"></i> <?php echo esc_html__( 'Sticky', 'snsnova' ) ; ?></span>
                <?php
                }
                // Author
                if ( $snsnova_obj->snsnova_getOption('show_author') == '' || $snsnova_obj->snsnova_getOption('show_author') == true) : ?>
                <span class="separator">|</span><span class="user-post"><i class="fa fa-user"></i> <?php the_author_posts_link(); ?></span>
                <?php endif; ?>
                <?php
                // Edit link
                edit_post_link(esc_html__('Edit','snsnova'), '<span class="separator">|</span><span class="edit-post"><i class="fa fa-edit"></i> ', '</span>'); ?>
                <?php
                // Categories
                if ( $snsnova_obj->snsnova_getOption('show_categories') == '' || $snsnova_obj->snsnova_getOption('show_categories') == true) :
                    $categories_list = get_the_category_list( esc_html__( ', ', 'snsnova' ) );
                    if ( $categories_list ) {
                        echo '<span class="separator">|</span><span class="categories-links"><i class="fa fa-folder-o"></i> ' . $categories_list . '</span>';
                    }
                endif;
                // List tags
                if ( $snsnova_obj->snsnova_getOption('show_tags') == '' || $snsnova_obj->snsnova_getOption('show_tags') == true ) :
                    $tag_list = get_the_tag_list( '', esc_html__( ', ', 'snsnova' ) );
                    if ( $tag_list ) {
                        echo '<span class="separator">|</span><span class="tags-links"><i class="fa fa-tags"></i> ' . $tag_list . '</span>';
                    }
                endif; 
                ?>
            </div>
            <?php if( empty( $post->post_excerpt ) ) { ?>
                <?php
                $readmore = '<span class="button">'. esc_html__("Read More", 'snsnova') . '</span>';
                if ( is_search() && $post->post_type == 'page' ) {
                    // Trip shortcodes for post type is page on search result page
                    echo strip_shortcodes(get_the_content($readmore));
                }else{
                    the_content($readmore);
                }
                wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'snsnova' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
                ?>
            <?php } else { ?>
                <p class="excerpt"><?php echo snsnova_excerpt( esc_attr($snsnova_obj->snsnova_getOption('excerpt_length')) ); ?></p>
                <?php if ($snsnova_obj->snsnova_getOption('enable_readmore') == true) : ?>
                <a href="<?php the_permalink() ?>" class="more-link"><span class="button"><?php echo esc_html__('Read More','snsnova') ?></span></a>
                <?php endif; ?>
            <?php } ?>
        </div>
    </div>
</article>