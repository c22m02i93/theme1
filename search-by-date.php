<?php
/*
Template Name: Поиск по датам
*/

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <h1>Поиск по датам</h1>

        <form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <label for="start_date">Начальная дата:</label>
            <input type="date" id="start_date" name="start_date">

            <label for="end_date">Конечная дата:</label>
            <input type="date" id="end_date" name="end_date">

            <input type="hidden" name="post_type" value="post">
            <input type="submit" value="Поиск">
        </form>

        <?php
        // Проверяем, были ли переданы параметры для поиска по датам
        if ( isset( $_GET['start_date'] ) && isset( $_GET['end_date'] ) ) {
            $start_date = sanitize_text_field( $_GET['start_date'] );
            $end_date = sanitize_text_field( $_GET['end_date'] );

            // Формируем аргументы для запроса WP_Query
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => -1, // Показать все посты
                'date_query'     => array(
                    'after'     => $start_date,
                    'before'    => $end_date,
                    'inclusive' => true, // Включая начальную и конечную даты
                ),
            );

            // Выполняем запрос и выводим результаты
            $query = new WP_Query( $args );

            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    // Выводим заголовок поста или что-то еще
                    the_title();
                }
            } else {
                echo 'По вашему запросу ничего не найдено.';
            }

            wp_reset_postdata();
        }
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
