<?php

/**
 * Template Name: Главная страница
 * The Page base for MPC Themes
 *
 * Displays single page.
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */
get_header();



global $page_id;
global $paged;
add_action('wp_enqueue_scripts', 'true_stili_frontend', 25);

function true_stili_frontend()
{
  wp_enqueue_style('true_stili', get_stylesheet_directory_uri() . '/css/style.css');
}

$header_content = get_field('mpc_header_content');
$hide_title = get_field('mpc_hide_title');
?>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.css">
<!-- Подключаем стили вашего слайдера -->
<style>
  .your-slider-class {
    width: 100%;
    margin: 0 auto;
  }

  .slide {
    background: #f5f9ff;
    ;
    padding: 10px;
    text-align: center;
  }

  .slide img {
    width: 100%;
    height: auto;
    border-radius: 10px;
    margin-bottom: 10px;
  }

  .slide h2 {
    font-family: kazimir;
    font-size: 16px;
    color: rgba(19, 65, 135, .9);
    line-height: 1.2;
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
  }

  .slick-prev,
  .slick-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.5);
    color: #fff;
    padding: 10px;
    z-index: 1;
    transition: opacity 0.3s;
  }

  .slick-prev:hover,
  .slick-next:hover {
    opacity: 0.8;
  }

  .slick-prev {
    left: 10px;
  }

  .slick-next {
    right: 10px;
  }
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-6" style="margin-top: 8px;">
      <?php echo do_shortcode("[carousel_slide id='73999']"); ?>
    </div>
    <div class="col-md-6" style="margin-top: 8px;">
      <div class="row" style="margin-top: 00px;">
        <div class="col-sm">
          <?php
          $args = array(
            'category__in' => 69,
            'post_type' => 'post',
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
          );
          $q = new WP_Query($args);
          ?>
          <?php if ($q->have_posts()): ?>
            <?php while ($q->have_posts()):
              $q->the_post(); ?>
              <div class="kartindfrh">
                <a href="<?php the_permalink() ?>">
                  <?php
if ( has_post_thumbnail() ) {
    // Получаем ID вложения (миниатюры)
    $attachment_id = get_post_thumbnail_id();

    // Задаем параметры
    $width = 1200;
    $height = 692;
    $crop = true; // Для обрезки по центру
    $rotate_angle = 0; // Угол поворота, если требуется

    // Получаем URL миниатюры в формате WebP
    $webp_thumbnail_url = otf_webp_get_thumbnail( $attachment_id, $width, $height, $crop, $rotate_angle );

    if ( $webp_thumbnail_url ) {
        // Выводим изображение с заданными классами
        echo '<img src="' . esc_url( $webp_thumbnail_url ) . '" class="img-verh-isp imgverhzer" alt="' . esc_attr( get_the_title() ) . '">';
    } else {
        // Если по какой-то причине WebP не сгенерирован, используем стандартную миниатюру
        the_post_thumbnail( [ $width, $height ], [ 'class' => 'img-verh-isp imgverhzer' ] );
    }
}
?>

                </a>
                </a>
              </div>

              <div class="td-post-category kitbott">
                <a href="<?php the_permalink() ?>">
                  Слово Архипастыря
                </a>
              </div>

              <div class="time-item1 time-itemk">
                <div class="actual-item-title-new01">
                  <a class="actual-item-title-new01" href="<?php the_permalink() ?>">
                    <?php the_title(); ?>
                  </a>
                </div>
              </div>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
        <div class="col-sm">
          <?php
          $args = array(
            'category__in' => 150,
            'post_type' => 'post',
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
          );
          $q = new WP_Query($args);
          ?>

          <?php if ($q->have_posts()): ?>
            <?php while ($q->have_posts()):
              $q->the_post(); ?>
              <div class="kartindfrh"> <a href="<?php the_permalink() ?>">
                  <?php
