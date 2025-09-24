<?php
/**
 * Template Name: Галерея (Категория)
 * The Gallery Category Archive base for MPC Themes
 * Displays the gallery category archive page.
 */

get_header();
?>

<div id="mpcth_main">
    <?php
    mpcth_print_blog_archives_custom_header();
    ?>

    <div id="mpcth_main_container">
        <?php get_sidebar(); ?>
        <div class="container-fluid">
            <div class="row">
                <header id="mpcth_archive_header">
                    <h1 id="mpcth_archive_title" class="mpcth-deco-header"style="
    font-family: 'Kazimir',sans-serif;
    /* font-weight: 600; */
    font-size: 25px;
    /* line-height: 25px; */
    letter-spacing: .03em;
    color: #134187E6;
"><?php single_cat_title(); ?></h1>
                </header>

                <!-- Ваш код вывода категорий галерей сверху -->

                <?php
                $args = array(
                    'post_type' => 'gallery', // Замените на тип записи галереи, если он отличается от 'gallery'
                    'posts_per_page' => 12,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'paged' => max(1, get_query_var('page')), // Используем параметр 'paged' для пагинации
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'gallery_category', // Замените на название вашей таксономии галерей
                            'field' => 'slug',
                            'terms' => get_queried_object()->slug, // Получаем текущую категорию таксономии
                        ),
                    ),
                );
                $q = new WP_Query($args);

                ?>
                <?php if ($q->have_posts()) : ?>
                    <?php $counter = 0; ?>
                    <?php while ($q->have_posts()) : $q->the_post(); ?>
                        <?php if ($counter % 3 === 0) : ?>
                            <div class="w-100"></div> <!-- Добавляем блок для переноса на следующую строку после каждых трех карточек -->
                        <?php endif; ?>
                        <div class="col-lg-4 mb-4 d-flex align-items-stretch">
                            <div class="card">
                                <a class="card-img-top" href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail(); ?>
                                </a>
                                <div class="card-body">
                                    <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                    <p class="card-text"><?= get_the_date(); ?><i class="fa fa-eye" aria-hidden="true"></i> <?php if (function_exists('the_views')) { the_views(); } ?></p>
                                </div>
                            </div>
                        </div>
                        <?php $counter++; ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <p>Записей галереи не найдено.</p>
                <?php endif; ?>

                <div id="mpcth_pagination">
                    <?php
                    $total_pages = $q->max_num_pages; // Получаем общее количество страниц для пагинации
                    if ($total_pages > 1) {
                        $current_page = max(1, get_query_var('page')); // Получаем текущую страницу
                        echo paginate_links(array(
                            'base' => get_pagenum_link(1) . '%_%',
                            'format' => '?page=%#%',
                            'current' => $current_page,
                            'total' => $total_pages,
                            'prev_text' => __('&laquo; '),
                            'next_text' => __('Next &raquo;'),
                        ));
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
