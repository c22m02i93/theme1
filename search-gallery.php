<?php
// Добавляем функцию для обработки AJAX-запроса на сервере
function gallery_date_search() {
    // Проверяем, получен ли параметр date в запросе
    if (isset($_POST['date'])) {
        $selected_date = sanitize_text_field($_POST['date']);

        // Создаем аргументы для запроса к базе данных
        $args = array(
            'post_type' => 'gallery',
            'posts_per_page' => 12,
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => array(
                array(
                    'key' => 'date_field_name', // Замените на название вашего метаполя с датой
                    'value' => $selected_date,
                    'compare' => '=',
                ),
            ),
        );

        // Получаем результаты поиска
        $q = new WP_Query($args);

        // Выводим результаты поиска
        if ($q->have_posts()) {
            while ($q->have_posts()) {
                $q->the_post();
                 <div class="col-lg-4 mb-4 d-flex align-items-stretch">
                                <div class="card">
                                    <a class="card-img-top" href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail(); ?>
                                    </a>
                                    <div class="card-body">
                                        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                       <p class="card-text"><?= get_the_date(); ?><i class="fa fa-eye" aria-hidden="true"></i> <?php if(function_exists('the_views')) { the_views(); } ?></p>
                                    </div>
                                </div>
                            </div>
            }
        } else {
            echo '<p>Записей галереи не найдено.</p>';
        }

        wp_reset_postdata();
    }

    // Останавливаем выполнение скрипта
    wp_die();
}
add_action('wp_ajax_gallery_date_search', 'gallery_date_search');
add_action('wp_ajax_nopriv_gallery_date_search', 'gallery_date_search');
