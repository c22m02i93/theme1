<?php
/**
 * The Page base for MPC Themes
 * Template Name: Газета 2020
 * Displays single page.
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */

get_header(); ?>

<div id="mpcth_main">

    <?php dynamic_sidebar("Datatim"); ?>

    <?php mpcth_print_blog_archives_custom_header(); ?>

    <div id="mpcth_main_container">
        <?php get_sidebar(); ?>
        <div class="container-fluid">
            <div class="row">
                <header id="mpcth_archive_header">
                    <?php mpcth_breadcrumbs(); ?>
                    <h1 id="mpcth_archive_title" class="mpcth-deco-header" style="font-family: oswald; font-size: 2.15em;"><?php echo single_cat_title('', false); ?></h1>

                </header>

                <?php
                $query = new WP_Query([
                    'category__in' => 98,
                    'post_type' => 'post',
                    'posts_per_page' => 8,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'paged' => $paged
                ]);

                // Обрабатываем полученные в запросе продукты, если они есть
                if ($query->have_posts()) {
                    $counter = 0;
                    while ($query->have_posts()) {
                        $query->the_post();
                        if ($counter % 3 === 0) { ?>
                            <div class="container">
                                <div class="row">
                        <?php }
                        ?>

                        <div class="col-md-4 p-3">
                            <div class="row ">
                            <div class="col-md-12 text-center">
                            <a class="w-100" href="<?php the_permalink() ?>">
                              <?php
                              if ( has_post_thumbnail() ) {
                                the_post_thumbnail('large', array('class' => 'mpcth-custom-image'));
                            } else {
                                $attachments = get_children( array(
                                    'post_parent' => get_the_ID(),
                                    'post_type' => 'attachment',
                                    'numberposts' => 1,
                                    'order' => 'ASC',
                                    'post_mime_type' => 'image'
                                ) );
                                if ( $attachments ) {
                                    foreach ( $attachments as $attachment ) {
                                        echo wp_get_attachment_image( $attachment->ID, 'full' );
                                    }
                                }
                            }
                            
                              ?>
                            </a>
                            <h4> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </h4>
                          </div>
                            </div>
                        </div>
                        <?php if ($counter % 3 === 2 || $query->current_post === $query->post_count - 1) { ?>
                            </div>
                            </div>
                        <?php }
                        $counter++;
                    }
                    wp_reset_postdata();
                } ?>

                <div id="mpcth_pagination">
                    <?php
                    if ($blog_load_more)
                        mpcth_display_load_more($q);
                    else
                        mpcth_display_pagination($q);
                    ?>
                </div>

            </div>

        </div>
    </div>

    <?php get_footer(); ?>
