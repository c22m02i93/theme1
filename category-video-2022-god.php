<?php

/**
 * The Page base for MPC Themes
 *Template Name: Видео  2022 год
 * Displays single page.
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */

get_header(); ?>
<div id="mpcth_main">

  <?php
  mpcth_print_blog_archives_custom_header();
  ?>

  <div>
    <?php get_sidebar(); ?>
    <div class="container-fluid">
      <div class="row">
        <header id="mpcth_archive_header">
          <?php mpcth_breadcrumbs(); ?>
          <h1 id="mpcth_archive_title" class="mpcth-deco-header" style="font-family: Kazimir; font-size: 1.65em;">
            <?php echo single_cat_title('', false); ?>
          </h1>

          <div class="bat_kit" style="margin-bottom: 16px;">

            <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/video-2019" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Видео 2019 год</a>
            <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/video-2020" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Видео 2020 год</a>
            <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/video-2021" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Видео 2021 год</a>
            <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/media/video/video-2022-god" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Видео 2022 год</a>
            <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/video-2023-god" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Видео 2023 год</a>
            <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/video-2024-god" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Видео 2024 год</a>
            <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/media/video" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Видео 2025 год</a>
          </div>
        </header>



        <?php
        $args = array(
          'category__in' => 233,
          'post_type' => 'post',
          'posts_per_page' => 18,
          'orderby' => 'date',
          'order' => 'DESC',
          'paged' => $paged
        );
        $q = new WP_Query($args);
        ?>
        <?php if ($q->have_posts()): ?>
          <?php while ($q->have_posts()):
            $q->the_post(); ?>



     <div class="col-lg-4 mb-4 d-flex align-items-stretch">
              <div class="card border-white">
                <a class="card-img-top" href="<?php the_permalink(); ?>">
                  <?php echo kama_thumb_img('w=375&h=200&class=sobyt_sob&crop=center/center'); ?>
                </a>
                <div class="td-post-category kitbott">
                  <a style="white-space: nowrap; text-transform: uppercase; font-family: Kazimir; font-size: 12px; color: #fff; background-color: rgba(19, 65, 135, 0.8); font-weight: 400 !important; position: relative; top: -22px; left: 0; bottom: 0; padding: 3px 4px 1px;" href="<?php the_permalink(); ?>">
                    <span class="ico-svg ico-time-grey">
                      <?php the_time('j.m.Y'); ?>
                      <i class="fa fa-eye" aria-hidden="true"></i>
                      <?php if (function_exists('the_views')) {
                        the_views();
                      } ?>
                    </span>
                  </a>
                </div>

                <div class="card-body">
                  <h5 class="card-title mt-4">
                    <a href="<?php the_permalink(); ?>">
                      <?php the_title(); ?>
                    </a>
                  </h5>
                </div>

              </div>

          
            </div>

          <?php endwhile; ?>
        <?php endif; ?>
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





  <?php get_footer();
