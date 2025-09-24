<?php
/**
 * The Gallery Archive base for MPC Themes
 *
 * Template Name: Галерея
 *
 * Displays the gallery archive page.
 *
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
                    <?php mpcth_breadcrumbs(); ?>
                    <h1 id="mpcth_archive_title" class="mpcth-deco-header"><?php echo single_cat_title('', false); ?></h1>
                </header>

                <!-- Вывод категорий галерей (таксономии) сверху -->
                <?php
                $gallery_categories = get_terms(array(
                    'taxonomy' => 'gallery_category', // Измените на название вашей таксономии галерей
                    'hide_empty' => false, // Показываем все категории, даже если они пусты
                ));
                if (!empty($gallery_categories)) : ?>
                    <div class="gallery-categories">
                        <ul>
                            <?php foreach ($gallery_categories as $category) : ?>
                                <li><a href="<?php echo get_term_link($category); ?>"><?php echo $category->name; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php
                $args = array(
                    'post_type' => 'gallery', // Замените на тип записи галереи, если он отличается от 'gallery'
                    'posts_per_page' => 12,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'paged' => max(1, get_query_var('page')), // Используем параметр 'paged' для пагинации
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
