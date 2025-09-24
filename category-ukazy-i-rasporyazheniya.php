<?php
/**
 * The Page base for MPC Themes
 * Template Name: Указы и Распоряжения 2024
 * Displays single page.
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */

get_header(); ?>
<div id="mpcth_main">

    <?php mpcth_print_blog_archives_custom_header(); ?>

    <div>
        <?php get_sidebar(); ?>
        <div class="container-fluid">
            <div class="row">
               <header id="mpcth_archive_header">
                    <?php mpcth_breadcrumbs(); ?>
                    <h1 id="mpcth_archive_title" class="mpcth-deco-header" style="font-family: Kazimir; font-size: 1.65em;">
                        <?php echo single_cat_title('', false); ?>
                    </h1>
                    <div class="bat_kit">
                        <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/ukazy-i-rasporyazheniya-2020" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Указы и распоряжения 2020 год</a>
                        <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/ukazy-i-rasporyazheniya-2021-god" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Указы и распоряжения 2021 год</a>
                        <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/mitropoliya/ukazy-i-rasporyazheniya/ukazy-i-rasporyazheniya-2022-god" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Указы и распоряжения 2022 год</a>
                        <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/ukazy-i-rasporyazheniya-2023-god" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Указы и распоряжения 2023 год</a>
                        <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/ukazy-i-rasporyazheniya-2024-god" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Указы и распоряжения 2024 год</a>
                        <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/mitropoliya/ukazy-i-rasporyazheniya" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Указы и распоряжения 2025 год</a>
                    </div>
                </header>

                <?php
                $args = [
                    'category__in' => 54,
                    'post_type'      => 'post',
                    'posts_per_page' => 18,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'paged'          => $paged
                ];
                $q = new WP_Query($args);
                ?>

                <?php if ($q->have_posts()) : ?>
                    <?php while ($q->have_posts()) : $q->the_post(); ?>
                        <div class="col-lg-12 mb-12">
                            <div class="drim">
                                <ul class="list-group list-group-flush drimtxt">
                                    <li class="list-group-item">
                                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    </li>
                                </ul>
                                <div class="drimlin"></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>

                <div id="mpcth_pagination">
                    <?php
                    if ($blog_load_more) {
                        mpcth_display_load_more($q);
                    } else {
                        mpcth_display_pagination($q);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php get_footer(); ?>