if ( has_post_thumbnail() ) {
    // Получаем ID вложения (миниатюры)
    $attachment_id = get_post_thumbnail_id();

    // Задаем параметры
    $width = 1200;
    $height = 692;
    $crop = true; // Для обрезки по центру
    $rotate_angle = 0; // Угол поворота, если требуется

    // Получаем URL миниатюры в формате WebP
    $webp_thumbnail_url = otf_webp_get_thumbnail( $attachment_id, $width, $height, $crop, $rotate_angle );

    if ( $webp_thumbnail_url ) {
        // Выводим изображение с заданными классами
        echo '<img src="' . esc_url( $webp_thumbnail_url ) . '" class="img-verh-isp imgverhzer" alt="' . esc_attr( get_the_title() ) . '">';
    } else {
        // Если по какой-то причине WebP не сгенерирован, используем стандартную миниатюру
        the_post_thumbnail( [ $width, $height ], [ 'class' => 'img-verh-isp imgverhzer' ] );
    }
}
?>

                </a> </div>
              <div class="td-post-category kitbott">
                <a href="<?php the_permalink() ?>">
                  Слово Патриарха
                </a>
              </div>


              <div class="time-item1 time-itemk">
                <div class="actual-item-title-new01">
                  <a class="actual-item-title-new01" href="<?php the_permalink() ?>">
                    <?php the_title(); ?>
                  </a>
                </div>
              </div>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="row" style="margin-top: 10px;">
        <div class="col-sm">
          <?php
          $args = array(
            'category__in' => 72,
            'post_type' => 'post',
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
          );
          $q = new WP_Query($args);
          ?>

          <?php if ($q->have_posts()): ?>
            <?php while ($q->have_posts()):
              $q->the_post(); ?>
              <div class="kartindfrh"> <a div class="kartindfrh" href="<?php the_permalink() ?>">
                  <?php
if ( has_post_thumbnail() ) {
    // Получаем ID вложения (миниатюры)
    $attachment_id = get_post_thumbnail_id();

    // Задаем параметры
    $width = 300;
    $height = 173;
    $crop = true; // Для обрезки по центру
    $rotate_angle = 0; // Угол поворота, если требуется

    // Получаем URL миниатюры в формате WebP
    $webp_thumbnail_url = otf_webp_get_thumbnail( $attachment_id, $width, $height, $crop, $rotate_angle );

    if ( $webp_thumbnail_url ) {
        // Выводим изображение с заданными классами
        echo '<img src="' . esc_url( $webp_thumbnail_url ) . '" class="img-verh-isp imgverhzer" alt="' . esc_attr( get_the_title() ) . '">';
    } else {
        // Если по какой-то причине WebP не сгенерирован, используем стандартную миниатюру
        the_post_thumbnail( [ $width, $height ], [ 'class' => 'img-verh-isp imgverhzer' ] );
    }
}
?>

                </a> </div>
              <div class="td-post-category kitbott">
                <a href="<?php the_permalink() ?>">
                  Газета «Православный Симбирск»
                </a>
              </div>

              <div class="time-item1 time-itemk">
                <div class="actual-item-title-new01">
                  <a class="actual-item-title-new01" href="<?php the_permalink() ?>">
                    <?php the_title(); ?>
                  </a>
                </div>
              </div>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
        <div class="col">
          <?php
          $args = array(
            'category__in' => 16,
            'post_type' => 'post',
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
          );
          $q = new WP_Query($args);
          ?>

          <?php if ($q->have_posts()): ?>
            <?php while ($q->have_posts()):
              $q->the_post(); ?>
              <div class="kartindfrh">
                <a href="<?php the_permalink() ?>">
                 <?php
