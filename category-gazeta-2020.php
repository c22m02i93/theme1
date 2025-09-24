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

  <?php

  dynamic_sidebar("Datatim");

  ?>

  <?php
  mpcth_print_blog_archives_custom_header();
  ?>

  <div id="mpcth_main_container">
    <?php get_sidebar(); ?>
    <div class="container-fluid">
      <div class="row">
       <header id="mpcth_archive_header">
          <?php mpcth_breadcrumbs(); ?>
          <h1 id="mpcth_archive_title" class="mpcth-deco-header" style="font-family: Kazimir; font-size: 1.65em;">
            <?php echo single_cat_title('', false); ?>
          </h1>
          <div class="bat_kit">
            <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/gazeta-2020" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Газета «Православный Симбирск» 2020 год</a>
            <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/gazeta-2021" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Газета «Православный Симбирск» 2021 год</a>
            <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/media/gazeta-pravoslavnyj-simbirsk/gazeta-2022" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Газета «Православный Симбирск» 2022 год</a>
            <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/gazeta-pravoslavnyj-simbirsk-2023-god" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Газета «Православный Симбирск» 2023 год</a>
            <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/gazeta-pravoslavnyj-simbirsk-2024" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Газета «Православный Симбирск» 2024 год</a>
            <a class="bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/media/gazeta-pravoslavnyj-simbirsk" role="bat_akt_col" style="background: rgba(19, 65, 135, 0.8);">Газета «Православный Симбирск» 2025 год</a>
          </div>
        </header>
        <?php
        $query = new WP_Query([
          'category__in' => 94,
          'post_type' => 'post',
          'posts_per_page' => 8,
          'orderby' => 'date',
          'order' => 'DESC',
          'paged' => $paged
        ]);

        // Обрабатываем полученные в запросе продукты, если они есть
        if ($query->have_posts()) {

          while ($query->have_posts()) {
            $query->the_post(); ?>
            <div class="container">
              <div class="row">
                <div class="col-md-12 p-3">
                  <div class="row ">
                    <div class="col-md-4">
                      <img>
                      <a class="w-100" href="<?php the_permalink() ?>">
                        <?php the_post_thumbnail('post-thumbnail', array('class' => 'my-image-sobytiy')); ?>
                      </a>
                      </img>
                    </div>
                    <div class="col-md-8">
                      <h4> <a href="<?php the_permalink(); ?>">
                          <?php the_title(); ?>
                        </a> </h4>
                      <p>
                        <?php do_excerpt(get_the_excerpt(), 100); ?>
                      </p>
                      <div class="kittim">
                        <?php the_time('j.m.Y'); ?>
                        <i class="fa fa-eye" aria-hidden="true"></i>
                        <?php if (function_exists('the_views')) {
                          the_views();
                        } ?>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="drimlin"></div>
              </div>
            </div>

          <?php } ?>
          <?php wp_reset_postdata();
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

  <?php get_footer();