<?php
/**
 * The Page base for MPC Themes
 * Template Name: Расписание
 * Displays single page.
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */

get_header();

global $page_id;
global $paged;

// Скрыть заголовок страницы, если нужно
$hide_title = get_field('mpc_hide_title');

if (function_exists('is_account_page') && is_account_page()) {
    $url = $_SERVER['REQUEST_URI'];
    if (strpos($url, 'edit-address') !== false) {
        $hide_title = true;
    }
}
?>

<div id="mpcth_main">
    <div id="mpcth_main_container">
        <?php get_sidebar(); ?>
        <div id="mpcth_content_wrap">
            <div id="mpcth_content">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="page-<?php the_ID(); ?>" <?php post_class('mpcth-page'); ?>>
                            <?php if (!$hide_title) : ?>
                                <header class="mpcth-page-header">
                                    <?php
                                    // (Если у вас не используется WooCommerce, этот блок можно удалить)
                                    if (function_exists('is_checkout') && is_checkout()) {
                                        $order_url = $_SERVER['REQUEST_URI'];
                                        $order_received = strpos($order_url, '/order-received');
                                        ?>
                                        <div class="mpcth-order-path">
                                            <span><?php _e('Shopping Cart', 'mpcth'); ?></span>
                                            <i class="fa fa-angle-right"></i>
                                            <span <?php echo !$order_received ? 'class="mpcth-color-main-color"' : ''; ?>>
                                                <?php _e('Checkout Details', 'mpcth'); ?>
                                            </span>
                                            <i class="fa fa-angle-right"></i>
                                            <span <?php echo $order_received ? 'class="mpcth-color-main-color"' : ''; ?>>
                                                <?php _e('Order Complete', 'mpcth'); ?>
                                            </span>
                                        </div>
                                    <?php }
                                    ?>
                                    <?php mpcth_breadcrumbs(); ?>
                                    <h2 class="mpcth-page-title mpcth-deco-header">
                                        <h1 id="mpcth_archive_title" class="mpcth-deco-header" style="font-family: Kazimir; font-size: 1.65em;">
                                            <?php the_title(); ?>
                                        </h1>
                                    </h2>
                                </header>
                            <?php endif; ?>

                            <section class="raspusanie_osnov mpcth-page-content">
                                <!-- Кнопка "Добавить богослужение" для залогиненных -->
                                <?php if (is_user_logged_in()) : ?>
                                    <div class="worship-button">
                                        <a href="<?php echo admin_url('post-new.php?post_type=book'); ?>" class="worship-link">Добавить богослужение</a>
                                    </div>
                                <?php endif; ?>

                                <?php
                                // Запрашиваем посты типа "book"
                                $query = new WP_Query(array(
                                    'post_type'      => 'book',
                                    'posts_per_page' => 90,
                                    'paged'          => get_query_var('page'),
                                    'relation'       => 'OR',
                                    'meta_key'       => 'vremya',
                                    'orderby'        => 'meta_value',
                                    'order'          => 'ASC'
                                ));

                                if ($query->have_posts()) {
                                    // Массив коротких названий дней недели
                                    $days_map_short = array(
                                        'Monday'    => 'пн.',
                                        'Tuesday'   => 'вт.',
                                        'Wednesday' => 'ср.',
                                        'Thursday'  => 'чт.',
                                        'Friday'    => 'пт.',
                                        'Saturday'  => 'сб.',
                                        'Sunday'    => 'вс.',
                                    );

                                    while ($query->have_posts()) {
                                        $query->the_post();

                                        // Получаем поле "vremya" (в формате "d.m H:i" без года)
                                        $vremya_raw = get_field('vremya'); // например, "24.02 05:00"
                                        $czvet      = get_field('czvet_teksta'); // цвет текста

                                        // Инициализируем переменные для вывода
                                        $date_part = '';
                                        $time_part = '';
                                        $day_short = ''; // пн., вт., ср., ...

                                        if (!empty($vremya_raw)) {
                                            // Добавляем текущий год, чтобы получить корректный день недели
                                            // (Если нужно другое поведение — подставьте нужный год вручную)
                                            $current_year = date('Y');
                                            // Превращаем "24.02 05:00" в "24.02 05:00 2025" (примерно)
                                            $vremya_with_year = $vremya_raw . ' ' . $current_year;

                                            // Создаём объект DateTime с форматом "дд.мм чч:мм ГГГГ"
                                           $vremya_obj = DateTime::createFromFormat('Y.m.d H:i', $vremya_raw);
                                            if ($vremya_obj) {
                                                // Пример: $vremya_obj->format('d.m') => "24.02"
                                                $date_part = $vremya_obj->format('d.m');
                                                $time_part = $vremya_obj->format('H:i');

                                                // Определяем день недели на английском (Monday, Tuesday и т.д.)
                                                $eng_day = $vremya_obj->format('l');
                                                // Переводим на короткий русский вариант
                                                if (isset($days_map_short[$eng_day])) {
                                                    $day_short = $days_map_short[$eng_day]; // например "сб."
                                                }
                                            }
                                        }
                                        ?>

                                        <!-- Вывод: "24.02. сб. Название поста" -->
                                        <h6 class="entry-title default-max-width">
                                            <div style="color: <?php echo esc_attr($czvet); ?>; font-weight: bold;">
                                                <?php
                                                // Добавим точку после даты для визуального соответствия: "24.02."
                                                if ($date_part) {
                                                    echo esc_html($date_part) . '. ';
                                                }
                                                // Затем день недели (например, "сб.") и пробел
                                                echo esc_html($day_short) . ' ';

                                                // В конце — заголовок поста
                                                the_title();
                                                ?>
                                            </div>
                                        </h6>

                                        <!-- Вторая строка: "05:00 Описание. Место" -->
                                        <div class="entry-content" style="margin-bottom: 20px;">
                                            <div style="color: <?php echo esc_attr($czvet); ?>;">
                                                <?php
                                                if ($time_part) {
                                                    echo esc_html($time_part) . ' ';
                                                }
                                                // Поле opisanie (что за служба)
                                                the_field('opisanie');
                                                echo '. ';
                                                // Поле mesto_provedeniya
                                                the_field('mesto_provedeniya');
                                                ?>
                                            </div>
                                        </div>

                                        <!-- Если пользователь авторизован, показываем кнопки "Редактировать" и "Удалить" -->
                                        <?php if (is_user_logged_in()) : ?>
                                            <div class="entry-actions" style="margin-bottom: 30px;">
                                                <a href="<?php echo get_edit_post_link(); ?>" class="edit-post-link">Редактировать</a>
                                                <a href="<?php echo get_delete_post_link(); ?>"
                                                   class="delete-post-link"
                                                   data-confirm="Вы уверены, что хотите удалить запись?">
                                                   Удалить
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <?php
                                    } // конец while по постам

                                    // Пагинация
                                    echo paginate_links(array(
                                        'base'    => user_trailingslashit(wp_normalize_path(get_permalink() . '/%#%/')),
                                        'current' => max(1, get_query_var('page')),
                                        'total'   => $query->max_num_pages,
                                    ));

                                    wp_reset_postdata();
                                }
                                ?>
                            </section>
                        </article>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div><!-- end #mpcth_content -->
        </div><!-- end #mpcth_content_wrap -->
    </div><!-- end #mpcth_main_container -->
</div><!-- end #mpcth_main -->

<script>
    // Скрипт подтверждения удаления записи
    document.addEventListener('DOMContentLoaded', function () {
        var deleteLinks = document.querySelectorAll('.delete-post-link');

        deleteLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                var confirmMessage = link.getAttribute('data-confirm');
                if (!confirm(confirmMessage)) {
                    event.preventDefault();
                    return false;
                }
            });
        });
    });
</script>

<?php get_footer(); ?>