if ( has_post_thumbnail() ) {
    // Получаем ID вложения (миниатюры)
    $attachment_id = get_post_thumbnail_id();

    // Задаем параметры
    $width = 1200;
    $height = 692;
    $crop = true; // Для обрезки по центру
    $rotate_angle = 0; // Угол поворота, если требуется

    // Получаем URL миниатюры в формате WebP
    $webp_thumbnail_url = otf_webp_get_thumbnail( $attachment_id, $width, $height, $crop, $rotate_angle );

    if ( $webp_thumbnail_url ) {
        // Выводим изображение с заданными классами
        echo '<img src="' . esc_url( $webp_thumbnail_url ) . '" class="img-verh-isp imgverhzer" alt="' . esc_attr( get_the_title() ) . '">';
    } else {
        // Если по какой-то причине WebP не сгенерирован, используем стандартную миниатюру
        the_post_thumbnail( [ $width, $height ], [ 'class' => 'img-verh-isp imgverhzer' ] );
    }
}
?>

                </a>
                </a>
              </div>

              <div class="td-post-category kitbott">
                <a href="<?php the_permalink() ?>">
                  Видео
                </a>
              </div>

              <div class="time-item1 time-itemk">
                <div class="actual-item-title-new01">
                  <a class="actual-item-title-new01" href="<?php the_permalink() ?>">
                    <?php the_title(); ?>
                  </a>
                </div>
              </div>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="container-fluid" style="margin-top: 8px;">
  <div class="row">
    <div class="col-md-3 order-1 order-md-0">
      <div class="kittitle kittitle-block"><span><a
            href="https://mitropolia-simbirsk.ru/category/hronika">Хроника</a></span></div>
      <?php
      $args = array(
        'category__in' => 154,
        'post_type' => 'post',
        'posts_per_page' => 11,
        'orderby' => 'date',
        'order' => 'DESC',
      );
      $q = new WP_Query($args);
      ?>

      <?php if ($q->have_posts()): ?>
        <?php while ($q->have_posts()):
          $q->the_post(); ?>
          <div class="time-item">

            <div class="actual-item-title"><a class="actual-item-title" href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
              </a>
            </div>
            <div class="kittim">
  <?php the_time('j.m.Y'); ?>
  <i class="fa fa-eye" aria-hidden="true"></i>
  <?php if (function_exists('the_views')) {
    the_views();
  } ?>

  <?php
  $post_content = get_post_field('post_content', get_the_ID());
  preg_match('/\[gallery.*ids=.(.*).\]/', $post_content, $ids);
  if ($ids) {
    $ids_array = explode(",", $ids[1]);
    if (count($ids_array) > 3) {
      echo '<i class="fa fa-camera" aria-hidden="true"></i> ' . count($ids_array);
    }
  }
  ?>
</div>

            <div class="kill_line">

            </div>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>
    </div>
    <div class="col-md-5">
      <div class="kittitle kittitle-block"><span> <a href="https://mitropolia-simbirsk.ru/category/media/novosti">
            События </a></span></div>
      <?php
      $args = array(
        'category__in' => 4,
        'post_type' => 'post',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
      );
      $q = new WP_Query($args);
      ?>

      <?php if ($q->have_posts()): ?>
        <?php while ($q->have_posts()):
          $q->the_post(); ?>
          <div class="kitimg"> <a class="kitimg" href="<?php the_permalink() ?>">
            <?php
if ( has_post_thumbnail() ) {
    // Получаем ID вложения (миниатюры)
    $attachment_id = get_post_thumbnail_id();
    
    // Задаём исходные параметры из строки 'w=348&h=232&class=sobyt_sob&crop=center/center'
    $original_width = 174; // Желаемая ширина отображения
    $original_height = 116; // Желаемая высота отображения
    $crop = true; // Для обрезки по центру
    $rotate_angle = 0; // Угол поворота, если требуется
    
    // Увеличиваем размеры в 2 раза для улучшения качества (4-кратное увеличение пикселей)
    $generated_width = $original_width * 2; // 348
    $generated_height = $original_height * 2; // 232
    
    // Получаем URL миниатюры в формате WebP с увеличенными размерами
    $webp_thumbnail_url = otf_webp_get_thumbnail( $attachment_id, $generated_width, $generated_height, $crop, $rotate_angle );
    
    if ( $webp_thumbnail_url ) {
        // Выводим изображение с заданными классами и устанавливаем размеры отображения
        echo '<img src="' . esc_url( $webp_thumbnail_url ) . '" 
                  class="sobyt_sob" 
                  alt="' . esc_attr( get_the_title() ) . '"
                  width="' . esc_attr( $original_width ) . '" 
                  height="' . esc_attr( $original_height ) . '" 
                  style="width: ' . esc_attr( $original_width ) . 'px; height: ' . esc_attr( $original_height ) . 'px;">';
    } else {
        // Если по какой-то причине WebP не сгенерирован, используем стандартную миниатюру
        the_post_thumbnail( [ $original_width, $original_height ], [ 
            'class' => 'sobyt_sob',
            'width' => $original_width,
            'height' => $original_height,
            'style' => 'width: ' . esc_attr( $original_width ) . 'px; height: ' . esc_attr( $original_height ) . 'px;'
        ] );
    }
}
?>



            </a>
            <div class="time-item">
              <div class="actual-item-title"><a class="actual-item-title" href="<?php the_permalink(); ?>">
                  <?php the_title(); ?>
                </a></div>
              <div class="news-item-text">
                <?php do_excerpt(get_the_excerpt(), 20); ?>
              </div>
            <div class="kittim">
  <?php the_time('j.m.Y'); ?>
  <i class="fa fa-eye" aria-hidden="true"></i>
  <?php if (function_exists('the_views')) {
    the_views();
  } ?>

  <?php
  $post_content = get_post_field('post_content', get_the_ID());
  preg_match('/\[gallery.*ids=.(.*).\]/', $post_content, $ids);
  if ($ids) {
    $ids_array = explode(",", $ids[1]);
    if (count($ids_array) > 3) {
      echo '<i class="fa fa-camera" aria-hidden="true"></i> ' . count($ids_array);
    }
  }
  ?>
