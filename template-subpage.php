<?php
/**
 * Template Name: Список подстраниц
 *
 * @since 1.0
 */
get_header();

global $page_id;
global $paged;
global $mpcth_options;
?>

<div id="mpcth_main">
    <div id="mpcth_main_container">
        <?php get_sidebar(); ?>
        <div id="mpcth_content_wrap">
            <div id="mpcth_content">
                <h3 class="mpcth-deco-header">
                    <span>
                        <?php
                        $title = get_the_title();
                        echo $title;
                        ?>
                    </span>
                </h3>
                    <?php
                    $args = array(
                        'post_type' => 'page', // тип записи
                        'publish' => true,
                        'nopaging' => true,
                        'post_parent' => get_the_ID()
                    );
                    $query = new WP_Query($args);
                    if ($query->have_posts()) {
                        echo '<ul class="menu" >';
                        while ($query->have_posts()) {
                            $query->the_post();
                            the_title('<li><a href="' . get_the_permalink() . '">', '</a></li>');
                        }
                        echo '</ul>';
                    }
                    wp_reset_postdata(); // сбрасываем переменную $post    
                    /* translators: %s: Name of current post */
                    the_content();
                    ?>
            </div><!-- end #mpcth_content -->
        </div><!-- end #mpcth_content_wrap -->
    </div><!-- end #mpcth_main_container -->
</div><!-- end #mpcth_main -->
<script>
    jQuery(document).ready(function ($) {
        $("span.month").click(function (a) {
            //$("ul.items").hide("fast");
            $(this).next("ul.items").toggle("fast");
        });
    });
</script>
<?php
get_footer();
