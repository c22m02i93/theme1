<?php
/**
 * Flexible Posts Widget: Anons
 * 
 * @since 3.4.0
 *
 * This template was added to overcome some often-requested changes
 * to the old default template (widget.php).
 */
// Block direct requests
if (!defined('ABSPATH'))
    die('-1');

echo $before_widget;

if (!empty($title))
    echo $before_title . $title . $after_title;

if ($flexible_posts->have_posts()):
    ?>
    <div class="newsshort">   
        <?php
        while ($flexible_posts->have_posts()) : $flexible_posts->the_post();
            global $post;
            ?>
            <div class="row">
               
                <div class="col-sm-2 datetime">
                    <?php
                    ugskaya_posted_on();
                    ?>   
                </div>
                <div class="col-sm-1">
                    <?php if ('post' == get_post_type()) : ?>
                        <?php
                        echo '<div class="autor" ><a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" data-toggle="tooltip" data-placement="top" title="' . get_the_author() . '" >' . get_avatar(get_the_author_meta('ID'), 30);
                        // the_author_posts_link();    
                        echo '</a></div>';
                        ?>
                    <?php endif; ?>
                </div>                 
                <div class="col-sm-9">
                    <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
                </div>

            </div>
        <?php endwhile; ?>
    </div>
    <?php
endif; // End have_posts()

echo $after_widget;