</div>


            </div>
            <div class="Kill_line_sob">

            </div>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>

    </div>
    <div class="col-md-4 patr">
      <div class="kittitle kittitle-block"><span> <a
            href="https://mitropolia-simbirsk.ru/category/media/arhipastyrskoe-sluzhenie"> Архипастырское служение
          </a></span></div>
      <?php
      $args = array(
        'category__in' => 57,
        'post_type' => 'post',
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => 'DESC',
      );
      $q = new WP_Query($args);
      ?>

      <?php if ($q->have_posts()): ?>
        <?php while ($q->have_posts()):
          $q->the_post(); ?>


          <div class="kitimg "> <a class="kitimg" href="<?php the_permalink() ?>">
            <?php
if ( has_post_thumbnail() ) {
    // Получаем ID вложения (миниатюры)
    $attachment_id = get_post_thumbnail_id();
    
    // Задаём исходные параметры
    $display_width = 105; // Желаемая ширина отображения (в пикселях)
    $display_height = 70; // Желаемая высота отображения (в пикселях)
    $crop = true; // Для обрезки по центру (соответствует 'crop=center/center')
    $rotate_angle = 0; // Угол поворота, если требуется

    // Увеличиваем размеры в 2 раза для улучшения качества (4-кратное увеличение пикселей)
    $generated_width = $display_width * 4; // 210 пикселей
    $generated_height = $display_height * 4; // 140 пикселей

    // Получаем URL миниатюры в формате WebP с увеличенными размерами
    $webp_thumbnail_url = otf_webp_get_thumbnail( $attachment_id, $generated_width, $generated_height, $crop, $rotate_angle );

    if ( $webp_thumbnail_url ) {
        // Выводим изображение с заданными классами и устанавливаем размеры отображения
        echo '<img src="' . esc_url( $webp_thumbnail_url ) . '" 
                  class="sobyt_arhip" 
                  alt="' . esc_attr( get_the_title() ) . '"
                  width="' . esc_attr( $display_width ) . '" 
                  height="' . esc_attr( $display_height ) . '" 
                  style="width: ' . esc_attr( $display_width ) . 'px; height: ' . esc_attr( $display_height ) . 'px; object-fit: cover;">';
    } else {
        // Если по какой-то причине WebP не сгенерирован, используем стандартную миниатюру
        the_post_thumbnail( [ $display_width, $display_height ], [ 
            'class' => 'sobyt_arhip',
            'width' => $display_width,
            'height' => $display_height,
            'style' => 'width: ' . esc_attr( $display_width ) . 'px; height: ' . esc_attr( $display_height ) . 'px; object-fit: cover;'
        ] );
    }
}
?>


            </a>
            <div class="time-item">
              <div class="actual-item-title"><a class="actual-item-title" href="<?php the_permalink(); ?>">
                  <?php the_title(); ?>
                </a></div>
              <div class="news-item-text">
                <?php do_excerpt(get_the_excerpt(), 10); ?>
              </div>
            <div class="kittim">
  <?php the_time('j.m.Y'); ?>
  <i class="fa fa-eye" aria-hidden="true"></i>
  <?php if (function_exists('the_views')) {
    the_views();
  } ?>

  <?php
  $post_content = get_post_field('post_content', get_the_ID());
  preg_match('/\[gallery.*ids=.(.*).\]/', $post_content, $ids);
  if ($ids) {
    $ids_array = explode(",", $ids[1]);
    if (count($ids_array) > 3) {
      echo '<i class="fa fa-camera" aria-hidden="true"></i> ' . count($ids_array);
    }
  }
  ?>
