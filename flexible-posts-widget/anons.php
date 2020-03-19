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
    echo $before_title  . $title . $after_title;

if ($flexible_posts->have_posts()):
    ?>
    <div class="newsshort">
        <ul>   
            <?php
            while ($flexible_posts->have_posts()) : $flexible_posts->the_post();
                global $post;
                ?>
                <li>
                    <div class="wrap" >
                        <?php if (has_post_thumbnail()) { ?>
                    <a href="<?php the_permalink(); ?>" class="thumb"
                    rel="bookmark"><?php the_post_thumbnail('gallerythumb', array('alt' => get_the_title())); ?></a>
                    <?php
                    }
                    the_title(sprintf('<h3 class="anonstitle"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>');
                    ?>
                    <?php if ('post' == get_post_type()) : ?>
                        <?php //metatags2(); ?>
                    <?php endif; ?>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
    <?php
endif; // End have_posts()

echo $after_widget;
