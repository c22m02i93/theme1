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
    <ul>   
        <?php
        while ($flexible_posts->have_posts()) : $flexible_posts->the_post();
            global $post;
            ?>
            <li>
                <?php
                echo get_the_date();
                the_title(sprintf('<p><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></p>');
                ?>
                <?php if ('post' == get_post_type()) : ?>
                    <?php //metatags2(); ?>
                <?php endif; ?>
            </li>
        <?php endwhile; ?>
    </ul>
    <?php
endif; // End have_posts()

echo $after_widget;