</div>


            </div>
            <div class="kill_line_arhip">

            </div>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>

    </div>

  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-4">
      <a href="https://mitropolia-simbirsk.ru/eparhiya/kanonicheskaya-komissiya-simbirskoj-eparhii">
        <img
          src="https://mitropolia-simbirsk.ru/wp-content/uploads/2023/11/bez-imeni-2-2023_05_12_0944-2023_11_14_0201.webp"
          width="100%" height="auto" alt="Карта Симбирской епархии" style="border-radius: 15px; margin-top: 10px;">
      </a>
    </div>
    <div class="col-md-4">
      <div class="kittitle kittitle-block"><span><a
            href="https://mitropolia-simbirsk.ru/category/mitropoliya/ukazy-i-rasporyazheniya">Указы и
            распоряжения</a></span></div>
      <?php
      $args = array(
        'category__in' => 54,
        'post_type' => 'post',
        'posts_per_page' => 3,
        'orderby' => 'date',
        'order' => 'DESC',
      );
      $q = new WP_Query($args);
      ?>

      <?php if ($q->have_posts()): ?>
        <?php while ($q->have_posts()):
          $q->the_post(); ?>
          <div class="time-item">


            <div class="actual-item-title"><a class="actual-item-title" href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
              </a>
            </div>
            <div class="kittim">
              <?php the_time('j.m.Y'); ?>
              <i class="fa fa-eye" aria-hidden="true"></i>
              <?php if (function_exists('the_views')) {
                the_views();
              } ?>

            </div>
            <div class="kill_line_ykaz">

            </div>

          </div>
        <?php endwhile; ?>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>
    </div>
	  
	  
   <div class="col-md-4">
  <div class="kittitle kittitle-block">
    <span>
      <a href="https://mitropolia-simbirsk.ru/mitropolit/raspisanie-arhierejskih-bogosluzhenij">
        Расписание богослужений
      </a>
    </span>
  </div>
  <div class="patr">
    <?php
    // Параметры выборки для ближайших двух записей (дата >= сейчас)
    $args = array(
      'post_type'      => 'book',
      'posts_per_page' => 2, // выводим по 2 записи
      'meta_key'       => 'vremya',
      'orderby'        => 'meta_value_num vremya',
      'order'          => 'ASC',
      'meta_type'      => 'DATETIME',
      'meta_query'     => array(
        array(
          'key'     => 'vremya',
          'value'   => date('Y.m.d H:i'),
          'compare' => '>=',
          'type'    => 'DATETIME'
        )
      ),
    );

    // Запрос
    $query = new WP_Query($args);
    ?>
    <?php if ($query->have_posts()): ?>
      <!-- цикл для будущих записей -->
      <?php while ($query->have_posts()): $query->the_post(); ?>
        <?php
        // Получаем поле "vremya" (например, "24.02.2025 05:00")
        $vremya_raw = get_field('vremya');
        $czvet     = get_field('czvet_teksta'); // Цвет текста

        // Инициализируем переменные
        $date_part = '';
        $day_short = '';
        $time_part = '';

        if (!empty($vremya_raw)) {
          // Парсим поле по формату "Y.m.d H:i"
          $vremya_obj = DateTime::createFromFormat('Y.m.d H:i', $vremya_raw);
          if ($vremya_obj) {
            // Вывод даты: формат "d.m." (например, "01.03.")
            $date_part = $vremya_obj->format('d.m') . '.';
            // Вывод времени: формат "H:i"
            $time_part = $vremya_obj->format('H:i');
            // Получаем день недели на английском и преобразуем в короткий формат
            $eng_day = $vremya_obj->format('l');
            $days_map_short = array(
              'Monday'    => 'пн.',
              'Tuesday'   => 'вт.',
              'Wednesday' => 'ср.',
              'Thursday'  => 'чт.',
              'Friday'    => 'пт.',
              'Saturday'  => 'сб.',
              'Sunday'    => 'вс.'
            );
            if (isset($days_map_short[$eng_day])) {
              $day_short = $days_map_short[$eng_day];
            }
          }
        }
        ?>
        <!-- Первая строка: "01.03. сб. Заголовок" -->
        <h6 class="raspisanie_zag">
          <p>
            <div style="color:<?php echo esc_attr($czvet); ?>">
              <?php
              echo esc_html($date_part) . ' ' . esc_html($day_short) . ' ';
              the_title();
              ?>
            </div>
          </p>
        </h6>

        <!-- Вторая строка: "05:00 Описание. Место" -->
        <div class="raspisanie">
          <p>
            <div style="color:<?php echo esc_attr($czvet); ?>">
              <?php
              echo esc_html($time_part) . ' ';
              the_field('opisanie');
              echo '. ';
              the_field('mesto_provedeniya');
              ?>
            </div>
          </p>
        </div>
      <?php endwhile;
      wp_reset_postdata();
    else:
      // Если будущих записей не найдено, выводим последние две прошедшие записи
      $args_last_two_posts = array(
        'post_type'      => 'book',
        'posts_per_page' => 2,
        'meta_key'       => 'vremya',
        'orderby'        => 'meta_value_num vremya',
        'order'          => 'DESC',
        'meta_type'      => 'DATETIME',
        'meta_query'     => array(
          array(
            'key'     => 'vremya',
            'value'   => date('Y.m.d H:i'),
            'compare' => '<=',
            'type'    => 'DATETIME'
          )
        ),
      );

      $last_two_posts_query = new WP_Query($args_last_two_posts);

      if ($last_two_posts_query->have_posts()):
        ?>
        <!-- цикл для прошедших записей -->
        <?php while ($last_two_posts_query->have_posts()): $last_two_posts_query->the_post(); ?>
          <?php
          $vremya_raw = get_field('vremya');
          $czvet     = get_field('czvet_teksta');
          $date_part = '';
          $day_short = '';
          $time_part = '';

          if (!empty($vremya_raw)) {
            $vremya_obj = DateTime::createFromFormat('Y.m.d H:i', $vremya_raw);
            if ($vremya_obj) {
              $date_part = $vremya_obj->format('d.m') . '.';
              $time_part = $vremya_obj->format('H:i');
              $eng_day   = $vremya_obj->format('l');
              $days_map_short = array(
                'Monday'    => 'пн.',
                'Tuesday'   => 'вт.',
                'Wednesday' => 'ср.',
                'Thursday'  => 'чт.',
                'Friday'    => 'пт.',
                'Saturday'  => 'сб.',
                'Sunday'    => 'вс.'
              );
              if (isset($days_map_short[$eng_day])) {
                $day_short = $days_map_short[$eng_day];
              }
            }
          }
          ?>
          <h6 class="raspisanie_zag">
            <p>
              <div style="color:<?php echo esc_attr($czvet); ?>">
                <?php
                echo esc_html($date_part) . ' ' . esc_html($day_short) . ' ';
                the_title();
                ?>
              </div>
            </p>
          </h6>
          <div class="raspisanie">
            <p>
              <div style="color:<?php echo esc_attr($czvet); ?>">
                <?php
                echo esc_html($time_part) . ' ';
                the_field('opisanie');
                echo '. ';
                the_field('mesto_provedeniya');
                ?>
              </div>
            </p>
          </div>
        <?php endwhile;
        wp_reset_postdata();
      else: ?>
        <p><?php esc_html_e('Ничего не найдено.'); ?></p>
      <?php endif;
    endif;
    ?>
  </div>
