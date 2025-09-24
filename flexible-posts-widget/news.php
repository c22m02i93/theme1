<?php

/**
 * Flexible Posts Widget: Default widget template
 * 
 * @since 3.4.0
 *
 * This template was added to overcome some often-requested changes
 * to the old default template (widget.php).
 */
function more_posts($wp_query) {
    return $wp_query->current_post + 1 < $wp_query->post_count;
}

// Block direct requests
if (!defined('ABSPATH'))
    die('-1');
if ($thumbnail == true) {
    $col2 = 'col-sm-9';
} else {
    $col2 = 'col-sm-12';
}
echo $before_widget;

if (!empty($title))
    echo $before_title . $title . $after_title;

if ($flexible_posts->have_posts()):
    $i = 1;
    $j = 1;
    ?>

    <div id="owl-demo" >
        <div class="item">
            <div class="row">
                <?php
                while ($flexible_posts->have_posts()) : $flexible_posts->the_post();
                    global $post;
                    ?>
                    <div class="col-sm-4">
        <?php if ($thumbnail == true) { ?>
                            <div class="smalnews-thumbnail">
                                <a href="<?php the_permalink(); ?>" class="thumb" rel="bookmark"><?php the_post_thumbnail('gallerythumb', array('alt' => get_the_title())); ?></a>
                            </div>
                            <?php } ?>
                        <div class="date"><i class="fa fa-calendar"></i> <?php echo get_the_date(); ?>
                            <?php
                            $video = get_field('video');
                            if ($video) {
                                echo '<a href="' . get_the_permalink() . '#video" class="video" ><i class="fab fa-youtube"></i> Видео</a>';
                            }
                            ?>
                        </div>
                    <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
                    </div>    
                    <?php
                    $i++;
                    if ($i > 3) {
                        echo '</div>';
                        $i = 1;
                        $j++;
                        if ($j > 2 AND more_posts($flexible_posts)) {
                            echo '</div><div class="item hidden"><div class="row">';
                            $j = 1;
                        } else {
                            echo '<div class="row">';
                        }
                    }
                endwhile;
                ?>
            </div>
        </div>
    </div>
    <div class="allnews">
        <a href="/category/novosti-media/novosti/" class="allnews">Все новости <i class="fa fa-angle-right"></i></a>
        <a href="/arhiv-novostej/" class="allnews">Архив новостей <i class="fa fa-angle-right"></i></a>
    </div>    
    <?php
endif; // End have_posts()

echo $after_widget;
?>

<script>
    jQuery("#owl-demo").owlCarousel({
        navigation: true, // Show next and prev buttons
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem: true,
        lazyLoad: true,
        rewindNav: false,
        navigationText: ['<i class="fa fa-long-arrow-left"></i>', '<i class="fa fa-long-arrow-right"></i>']
    });
    jQuery("#owl-demo .item").removeClass('hidden');
</script>