</div>





    <div class="container-fluid">
      <div class="row">
      <div class="col-md-4">
  <div class="kittitle kittitle-block">
    <span><a href="https://mitropolia-simbirsk.ru/category/media/video">Видео</a></span>
  </div>

  <div id="videoCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="10000" >
    <div class="carousel-inner">
      <?php
      $categoryIDs = array(16);
      $numberOfPosts = 8;
      $args = array(
        'category__in' => $categoryIDs,
        'posts_per_page' => $numberOfPosts,
      );
      $query = new WP_Query($args);
      $active = 'active';

      if ($query->have_posts()):
        while ($query->have_posts()): $query->the_post();

          $attachment_id = get_post_thumbnail_id();
          $width = 600;
          $height = 400;
          $crop = true;
          $rotate_angle = 0;
          $webp_thumbnail_url = otf_webp_get_thumbnail($attachment_id, $width, $height, $crop, $rotate_angle);
          $image_url = $webp_thumbnail_url ?: get_the_post_thumbnail_url(null, 'large');
          ?>
          <div class="carousel-item <?= $active ?>">
            <div class="carousel-slide-wrapper">
              <a href="<?php the_permalink(); ?>">
                <img src="<?= esc_url($image_url) ?>" class="d-block w-100"
                     style="border-radius: 10px; object-fit: cover;" alt="<?php the_title(); ?>">
              </a>
              <div class="actual-item-title mt-2">
                <a class="actual-item-title" href="<?php the_permalink(); ?>">
                  <?= get_the_title(); ?>
                </a>
              </div>
            </div>
          </div>
          <?php $active = ''; ?>
        <?php endwhile;
        wp_reset_postdata();
      else:
        echo '<div class="carousel-item active"><p class="text-center">Нет записей</p></div>';
      endif;
      ?>
    </div>

    <!-- Кнопки навигации -->
    <button class="carousel-control-prev" type="button" data-bs-target="#videoCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Назад</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#videoCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Вперёд</span>
    </button>
  </div>
</div>
		  <script>
document.addEventListener('DOMContentLoaded', function () {
  const carousel = document.querySelector('#videoCarousel .carousel-inner');
  const slides = carousel.querySelectorAll('.carousel-item');

  let maxHeight = 0;

  slides.forEach(function (slide) {
    slide.classList.add('d-block'); // временно делаем все видимыми
    const height = slide.offsetHeight;
    if (height > maxHeight) maxHeight = height;
    slide.classList.remove('d-block');
  });

  // Применим максимальную высоту ко всем слайдам
  slides.forEach(function (slide) {
    slide.style.minHeight = maxHeight + 'px';
  });
});
</script>
        <div class="col-md-4">
          <div class="kittitle kittitle-block"> <span><a
                href="https://mitropolia-simbirsk.ru/category/media/monitoring">Публикации</a></span></div>
          <?php
          $args = array(
            'category__in' => 73,
            'post_type' => 'post',
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
          );
          $q = new WP_Query($args);
          ?>

          <?php if ($q->have_posts()): ?>
            <?php while ($q->have_posts()):
              $q->the_post(); ?>
              <div class="row">
                <div class="col-md-12">
                  <a href="<?php the_permalink() ?>">
                   <?php
if ( has_post_thumbnail() ) {
    // Получаем ID вложения (миниатюры)
    $attachment_id = get_post_thumbnail_id();

    // Задаем параметры
    $width = 300;
    $height = 173;
    $crop = true; // Для обрезки по центру
    $rotate_angle = 0; // Угол поворота, если требуется

    // Получаем URL миниатюры в формате WebP
    $webp_thumbnail_url = otf_webp_get_thumbnail( $attachment_id, $width, $height, $crop, $rotate_angle );

    if ( $webp_thumbnail_url ) {
        // Выводим изображение с заданными классами
        echo '<img src="' . esc_url( $webp_thumbnail_url ) . '" class="img-verh-isp imgverhzer" alt="' . esc_attr( get_the_title() ) . '">';
    } else {
        // Если по какой-то причине WebP не сгенерирован, используем стандартную миниатюру
        the_post_thumbnail( [ $width, $height ], [ 'class' => 'img-verh-isp imgverhzer' ] );
    }
}
?>

                  </a>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="time-item">
                    <div class="actual-item-title">
                      <a class="actual-item-title" href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                      </a>
                    </div>
                  </div>
                  <div class="kittim">
                    <?php the_time('j.m.Y'); ?>

                    <i class="fa fa-eye" aria-hidden="true"></i>
                    <?php if (function_exists('the_views')) {
                      the_views();
                    } ?>
                  </div>
                <?php endwhile; ?>
              <?php endif; ?>
              <?php wp_reset_postdata(); ?>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <p><a href="https://mitropolia-simbirsk.ru/mitropoliya/karta-simbirskoj-mitropolii"><img
                src="https://mitropolia-simbirsk.ru/wp-content/uploads/2023/11/bez-imeni-2-2-2023_03_12_1507-2023_11_14_0206.webp"
                width="100%" height="auto" alt="Карта Симбирской епархии"
                style="border-radius: 15px; margin-top: 10px;"></a></p>
        </div>
      </div>
    </div>


    <div class="row">
      <div class="col-md-9">
        <div class="row">
          <div class="col-md-6">
            <?php dynamic_sidebar('new01'); ?>
          </div>
          <div class="col-md-6">
            <?php dynamic_sidebar('new02'); ?>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="pravcal">
          <?php dynamic_sidebar('new10'); ?>
          
        </div>
      </div>
    </div>

    <?php
    get_footer();
