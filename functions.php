<?php

add_action('add_attachment', function($post_ID) {
    // Получаем последнее значение мета-поля
    $last_order = get_option('last_upload_order', 0);
    $current_order = $last_order + 1;

    // Сохраняем в метаполе
    update_post_meta($post_ID, '_upload_order', $current_order);

    // Обновляем счетчик
    update_option('last_upload_order', $current_order);
});


function display_today_event_shortcode() {
    // Получаем завтрашнюю дату в формате YYYY-MM-DD
    $tomorrow = date( 'Y-m-d', strtotime( '+1 day' ) );
    
    // Формируем URL запроса к API, подставляя дату завтрашнего дня
    $url = 'https://api.patriarchia.ru/v1/events/' . $tomorrow;
    
    // Выполняем запрос к API
    $response = wp_remote_get( $url );
    if ( is_wp_error( $response ) ) {
        return 'Ошибка получения данных с API';
    }
    
    $body = wp_remote_retrieve_body( $response );
    $event = json_decode( $body, true );
    
    // Проверяем наличие данных события
    if ( ! $event || ! isset( $event['calendar_text'] ) ) {
        return 'Событие на завтра не найдено';
    }
    
    // Выводим только поле calendar_text, разрешая только безопасный HTML
    return '<div class="calendar-event">' . wp_kses_post( $event['calendar_text'] ) . '</div>';
}
add_shortcode( 'today_event', 'display_today_event_shortcode' );
/**
 * Функция-обработчик маршрута /myplugin/v1/tags
 */
function my_custom_tags_endpoint(WP_REST_Request $request) {
    // Получаем параметры из запроса (например, page, per_page)
    $page     = $request->get_param('page') ?: 1;
    $per_page = $request->get_param('per_page') ?: 10;

    // Настраиваем аргументы для get_terms()
    $args = [
        'taxonomy'   => 'post_tag',
        'hide_empty' => false,
        'offset'     => ($page - 1) * $per_page,
        'number'     => $per_page,
    ];

    // Получаем массив тегов
    $tags = get_terms($args);

    // Чтобы вернуть JSON-ответ, обычно формируем структуру данных
    $data = [];

    foreach ($tags as $tag) {
        $data[] = [
            'id'   => $tag->term_id,
            'name' => $tag->name,
            'slug' => $tag->slug,
            // при необходимости можно вернуть description, count и т.п.
        ];
    }

    // Можно добавить общее кол-во для пагинации
    $total_tags = wp_count_terms(['taxonomy' => 'post_tag', 'hide_empty' => false]);

    // Формируем ответ
    // (WP_REST_Response удобен, если нужно добавить заголовки, статус, и т.д.)
    $response = new WP_REST_Response([
        'page'       => (int) $page,
        'per_page'   => (int) $per_page,
        'total'      => (int) $total_tags,
        'total_pages' => ceil($total_tags / $per_page),
        'tags'       => $data,
    ]);

    // При желании можем выставить свой статус или хедеры
    // $response->set_status(200);

    return $response;
}

// functions.php вашей темы
function add_views_to_rest_api() {
    register_rest_field('post', 'views', array(
        'get_callback'    => function($post) {
            return get_post_meta($post['id'], 'views', true) ?: 0;
        },
        'schema'          => null,
    ));
}
add_action('rest_api_init', 'add_views_to_rest_api');

/**
 * Регистрируем меню.
 * Здесь регистрируются две локации: 'primary-menu' и 'main-menu'.
 */
function register_my_theme_menus() {
    register_nav_menus( array(
        'primary-menu' => __( 'Primary Menu', 'your-theme-textdomain' ),
        'main-menu'    => __( 'Главное меню', 'yourtheme' ),
    ) );
}
add_action( 'after_setup_theme', 'register_my_theme_menus' );

/**
 * Добавляем CORS-заголовки для разрешения запросов с локального сервера.
 */
function add_cors_http_header() {
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
}
add_action('init', 'add_cors_http_header');

/**
 * Регистрируем endpoint для получения меню через REST API
 * под namespace "mytheme/v1".
 */
function my_register_menu_rest_route() {
    register_rest_route( 'mytheme/v1', '/menu/(?P<location>[a-zA-Z0-9_-]+)', array(
        'methods'  => 'GET',
        'callback' => 'my_get_menu_by_location',
    ) );
}
add_action( 'rest_api_init', 'my_register_menu_rest_route' );

function my_get_menu_by_location( $data ) {
    $location = $data['location'];
    $locations = get_nav_menu_locations();

    if ( ! isset( $locations[ $location ] ) ) {
        return new WP_Error( 'no_menu', 'Menu not found', array( 'status' => 404 ) );
    }

    $menu = wp_get_nav_menu_object( $locations[ $location ] );
    $menu_items = wp_get_nav_menu_items( $menu->term_id );
    return rest_ensure_response( $menu_items );
}

/**
 * Дополнительный endpoint для получения меню под namespace "custom/v1".
 * Этот endpoint открыт для публичного доступа.
 */
function custom_register_menu_endpoints() {
    register_rest_route( 'custom/v1', '/menu/(?P<location>[a-zA-Z0-9_-]+)', array(
        'methods'             => 'GET',
        'callback'            => 'custom_get_menu_items',
        'permission_callback' => '__return_true',
    ));
}
add_action( 'rest_api_init', 'custom_register_menu_endpoints' );

function custom_get_menu_items( $request ) {
    $location  = $request['location'];
    $locations = get_nav_menu_locations();

    if ( ! isset( $locations[ $location ] ) ) {
        return new WP_Error( 'no_menu', 'Меню не найдено', array( 'status' => 404 ) );
    }

    $menu_object = wp_get_nav_menu_object( $locations[ $location ] );
    $menu_items  = wp_get_nav_menu_items( $menu_object->term_id );

    $items = array();
    if ( $menu_items ) {
        foreach ( $menu_items as $item ) {
            $items[] = array(
                'ID'     => $item->ID,
                'title'  => $item->title,
                'url'    => $item->url,
                'parent' => $item->menu_item_parent,
            );
        }
    }

    return rest_ensure_response( $items );
}
/* AJAX Callback для фильтрации галерей по году с пагинацией */
function filter_galleries_by_year_callback() {
    $year  = isset($_POST['year']) ? intval($_POST['year']) : 0;
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    if ( !$year ) {
        wp_send_json_error('Неверно указан год.');
        wp_die();
    }

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 16,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'paged'          => $paged,
        'date_query'     => array(
            array(
                'year' => $year,
            ),
        ),
    );

    $query = new WP_Query($args);
    ob_start();
    $unique_galleries = array();

    if ( $query->have_posts() ) {
        echo '<div class="row">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $gallery = get_post_gallery( get_the_ID(), false );
            if ( ! $gallery ) {
                continue;
            }
            $gallery_ids = isset( $gallery['ids'] ) ? $gallery['ids'] : '';
            if ( empty( $gallery_ids ) ) {
                continue;
            }
            if ( in_array( $gallery_ids, $unique_galleries ) ) {
                continue;
            }
            $unique_galleries[] = $gallery_ids;
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <a href="<?php the_permalink(); ?>">
                        <div class="kartindfrh">
                            <?php
                            if ( has_post_thumbnail() ) {
                                $attachment_id = get_post_thumbnail_id();
                                $width         = 1200;
                                $height        = 692;
                                $crop          = true;
                                $rotate_angle  = 0;
                                $webp_thumbnail_url = function_exists('otf_webp_get_thumbnail')
                                    ? otf_webp_get_thumbnail( $attachment_id, $width, $height, $crop, $rotate_angle )
                                    : false;
                                if ( $webp_thumbnail_url ) {
                                    echo '<img src="' . esc_url( $webp_thumbnail_url ) . '" class="img-verh-isp imgverhzer" alt="' . esc_attr( get_the_title() ) . '">';
                                } else {
                                    the_post_thumbnail( [ $width, $height ], [ 'class' => 'img-verh-isp imgverhzer' ] );
                                }
                            }
                            ?>
                        </div>
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h5>
                        <p class="card-text">
                            <small class="text-muted"><?php echo get_the_date(); ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<div class="row"><div class="col-12">Нет галерей для отображения.</div></div>';
    }

    $output = ob_get_clean();

    // Формирование пагинации
    $total_pages = $query->max_num_pages;
    $pagination_html = '';
    if ( $total_pages > 1 ) {
        $current_page = $paged;
        // Добавляем классы Bootstrap для горизонтального отображения
        $pagination_html = '<ul class="pagination d-flex flex-wrap">';
        for ( $i = 1; $i <= $total_pages; $i++ ) {
            $active = ($i == $current_page) ? ' active' : '';
            $pagination_html .= '<li class="page-item' . $active . '">';
            $pagination_html .= '<a href="#" class="page-link ajax-pagination" data-paged="' . $i . '" data-action="filter_galleries_by_year" data-year="' . esc_attr($year) . '">' . $i . '</a>';
            $pagination_html .= '</li>';
        }
        $pagination_html .= '</ul>';
    }

    wp_send_json_success(array(
        'content'    => $output,
        'pagination' => $pagination_html,
    ));
    wp_die();
}
add_action('wp_ajax_filter_galleries_by_year', 'filter_galleries_by_year_callback');
add_action('wp_ajax_nopriv_filter_galleries_by_year', 'filter_galleries_by_year_callback');

/* AJAX Callback для фильтрации галерей по диапазону дат с пагинацией */
function filter_galleries_by_date_range_callback() {
    $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
    $end_date   = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';
    $paged      = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    if ( !$start_date || !$end_date ) {
        wp_send_json_error('Неверно указан диапазон дат.');
        wp_die();
    }

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 16,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'paged'          => $paged,
        'date_query'     => array(
            array(
                'after'     => $start_date,
                'before'    => $end_date,
                'inclusive' => true,
            ),
        ),
    );

    $query = new WP_Query($args);
    ob_start();
    $unique_galleries = array();

    if ( $query->have_posts() ) {
        echo '<div class="row">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $gallery = get_post_gallery( get_the_ID(), false );
            if ( ! $gallery ) {
                continue;
            }
            $gallery_ids = isset( $gallery['ids'] ) ? $gallery['ids'] : '';
            if ( empty( $gallery_ids ) ) {
                continue;
            }
            if ( in_array( $gallery_ids, $unique_galleries ) ) {
                continue;
            }
            $unique_galleries[] = $gallery_ids;
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <a href="<?php the_permalink(); ?>">
                        <div class="kartindfrh">
                            <?php
                            if ( has_post_thumbnail() ) {
                                $attachment_id = get_post_thumbnail_id();
                                $width         = 1200;
                                $height        = 692;
                                $crop          = true;
                                $rotate_angle  = 0;
                                $webp_thumbnail_url = function_exists('otf_webp_get_thumbnail')
                                    ? otf_webp_get_thumbnail( $attachment_id, $width, $height, $crop, $rotate_angle )
                                    : false;
                                if ( $webp_thumbnail_url ) {
                                    echo '<img src="' . esc_url( $webp_thumbnail_url ) . '" class="img-verh-isp imgverhzer" alt="' . esc_attr( get_the_title() ) . '">';
                                } else {
                                    the_post_thumbnail( [ $width, $height ], [ 'class' => 'img-verh-isp imgverhzer' ] );
                                }
                            }
                            ?>
                        </div>
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h5>
                        <p class="card-text">
                            <small class="text-muted"><?php echo get_the_date(); ?></small>
                        </p>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<div class="row"><div class="col-12">Нет галерей для отображения.</div></div>';
    }

    $output = ob_get_clean();

    // Формирование пагинации для диапазона дат
    $total_pages = $query->max_num_pages;
    $pagination_html = '';
    if ( $total_pages > 1 ) {
        $current_page = $paged;
        // Аналогично добавляем классы Bootstrap для горизонтального отображения
        $pagination_html = '<ul class="pagination d-flex flex-wrap">';
        for ( $i = 1; $i <= $total_pages; $i++ ) {
            $active = ($i == $current_page) ? ' active' : '';
            $pagination_html .= '<li class="page-item' . $active . '">';
            $pagination_html .= '<a href="#" class="page-link ajax-pagination" data-paged="' . $i . '" data-action="filter_galleries_by_date_range" data-start_date="' . esc_attr($start_date) . '" data-end_date="' . esc_attr($end_date) . '">' . $i . '</a>';
            $pagination_html .= '</li>';
        }
        $pagination_html .= '</ul>';
    }

    wp_send_json_success(array(
        'content'    => $output,
        'pagination' => $pagination_html,
    ));
    wp_die();
}
add_action('wp_ajax_filter_galleries_by_date_range', 'filter_galleries_by_date_range_callback');
add_action('wp_ajax_nopriv_filter_galleries_by_date_range', 'filter_galleries_by_date_range_callback');



//начало духовенства 
// Подключаем Bootstrap стили и скрипты в админке только на наших страницах
function ccm_enqueue_bootstrap_admin($hook) {
    // Проверяем, находимся ли мы на страницах нашего плагина
    $pages = ['toplevel_page_ccm_custom_clergy', 'clergy_page_ccm_add_custom_clergy', 'clergy_page_ccm_edit_custom_clergy'];
    if (in_array($hook, $pages)) {
        // Подключаем Bootstrap CSS
        wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
        // Подключаем Bootstrap JS
        wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'ccm_enqueue_bootstrap_admin');

// Обновление структуры базы данных для связи "многие ко многим" между духовенством и храмами
function ccm_update_clergy_table() {
    global $wpdb;
    $clergy_table = $wpdb->prefix . 'clergy';
    $clergy_church_table = $wpdb->prefix . 'clergy_church';
    $charset_collate = $wpdb->get_charset_collate();

    // Создаем таблицу духовенства, если не существует
    if($wpdb->get_var("SHOW TABLES LIKE '{$clergy_table}'") != $clergy_table) {
        $sql = "CREATE TABLE $clergy_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name tinytext NOT NULL,
            post_url text NOT NULL,
            date_of_birth date NOT NULL,
            date_of_tonsure date NOT NULL,
            date_of_ordination date NOT NULL,
            date_of_last_award date NOT NULL,
            award_description text NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    // Создаем таблицу связей между духовенством и храмами
    if($wpdb->get_var("SHOW TABLES LIKE '{$clergy_church_table}'") != $clergy_church_table) {
        $sql = "CREATE TABLE $clergy_church_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            clergy_id mediumint(9) NOT NULL,
            church_id mediumint(9) NOT NULL,
            position tinytext NOT NULL,
            PRIMARY KEY  (id),
            KEY clergy_id (clergy_id),
            KEY church_id (church_id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
add_action('after_setup_theme', 'ccm_update_clergy_table');

// Добавление меню и подменю в админке для управления духовенством
function ccm_custom_clergy_admin_menu() {
    add_menu_page(
        esc_html__('Управление духовенством', 'your-text-domain'),
        esc_html__('Духовенство', 'your-text-domain'),
        'manage_options',
        'ccm_custom_clergy',
        'ccm_display_custom_clergy_page',
        'dashicons-admin-users',
        7
    );

    add_submenu_page(
        'ccm_custom_clergy',
        esc_html__('Добавить новое духовенство', 'your-text-domain'),
        esc_html__('Добавить духовенство', 'your-text-domain'),
        'manage_options',
        'ccm_add_custom_clergy',
        'ccm_display_add_custom_clergy_page'
    );

    // Добавляем скрытую страницу для редактирования
    add_submenu_page(
        null, // Parent slug is null
        esc_html__('Редактировать духовенство', 'your-text-domain'),
        esc_html__('Редактировать', 'your-text-domain'),
        'manage_options',
        'ccm_edit_custom_clergy',
        'ccm_display_edit_custom_clergy_page'
    );
}
add_action('admin_menu', 'ccm_custom_clergy_admin_menu');

// Функция для отображения страницы с формой добавления нового духовенства
function ccm_display_add_custom_clergy_page() {
    global $wpdb;
    $locations_table = $wpdb->prefix . 'locations';

    // Получение всех храмов из таблицы locations
    $churches = $wpdb->get_results("SELECT id, name FROM $locations_table");

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__('Добавить новое духовенство', 'your-text-domain') . '</h1>';
    echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" class="bootstrap-wrapper">';
    echo '<input type="hidden" name="action" value="ccm_add_custom_clergy">';
    wp_nonce_field('ccm_add_custom_clergy_nonce');

    // Начало формы с Bootstrap классами
    echo '<div class="form-group">';
    echo '<label for="name">' . esc_html__('Имя', 'your-text-domain') . '</label>';
    echo '<input name="name" id="name" type="text" class="form-control">';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="post_url">' . esc_html__('Ссылка на запись', 'your-text-domain') . '</label>';
    echo '<input name="post_url" id="post_url" type="text" class="form-control">';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="date_of_birth">' . esc_html__('Дата рождения', 'your-text-domain') . '</label>';
    echo '<input name="date_of_birth" id="date_of_birth" type="date" class="form-control">';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="date_of_tonsure">' . esc_html__('Дата пострига', 'your-text-domain') . '</label>';
    echo '<input name="date_of_tonsure" id="date_of_tonsure" type="date" class="form-control">';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="date_of_ordination">' . esc_html__('Дата хиротонии', 'your-text-domain') . '</label>';
    echo '<input name="date_of_ordination" id="date_of_ordination" type="date" class="form-control">';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="date_of_last_award">' . esc_html__('Дата последней награды', 'your-text-domain') . '</label>';
    echo '<input name="date_of_last_award" id="date_of_last_award" type="date" class="form-control">';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="award_description">' . esc_html__('Описание награды', 'your-text-domain') . '</label>';
    echo '<textarea name="award_description" id="award_description" class="form-control"></textarea>';
    echo '</div>';

    // Возможность добавить несколько храмов и должностей
    echo '<h3>' . esc_html__('Прикрепить храмы и должности', 'your-text-domain') . '</h3>';
    echo '<div id="church-positions">';
    echo '<div class="church-position">';
    echo '<div class="form-group">';
    echo '<label>' . esc_html__('Храм', 'your-text-domain') . '</label>';
    echo '<select name="church_id[]" class="form-control">';
    echo '<option value="">' . esc_html__('Выберите храм', 'your-text-domain') . '</option>';
    foreach ($churches as $church) {
        echo '<option value="' . esc_attr($church->id) . '">' . esc_html($church->name) . '</option>';
    }
    echo '</select>';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label>' . esc_html__('Должность', 'your-text-domain') . '</label>';
    echo '<select name="position[]" class="form-control">';
    echo '<option value="Настоятель">' . esc_html__('Настоятель', 'your-text-domain') . '</option>';
    echo '<option value="Ключарь">' . esc_html__('Ключарь', 'your-text-domain') . '</option>';
    echo '<option value="Штатный священник">' . esc_html__('Штатный священник', 'your-text-domain') . '</option>';
    echo '<option value="Штатный диакон">' . esc_html__('Штатный диакон', 'your-text-domain') . '</option>';
    echo '</select>';
    echo '</div>';
    echo '</div>'; // Закрываем .church-position
    echo '</div>'; // Закрываем #church-positions

    echo '<button type="button" id="add-church-position" class="btn btn-secondary">' . esc_html__('Добавить еще храм', 'your-text-domain') . '</button>';

    echo '<p class="submit"><input type="submit" name="submit" id="submit" class="btn btn-primary" value="' . esc_attr__('Добавить духовенство', 'your-text-domain') . '"></p>';
    echo '</form>';
    echo '</div>';

    // Добавляем скрипт для динамического добавления полей
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('#add-church-position').on('click', function() {
            var churchPosition = $('.church-position:first').clone();
            churchPosition.find('select').val('');
            $('#church-positions').append(churchPosition);
        });
    });
    </script>
    <?php
}

// Обработка добавления нового духовенства
function ccm_handle_add_custom_clergy() {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'ccm_add_custom_clergy_nonce')) {
        wp_die(esc_html__('Ошибка проверки безопасности', 'your-text-domain'));
    }

    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Недостаточно прав', 'your-text-domain'));
    }

    if (isset($_POST['name'], $_POST['post_url'], $_POST['date_of_birth'], $_POST['date_of_tonsure'], $_POST['date_of_ordination'], $_POST['date_of_last_award'], $_POST['award_description'])) {
        global $wpdb;
        $clergy_table = $wpdb->prefix . 'clergy';
        $clergy_church_table = $wpdb->prefix . 'clergy_church';

        $name = sanitize_text_field($_POST['name']);
        $post_url = esc_url_raw($_POST['post_url']);
        $date_of_birth = sanitize_text_field($_POST['date_of_birth']);
        $date_of_tonsure = sanitize_text_field($_POST['date_of_tonsure']);
        $date_of_ordination = sanitize_text_field($_POST['date_of_ordination']);
        $date_of_last_award = sanitize_text_field($_POST['date_of_last_award']);
        $award_description = sanitize_textarea_field($_POST['award_description']);

        // Вставляем данные в таблицу clergy
        $wpdb->insert(
            $clergy_table,
            array(
                'name' => $name,
                'post_url' => $post_url,
                'date_of_birth' => $date_of_birth,
                'date_of_tonsure' => $date_of_tonsure,
                'date_of_ordination' => $date_of_ordination,
                'date_of_last_award' => $date_of_last_award,
                'award_description' => $award_description,
            )
        );

        $clergy_id = $wpdb->insert_id;

        // Обрабатываем храмы и должности
        if (isset($_POST['church_id']) && is_array($_POST['church_id'])) {
            $positions = $_POST['position'];
            foreach ($_POST['church_id'] as $key => $church_id) {
                $church_id = intval($church_id);
                $position = sanitize_text_field($positions[$key]);

                if ($church_id && $position) {
                    $wpdb->insert(
                        $clergy_church_table,
                        array(
                            'clergy_id' => $clergy_id,
                            'church_id' => $church_id,
                            'position' => $position,
                        )
                    );
                }
            }
        }

        wp_redirect(admin_url('admin.php?page=ccm_custom_clergy'));
        exit;
    }
}
add_action('admin_post_ccm_add_custom_clergy', 'ccm_handle_add_custom_clergy');

// Отображение всего духовенства и формы для их редактирования
function ccm_display_custom_clergy_page() {
    global $wpdb;
    $clergy_table = $wpdb->prefix . 'clergy';
    $clergy_church_table = $wpdb->prefix . 'clergy_church';
    $locations_table = $wpdb->prefix . 'locations';

    // Обработка поиска
    $search_query = '';
    if (isset($_GET['s'])) {
        $search_query = sanitize_text_field($_GET['s']);
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $clergy_table WHERE name LIKE %s", '%' . $wpdb->esc_like($search_query) . '%'));
    } else {
        $results = $wpdb->get_results("SELECT * FROM $clergy_table");
    }

    // Получаем список храмов для выбора
    $churches = $wpdb->get_results("SELECT id, name FROM $locations_table");

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__('Все духовенство', 'your-text-domain') . '</h1>';

    // Форма поиска
    echo '<form method="get" action="">';
    echo '<input type="hidden" name="page" value="ccm_custom_clergy">';
    echo '<p class="search-box">';
    echo '<label class="screen-reader-text" for="search-input">' . esc_html__('Поиск духовенства:', 'your-text-domain') . '</label>';
    echo '<input type="search" id="search-input" name="s" value="' . esc_attr($search_query) . '">';
    echo '<input type="submit" id="search-submit" class="button" value="' . esc_attr__('Поиск', 'your-text-domain') . '">';
    echo '</p>';
    echo '</form>';

    echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" class="bootstrap-wrapper">';
    echo '<input type="hidden" name="action" value="ccm_bulk_edit_custom_clergy">';
    wp_nonce_field('ccm_bulk_edit_custom_clergy_nonce');

    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered">';
    echo '<thead class="thead-dark"><tr>
        <th>' . esc_html__('ID', 'your-text-domain') . '</th>
        <th>' . esc_html__('Имя', 'your-text-domain') . '</th>
        <th>' . esc_html__('Храмы и должности', 'your-text-domain') . '</th>
        <th>' . esc_html__('Ссылка на запись', 'your-text-domain') . '</th>
        <th>' . esc_html__('Дата рождения', 'your-text-domain') . '</th>
        <th>' . esc_html__('Дата пострига', 'your-text-domain') . '</th>
        <th>' . esc_html__('Дата хиротонии', 'your-text-domain') . '</th>
        <th>' . esc_html__('Дата последней награды', 'your-text-domain') . '</th>
        <th>' . esc_html__('Описание награды', 'your-text-domain') . '</th>
        <th>' . esc_html__('Выбрать', 'your-text-domain') . '</th>
        <th>' . esc_html__('Действия', 'your-text-domain') . '</th>
    </tr></thead>';
    echo '<tbody>';

    foreach ($results as $row) {
        // Получаем храмы и должности для данного духовенства
        $church_positions = $wpdb->get_results($wpdb->prepare("SELECT * FROM $clergy_church_table WHERE clergy_id = %d", $row->id));

        echo '<tr>';
        echo '<td>' . esc_html($row->id) . '</td>';
        echo '<td>' . esc_html($row->name) . '</td>';

        // Отображаем храмы и должности
        echo '<td>';
        foreach ($church_positions as $index => $cp) {
            $church_name = $wpdb->get_var($wpdb->prepare("SELECT name FROM $locations_table WHERE id = %d", $cp->church_id));
            echo '<p><strong>' . esc_html($cp->position) . ':</strong> ' . esc_html($church_name) . '</p>';
        }
        echo '</td>';

        echo '<td>' . esc_html($row->post_url) . '</td>';
        echo '<td>' . esc_html($row->date_of_birth) . '</td>';
        echo '<td>' . esc_html($row->date_of_tonsure) . '</td>';
        echo '<td>' . esc_html($row->date_of_ordination) . '</td>';
        echo '<td>' . esc_html($row->date_of_last_award) . '</td>';
        echo '<td>' . esc_html($row->award_description) . '</td>';
        echo '<td><input type="checkbox" name="selected[]" value="' . esc_attr($row->id) . '"></td>';
        echo '<td><a href="' . admin_url('admin.php?page=ccm_edit_custom_clergy&id=' . $row->id) . '" class="btn btn-sm btn-primary">Редактировать</a></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>'; // Закрываем div.table-responsive

    echo '<p class="submit">';
    echo '<input type="submit" name="delete" id="delete" class="btn btn-danger" value="' . esc_attr__('Удалить выбранные', 'your-text-domain') . '" onclick="return confirm(\'' . esc_js(__('Вы уверены, что хотите удалить выбранные записи?', 'your-text-domain')) . '\');">';
    echo '</p>';
    echo '</form>';
    echo '</div>';
}

// Функция для отображения страницы редактирования духовенства
function ccm_display_edit_custom_clergy_page() {
    if (!isset($_GET['id'])) {
        wp_die(esc_html__('Не указан ID духовенства для редактирования', 'your-text-domain'));
    }

    $clergy_id = intval($_GET['id']);

    global $wpdb;
    $clergy_table = $wpdb->prefix . 'clergy';
    $clergy_church_table = $wpdb->prefix . 'clergy_church';
    $locations_table = $wpdb->prefix . 'locations';

    // Получаем данные духовенства
    $clergy = $wpdb->get_row($wpdb->prepare("SELECT * FROM $clergy_table WHERE id = %d", $clergy_id));

    if (!$clergy) {
        wp_die(esc_html__('Духовенство не найдено', 'your-text-domain'));
    }

    // Получаем храмы и должности
    $church_positions = $wpdb->get_results($wpdb->prepare("SELECT * FROM $clergy_church_table WHERE clergy_id = %d", $clergy_id));

    // Получаем список храмов для выбора
    $churches = $wpdb->get_results("SELECT id, name FROM $locations_table");

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__('Редактировать духовенство', 'your-text-domain') . '</h1>';
    echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" class="bootstrap-wrapper">';
    echo '<input type="hidden" name="action" value="ccm_update_custom_clergy">';
    echo '<input type="hidden" name="clergy_id" value="' . esc_attr($clergy_id) . '">';
    wp_nonce_field('ccm_update_custom_clergy_nonce');

    // Начало формы с Bootstrap классами
    echo '<div class="form-group">';
    echo '<label for="name">' . esc_html__('Имя', 'your-text-domain') . '</label>';
    echo '<input name="name" id="name" type="text" class="form-control" value="' . esc_attr($clergy->name) . '">';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="post_url">' . esc_html__('Ссылка на запись', 'your-text-domain') . '</label>';
    echo '<input name="post_url" id="post_url" type="text" class="form-control" value="' . esc_attr($clergy->post_url) . '">';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="date_of_birth">' . esc_html__('Дата рождения', 'your-text-domain') . '</label>';
    echo '<input name="date_of_birth" id="date_of_birth" type="date" class="form-control" value="' . esc_attr($clergy->date_of_birth) . '">';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="date_of_tonsure">' . esc_html__('Дата пострига', 'your-text-domain') . '</label>';
    echo '<input name="date_of_tonsure" id="date_of_tonsure" type="date" class="form-control" value="' . esc_attr($clergy->date_of_tonsure) . '">';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="date_of_ordination">' . esc_html__('Дата хиротонии', 'your-text-domain') . '</label>';
    echo '<input name="date_of_ordination" id="date_of_ordination" type="date" class="form-control" value="' . esc_attr($clergy->date_of_ordination) . '">';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="date_of_last_award">' . esc_html__('Дата последней награды', 'your-text-domain') . '</label>';
    echo '<input name="date_of_last_award" id="date_of_last_award" type="date" class="form-control" value="' . esc_attr($clergy->date_of_last_award) . '">';
    echo '</div>';

    echo '<div class="form-group">';
    echo '<label for="award_description">' . esc_html__('Описание награды', 'your-text-domain') . '</label>';
    echo '<textarea name="award_description" id="award_description" class="form-control">' . esc_textarea($clergy->award_description) . '</textarea>';
    echo '</div>';

    // Возможность редактировать храмы и должности
    echo '<h3>' . esc_html__('Храмы и должности', 'your-text-domain') . '</h3>';
    echo '<div id="church-positions">';
    if (!empty($church_positions)) {
        foreach ($church_positions as $cp) {
            echo '<div class="church-position">';
            echo '<div class="form-group">';
            echo '<label>' . esc_html__('Храм', 'your-text-domain') . '</label>';
            echo '<select name="church_id[]" class="form-control">';
            echo '<option value="">' . esc_html__('Выберите храм', 'your-text-domain') . '</option>';
            foreach ($churches as $church) {
                echo '<option value="' . esc_attr($church->id) . '"' . selected($cp->church_id, $church->id, false) . '>' . esc_html($church->name) . '</option>';
            }
            echo '</select>';
            echo '</div>';

            echo '<div class="form-group">';
            echo '<label>' . esc_html__('Должность', 'your-text-domain') . '</label>';
            echo '<select name="position[]" class="form-control">';
            echo '<option value="Настоятель"' . selected($cp->position, 'Настоятель', false) . '>' . esc_html__('Настоятель', 'your-text-domain') . '</option>';
            echo '<option value="Ключарь"' . selected($cp->position, 'Ключарь', false) . '>' . esc_html__('Ключарь', 'your-text-domain') . '</option>';
            echo '<option value="Штатный священник"' . selected($cp->position, 'Штатный священник', false) . '>' . esc_html__('Штатный священник', 'your-text-domain') . '</option>';
            echo '<option value="Штатный диакон"' . selected($cp->position, 'Штатный диакон', false) . '>' . esc_html__('Штатный диакон', 'your-text-domain') . '</option>';
            echo '</select>';
            echo '</div>';
            echo '<button type="button" class="btn btn-danger remove-church-position">Удалить</button>';
            echo '</div>'; // Закрываем .church-position
        }
    } else {
        // Если нет записей, показываем пустые поля
        echo '<div class="church-position">';
        echo '<div class="form-group">';
        echo '<label>' . esc_html__('Храм', 'your-text-domain') . '</label>';
        echo '<select name="church_id[]" class="form-control">';
        echo '<option value="">' . esc_html__('Выберите храм', 'your-text-domain') . '</option>';
        foreach ($churches as $church) {
            echo '<option value="' . esc_attr($church->id) . '">' . esc_html($church->name) . '</option>';
        }
        echo '</select>';
        echo '</div>';

        echo '<div class="form-group">';
        echo '<label>' . esc_html__('Должность', 'your-text-domain') . '</label>';
        echo '<select name="position[]" class="form-control">';
        echo '<option value="Настоятель">' . esc_html__('Настоятель', 'your-text-domain') . '</option>';
        echo '<option value="Ключарь">' . esc_html__('Ключарь', 'your-text-domain') . '</option>';
        echo '<option value="Штатный священник">' . esc_html__('Штатный священник', 'your-text-domain') . '</option>';
        echo '<option value="Штатный диакон">' . esc_html__('Штатный диакон', 'your-text-domain') . '</option>';
        echo '</select>';
        echo '</div>';
        echo '<button type="button" class="btn btn-danger remove-church-position">Удалить</button>';
        echo '</div>'; // Закрываем .church-position
    }
    echo '</div>'; // Закрываем #church-positions

    echo '<button type="button" id="add-church-position" class="btn btn-secondary">' . esc_html__('Добавить еще храм', 'your-text-domain') . '</button>';

    echo '<p class="submit"><input type="submit" name="submit" id="submit" class="btn btn-primary" value="' . esc_attr__('Сохранить изменения', 'your-text-domain') . '"></p>';
    echo '</form>';
    echo '</div>';

    // Добавляем скрипт для динамического добавления и удаления полей
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('#add-church-position').on('click', function() {
            var churchPosition = $('.church-position:first').clone();
            churchPosition.find('select').val('');
            $('#church-positions').append(churchPosition);
        });

        $(document).on('click', '.remove-church-position', function() {
            $(this).closest('.church-position').remove();
        });
    });
    </script>
    <?php
}

// Обработка обновления духовенства
function ccm_handle_update_custom_clergy() {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'ccm_update_custom_clergy_nonce')) {
        wp_die(esc_html__('Ошибка проверки безопасности', 'your-text-domain'));
    }

    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Недостаточно прав', 'your-text-domain'));
    }

    if (isset($_POST['clergy_id'], $_POST['name'], $_POST['post_url'], $_POST['date_of_birth'], $_POST['date_of_tonsure'], $_POST['date_of_ordination'], $_POST['date_of_last_award'], $_POST['award_description'])) {
        global $wpdb;
        $clergy_table = $wpdb->prefix . 'clergy';
        $clergy_church_table = $wpdb->prefix . 'clergy_church';

        $clergy_id = intval($_POST['clergy_id']);
        $name = sanitize_text_field($_POST['name']);
        $post_url = esc_url_raw($_POST['post_url']);
        $date_of_birth = sanitize_text_field($_POST['date_of_birth']);
        $date_of_tonsure = sanitize_text_field($_POST['date_of_tonsure']);
        $date_of_ordination = sanitize_text_field($_POST['date_of_ordination']);
        $date_of_last_award = sanitize_text_field($_POST['date_of_last_award']);
        $award_description = sanitize_textarea_field($_POST['award_description']);

        // Обновляем данные в таблице clergy
        $wpdb->update(
            $clergy_table,
            array(
                'name' => $name,
                'post_url' => $post_url,
                'date_of_birth' => $date_of_birth,
                'date_of_tonsure' => $date_of_tonsure,
                'date_of_ordination' => $date_of_ordination,
                'date_of_last_award' => $date_of_last_award,
                'award_description' => $award_description,
            ),
            array('id' => $clergy_id)
        );

        // Обрабатываем храмы и должности
        // Удаляем старые записи
        $wpdb->delete($clergy_church_table, array('clergy_id' => $clergy_id));

        if (isset($_POST['church_id']) && is_array($_POST['church_id'])) {
            $positions = $_POST['position'];
            foreach ($_POST['church_id'] as $key => $church_id) {
                $church_id = intval($church_id);
                $position = sanitize_text_field($positions[$key]);

                if ($church_id && $position) {
                    $wpdb->insert(
                        $clergy_church_table,
                        array(
                            'clergy_id' => $clergy_id,
                            'church_id' => $church_id,
                            'position' => $position,
                        )
                    );
                }
            }
        }

        wp_redirect(admin_url('admin.php?page=ccm_custom_clergy'));
        exit;
    }
}
add_action('admin_post_ccm_update_custom_clergy', 'ccm_handle_update_custom_clergy');

// Обработка удаления выбранных записей из списка
function ccm_handle_bulk_delete_custom_clergy() {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'ccm_bulk_edit_custom_clergy_nonce')) {
        wp_die(esc_html__('Ошибка проверки безопасности', 'your-text-domain'));
    }

    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Недостаточно прав', 'your-text-domain'));
    }

    global $wpdb;
    $clergy_table = $wpdb->prefix . 'clergy';
    $clergy_church_table = $wpdb->prefix . 'clergy_church';

    if (isset($_POST['selected']) && is_array($_POST['selected'])) {
        // Удаление выбранных записей
        foreach ($_POST['selected'] as $id) {
            $id = intval($id);
            $wpdb->delete($clergy_table, array('id' => $id));
            $wpdb->delete($clergy_church_table, array('clergy_id' => $id));
        }
    }

    wp_redirect(admin_url('admin.php?page=ccm_custom_clergy'));
    exit;
}
add_action('admin_post_ccm_bulk_edit_custom_clergy', 'ccm_handle_bulk_delete_custom_clergy');

// Получение данных духовенства через REST API
function ccm_get_clergy_with_post_data(WP_REST_Request $request) {
    global $wpdb;
    $clergy_table = $wpdb->prefix . 'clergy';
    $clergy_church_table = $wpdb->prefix . 'clergy_church';

    // Получаем параметр church_id из запроса
    $church_id = $request->get_param('church_id');

    if ($church_id) {
        // Получаем духовенство, связанное с данным храмом
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT c.*, cc.position, cc.church_id FROM $clergy_table c
            INNER JOIN $clergy_church_table cc ON c.id = cc.clergy_id
            WHERE cc.church_id = %d", $church_id));
    } else {
        $results = $wpdb->get_results("SELECT c.*, cc.position, cc.church_id FROM $clergy_table c
            INNER JOIN $clergy_church_table cc ON c.id = cc.clergy_id");
    }

    $clergy = array();

    foreach ($results as $row) {
        $post_id = url_to_postid($row->post_url);
        // Получаем дополнительные данные из записи, если нужно
        $custom_field = get_post_meta($post_id, 'custom_field_key', true);
        $image = get_the_post_thumbnail_url($post_id, 'full');

        $clergy[] = array(
            'name' => $row->name,
            'position' => $row->position,
            'post_url' => $row->post_url,
            'date_of_birth' => $row->date_of_birth,
            'date_of_tonsure' => $row->date_of_tonsure,
            'date_of_ordination' => $row->date_of_ordination,
            'date_of_last_award' => $row->date_of_last_award,
            'award_description' => $row->award_description,
            'church_id' => $row->church_id,
            'custom_field' => $custom_field,
            'image' => $image,
        );
    }

    return rest_ensure_response(array('clergy' => $clergy));
}

add_action('rest_api_init', function () {
    register_rest_route('clergy/v1', '/list', array(
        'methods' => 'GET',
        'callback' => 'ccm_get_clergy_with_post_data',
        'args' => array(
            'church_id' => array(
                'required' => false,
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
        ),
    ));
});
// конец

   









function enqueue_font_awesome() {
    wp_enqueue_style(
        'font-awesome', 
        get_stylesheet_directory_uri() . '/fonts/font-awesome-4/font-awesome-4/font-awesome-4.7.0/css/font-awesome.min.css'
    );
}
add_action('wp_enqueue_scripts', 'enqueue_font_awesome');


//рест

add_action('rest_api_init', function() {
    register_rest_route('custom/v1', '/posts/', array(
        'methods' => 'GET',
        'callback' => 'get_custom_posts',
    ));
});

function get_custom_posts() {
    $args = array(
        'category__in' => array(66, 65, 102, 67),
        'post_type'    => 'post',
        'post_status'  => 'publish',
        'posts_per_page' => -1, // Возвращает все посты
    );

    $query = new WP_Query($args);
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $acf_fields = get_fields(); // Получение всех ACF полей

            $posts[] = [
                'title'     => get_the_title(),
                'image'     => get_the_post_thumbnail_url(),
                'acf'       => $acf_fields, // Все поля ACF
            ];
        }
        wp_reset_postdata();
    }

    return rest_ensure_response($posts);
}


// media
// Добавление метабокса
function add_custom_publish_metabox() {
    add_meta_box(
        'custom_publish_metabox',
        'Публикация в социальные сети',
        'render_custom_publish_metabox',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_custom_publish_metabox');

function render_custom_publish_metabox($post) {
    // Получение сохраненных данных
    $custom_title = get_post_meta($post->ID, '_custom_title', true);
    $custom_content = get_post_meta($post->ID, '_custom_content', true);
    $custom_images = get_post_meta($post->ID, '_custom_images', true);
    $use_source = get_post_meta($post->ID, '_use_source', true);
    $use_custom_title = get_post_meta($post->ID, '_use_custom_title', true);

    // Преобразование строки с идентификаторами изображений в массив
    $custom_images = $custom_images ? explode(',', $custom_images) : [];
    ?>
    <p>
        <input type="checkbox" id="use_custom_title" name="use_custom_title" value="1" <?php checked($use_custom_title, 1); ?>>
        <label for="use_custom_title">Использовать кастомный заголовок и текст</label>
    </p>
    <div id="custom_fields" style="display: <?php echo $use_custom_title ? 'block' : 'none'; ?>;">
        <p>
            <label for="custom_title">Заголовок:</label>
            <input type="text" id="custom_title" name="custom_title" value="<?php echo esc_attr($custom_title); ?>" class="widefat">
        </p>
        <p>
            <label for="custom_content">Текст новости:</label>
            <textarea id="custom_content" name="custom_content" class="widefat"><?php echo esc_textarea($custom_content); ?></textarea>
        </p>
    </div>
    <p>
        <label for="custom_images">Прикрепить картинки:</label>
        <input type="hidden" id="custom_images" name="custom_images" value="<?php echo esc_attr(implode(',', $custom_images)); ?>">
        <button type="button" class="button" id="upload_images_button">Загрузить картинки</button>
    </p>
    <div id="selected_images">
        <?php foreach ($custom_images as $image_id): ?>
            <div class="selected-image">
                <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
            </div>
        <?php endforeach; ?>
    </div>
    <p>
        <input type="checkbox" id="use_source" name="use_source" value="1" <?php checked($use_source, 1); ?>>
        <label for="use_source">Указывать источник</label>
    </p>
    <button type="button" id="publish-vk" class="button">Публиковать в ВКонтакте</button>
    <button type="button" id="publish-ok" class="button">Публиковать в Одноклассники</button>
    <button type="button" id="publish-zen" class="button">Публиковать в Яндекс.Дзен</button>
    <button type="button" id="publish-tg" class="button">Публиковать в Telegram</button>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#use_custom_title').change(function() {
                if ($(this).is(':checked')) {
                    $('#custom_fields').show();
                } else {
                    $('#custom_fields').hide();
                }
            });

            $('#upload_images_button').click(function(e) {
                e.preventDefault();
                var imageFrame;
                if (imageFrame) {
                    imageFrame.open();
                } else {
                    imageFrame = wp.media({
                        title: 'Выбрать картинки',
                        multiple: 'add',
                        library: {
                            type: 'image',
                        },
                        button: {
                            text: 'Выбрать'
                        }
                    });

                    imageFrame.on('select', function() {
                        var images = imageFrame.state().get('selection').toJSON();
                        var imageIds = images.map(function(image) {
                            return image.id;
                        });
                        $('#custom_images').val(imageIds.join(','));
                        
                        // Отображение выбранных изображений
                        var selectedImagesContainer = $('#selected_images');
                        selectedImagesContainer.empty();
                        images.forEach(function(image) {
                            selectedImagesContainer.append('<div class="selected-image"><img src="' + image.url + '" style="width: 100px; height: auto;" /></div>');
                        });
                    });

                    imageFrame.open();
                }
            });

            $('#publish-vk').click(function() {
                publishToSocial('vk');
            });
            $('#publish-ok').click(function() {
                publishToSocial('ok');
            });
            $('#publish-zen').click(function() {
                publishToSocial('zen');
            });
            $('#publish-tg').click(function() {
                publishToSocial('tg');
            });

            function publishToSocial(network) {
                var postId = <?php echo $post->ID; ?>;
                $.post(ajaxurl, {
                    action: 'publish_to_' + network,
                    post_id: postId,
                    custom_title: $('#custom_title').val(),
                    custom_content: $('#custom_content').val(),
                    custom_images: $('#custom_images').val(),
                    use_source: $('#use_source').is(':checked') ? 1 : 0,
                    use_custom_title: $('#use_custom_title').is(':checked') ? 1 : 0
                }, function(response) {
                    alert('Публикация в ' + network + ': ' + response);
                });
            }
        });
    </script>
    <?php
}

function save_custom_publish_metabox($post_id) {
    if (array_key_exists('custom_title', $_POST)) {
        update_post_meta($post_id, '_custom_title', sanitize_text_field($_POST['custom_title']));
    }
    if (array_key_exists('custom_content', $_POST)) {
        update_post_meta($post_id, '_custom_content', sanitize_textarea_field($_POST['custom_content']));
    }
    if (array_key_exists('custom_images', $_POST)) {
        update_post_meta($post_id, '_custom_images', sanitize_text_field($_POST['custom_images']));
    }
    if (array_key_exists('use_source', $_POST)) {
        update_post_meta($post_id, '_use_source', $_POST['use_source']);
    } else {
        delete_post_meta($post_id, '_use_source');
    }
    if (array_key_exists('use_custom_title', $_POST)) {
        update_post_meta($post_id, '_use_custom_title', $_POST['use_custom_title']);
    } else {
        delete_post_meta($post_id, '_use_custom_title');
    }
}
add_action('save_post', 'save_custom_publish_metabox');

// AJAX обработчики
function ajax_publish_to_vk() {
    $post_id = intval($_POST['post_id']);
    $custom_title = sanitize_text_field($_POST['custom_title']);
    $custom_content = sanitize_textarea_field($_POST['custom_content']);
    $custom_images = explode(',', sanitize_text_field($_POST['custom_images']));
    $use_source = intval($_POST['use_source']);
    $use_custom_title = intval($_POST['use_custom_title']);
    
    post_to_vk($post_id, $custom_title, $custom_content, $custom_images, $use_source, $use_custom_title);
    echo 'Успешно';
    wp_die();
}
add_action('wp_ajax_publish_to_vk', 'ajax_publish_to_vk');

function ajax_publish_to_ok() {
    $post_id = intval($_POST['post_id']);
    $custom_title = sanitize_text_field($_POST['custom_title']);
    $custom_content = sanitize_textarea_field($_POST['custom_content']);
    $custom_images = explode(',', sanitize_text_field($_POST['custom_images']));
    $use_source = intval($_POST['use_source']);
    $use_custom_title = intval($_POST['use_custom_title']);
    
    post_to_ok($post_id, $custom_title, $custom_content, $custom_images, $use_source, $use_custom_title);
    echo 'Успешно';
    wp_die();
}
add_action('wp_ajax_publish_to_ok', 'ajax_publish_to_ok');

function ajax_publish_to_zen() {
    $post_id = intval($_POST['post_id']);
    $custom_title = sanitize_text_field($_POST['custom_title']);
    $custom_content = sanitize_textarea_field($_POST['custom_content']);
    $custom_images = explode(',', sanitize_text_field($_POST['custom_images']));
    $use_source = intval($_POST['use_source']);
    $use_custom_title = intval($_POST['use_custom_title']);
    
    post_to_yandex_zen($post_id, $custom_title, $custom_content, $custom_images, $use_source, $use_custom_title);
    echo 'Успешно';
    wp_die();
}
add_action('wp_ajax_publish_to_zen', 'ajax_publish_to_zen');

function ajax_publish_to_tg() {
    $post_id = intval($_POST['post_id']);
    $custom_title = sanitize_text_field($_POST['custom_title']);
    $custom_content = sanitize_textarea_field($_POST['custom_content']);
    $custom_images = explode(',', sanitize_text_field($_POST['custom_images']));
    $use_source = intval($_POST['use_source']);
    $use_custom_title = intval($_POST['use_custom_title']);
    
    post_to_telegram($post_id, $custom_title, $custom_content, $custom_images, $use_source, $use_custom_title);
    echo 'Успешно';
    wp_die();
}
add_action('wp_ajax_publish_to_tg', 'ajax_publish_to_tg');

// Функция публикации в ВКонтакте
function post_to_vk($post_id, $custom_title, $custom_content, $custom_images, $use_source, $use_custom_title) {
    $post = get_post($post_id);
    $title = strtoupper($use_custom_title ? $custom_title : $post->post_title);
    $content = $use_custom_title ? $custom_content : $post->post_content;
    $source = $use_source ? get_permalink($post_id) : '';

    $images = [];
    foreach ($custom_images as $image_id) {
        $image_url = wp_get_attachment_url($image_id);
        if ($image_url) {
            $images[] = $image_url;
        }
    }

    $message = $title . "\n" . $content . "\n" . $source;

    // Используйте ваш access_token
    $access_token = 'vk1.a.088FJqaqEU1XFQIyDLmEN6koHXVfodkOVivHNESyllHCPIGAlXUh86GCqkbrs9-OAbYcus2Vq0c40FLTOIBthEDdMMif3NMO7l4drgf9xsFlr5dV7h-kmID9GBOTJGIWPbD_Zw51hLLUcb-vh7IxqISLF2gatcjG1g21xwhSwPQ0MH5uAQ_YXACb2U6O_SeDCHsGZVH79QXwEFYiMnYYnA';
    $group_id = '219698477';
    $owner_id = '-' . $group_id; // owner_id для группы должно быть отрицательным

    // Сначала загрузим изображения на сервер ВКонтакте
    $attachments = [];
    $upload_url_response = wp_remote_get("https://api.vk.com/method/photos.getWallUploadServer?group_id={$group_id}&access_token={$access_token}&v=5.131");
    $upload_url = json_decode(wp_remote_retrieve_body($upload_url_response), true)['response']['upload_url'];

    foreach ($images as $image_url) {
        $image_data = file_get_contents($image_url);
        $boundary = wp_generate_uuid4();
        $body = "--$boundary\r\n"
              . "Content-Disposition: form-data; name=\"photo\"; filename=\"image.jpg\"\r\n"
              . "Content-Type: image/jpeg\r\n\r\n"
              . $image_data . "\r\n"
              . "--$boundary--\r\n";

        $response = wp_remote_post($upload_url, [
            'headers' => [
                'Content-Type' => 'multipart/form-data; boundary=' . $boundary,
            ],
            'body' => $body,
        ]);

        $response_body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($response_body['photo'])) {
            $save_photo_response = wp_remote_get("https://api.vk.com/method/photos.saveWallPhoto?group_id={$group_id}&photo={$response_body['photo']}&server={$response_body['server']}&hash={$response_body['hash']}&access_token={$access_token}&v=5.131");
            $save_photo_body = json_decode(wp_remote_retrieve_body($save_photo_response), true);

            if (isset($save_photo_body['response'][0]['id'])) {
                $attachments[] = 'photo' . $save_photo_body['response'][0]['owner_id'] . '_' . $save_photo_body['response'][0]['id'];
            }
        }
    }

    // Добавляем ссылку на пост
    $attachments[] = $source;

    $params = [
        'owner_id' => $owner_id,
        'message' => $message,
        'attachments' => implode(',', $attachments),
        'access_token' => $access_token,
        'v' => '5.131'
    ];

    $vk_url = 'https://api.vk.com/method/wall.post';

    $response = wp_remote_post($vk_url, [
        'body' => $params
    ]);

    if (is_wp_error($response)) {
        error_log('Error posting to VK: ' . $response->get_error_message());
        return;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['error'])) {
        error_log('VK API Error: ' . $data['error']['error_msg']);
    } else {
        error_log('VK API Success: ' . print_r($data, true));
    }
}




// Добавление страницы настроек в админку
function add_update_meta_fields_page_v2() {
    add_menu_page(
        'Обновление полей мета',
        'Обновление полей мета',
        'manage_options',
        'update-meta-fields-v2',
        'render_update_meta_fields_page_v2'
    );
}
add_action('admin_menu', 'add_update_meta_fields_page_v2');

// Рендеринг страницы настроек
function render_update_meta_fields_page_v2() {
    ?>
    <div class="wrap">
        <h1>Обновление полей мета</h1>
        <form method="post" action="">
            <input type="hidden" name="update_meta_fields_v2" value="1">
            <?php submit_button('Обновить поля мета'); ?>
        </form>
    </div>
    <?php

    // Проверка, была ли отправлена форма
    if (isset($_POST['update_meta_fields_v2']) && $_POST['update_meta_fields_v2'] == '1') {
        update_meta_fields_for_posts_v2('ierei');
        echo '<div class="notice notice-success is-dismissible"><p>Поля мета обновлены.</p></div>';
    }
}

// Функция для обновления мета полей
function update_meta_fields_for_posts_v2($category_slug) {
    $category = get_category_by_slug($category_slug);
    if (!$category) {
        error_log("Категория не найдена: " . $category_slug);
        return;
    }
    $category_id = $category->term_id;

    $args = array(
        'category' => $category_id,
        'post_type' => 'post',
        'posts_per_page' => -1,
    );
    $posts = get_posts($args);

    foreach ($posts as $post) {
        $content = json_decode($post->post_content)->rendered;
        $parsed_data = my_parse_post_content_from_json_v2($content);

        if (!empty($parsed_data['data_rozhdeniya'])) {
            update_post_meta($post->ID, 'data_rozhdeniya', $parsed_data['data_rozhdeniya']);
            error_log("Обновлено поле 'data_rozhdeniya' для поста ID: {$post->ID}");
        }

        if (!empty($parsed_data['obrazovanie'])) {
            update_field('obrazovanie', $parsed_data['obrazovanie'], $post->ID);
            error_log("Обновлено поле 'obrazovanie' для поста ID: {$post->ID}");
        }

        if (!empty($parsed_data['rukopolozhenie'])) {
            update_field('rukopolozhenie', $parsed_data['rukopolozhenie'], $post->ID);
            error_log("Обновлено поле 'rukopolozhenie' для поста ID: {$post->ID}");
        }

        if (!empty($parsed_data['mesto_sluzheniya_i_dolzhnost'])) {
            update_field('mesto_sluzheniya_i_dolzhnost', $parsed_data['mesto_sluzheniya_i_dolzhnost'], $post->ID);
            error_log("Обновлено поле 'mesto_sluzheniya_i_dolzhnost' для поста ID: {$post->ID}");
        }

        if (!empty($parsed_data['nagrady'])) {
            update_field('nagrady', $parsed_data['nagrady'], $post->ID);
            error_log("Обновлено поле 'nagrady' для поста ID: {$post->ID}");
        }
    }
}



//Начало благочиния

// Создание или обновление таблицы deaneries с дополнительными полями для редактирования
function update_deaneries_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'deaneries';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        category_url text NOT NULL,
        district text NOT NULL,
        dean_url text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_setup_theme', 'update_deaneries_table');

// Функция отображения страницы добавления нового благочиния
function display_add_deanery_page() {
    ?>
    <div class="wrap">
        <h1>Добавить новое благочиние</h1>
        <form method="post" action="admin-post.php">
            <input type="hidden" name="action" value="add_deanery">
            <?php wp_nonce_field('add_deanery_nonce'); ?>

            <div class="form-group">
                <label for="name">Название благочиния</label>
                <input name="name" id="name" type="text" class="regular-text">
            </div>

            <div class="form-group">
                <label for="category_url">Ссылка на категорию</label>
                <input name="category_url" id="category_url" type="text" class="regular-text">
            </div>

            <div class="form-group">
                <label for="district">Район</label>
                <input name="district" id="district" type="text" class="regular-text">
            </div>

            <div class="form-group">
                <label for="dean_url">Ссылка на благочинного</label>
                <input name="dean_url" id="dean_url" type="text" class="regular-text">
            </div>

            <button type="submit" class="button button-primary">Добавить благочиние</button>
        </form>
    </div>
    <?php
}

// Добавление меню и подменю для управления благочиниями
add_action('admin_menu', function() {
    add_menu_page(
        'Управление благочиниями',
        'Благочиния',
        'manage_options',
        'custom_deaneries',
        'display_deaneries_page',
        'dashicons-admin-site-alt3',
        6
    );

    add_submenu_page(
        'custom_deaneries',
        'Добавить новое благочиние',
        'Добавить благочиние',
        'manage_options',
        'add_deanery',
        'display_add_deanery_page'
    );
});

// Регистрация REST API для получения списка благочиний
function register_deaneries_endpoint() {
    register_rest_route('custom_deaneries/v1', '/posts', array(
        'methods' => 'GET',
        'callback' => 'get_deaneries',
        'permission_callback' => '__return_true', // Разрешить доступ всем
    ));
}
add_action('rest_api_init', 'register_deaneries_endpoint');

function get_deaneries() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'deaneries';

    // Проверка существования таблицы
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        return new WP_REST_Response(array('error' => 'Таблица не найдена'), 404);
    }

    // Выполнение SQL запроса
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    if (!$results) {
        return new WP_REST_Response(array('error' => 'Нет данных в таблице или ошибка запроса'), 404);
    }

    $deaneries = array();
    foreach ($results as $row) {
        $dean_id = url_to_postid($row->dean_url);
        $dean_name = $dean_id ? get_the_title($dean_id) : '';
        $dean_image = $dean_id ? get_the_post_thumbnail_url($dean_id, 'full') : '';

        $deaneries[] = array(
            'id' => $row->id,
            'name' => $row->name,
            'category_url' => esc_url($row->category_url),
            'district' => $row->district,
            'dean_url' => esc_url($row->dean_url),
            'dean_name' => $dean_name,
            'dean_image' => $dean_image
        );
    }

    return new WP_REST_Response(array('deaneries' => $deaneries), 200);
}

// Обработка добавления нового благочиния
function handle_add_deanery() {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'add_deanery_nonce')) {
        wp_die('Ошибка проверки безопасности');
    }

    if (!current_user_can('manage_options')) {
        wp_die('Недостаточно прав');
    }

    if (isset($_POST['name'], $_POST['category_url'], $_POST['district'], $_POST['dean_url'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'deaneries';

        $name = sanitize_text_field($_POST['name']);
        $category_url = esc_url_raw($_POST['category_url']);
        $district = sanitize_text_field($_POST['district']);
        $dean_url = esc_url_raw($_POST['dean_url']);

        $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'category_url' => $category_url,
                'district' => $district,
                'dean_url' => $dean_url
            )
        );

        wp_redirect(admin_url('admin.php?page=custom_deaneries'));
        exit;
    }
}
add_action('admin_post_add_deanery', 'handle_add_deanery');

// Отображение страницы со всеми благочиниями
function display_deaneries_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'deaneries';
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    ?>
    <div class="wrap">
        <h1>Все благочиния</h1>
        <form method="post" action="admin-post.php">
            <input type="hidden" name="action" value="bulk_edit_deaneries">
            <?php wp_nonce_field('bulk_edit_deaneries_nonce'); ?>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Ссылка на категорию</th>
                        <th>Район</th>
                        <th>Ссылка на благочинного</th>
                        <th>Благочинный</th>
                        <th>Выбрать</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): 
                        $dean_id = url_to_postid($row->dean_url);
                        $dean_title = $dean_id ? get_the_title($dean_id) : '';
                    ?>
                    <tr>
                        <td><?php echo $row->id; ?></td>
                        <td><input type="text" name="name[<?php echo $row->id; ?>]" value="<?php echo esc_attr($row->name); ?>" class="regular-text"></td>
                        <td><input type="text" name="category_url[<?php echo $row->id; ?>]" value="<?php echo esc_attr($row->category_url); ?>" class="regular-text"></td>
                        <td><input type="text" name="district[<?php echo $row->id; ?>]" value="<?php echo esc_attr($row->district); ?>" class="regular-text"></td>
                        <td><input type="text" name="dean_url[<?php echo $row->id; ?>]" value="<?php echo esc_attr($row->dean_url); ?>" class="regular-text"></td>
                        <td><?php echo esc_html($dean_title); ?></td>
                        <td><input type="checkbox" name="selected[]" value="<?php echo $row->id; ?>"></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p class="submit">
                <button type="submit" name="submit" id="submit" class="button button-primary">Сохранить изменения</button>
                <button type="submit" name="delete" id="delete" class="button button-secondary" onclick="return confirm('Вы уверены, что хотите удалить выбранные благочиния?');">Удалить выбранные</button>
            </p>
        </form>
    </div>
    <?php
}

// Обработка редактирования и удаления благочиний
function handle_edit_and_delete_deaneries() {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'bulk_edit_deaneries_nonce')) {
        wp_die('Ошибка проверки безопасности');
    }

    if (!current_user_can('manage_options')) {
        wp_die('Недостаточно прав');
    }

    if (isset($_POST['selected']) && is_array($_POST['selected'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'deaneries';

        if (isset($_POST['delete'])) {
            foreach ($_POST['selected'] as $id) {
                $id = intval($id);
                $wpdb->delete($table_name, array('id' => $id));
            }
        } else {
            foreach ($_POST['selected'] as $id) {
                $id = intval($id);
                $name = sanitize_text_field($_POST['name'][$id]);
                $category_url = esc_url_raw($_POST['category_url'][$id]);
                $district = sanitize_text_field($_POST['district'][$id]);
                $dean_url = esc_url_raw($_POST['dean_url'][$id]);

                $wpdb->update(
                    $table_name,
                    array(
                        'name' => $name,
                        'category_url' => $category_url,
                        'district' => $district,
                        'dean_url' => $dean_url,
                    ),
                    array('id' => $id)
                );
            }
        }
    }

    wp_redirect(admin_url('admin.php?page=custom_deaneries'));
    exit;
}
add_action('admin_post_bulk_edit_deaneries', 'handle_edit_and_delete_deaneries');

// Разрешение CORS для REST API
function allow_rest_api_cors() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
}
add_action('rest_api_init', 'allow_rest_api_cors', 15);





//

function custom_get_gallery_image_count($post_id) {
    $post = get_post($post_id);
    if (!$post) {
        return 0;
    }

    // Поиск шорткода галереи в контенте
    if (has_shortcode($post->post_content, 'gallery')) {
        preg_match_all('/\[gallery.*ids=.(.*).\]/', $post->post_content, $matches);
        if (isset($matches[1][0])) {
            $ids = explode(',', $matches[1][0]);
            return count($ids);
        }
    }

    return 0;
}


add_filter('kama_thumbnail_image_src', 'convert_thumbnail_to_webp', 10, 2);

//
//
//начало меток 
//
//

// 1. Подключение Bootstrap в админку и регистрация необходимых стилей и скриптов
function enqueue_custom_admin_styles($hook) {
    if (
        $hook != 'toplevel_page_custom_locations' &&
        $hook != 'custom_locations_page_add_custom_location' &&
        $hook != 'custom_locations_page_edit_custom_location'
    ) {
        return;
    }

    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_style('custom-admin-style', plugin_dir_url(__FILE__) . 'css/custom-admin-style.css');
    wp_enqueue_script('custom-admin-script', plugin_dir_url(__FILE__) . 'js/custom-admin-script.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'enqueue_custom_admin_styles');

// 2. Создание или обновление таблицы locations (таблица праздников удалена)
function update_locations_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'locations';
    $charset_collate = $wpdb->get_charset_collate();

    // Создание основной таблицы locations
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        latitude float(10,6) NOT NULL,
        longitude float(10,6) NOT NULL,
        post_url text NOT NULL,
        deanery tinytext NOT NULL,
        balloon_type tinytext NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'update_locations_table');
add_action('after_setup_theme', 'update_locations_table');

// 3. Добавление меню и подменю в админке для управления метками
function custom_admin_menu() {
    add_menu_page(
        'Управление метками',
        'Метки на карте',
        'manage_options',
        'custom_locations',
        'display_custom_locations_page',
        'dashicons-location',
        6
    );

    add_submenu_page(
        'custom_locations',
        'Добавить новую метку',
        'Добавить метку',
        'manage_options',
        'add_custom_location',
        'display_add_custom_location_page'
    );

    // Добавление страницы редактирования (динамическая, обрабатывается через query vars)
    add_submenu_page(
        null,
        'Редактировать метку',
        'Редактировать метку',
        'manage_options',
        'edit_custom_location',
        'display_edit_custom_location_page'
    );
}
add_action('admin_menu', 'custom_admin_menu');

// 4. Страница добавления новой метки
function display_add_custom_location_page() {
    ?>
    <div class="wrap">
        <h1 class="mb-4">Добавить новую метку</h1>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="add_custom_location">
            <?php wp_nonce_field('add_custom_location_nonce'); ?>

            <div class="form-group">
                <label for="name">Название</label>
                <input name="name" id="name" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="latitude">Широта</label>
                <input name="latitude" id="latitude" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="longitude">Долгота</label>
                <input name="longitude" id="longitude" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="post_url">Ссылка на запись</label>
                <input name="post_url" id="post_url" type="url" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="deanery">Благочиние</label>
                <select name="deanery" id="deanery" class="form-control" required>
                    <option value="">-- Выберите благочиние --</option>
                    <option value="Первое Симбирское городское благочиние">Первое Симбирское городское благочиние</option>
                    <option value="Второе Симбирское городское благочиние">Второе Симбирское городское благочиние</option>
                    <option value="Третье Симбирское городское благочиние">Третье Симбирское городское благочиние</option>
                    <option value="Новоульяновское благочиние">Новоульяновское благочиние</option>
                    <option value="Цильнинское благочиние">Цильнинское благочиние</option>
                    <option value="Вешкаймское благочиние">Вешкаймское благочиние</option>
                    <option value="Майнское благочиние">Майнское благочиние</option>
                    <option value="Карсунское благочиние">Карсунское благочиние</option>
                    <option value="Сурское благочиние">Сурское благочиние</option>
                    <option value="Новоспасское благочиние">Новоспасское благочиние</option>
                    <option value="Кузоватовское благочиние">Кузоватовское благочиние</option>
                    <option value="Монастыри">Монастыри</option>
                </select>
            </div>
            <div class="form-group">
                <label for="balloon_type">Тип балуна</label>
                <select name="balloon_type" id="balloon_type" class="form-control" required>
                    <option value="">-- Выберите тип --</option>
                    <option value="monastery">Монастырь</option>
                    <option value="church">Церковь</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Добавить метку</button>
        </form>
    </div>
    <?php
}

// 5. Обработка добавления новой метки
function handle_add_custom_location() {
    if (
        !isset($_POST['_wpnonce']) ||
        !wp_verify_nonce($_POST['_wpnonce'], 'add_custom_location_nonce')
    ) {
        wp_die('Ошибка проверки безопасности');
    }

    if (!current_user_can('manage_options')) {
        wp_die('Недостаточно прав');
    }

    if (
        isset($_POST['name'], $_POST['latitude'], $_POST['longitude'], $_POST['post_url'], $_POST['deanery'], $_POST['balloon_type'])
    ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'locations';

        $name = sanitize_text_field($_POST['name']);
        $latitude = floatval($_POST['latitude']);
        $longitude = floatval($_POST['longitude']);
        $post_url = esc_url_raw($_POST['post_url']);
        $deanery = sanitize_text_field($_POST['deanery']);
        $balloon_type = sanitize_text_field($_POST['balloon_type']);

        $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'post_url' => $post_url,
                'deanery' => $deanery,
                'balloon_type' => $balloon_type,
            )
        );

        wp_redirect(admin_url('admin.php?page=custom_locations'));
        exit;
    } else {
        wp_die('Не заполнены все обязательные поля.');
    }
}
add_action('admin_post_add_custom_location', 'handle_add_custom_location');

// 6. Функция для отображения всех меток
function display_custom_locations_page() {
    global $wpdb;
    $locations_table = $wpdb->prefix . 'locations';
    $results = $wpdb->get_results("SELECT * FROM $locations_table");

    ?>
    <div class="wrap">
        <h1 class="mb-4">Все метки</h1>
        <a href="<?php echo admin_url('admin.php?page=add_custom_location'); ?>" class="btn btn-success mb-3">Добавить новую метку</a>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="bulk_edit_custom_locations">
            <?php wp_nonce_field('bulk_edit_custom_locations_nonce'); ?>

            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Широта</th>
                        <th>Долгота</th>
                        <th>Ссылка на запись</th>
                        <th>Благочиние</th>
                        <th>Тип балуна</th>
                        <th>Действия</th>
                        <th>Выбрать</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($results): ?>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td><?php echo esc_html($row->id); ?></td>
                                <td><?php echo esc_html($row->name); ?></td>
                                <td><?php echo esc_html($row->latitude); ?></td>
                                <td><?php echo esc_html($row->longitude); ?></td>
                                <td><a href="<?php echo esc_url($row->post_url); ?>" target="_blank">Ссылка</a></td>
                                <td><?php echo esc_html($row->deanery); ?></td>
                                <td><?php echo esc_html($row->balloon_type == 'monastery' ? 'Монастырь' : 'Церковь'); ?></td>
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=edit_custom_location&id=' . intval($row->id)); ?>" class="btn btn-primary btn-sm">Редактировать</a>
                                    <a href="<?php echo admin_url('admin-post.php?action=delete_custom_location&id=' . intval($row->id) . '&_wpnonce=' . wp_create_nonce('delete_custom_location_nonce')); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите удалить эту метку?');">Удалить</a>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" name="selected[]" value="<?php echo esc_attr($row->id); ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Метки не найдены.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="mb-3">
                <button type="submit" name="submit" class="btn btn-primary">Сохранить изменения</button>
                <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить выбранные метки?');">Удалить выбранные</button>
            </div>
        </form>
    </div>
    <?php
}



// 7. Обработка сохранения изменений и удаления меток
function handle_edit_and_delete_custom_locations() {
    if (
        !isset($_POST['_wpnonce']) ||
        !wp_verify_nonce($_POST['_wpnonce'], 'bulk_edit_custom_locations_nonce')
    ) {
        wp_die('Ошибка проверки безопасности');
    }

    if (!current_user_can('manage_options')) {
        wp_die('Недостаточно прав');
    }

    if (isset($_POST['selected']) && is_array($_POST['selected'])) {
        global $wpdb;
        $locations_table = $wpdb->prefix . 'locations';

        if (isset($_POST['delete'])) {
            foreach ($_POST['selected'] as $id) {
                $id = intval($id);
                $nonce = isset($_GET['_wpnonce']) ? $_GET['_wpnonce'] : '';
                if (wp_verify_nonce($nonce, 'delete_custom_location_nonce')) {
                    $wpdb->delete($locations_table, array('id' => $id));
                }
            }
        } else {
            // Обновление полей (если были бы поля для массового редактирования)
            // Здесь при желании можно реализовать массовое редактирование
        }
    }

    wp_redirect(admin_url('admin.php?page=custom_locations'));
    exit;
}
add_action('admin_post_bulk_edit_custom_locations', 'handle_edit_and_delete_custom_locations');

// 8. Обработка удаления отдельной метки
function handle_delete_custom_location() {
    if (
        !isset($_GET['_wpnonce']) ||
        !wp_verify_nonce($_GET['_wpnonce'], 'delete_custom_location_nonce')
    ) {
        wp_die('Ошибка проверки безопасности');
    }

    if (!current_user_can('manage_options')) {
        wp_die('Недостаточно прав');
    }

    if (isset($_GET['id'])) {
        global $wpdb;
        $locations_table = $wpdb->prefix . 'locations';
        $id = intval($_GET['id']);
        $wpdb->delete($locations_table, array('id' => $id));
    }

    wp_redirect(admin_url('admin.php?page=custom_locations'));
    exit;
}
add_action('admin_post_delete_custom_location', 'handle_delete_custom_location');

// 9. Функция для отображения страницы редактирования метки
function display_edit_custom_location_page() {
    if (!isset($_GET['id'])) {
        wp_die('Отсутствует ID метки.');
    }

    $id = intval($_GET['id']);

    global $wpdb;
    $locations_table = $wpdb->prefix . 'locations';

    $location = $wpdb->get_row($wpdb->prepare("SELECT * FROM $locations_table WHERE id = %d", $id));
    if (!$location) {
        wp_die('Метка не найдена.');
    }

    ?>
    <div class="wrap">
        <h1 class="mb-4">Редактировать метку: <?php echo esc_html($location->name); ?></h1>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="edit_custom_location">
            <input type="hidden" name="id" value="<?php echo esc_attr($id); ?>">
            <?php wp_nonce_field('edit_custom_location_nonce'); ?>

            <div class="form-group">
                <label for="name">Название</label>
                <input name="name" id="name" type="text" class="form-control" value="<?php echo esc_attr($location->name); ?>" required>
            </div>
            <div class="form-group">
                <label for="latitude">Широта</label>
                <input name="latitude" id="latitude" type="text" class="form-control" value="<?php echo esc_attr($location->latitude); ?>" required>
            </div>
            <div class="form-group">
                <label for="longitude">Долгота</label>
                <input name="longitude" id="longitude" type="text" class="form-control" value="<?php echo esc_attr($location->longitude); ?>" required>
            </div>
            <div class="form-group">
                <label for="post_url">Ссылка на запись</label>
                <input name="post_url" id="post_url" type="url" class="form-control" value="<?php echo esc_attr($location->post_url); ?>" required>
            </div>
            <div class="form-group">
                <label for="deanery">Благочиние</label>
                <select name="deanery" id="deanery" class="form-control" required>
                    <option value="">-- Выберите благочиние --</option>
                    <option value="Первое Симбирское городское благочиние" <?php selected($location->deanery, 'Первое Симбирское городское благочиние'); ?>>Первое Симбирское городское благочиние</option>
                    <option value="Второе Симбирское городское благочиние" <?php selected($location->deanery, 'Второе Симбирское городское благочиние'); ?>>Второе Симбирское городское благочиние</option>
                    <option value="Третье Симбирское городское благочиние" <?php selected($location->deanery, 'Третье Симбирское городское благочиние'); ?>>Третье Симбирское городское благочиние</option>
                    <option value="Новоульяновское благочиние" <?php selected($location->deanery, 'Новоульяновское благочиние'); ?>>Новоульяновское благочиние</option>
                    <option value="Цильнинское благочиние" <?php selected($location->deanery, 'Цильнинское благочиние'); ?>>Цильнинское благочиние</option>
                    <option value="Вешкаймское благочиние" <?php selected($location->deanery, 'Вешкаймское благочиние'); ?>>Вешкаймское благочиние</option>
                    <option value="Майнское благочиние" <?php selected($location->deanery, 'Майнское благочиние'); ?>>Майнское благочиние</option>
                    <option value="Карсунское благочиние" <?php selected($location->deanery, 'Карсунское благочиние'); ?>>Карсунское благочиние</option>
                    <option value="Сурское благочиние" <?php selected($location->deanery, 'Сурское благочиние'); ?>>Сурское благочиние</option>
                    <option value="Новоспасское благочиние" <?php selected($location->deanery, 'Новоспасское благочиние'); ?>>Новоспасское благочиние</option>
                    <option value="Кузоватовское благочиние" <?php selected($location->deanery, 'Кузоватовское благочиние'); ?>>Кузоватовское благочиние</option>
                    <option value="Монастыри" <?php selected($location->deanery, 'Монастыри'); ?>>Монастыри</option>
                </select>
            </div>
            <div class="form-group">
                <label for="balloon_type">Тип балуна</label>
                <select name="balloon_type" id="balloon_type" class="form-control" required>
                    <option value="">-- Выберите тип --</option>
                    <option value="monastery" <?php selected($location->balloon_type, 'monastery'); ?>>Монастырь</option>
                    <option value="church" <?php selected($location->balloon_type, 'church'); ?>>Церковь</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>
    </div>
    <?php
}

// 10. Обработка редактирования метки
function handle_edit_custom_location() {
    if (
        !isset($_POST['_wpnonce']) ||
        !wp_verify_nonce($_POST['_wpnonce'], 'edit_custom_location_nonce')
    ) {
        wp_die('Ошибка проверки безопасности');
    }

    if (!current_user_can('manage_options')) {
        wp_die('Недостаточно прав');
    }

    if (
        isset($_POST['id'], $_POST['name'], $_POST['latitude'], $_POST['longitude'], $_POST['post_url'], $_POST['deanery'], $_POST['balloon_type'])
    ) {
        global $wpdb;
        $locations_table = $wpdb->prefix . 'locations';

        $id = intval($_POST['id']);
        $name = sanitize_text_field($_POST['name']);
        $latitude = floatval($_POST['latitude']);
        $longitude = floatval($_POST['longitude']);
        $post_url = esc_url_raw($_POST['post_url']);
        $deanery = sanitize_text_field($_POST['deanery']);
        $balloon_type = sanitize_text_field($_POST['balloon_type']);

        $wpdb->update(
            $locations_table,
            array(
                'name' => $name,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'post_url' => $post_url,
                'deanery' => $deanery,
                'balloon_type' => $balloon_type,
            ),
            array('id' => $id)
        );

        wp_redirect(admin_url('admin.php?page=custom_locations'));
        exit;
    } else {
        wp_die('Не заполнены все обязательные поля.');
    }
}
add_action('admin_post_edit_custom_location', 'handle_edit_custom_location');

// 13. REST API для получения данных о метках
function get_locations_with_post_data() {
    global $wpdb;
    $locations_table = $wpdb->prefix . 'locations';

    $results = $wpdb->get_results("SELECT * FROM $locations_table");
    $locations = array();

    foreach ($results as $row) {
        $post_id = url_to_postid($row->post_url);
        $custom_field = get_post_meta($post_id, 'custom_field_key', true);
        $address = function_exists('get_field') ? get_field('address', $post_id) : '';
        $image = get_the_post_thumbnail_url($post_id, 'full');

        $locations[] = array(
            'id' => $row->id,
            'name' => $row->name,
            'latitude' => $row->latitude,
            'longitude' => $row->longitude,
            'image' => $image,
            'custom_field' => $custom_field,
            'address' => $address,
            'permalink' => $row->post_url,
            'deanery' => $row->deanery,
            'balloon_type' => $row->balloon_type,
        );
    }

    return rest_ensure_response(array('locations' => $locations));
}

add_action('rest_api_init', function () {
    register_rest_route('locations/v1', '/posts', array(
        'methods' => 'GET',
        'callback' => 'get_locations_with_post_data',
    ));
});


add_action('rest_api_init', function () {
    // Уже есть: register_rest_route('locations/v1', '/posts', [...]) - для списка
    // Добавим для одиночной записи:
    register_rest_route('locations/v1', '/posts/(?P<id>\d+)', [
        'methods'  => 'GET',
        'callback' => 'get_single_location',
        'permission_callback' => '__return_true',
    ]);
});

function get_single_location($request) {
    global $wpdb;
    $id = intval($request['id']);
    $table_name = $wpdb->prefix . 'locations';

    // Ищем запись
    $location = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));

    if (!$location) {
        return new WP_Error('no_location', 'Локация не найдена', ['status' => 404]);
    }

    // Если нужно собрать данные как в вашем "списке" (адрес, image и т.д.)
    // То же, что в get_locations_with_post_data, но для одной записи
    $post_id = url_to_postid($location->post_url);
    $address = function_exists('get_field') ? get_field('address', $post_id) : '';
    $image   = get_the_post_thumbnail_url($post_id, 'full');

    $data = [
        'id'       => $location->id,
        'name'     => $location->name,
        'latitude' => $location->latitude,
        'longitude'=> $location->longitude,
        'image'    => $image,
        'address'  => $address,
        'permalink'=> $location->post_url,
        'deanery'  => $location->deanery,
        'balloon_type'=> $location->balloon_type,
    ];

    return rest_ensure_response($data);
}


//прздники 

// ===================== Праздники =====================

// 1. Создание/обновление таблицы праздников
function update_holidays_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'holidays';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        location_id mediumint(9) NOT NULL,
        holiday_name tinytext NOT NULL,
        year smallint(4) NULL,
        month tinyint(2) NULL,
        day tinyint(2) NULL,
        offset_from_easter smallint(4) NULL,
        PRIMARY KEY (id),
        KEY location_id (location_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'update_holidays_table');
add_action('after_setup_theme', 'update_holidays_table');

// 2. Добавление верхнего уровня меню "Праздники"
function holidays_admin_menu() {
    add_menu_page(
        'Управление праздниками',
        'Праздники',
        'manage_options',
        'holidays',
        'display_holidays_page',
        'dashicons-calendar-alt', 
        7
    );

    add_submenu_page(
        'holidays',
        'Добавить праздник',
        'Добавить праздник',
        'manage_options',
        'add_holiday',
        'display_add_holiday_page'
    );

    add_submenu_page(
        null,
        'Редактировать праздник',
        'Редактировать праздник',
        'manage_options',
        'edit_holiday',
        'display_edit_holiday_page'
    );
}
add_action('admin_menu', 'holidays_admin_menu');

// 3. Страница со списком праздников с группировкой по храмам
function display_holidays_page() {
    global $wpdb;
    $holidays_table = $wpdb->prefix . 'holidays';
    $locations_table = $wpdb->prefix . 'locations';

    // Получаем все праздники с привязкой к храмам
    $results = $wpdb->get_results("
        SELECT h.*, l.name AS location_name, l.id AS loc_id
        FROM $holidays_table h
        LEFT JOIN $locations_table l ON h.location_id = l.id
        ORDER BY l.name, h.id
    ");

    // Группируем результаты по location_id
    $grouped = array();
    foreach ($results as $row) {
        $grouped[$row->loc_id]['name'] = $row->location_name;
        $grouped[$row->loc_id]['holidays'][] = $row;
    }

    ?>
    <div class="wrap">
        <h1 class="mb-4">Все праздники</h1>
        <a href="<?php echo admin_url('admin.php?page=add_holiday'); ?>" class="btn btn-success mb-3">Добавить новый праздник</a>

        <?php if (!empty($grouped)): ?>
            <?php foreach ($grouped as $location_id => $data): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title mb-0"><?php echo esc_html($data['name']); ?></h3>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($data['holidays'])): ?>
                            <table class="table table-striped table-bordered mb-0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Праздник</th>
                                        <th>Дата</th>
                                        <th class="text-center">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($data['holidays'] as $holiday): ?>
                                    <tr>
                                        <td><?php echo esc_html($holiday->id); ?></td>
                                        <td><?php echo esc_html($holiday->holiday_name); ?></td>
                                        <td>
                                            <?php
                                            if (!is_null($holiday->offset_from_easter)) {
                                                if ((int)$holiday->offset_from_easter === 0) {
                                                    echo "Пасха";
                                                } else {
                                                    echo ($holiday->offset_from_easter > 0 ? '+' : '') . $holiday->offset_from_easter . " дней от Пасхи";
                                                }
                                            } else {
                                                // Отображаем обычную дату
                                                $date_str = '';
                                                if (!empty($holiday->day) && !empty($holiday->month)) {
                                                    $date_str = $holiday->day . '.' . $holiday->month;
                                                    if (!empty($holiday->year)) {
                                                        $date_str .= '.' . $holiday->year;
                                                    }
                                                }
                                                echo $date_str ?: '–';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo admin_url('admin.php?page=edit_holiday&id=' . intval($holiday->id)); ?>" class="btn btn-primary btn-sm">Редактировать</a>
                                            <a href="<?php echo admin_url('admin-post.php?action=delete_holiday&id=' . intval($holiday->id) . '&_wpnonce=' . wp_create_nonce('delete_holiday_nonce')); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите удалить этот праздник?');">Удалить</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="p-3 mb-0">Нет праздников для данного храма.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card card-body">
                Праздники не найдены.
            </div>
        <?php endif; ?>
    </div>
    <?php
}

// 4. Страница добавления праздника
function display_add_holiday_page() {
    global $wpdb;
    $locations_table = $wpdb->prefix . 'locations';
    $locations = $wpdb->get_results("SELECT id, name FROM $locations_table ORDER BY name ASC");
    ?>
    <div class="wrap">
        <h1 class="mb-4">Добавить новый праздник</h1>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="card card-body">
            <input type="hidden" name="action" value="add_holiday_action">
            <?php wp_nonce_field('add_holiday_nonce'); ?>

            <!-- Скрытое поле для хранения выбранного location_id -->
            <input type="hidden" id="selected_location_id" name="location_id" value="">

            <div class="form-group">
                <label for="location_search">Название храма (поиск)</label>
                <input type="text" id="location_search" class="form-control" placeholder="Начните вводить название храма...">
                <datalist id="locations_datalist">
                    <?php foreach($locations as $loc): ?>
                        <option value="<?php echo esc_attr($loc->name); ?>" data-id="<?php echo $loc->id; ?>"></option>
                    <?php endforeach; ?>
                </datalist>
                <small class="text-muted">Выберите храм из появившегося списка.</small>
            </div>

            <!-- Старое поле select для храма (можно оставить для удобства или убрать) -->
            <div class="form-group">
                <label for="location_select">Или выберите храм из списка</label>
                <select name="location_select_id" id="location_select" class="form-control">
                    <option value="">-- Выберите храм --</option>
                    <?php foreach($locations as $loc): ?>
                        <option value="<?php echo $loc->id; ?>"><?php echo esc_html($loc->name); ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted">Если не нашли храм в поиске, можно выбрать вручную.</small>
            </div>

            <div class="form-group">
                <label for="holiday_name">Название праздника</label>
                <input name="holiday_name" id="holiday_name" type="text" class="form-control" required>
            </div>

            <h3>Дата</h3>
            <p>Укажите либо точную дату (год опционален), либо смещение от Пасхи.</p>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="year">Год (опционально)</label>
                    <input name="year" id="year" type="number" class="form-control" placeholder="Напр. 2024">
                </div>
                <div class="form-group col-md-4">
                    <label for="month">Месяц</label>
                    <input name="month" id="month" type="number" class="form-control" placeholder="1-12">
                </div>
                <div class="form-group col-md-4">
                    <label for="day">День</label>
                    <input name="day" id="day" type="number" class="form-control" placeholder="1-31">
                </div>
            </div>

            <div class="form-group">
                <label for="offset_from_easter">Смещение от Пасхи (целое число, 0 – Пасха, >0 – после, <0 – до). Если задано, фиксированная дата игнорируется.</label>
                <input name="offset_from_easter" id="offset_from_easter" type="number" class="form-control" placeholder="0 = Пасха, 1 = после, -2 = до">
            </div>

            <button type="submit" class="btn btn-primary">Добавить праздник</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const locationSearch = document.getElementById('location_search');
            const datalist = document.getElementById('locations_datalist');
            const hiddenLocationId = document.getElementById('selected_location_id');
            const locationSelect = document.getElementById('location_select');

            // Подключаем datalist к полю ввода
            locationSearch.setAttribute('list', 'locations_datalist');

            // При выборе из datalist найти соответствующий id
            locationSearch.addEventListener('input', function() {
                const val = locationSearch.value.toLowerCase();
                let found = false;
                for (let option of datalist.options) {
                    if (option.value.toLowerCase() === val) {
                        hiddenLocationId.value = option.getAttribute('data-id');
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    hiddenLocationId.value = '';
                }
            });

            // При изменении select, сбрасываем поле ввода
            locationSelect.addEventListener('change', function() {
                if (this.value) {
                    // Если выбран храм из select, выставляем hiddenLocationId
                    hiddenLocationId.value = this.value;
                    // Очищаем поле поиска
                    locationSearch.value = '';
                } else {
                    // Если ничего не выбрано
                    hiddenLocationId.value = '';
                }
            });
        });
    </script>
    <?php
}

// 5. Обработка добавления праздника
function handle_add_holiday_action() {
    if (
        !isset($_POST['_wpnonce']) ||
        !wp_verify_nonce($_POST['_wpnonce'], 'add_holiday_nonce')
    ) {
        wp_die('Ошибка проверки безопасности');
    }

    if (!current_user_can('manage_options')) {
        wp_die('Недостаточно прав');
    }

    // Попытаемся получить location_id из скрытого поля или из select
    $location_id = 0;
    if (!empty($_POST['location_id'])) {
        $location_id = intval($_POST['location_id']);
    } elseif (!empty($_POST['location_select_id'])) {
        $location_id = intval($_POST['location_select_id']);
    }

    if ($location_id <= 0) {
        wp_die('Не выбран храм. Пожалуйста, выберите храм из списка или через поиск.');
    }

    if (isset($_POST['holiday_name'])) {
        global $wpdb;
        $holidays_table = $wpdb->prefix . 'holidays';

        $holiday_name = sanitize_text_field($_POST['holiday_name']);
        $year = (isset($_POST['year']) && $_POST['year'] !== '') ? intval($_POST['year']) : null;
        $month = (isset($_POST['month']) && $_POST['month'] !== '') ? intval($_POST['month']) : null;
        $day = (isset($_POST['day']) && $_POST['day'] !== '') ? intval($_POST['day']) : null;
        $offset_from_easter = (isset($_POST['offset_from_easter']) && $_POST['offset_from_easter'] !== '') ? intval($_POST['offset_from_easter']) : null;

        $wpdb->insert(
            $holidays_table,
            array(
                'location_id' => $location_id,
                'holiday_name' => $holiday_name,
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'offset_from_easter' => $offset_from_easter,
            )
        );

        wp_redirect(admin_url('admin.php?page=holidays'));
        exit;
    } else {
        wp_die('Не заполнено название праздника.');
    }
}
add_action('admin_post_add_holiday_action', 'handle_add_holiday_action');

// 6. Страница редактирования праздника
function display_edit_holiday_page() {
    if (!isset($_GET['id'])) {
        wp_die('Отсутствует ID праздника.');
    }

    $id = intval($_GET['id']);

    global $wpdb;
    $holidays_table = $wpdb->prefix . 'holidays';
    $locations_table = $wpdb->prefix . 'locations';

    $holiday = $wpdb->get_row($wpdb->prepare("
        SELECT h.*, l.name as location_name
        FROM $holidays_table h
        LEFT JOIN $locations_table l ON h.location_id = l.id
        WHERE h.id = %d
    ", $id));

    if (!$holiday) {
        wp_die('Праздник не найден.');
    }

    $locations = $wpdb->get_results("SELECT id, name FROM $locations_table ORDER BY name ASC");
    ?>
    <div class="wrap">
        <h1 class="mb-4">Редактировать праздник: <?php echo esc_html($holiday->holiday_name); ?></h1>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" class="card card-body">
            <input type="hidden" name="action" value="edit_holiday_action">
            <input type="hidden" name="id" value="<?php echo esc_attr($id); ?>">
            <?php wp_nonce_field('edit_holiday_nonce'); ?>

            <input type="hidden" id="selected_location_id" name="location_id" value="<?php echo $holiday->location_id; ?>">

            <div class="form-group">
                <label for="location_search">Название храма (поиск)</label>
                <input type="text" id="location_search" class="form-control" placeholder="Начните вводить название храма..."
                       value="<?php echo esc_attr($holiday->location_name); ?>">
                <datalist id="locations_datalist">
                    <?php foreach($locations as $loc): ?>
                        <option value="<?php echo esc_attr($loc->name); ?>" data-id="<?php echo $loc->id; ?>"></option>
                    <?php endforeach; ?>
                </datalist>
                <small class="text-muted">Выберите храм из появившегося списка.</small>
            </div>

            <div class="form-group">
                <label for="location_select">Или выберите храм из списка</label>
                <select name="location_select_id" id="location_select" class="form-control">
                    <option value="">-- Выберите храм --</option>
                    <?php foreach($locations as $loc): ?>
                        <option value="<?php echo $loc->id; ?>" <?php selected($holiday->location_id, $loc->id); ?>><?php echo esc_html($loc->name); ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted">Если не нашли храм в поиске, можно выбрать вручную.</small>
            </div>

            <div class="form-group">
                <label for="holiday_name">Название праздника</label>
                <input name="holiday_name" id="holiday_name" type="text" class="form-control" value="<?php echo esc_attr($holiday->holiday_name); ?>" required>
            </div>

            <h3>Дата</h3>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="year">Год (опционально)</label>
                    <input name="year" id="year" type="number" class="form-control" value="<?php echo esc_attr($holiday->year); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="month">Месяц</label>
                    <input name="month" id="month" type="number" class="form-control" value="<?php echo esc_attr($holiday->month); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="day">День</label>
                    <input name="day" id="day" type="number" class="form-control" value="<?php echo esc_attr($holiday->day); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="offset_from_easter">Смещение от Пасхи (если указано, дата игнорируется)</label>
                <input name="offset_from_easter" id="offset_from_easter" type="number" class="form-control" value="<?php echo esc_attr($holiday->offset_from_easter); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const locationSearch = document.getElementById('location_search');
            const datalist = document.getElementById('locations_datalist');
            const hiddenLocationId = document.getElementById('selected_location_id');
            const locationSelect = document.getElementById('location_select');

            locationSearch.setAttribute('list', 'locations_datalist');

            locationSearch.addEventListener('input', function() {
                const val = locationSearch.value.toLowerCase();
                let found = false;
                for (let option of datalist.options) {
                    if (option.value.toLowerCase() === val) {
                        hiddenLocationId.value = option.getAttribute('data-id');
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    hiddenLocationId.value = '';
                }
            });

            locationSelect.addEventListener('change', function() {
                if (this.value) {
                    hiddenLocationId.value = this.value;
                    locationSearch.value = '';
                } else {
                    hiddenLocationId.value = '';
                }
            });
        });
    </script>
    <?php
}

// 7. Обработка редактирования праздника
function handle_edit_holiday_action() {
    if (
        !isset($_POST['_wpnonce']) ||
        !wp_verify_nonce($_POST['_wpnonce'], 'edit_holiday_nonce')
    ) {
        wp_die('Ошибка проверки безопасности');
    }

    if (!current_user_can('manage_options')) {
        wp_die('Недостаточно прав');
    }

    if (isset($_POST['id'], $_POST['holiday_name'])) {
        global $wpdb;
        $holidays_table = $wpdb->prefix . 'holidays';

        $id = intval($_POST['id']);

        // Определяем location_id
        $location_id = 0;
        if (!empty($_POST['location_id'])) {
            $location_id = intval($_POST['location_id']);
        } elseif (!empty($_POST['location_select_id'])) {
            $location_id = intval($_POST['location_select_id']);
        }

        if ($location_id <= 0) {
            wp_die('Не выбран храм. Пожалуйста, выберите храм из списка или через поиск.');
        }

        $holiday_name = sanitize_text_field($_POST['holiday_name']);
        $year = (isset($_POST['year']) && $_POST['year'] !== '') ? intval($_POST['year']) : null;
        $month = (isset($_POST['month']) && $_POST['month'] !== '') ? intval($_POST['month']) : null;
        $day = (isset($_POST['day']) && $_POST['day'] !== '') ? intval($_POST['day']) : null;
        $offset_from_easter = (isset($_POST['offset_from_easter']) && $_POST['offset_from_easter'] !== '') ? intval($_POST['offset_from_easter']) : null;

        $wpdb->update(
            $holidays_table,
            array(
                'location_id' => $location_id,
                'holiday_name' => $holiday_name,
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'offset_from_easter' => $offset_from_easter
            ),
            array('id' => $id)
        );

        wp_redirect(admin_url('admin.php?page=holidays'));
        exit;
    } else {
        wp_die('Не заполнены обязательные поля.');
    }
}
add_action('admin_post_edit_holiday_action', 'handle_edit_holiday_action');

// 8. Обработка удаления праздника
function handle_delete_holiday() {
    if (
        !isset($_GET['_wpnonce']) ||
        !wp_verify_nonce($_GET['_wpnonce'], 'delete_holiday_nonce')
    ) {
        wp_die('Ошибка проверки безопасности');
    }

    if (!current_user_can('manage_options')) {
        wp_die('Недостаточно прав');
    }

    if (isset($_GET['id'])) {
        global $wpdb;
        $holidays_table = $wpdb->prefix . 'holidays';
        $id = intval($_GET['id']);
        $wpdb->delete($holidays_table, array('id' => $id));
    }

    wp_redirect(admin_url('admin.php?page=holidays'));
    exit;
}
add_action('admin_post_delete_holiday', 'handle_delete_holiday');


// ===================== REST API для праздников =====================
function get_holidays_data() {
    global $wpdb;
    $holidays_table = $wpdb->prefix . 'holidays';
    $locations_table = $wpdb->prefix . 'locations';

    $results = $wpdb->get_results("
        SELECT h.*, l.name AS location_name, l.id AS loc_id
        FROM $holidays_table h
        LEFT JOIN $locations_table l ON h.location_id = l.id
        ORDER BY l.name, h.id
    ");

    $data = array();
    foreach ($results as $row) {
        if (!isset($data[$row->loc_id])) {
            $data[$row->loc_id] = array(
                'location_id' => $row->loc_id,
                'location_name' => $row->location_name,
                'holidays' => array(),
            );
        }

        $holiday_item = array(
            'id' => $row->id,
            'holiday_name' => $row->holiday_name,
            'year' => $row->year,
            'month' => $row->month,
            'day' => $row->day,
            'offset_from_easter' => $row->offset_from_easter,
        );
        $data[$row->loc_id]['holidays'][] = $holiday_item;
    }

    // Преобразуем ассоциативный массив в обычный индексированный
    $response = array_values($data);
    return rest_ensure_response($response);
}

add_action('rest_api_init', function () {
    register_rest_route('holidays/v1', '/list', array(
        'methods'  => 'GET',
        'callback' => 'get_holidays_data',
    ));
});


//конец





function convert_thumbnail_to_webp($image_src, $args) {
if (is_array($image_src)) {
// Исходный URL изображения
$original_url = $image_src[0];
// URL изображения в формате WebP
$webp_url = preg_replace('/\.(jpg|jpeg|png)$/', '.webp', $original_url);

// Проверяем, существует ли WebP-файл
$webp_path = ABSPATH . str_replace(home_url('/'), '', $webp_url);
if (file_exists($webp_path)) {
// Если существует, заменяем URL
$image_src[0] = $webp_url;
} else {
// Если нет, создаем WebP из исходного изображения
$original_path = ABSPATH . str_replace(home_url('/'), '', $original_url);
$source_image = imagecreatefromstring(file_get_contents($original_path));

if ($source_image) {
// Сохраняем WebP
imagewebp($source_image, $webp_path);
imagedestroy($source_image);

// Заменяем URL на WebP
$image_src[0] = $webp_url;
}
}
}

return $image_src;
}


// Подключение стилей для страницы входа WordPress
function custom_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/style-login.css' );
}
add_action( 'login_enqueue_scripts', 'custom_login_stylesheet' );


// скрыть метабоксы 
add_action('add_meta_boxes_post', 'remove_auto_cache_custom_meta_box_on_new_post');
function remove_auto_cache_custom_meta_box_on_new_post() {
    remove_meta_box('auto_cache_custom_meta_box', 'post', 'normal');
}
add_action('add_meta_boxes_post', 'remove_wpseo_meta_box');
function remove_wpseo_meta_box() {
    remove_meta_box('wpseo_meta', 'post', 'normal');
}

add_action('add_meta_boxes_post', 'remove_attachments_metabox');
function remove_attachments_metabox() {
    remove_meta_box('attachments', 'post', 'side');
}





add_action( 'edit_form_top', 'callback__edit_form_top' );
function callback__edit_form_top( $post ) {
    // Проверяем тип поста
    if ( 'book' !== $post->post_type ) {
        return; // Если тип не равен "book", просто выходим из функции
    }
	?>
	<div style="margin-top: 10px;padding: 15px;color: #fff;background: #673AB7;clear: both;">
		Тут надо указать святцы или святых или праздник. <b>Кому служат в этот день

</b>
	</div>
	<?php
}
// дочерка 
wp_enqueue_script(
    'my-ajax-script', 
    get_stylesheet_directory_uri() . '/js/my-ajax-script.js', 
    array('jquery'), 
    null, 
    true
);


// В functions.php или аналогичном файле
function my_ajax_enqueue_scripts() {
    wp_enqueue_script('my-ajax-script', get_template_directory_uri() . '/js/my-ajax-script.js', array('jquery'), null, true);
    wp_localize_script('my-ajax-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'my_ajax_enqueue_scripts');


function handle_date_range_search() {
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

    $query_args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
    );

    if ($start_date) {
        $query_args['date_query'][] = array(
            'after' => $start_date,
        );
    }

    if ($end_date) {
        $query_args['date_query'][] = array(
            'before' => $end_date,
            'inclusive' => true,
        );
    }

    $query = new WP_Query($query_args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <div class="col-lg-12 mb-12">
                <div class="drim">
                    <ul class="list-group list-group-flush drimtxt">
                        <li class="list-group-item h4">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </li>
                    </ul>
                    <div class="drimlin"></div>
                </div>
            </div>
            <?php
        }
    } else {
        echo 'Нет постов в этом диапазоне дат.';
    }

    wp_reset_postdata();

    wp_die(); // Завершаем выполнение, чтобы избежать вывода дополнительных данных
}

add_action('wp_ajax_date_range_search', 'handle_date_range_search');
add_action('wp_ajax_nopriv_date_range_search', 'handle_date_range_search');


//Новый посик
function enqueue_ajax_search_script() {
    wp_enqueue_script(
        'ajax-search-script',
        get_stylesheet_directory_uri() . '/js/ajax-search.js',
        array('jquery'),
        null,
        true
    );

    wp_localize_script(
        'ajax-search-script',
        'ajax_search_object',
        array('ajax_url' => admin_url('admin-ajax.php'))
    );
}

add_action('wp_enqueue_scripts', 'enqueue_ajax_search_script');

function handle_ajax_title_content_search() {
    $query = isset($_GET['query']) ? sanitize_text_field($_GET['query']) : '';

    if (!empty($query)) {
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            's' => $query, // 's' означает, что поиск будет происходить по заголовкам и содержимому
        );

        $search_query = new WP_Query($args);

        if ($search_query->have_posts()) {
            while ($search_query->have_posts()) {
                $search_query->the_post();
                ?>
                <div class="search-result-item">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </div>
                <?php
            }
        } else {
            echo 'Ничего не найдено.';
        }

        wp_reset_postdata();
    } else {
        echo 'Введите минимум 3 буквы для поиска.';
    }

    wp_die(); // Завершение запроса
}

add_action('wp_ajax_ajax_title_content_search', 'handle_ajax_title_content_search');
add_action('wp_ajax_nopriv_ajax_title_content_search', 'handle_ajax_title_content_search');

/**
 * Registers the "Слайдер на главной" custom post type.
 */
function mpcth_register_frontpage_slider_post_type() {
    $labels = array(
        'name'               => __( 'Слайдеры', 'mpcth' ),
        'singular_name'      => __( 'Слайдер', 'mpcth' ),
        'menu_name'          => __( 'Слайдер на главной', 'mpcth' ),
        'name_admin_bar'     => __( 'Слайдер', 'mpcth' ),
        'add_new'            => __( 'Добавить слайдер', 'mpcth' ),
        'add_new_item'       => __( 'Новый слайдер', 'mpcth' ),
        'new_item'           => __( 'Новый слайдер', 'mpcth' ),
        'edit_item'          => __( 'Редактировать слайдер', 'mpcth' ),
        'view_item'          => __( 'Просмотреть слайдер', 'mpcth' ),
        'all_items'          => __( 'Все слайдеры', 'mpcth' ),
        'search_items'       => __( 'Найти слайдер', 'mpcth' ),
        'not_found'          => __( 'Слайдеры не найдены', 'mpcth' ),
        'not_found_in_trash' => __( 'Слайдеры в корзине не найдены', 'mpcth' ),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => false,
        'show_in_rest'        => true,
        'menu_position'       => 21,
        'menu_icon'           => 'dashicons-images-alt2',
        'hierarchical'        => false,
        'supports'            => array( 'title' ),
        'has_archive'         => false,
        'rewrite'             => false,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
    );

    register_post_type( 'frontpage_slider', $args );
}
add_action( 'init', 'mpcth_register_frontpage_slider_post_type' );

/**
 * Returns an array of attachment IDs for the slider gallery.
 *
 * @param int $post_id Slider post ID.
 *
 * @return array
 */
function mpcth_get_frontpage_slider_image_ids( $post_id ) {
    $post_id = absint( $post_id );
    if ( ! $post_id ) {
        return array();
    }

    $ids = get_post_meta( $post_id, 'frontpage_slider_gallery', true );

    if ( empty( $ids ) ) {
        return array();
    }

    if ( is_string( $ids ) ) {
        $ids = array_filter( array_map( 'absint', explode( ',', $ids ) ) );
    } elseif ( is_array( $ids ) ) {
        $ids = array_filter( array_map( 'absint', $ids ) );
    } else {
        $ids = array();
    }

    return array_values( $ids );
}

/**
 * Returns attachment objects for the slider gallery.
 *
 * @param int|null $post_id Slider post ID. Defaults to current post in the loop.
 *
 * @return WP_Post[]
 */
function mpcth_get_frontpage_slider_images( $post_id = null ) {
    if ( null === $post_id ) {
        $post_id = get_the_ID();
    }

    $ids = mpcth_get_frontpage_slider_image_ids( $post_id );

    if ( empty( $ids ) ) {
        return array();
    }

    $query = array(
        'post_type'      => 'attachment',
        'post__in'       => $ids,
        'orderby'        => 'post__in',
        'posts_per_page' => -1,
    );

    return get_posts( $query );
}

/**
 * Registers admin meta boxes for the slider post type.
 */
function mpcth_frontpage_slider_add_meta_boxes() {
    add_meta_box(
        'frontpage-slider-gallery',
        __( 'Слайды галереи', 'mpcth' ),
        'mpcth_frontpage_slider_render_metabox',
        'frontpage_slider',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'mpcth_frontpage_slider_add_meta_boxes' );

/**
 * Outputs the gallery meta box markup.
 *
 * @param WP_Post $post Current post object.
 */
function mpcth_frontpage_slider_render_metabox( $post ) {
    wp_nonce_field( 'frontpage_slider_gallery_nonce', 'frontpage_slider_gallery_nonce' );

    $image_ids = mpcth_get_frontpage_slider_image_ids( $post->ID );
    $ids_value = $image_ids ? implode( ',', $image_ids ) : '';
    ?>
    <div class="frontpage-slider-meta">
        <p class="description"><?php esc_html_e( 'Добавьте изображения, перетаскивайте их для изменения порядка и сформируйте галерею для главного слайдера.', 'mpcth' ); ?></p>

        <div id="frontpage-slider-gallery" class="frontpage-slider-gallery" data-empty-text="<?php echo esc_attr__( 'Добавьте изображения, чтобы начать собирать слайды.', 'mpcth' ); ?>">
            <?php
            if ( $image_ids ) {
                foreach ( $image_ids as $attachment_id ) {
                    $thumbnail = wp_get_attachment_image( $attachment_id, 'thumbnail' );
                    if ( ! $thumbnail ) {
                        continue;
                    }
                    ?>
                    <div class="frontpage-slider-item" data-id="<?php echo esc_attr( $attachment_id ); ?>">
                        <span class="frontpage-slider-item__preview"><?php echo $thumbnail; ?></span>
                        <button type="button" class="frontpage-slider-remove button-link-delete" aria-label="<?php esc_attr_e( 'Удалить изображение', 'mpcth' ); ?>">
                            &times;
                        </button>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <p id="frontpage-slider-empty" class="frontpage-slider-empty <?php echo $image_ids ? 'is-hidden' : ''; ?>"><?php esc_html_e( 'Добавьте изображения, чтобы начать собирать слайды.', 'mpcth' ); ?></p>

        <input type="hidden" id="frontpage-slider-ids" name="frontpage_slider_ids" value="<?php echo esc_attr( $ids_value ); ?>" />

        <button type="button" class="button button-secondary" id="frontpage-slider-add" data-frame-title="<?php echo esc_attr__( 'Выберите изображения для слайдера', 'mpcth' ); ?>" data-frame-button="<?php echo esc_attr__( 'Добавить в слайдер', 'mpcth' ); ?>">
            <?php esc_html_e( 'Добавить слайды', 'mpcth' ); ?>
        </button>
    </div>
    <?php
}

/**
 * Saves slider gallery meta on post save.
 *
 * @param int $post_id Post ID.
 */
function mpcth_frontpage_slider_save_gallery( $post_id ) {
    if ( ! isset( $_POST['frontpage_slider_gallery_nonce'] ) || ! wp_verify_nonce( $_POST['frontpage_slider_gallery_nonce'], 'frontpage_slider_gallery_nonce' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( isset( $_POST['post_type'] ) && 'frontpage_slider' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    $ids = array();

    if ( isset( $_POST['frontpage_slider_ids'] ) && is_string( $_POST['frontpage_slider_ids'] ) ) {
        $raw_ids = explode( ',', sanitize_text_field( wp_unslash( $_POST['frontpage_slider_ids'] ) ) );
        $ids     = array_filter( array_map( 'absint', $raw_ids ) );
    }

    if ( ! empty( $ids ) ) {
        update_post_meta( $post_id, 'frontpage_slider_gallery', $ids );
    } else {
        delete_post_meta( $post_id, 'frontpage_slider_gallery' );
    }
}
add_action( 'save_post_frontpage_slider', 'mpcth_frontpage_slider_save_gallery' );

/**
 * Enqueues admin assets for the slider editor.
 *
 * @param string $hook Current admin page.
 */
function mpcth_frontpage_slider_admin_assets( $hook ) {
    global $post_type;

    if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        return;
    }

    if ( 'frontpage_slider' !== $post_type ) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_style( 'frontpage-slider-admin', get_stylesheet_directory_uri() . '/css/frontpage-slider-admin.css', array(), '1.0.0' );
    wp_enqueue_script( 'frontpage-slider-admin', get_stylesheet_directory_uri() . '/js/frontpage-slider-admin.js', array( 'jquery', 'jquery-ui-sortable' ), '1.0.0', true );

    wp_localize_script(
        'frontpage-slider-admin',
        'FrontpageSliderAdmin',
        array(
            'frameTitle'  => __( 'Выберите изображения для слайдера', 'mpcth' ),
            'frameButton' => __( 'Добавить в слайдер', 'mpcth' ),
            'removeLabel' => __( 'Удалить изображение', 'mpcth' ),
        )
    );
}
add_action( 'admin_enqueue_scripts', 'mpcth_frontpage_slider_admin_assets' );



// Функция для создания кастомного типа записи "Галерея"
function create_gallery_post_type() {
    $labels = array(
        'name'               => _x( 'Галереи', 'post type general name', 'your-text-domain' ),
        'singular_name'      => _x( 'Галерея', 'post type singular name', 'your-text-domain' ),
        'menu_name'          => _x( 'Галереи', 'admin menu', 'your-text-domain' ),
        'name_admin_bar'     => _x( 'Галерея', 'add new on admin bar', 'your-text-domain' ),
        'add_new'            => _x( 'Добавить новую', 'Галерея', 'your-text-domain' ),
        'add_new_item'       => __( 'Добавить новую галерею', 'your-text-domain' ),
        'new_item'           => __( 'Новая галерея', 'your-text-domain' ),
        'edit_item'          => __( 'Редактировать галерею', 'your-text-domain' ),
        'view_item'          => __( 'Просмотреть галерею', 'your-text-domain' ),
        'all_items'          => __( 'Все галереи', 'your-text-domain' ),
        'search_items'       => __( 'Искать галерею', 'your-text-domain' ),
        'not_found'          => __( 'Галереи не найдены', 'your-text-domain' ),
        'not_found_in_trash' => __( 'Галереи не найдены в корзине', 'your-text-domain' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'gallery' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail' ), // Добавьте 'thumbnail', чтобы иметь возможность использовать изображение для галереи
    );

    register_post_type( 'gallery', $args );
}

// Запускаем функцию при инициализации WordPress
add_action( 'init', 'create_gallery_post_type' );



function my_ajax_callback() {
    // Здесь должен быть код, который обрабатывает AJAX запрос
    // и возвращает ответ в формате JSON
}
add_action('wp_ajax_my_action', 'my_ajax_callback');
add_action('wp_ajax_nopriv_my_action', 'my_ajax_callback');

// Включите AJAX в WordPress
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-widget');
wp_enqueue_script('jquery-ui-accordion');
wp_enqueue_script('jquery-ui-autocomplete');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_script('jquery-ui-slider');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_script('jquery-ui-timepicker-addon');
wp_enqueue_script('jquery-ui-touch-punch');
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_script('jquery-ui-progressbar');
wp_enqueue_script('jquery-ui-resizable');
wp_enqueue_script('jquery-ui-selectable');
wp_enqueue_script('jquery-ui-draggable');
wp_enqueue_script('jquery-ui-droppable');
wp_enqueue_script('jquery-ui-effect');
wp_enqueue_script('jquery-ui-effect-blind');
wp_enqueue_script('jquery-ui-effect-bounce');
wp_enqueue_script('jquery-ui-effect-clip');
wp_enqueue_script('jquery-ui-effect-drop');
wp_enqueue_script('jquery-ui-effect-explode');
wp_enqueue_script('jquery-ui-effect-fade');
wp_enqueue_script('jquery-ui-effect-fold');
wp_enqueue_script('jquery-ui-effect-highlight');
wp_enqueue_script('jquery-ui-effect-pulsate');
wp_enqueue_script('jquery-ui-effect-scale');
wp_enqueue_script('jquery-ui-effect-shake');
wp_enqueue_script('jquery-ui-effect-slide');
wp_enqueue_script('jquery-ui-effect-transfer');
wp_enqueue_script('jquery-ui-slider-pips');
wp_enqueue_script('jquery-ui-slider-switch');
wp_enqueue_script('jquery-ui-spinner');
wp_enqueue_script('jquery-ui-tooltip');
wp_enqueue_script('jquery-ui-widget-mouse');
wp_enqueue_script('jquery-ui-position');
wp_enqueue_script('jquery-ui-widget-position');
wp_enqueue_script('jquery-ui-widget-resize');
wp_enqueue_script('jquery-ui-widget-draggable');
wp_enqueue_script('jquery-ui-widget-droppable');
wp_enqueue_script('jquery-ui-widget-mouse');
wp_enqueue_script('jquery-ui-widget-position');
wp_enqueue_script('jquery-ui-widget-resize');
wp_enqueue_script('jquery-ui-widget-draggable');
wp_enqueue_script('jquery-ui-widget-droppable');

// Включите скрипт AJAX фильтра
wp_enqueue_script('ajax-filter', get_template_directory_uri() . '/js/ajax-filter.js', array('jquery'), '1.0', true);

// Включите обработчик AJAX
wp_localize_script('ajax-filter', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));









function custom_posts_per_page($query) {
    if (is_archive() || is_search()) {
        $query->set('posts_per_page', 20); // Замените -1 на нужное вам количество записей на странице
    }
}
add_action('pre_get_posts', 'custom_posts_per_page');




//
//
//


////













//Запрет на обновления 
//плагинов 
add_filter( 'auto_update_plugin', '__return_false' );
//темы 
add_filter( 'auto_update_theme', '__return_false' );
//ядра 
//define( 'WP_AUTO_UPDATE_CORE', false );
//конец запретов 
// Register Script
// define('WP_ALLOW_REPAIR', true); //отладка таблицы
// Register Style
/*function slaiderJS() {

	wp_register_style( 'slaiderJS', '/sl/chiefslider.min.css', false, false );

}
add_action( 'wp_enqueue_scripts', 'slaiderJS' );

function custom_scripts() {

	wp_register_script( 'slaiderJS', '/sl/chiefslider.min.js', false, false, false );

}
add_action( 'wp_enqueue_scripts', 'custom_scripts' );
*/

add_filter('carousel_slider_load_scripts', 'carousel_slider_load_scripts');
function carousel_slider_load_scripts($load_scripts)
{
    return true;
}

add_action('admin_menu', 'menu_panel_grafik_rabot');
add_action('wp_enqueue_scripts', 'mpcth_child_enqueue_scripts');

function mpcth_child_enqueue_scripts()
{
    wp_enqueue_style('true_stili', get_stylesheet_directory_uri() . '/css/style.css');
    wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/bootstrap/css/bootstrap.min.css');
        wp_enqueue_style('slaider', get_stylesheet_directory_uri() . '/sl/chiefslider.min.css');

    wp_enqueue_style('tmplstyle', get_stylesheet_directory_uri() . '/css/style.css?v=5');
    wp_enqueue_script('mpc-child-main-js', get_stylesheet_directory_uri() . '/js/main.js', array('jquery', 'mpc-theme-plugins-js'), '1.0', true);
   /* wp_enqueue_script('respond', get_stylesheet_directory_uri() . '/js/respond.min.js');*/
    wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/bootstrap/js/bootstrap.min.js');

}

/**
 * Returns the default set of social links.
 *
 * @return array[]
 */
function mpcth_get_social_links() {
    $links = array(
        array(
            'id'     => 'vk',
            'icon'   => 'fa fa-vk',
            'url'    => 'https://vk.com/simbirskaya_mitropolia',
            'label'  => __( 'Мы ВКонтакте', 'mpcth' ),
            'target' => '_blank',
            'rel'    => 'noopener noreferrer',
        ),
        array(
            'id'     => 'telegram',
            'icon'   => 'fa fa-telegram',
            'url'    => 'https://t.me/simbmit',
            'label'  => __( 'Наш Telegram', 'mpcth' ),
            'target' => '_blank',
            'rel'    => 'noopener noreferrer',
        ),
        array(
            'id'     => 'mail',
            'icon'   => 'fa fa-envelope',
            'url'    => 'mailto:info@mitropolia-simbirsk.ru',
            'label'  => __( 'Электронная почта', 'mpcth' ),
            'target' => '_self',
            'rel'    => 'nofollow',
        ),
    );

    return apply_filters( 'mpcth_social_links', $links );
}

/**
 * Renders a group of social icons with consistent styling.
 *
 * @param array $args Optional arguments.
 *
 * @return string
 */
function mpcth_render_social_links( $args = array() ) {
    $defaults = array(
        'links'           => mpcth_get_social_links(),
        'container'       => 'div',
        'container_class' => 'mpcth-social-links',
        'link_class'      => 'mpcth-social-link',
        'echo'            => true,
    );

    $args = wp_parse_args( $args, $defaults );

    $links = array();
    foreach ( (array) $args['links'] as $link ) {
        if ( empty( $link['url'] ) ) {
            continue;
        }
        $links[] = $link;
    }

    if ( empty( $links ) ) {
        return '';
    }

    $container       = in_array( $args['container'], array( 'div', 'nav', 'p' ), true ) ? $args['container'] : 'div';
    $container_class = trim( $args['container_class'] );
    $link_class      = trim( $args['link_class'] );

    $items = array();
    foreach ( $links as $link ) {
        $icon  = ! empty( $link['icon'] ) ? '<i class="' . esc_attr( $link['icon'] ) . '" aria-hidden="true"></i>' : '';
        $label = ! empty( $link['label'] ) ? '<span class="screen-reader-text">' . esc_html( $link['label'] ) . '</span>' : '';
        $target = empty( $link['target'] ) ? '_blank' : $link['target'];
        $rel    = empty( $link['rel'] ) ? 'noopener noreferrer' : $link['rel'];

        $items[] = sprintf(
            '<a class="%1$s" href="%2$s" target="%3$s" rel="%4$s">%5$s%6$s</a>',
            esc_attr( $link_class ),
            esc_url( $link['url'] ),
            esc_attr( $target ),
            esc_attr( $rel ),
            $icon,
            $label
        );
    }

    $output = sprintf(
        '<%1$s class="%2$s">%3$s</%1$s>',
        esc_attr( $container ),
        esc_attr( $container_class ),
        implode( '', $items )
    );

    if ( $args['echo'] ) {
        echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        return '';
    }

    return $output;
}

/**
 * Shortcode handler for rendering social links anywhere on the site.
 *
 * Usage: [mpcth_social_links class="my-class" show="vk,telegram"]
 *
 * @param array  $atts Shortcode attributes.
 * @param string $content Optional content (unused).
 *
 * @return string
 */
function mpcth_social_links_shortcode( $atts, $content = null ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
    $atts = shortcode_atts(
        array(
            'class' => '',
            'show'  => '',
        ),
        $atts,
        'mpcth_social_links'
    );

    $links = mpcth_get_social_links();

    if ( ! empty( $atts['show'] ) ) {
        $requested = array_map( 'trim', explode( ',', $atts['show'] ) );
        $requested = array_filter( $requested );

        if ( $requested ) {
            $links = array_filter(
                $links,
                static function ( $link ) use ( $requested ) {
                    return isset( $link['id'] ) && in_array( $link['id'], $requested, true );
                }
            );
        }
    }

    $container_class = trim( 'mpcth-social-links ' . $atts['class'] );

    return mpcth_render_social_links(
        array(
            'links'           => $links,
            'container_class' => $container_class,
            'echo'            => false,
        )
    );
}
add_shortcode( 'mpcth_social_links', 'mpcth_social_links_shortcode' );



add_image_size('gallerythumb', 300, 200, true); // (cropped)
add_image_size('facethumb', 200, 250, true); // (cropped)
add_image_size('news', 300, 300, false); // (cropped)

function filter_jpeg_quality($quality)
{
    return 60;
}
add_filter('jpeg_quality', 'filter_jpeg_quality');

/* ---------------------------------------------------------------- */
/* Add post meta
  /* ---------------------------------------------------------------- */

function mpcth_add_meta2()
{
    echo '<time datetime="' . get_the_date('c') . '"><i class="fa fa-calendar"></i> ' . get_the_time(get_option('date_format')) . '</time>';
    //echo '<span class="mpcth-author"><span class="mpcth-static-text">' . __(' by', 'mpcth') . ' </span><a href="' . get_author_posts_url( get_the_author_meta( 'ID') ) . '">' . get_the_author() . '</a></span>';

    if (get_post_type() == 'mpc_portfolio')
        $categories = get_the_term_list(get_the_ID(), 'mpc_portfolio_cat', '', ', ', '');
    else
        $categories = get_the_category_list(__(', ', 'mpcth'));

    if ($categories)
        echo '<span class="mpcth-categories"><span class="mpcth-static-text"> | </span>' . $categories . '</span>';

    if (comments_open()) {
        echo '<span class="mpcth-comments"><a href="' . get_comments_link(get_the_ID()) . '" title="' . esc_attr(__('View post comments', 'mpcth')) . '" rel="comments">';
        comments_number(__('0 comments', 'mpcth'), __('1 comment', 'mpcth'), __('% comments', 'mpcth'));
        echo '</a></span>';
    }

    $video = get_field('video');
    if ($video) {
        echo '<a href="' . get_the_permalink() . '#video" class="video" ><i class="fas fa-video"></i> Видео</a>';
    }
}



// Добавление виджетов widgets

function my_widgets_init()
{

  register_sidebar(array(
        'name'          => esc_html__('Дата', 'data'),
        'id'            => 'data',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>'
    ));
   
    register_sidebar(array(
        'name'          => esc_html__('Сладер', 'zs'),
        'id'            => 'zs',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>'
    ));

    register_sidebar(array(
        'name'          => esc_html__('Сладер2', 'zs01'),
        'id'            => 'zs01',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>'
    ));



    register_sidebar(array(
        'name'          => esc_html__('Сладер4', 'zs04'),
        'id'            => 'zs04',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>'
    ));

    register_sidebar(array(
        'name'          => esc_html__('Сладер5', 'zs05'),
        'id'            => 'zs05',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>'
    ));

    register_sidebar(array(
        'name'          => esc_html__('Сладер6', 'zs06'),
        'id'            => 'zs06',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>'
    ));

    register_sidebar(array(
        'name'          => esc_html__('Сладер7', 'zs07'),
        'id'            => 'zs07',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>'
    ));

    register_sidebar(array(
        'name'          => esc_html__('Сладер8', 'zs08'),
        'id'            => 'zs08',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>'
    ));


  
   

   

  

    register_sidebar(array(
        'name'          => esc_html__('Видео', 'newszine'),
        'id'            => 'new6',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Указы', 'newszine'),
        'id'            => 'new7',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>',
    ));

  

    register_sidebar(array(
        'name'          => esc_html__('Монитиоринг', 'newszine'),
        'id'            => 'new9',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Календарь', 'newszine'),
        'id'            => 'new10',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Нев патр', 'newszine'),
        'id'            => 'new11',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('клоллик', 'newszine'),
        'id'            => 'new11',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Новости Патрихии', 'newszine'),
        'id'            => 'new01',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>'
    ));

    register_sidebar(array(
        'name'          => esc_html__('Патриарх', 'newszine'),
        'id'            => 'new02',
        'description'   => '',
        'before_widget' => '<div class="block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="block-title">',
        'after_title'   => '</h2>'
    ));

    
    register_sidebar(array(
        'name' => 'Footer1',
        'id' => 'footer1',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => 'Footer2',
        'id' => 'footer2',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));

    register_sidebar(array(
        'name' => 'Footer21',
        'id' => 'footer21',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));

    register_sidebar(array(
        'name' => 'Footer201',
        'id' => 'footer201',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));

    register_sidebar(array(
        'name' => 'Footer3',
        'id' => 'footer3',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    
}

add_action('widgets_init', 'my_widgets_init');

function raft_cat_archive($CATID)
{
    global $month, $wpdb;
    $li = '';
    $SQL = '';
    $SQLL = '';
    $result = '';
    $args = array(
        'numberposts' => 0,
        'showposts' => 1000,
        'offset' => 0,
        'category' => $CATID,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'include' => '',
        'exclude' => '',
        'meta_key' => '',
        'meta_value' => '',
        'post_type' => 'post',
        'post_mime_type' => '',
        'post_parent' => '',
        'post_status' => 'publish'
    );
    $posts_array = get_posts($args);
    if (is_array($posts_array) && count($posts_array) > 0) {
        foreach ($posts_array as $vp) {
            $SQL .= " ID='$vp->ID' OR";
        }
    }
    $SQL = substr($SQL, 0, strlen($SQL) - 3);
    $SQLL = "SELECT YEAR(post_date) AS 'Y', MONTH(post_date) AS 'M', ID, post_date, post_title, comment_status, guid, comment_count FROM wp_posts WHERE $SQL ORDER BY post_date DESC";
    $related_posts = $wpdb->get_results($SQLL);
    foreach ($related_posts as $post) {
        $PST[$post->Y][$post->M][] = array($post->post_title, $post->ID);
    }
    $MON = array(
        '1' => 'Январь',
        '2' => 'Февраль',
        '3' => 'Март',
        '4' => 'Апрель',
        '5' => 'Май',
        '6' => 'Июнь',
        '7' => 'Июль',
        '8' => 'Август',
        '9' => 'Сентябрь',
        '10' => 'Октябрь',
        '11' => 'Ноябрь',
        '12' => 'Декабрь'
    );
    foreach ($PST as $YS => $PL) {
        foreach ($PL as $MN => $PM) {
            $DDD = "" . $MON[$MN] . " $YS";
            foreach ($PM as $K => $POS) {
                $url = get_permalink($POS[1]);
                $arc_title = $POS[0];
                if ($arc_title)
                    $text = strip_tags($arc_title);
                $dd = get_the_time('d.m.Y', $POS[1]);
                $result .= "<div class='archnews'><div class='archnewsd'>" . $dd . "</div>\n";
                $result .= "<div class='archnewst'>| &nbsp; " . get_archives_link($url, $text, '') . "</div><div class='clear'></div></div>\n";
            }
            $G = "<div class='archnewslist'>$result</div><hr />";
            $result = '';
            $li .= "<div class='archnewsdate'>$DDD</div>$G";
        }
    }
    return "$li";
}

// Вывод галереи
// Фильтр, который переопределяет вывод стандартной галереи
add_filter('post_gallery', 'my_post_gallery', 10, 2);

function my_post_gallery($output, $attr) {
    global $post;

    // Обрабатываем параметры шорткода [gallery]
    if (isset($attr['orderby'])) {
        $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
        if (!$attr['orderby']) {
            unset($attr['orderby']);
        }
    }

    // Параметры галереи с умолчаниями
    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post->ID,
        'itemtag'    => 'dl',
        'icontag'    => 'dt',
        'captiontag' => 'dd',
        'columns'    => 3,
        'size'       => 'medium', // Считаем, что это ~ 300 px
        'include'    => '',
        'exclude'    => ''
    ), $attr));

    $id = intval($id);
    if ('RAND' === $order) {
        $orderby = 'none';
    }

    // Получаем список вложений
    if (!empty($include)) {
        $include = preg_replace('/[^0-9,]+/', '', $include);
        $_attachments = get_posts(array(
            'include'        => $include,
            'post_status'    => 'inherit',
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'order'          => $order,
            'orderby'        => $orderby
        ));

        $attachments = array();
        foreach ($_attachments as $val) {
            $attachments[$val->ID] = $val;
        }
    }

    if (empty($attachments)) {
        return '';
    }

    // Встраиваем стили прямо в HTML галереи
    // Основные моменты:
    //  - grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)) => по возможности плитки шириной от 300px;
    //  - @media max-width: 600px => показываем ровно 2 колонки на смартфонах;
    //  - aspect-ratio: 1 => сохраняем квадраты (300×300 примерно);
    //  - box-shadow => тень вокруг миниатюр;
    //  - max-width: 1600px => при слишком больших экранах ограничиваем в ~5 столбцов по 300px и отступы.
    $output = "
    <style>
     .kvgallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 5 столбцов на ПК */
            gap: 10px;
            max-width: 1500px; /* Ширина под 5 столбцов ~ 300px + отступы */
            margin: 0 auto;    /* Центровка всей галереи */
        }
        @media (max-width: 600px) {
            .kvgallery {
                grid-template-columns: repeat(2, 1fr); /* 2 столбца на мобильных */
                max-width: 100%;
            }
        }
        
        .gallery-tile {
            width: 100%; 
            aspect-ratio: 1 / 1; /* Гарантирует квадратную форму */
            overflow: hidden;
            border-radius: 10px; /* Скруглённые углы */
			
            
        }
        
        .gallery-tile img {
            width: 100%;
            height: 100%;
            object-fit: cover;   /* Обрезка/центрирование */
            border-radius: 10px; 
            display: block;
        }
        
        .clr {
            clear: both;
        }
    </style>

    <div class='clr'></div>
    <div class='kvgallery'>
    ";

    // Генерируем «плитки»
    foreach ($attachments as $att_id => $attachment) {
        // Получаем ссылку на medium-версию (около 300px)
        $thumb     = wp_get_attachment_image_src($att_id, $size);
        $img_large = wp_get_attachment_image_src($att_id, 'full');

        // Одна «плитка»
        $output .= "
            <div class='gallery-tile'>
                <a href='{$img_large[0]}' 
                   rel='gallery-" . get_the_ID() . "' 
                   class='mpcth-lightbox mpcth-lightbox-type-image gallitem'>
                    <img src='{$thumb[0]}' alt='' />
                </a>
            </div>
        ";
    }

    // Закрываем контейнер
    $output .= "</div><div class='clr'></div>";

    return $output;
}

// Пример шорткода для текущего подпункта меню (из вашего кода, если нужно)
function currentsubmenu_shortcode() {
    wp_nav_menu(array(
        'theme_location' => 'mpcth_menu',
        'sub_menu'       => true
    ));
}
add_shortcode('currentsubmenu', 'currentsubmenu_shortcode');

// конец


// filter_hook function to react on sub_menu flag
function my_wp_nav_menu_objects_sub_menu($sorted_menu_items, $args)
{
    if (isset($args->sub_menu)) {
        $root_id = 0;

        // find the current menu item
        foreach ($sorted_menu_items as $menu_item) {
            if ($menu_item->current) {
                // set the root id based on whether the current menu item has a parent or not
                $root_id = ($menu_item->menu_item_parent) ? $menu_item->menu_item_parent : $menu_item->ID;
                break;
            }
        }

        // find the top level parent
        if (!isset($args->direct_parent)) {
            $prev_root_id = $root_id;
            while ($prev_root_id != 0) {
                foreach ($sorted_menu_items as $menu_item) {
                    if ($menu_item->ID == $prev_root_id) {
                        $prev_root_id = $menu_item->menu_item_parent;
                        // don't set the root_id to 0 if we've reached the top of the menu
                        if ($prev_root_id != 0)
                            $root_id = $menu_item->menu_item_parent;
                        break;
                    }
                }
            }
        }

        $menu_item_parents = array();
        foreach ($sorted_menu_items as $key => $item) {
            // init menu_item_parents
            if ($item->ID == $root_id)
                $menu_item_parents[] = $item->ID;

            if (in_array($item->menu_item_parent, $menu_item_parents)) {
                // part of sub-tree: keep!
                $menu_item_parents[] = $item->ID;
            } else if (!(isset($args->show_parent) && in_array($item->ID, $menu_item_parents))) {
                // not part of sub-tree: away with it!
                unset($sorted_menu_items[$key]);
            }
        }

        return $sorted_menu_items;
    } else {
        return $sorted_menu_items;
    }
}

function submenuList_func($atts)
{
    $submenu =  wp_nav_menu(array(
        'theme_location' => 'mpcth_menu',
        'sub_menu' => true,
        'echo' => false,
        'menu_class' => 'submenupage',
    ));
    return $submenu;
}
add_filter('widget_title', 'accept_html_widget_title');
function accept_html_widget_title($mytitle)
{

    $mytitle = str_replace('[link', '<a', $mytitle);
    $mytitle = str_replace('[/link]', '</a>', $mytitle);
    $mytitle = str_replace(']', '>', $mytitle);
    return $mytitle;
}
add_shortcode('submenuList', 'submenuList_func');

add_action('admin_print_scripts', 'my_admin_term_filter', 99);
function my_admin_term_filter()
{
    $screen = get_current_screen();

    if ('post' !== $screen->base) return; // только для страницы редактирвоания любой записи
?>
    <script>
        jQuery(document).ready(function($) {
            var $categoryDivs = $('.categorydiv');

            $categoryDivs.prepend('<input type="search" class="fc-search-field" placeholder="фильтр..." style="width:100%" />');

            $categoryDivs.on('keyup search', '.fc-search-field', function(event) {

                var searchTerm = event.target.value,
                    $listItems = $(this).parent().find('.categorychecklist li');

                if ($.trim(searchTerm)) {
                    $listItems.hide().filter(function() {
                        return $(this).text().toLowerCase().indexOf(searchTerm.toLowerCase()) !== -1;
                    }).show();
                } else {
                    $listItems.show();
                }
            });
        });
    </script>
<?php
}

if (!current_user_can('edit_users')) {
    add_filter('auto_update_core', '__return_false');   // обновление ядра

    add_filter('pre_site_transient_update_core', '__return_null');
}

add_action('admin_print_footer_scripts', 'hide_tax_metabox_tabs_admin_styles', 99);
function hide_tax_metabox_tabs_admin_styles()
{
    $cs = get_current_screen();
    if ($cs->base !== 'post' || empty($cs->post_type)) return; // не страница редактирования записи
?>
    <style>
        .postbox div.tabs-panel {
            max-height: 1200px;
            border: 0;
        }

        .category-tabs {
            display: none;
        }
    </style>
<?php
}

add_filter('transient_update_plugins', 'update_active_plugins');    // Hook for 2.8.+
//add_filter('option_update_plugins', 'update_active_plugins');    // Hook for 2.7.x
function update_active_plugins($value = '')
{
    /*
	The $value array passed in contains the list of plugins with time
	marks when the last time the groups was checked for version match
	The $value->reponse node contains an array of the items that are
	out of date. This response node is use by the 'Plugins' menu
	for example to indicate there are updates. Also on the actual
	plugins listing to provide the yellow box below a given plugin
	to indicate action is needed by the user.
	*/
    if ((isset($value->response)) && (count($value->response))) {

        // Get the list cut current active plugins
        $active_plugins = get_option('active_plugins');
        if ($active_plugins) {

            //  Here we start to compare the $value->response
            //  items checking each against the active plugins list.
            foreach ($value->response as $plugin_idx => $plugin_item) {

                // If the response item is not an active plugin then remove it.
                // This will prevent WordPress from indicating the plugin needs update actions.
                if (!in_array($plugin_idx, $active_plugins))
                    unset($value->response[$plugin_idx]);
            }
        } else {
            // If no active plugins then ignore the inactive out of date ones.
            foreach ($value->response as $plugin_idx => $plugin_item) {
                unset($value->response);
            }
        }
    }
    return $value;
}

/**
 * Allow SVG files in Media Library.
 */
function extra_mime_types($mimes)
{

    $mimes['svg'] = 'image/svg+xml';

    return $mimes;
}
add_filter('upload_mimes', 'extra_mime_types');

// Стили для TinyMCE редактора
// Нужно создать файл 'editor-styles.css' в папке темы
add_action('current_screen', 'my_theme_add_editor_styles');
function my_theme_add_editor_styles()
{
    add_editor_style('editor-styles.css');
}

## CSS для страницы входа (login)
## Нужно создать файл 'wp-login.css' в папке темы
add_action('login_head', 'my_loginCSS');
function my_loginCSS()
{
    echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/wp-login.css"/>';
}

if (extension_loaded('zlib') && ini_get('output_handler') != 'ob_gzhandler') {
    add_action('wp', function () {
        @ob_end_clean();
        @ini_set('zlib.output_compression', 'on');
    });
}

function do_excerpt($string, $word_limit)
{
    $words = explode(' ', $string, ($word_limit + 1));
    if (count($words) > $word_limit)
        array_pop($words);
    echo implode(' ', $words) . ' ...';
}

function getPostViews($postID)
{
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 просмотров";
    }
    return $count . ' просмотров';
}
function setPostViews($postID)
{
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}


/* Дополнительные сортируемые колонки для постов в админке 
------------------------------------------------------------------------ */
// создаем новую колонку
add_filter('manage_book_posts_columns', 'add_views_column', 4);
function add_views_column($columns)
{
    // удаляем колонку Автор
    //unset($columns['author']);

    // вставляем в нужное место - 3 - 3-я колонка
    $out = array();
    foreach ($columns as $col => $name) {
        if (++$i == 3)
            $out['mesto_provedeniya'] = 'Храм';
        $out[$col] = $name;
    }

    return $out;
}
// заполняем колонку данными -  wp-admin/includes/class-wp-posts-list-table.php
add_filter('manage_book_posts_custom_column', 'fill_views_column', 5, 2);
function fill_views_column($colname, $post_id)
{
    if ($colname === 'mesto_provedeniya') {
        echo get_post_meta($post_id, 'mesto_provedeniya', 1);
    }
}

// подправим ширину колонки через css
add_action('admin_head', 'add_views_column_css');
function add_views_column_css()
{
    if (get_current_screen()->base == 'edit')
        echo '<style type="text/css">.column-views{width:10%;}</style>';
}

// добавляем возможность сортировать колонку
add_filter('manage_edit-post_sortable_columns', 'add_views_sortable_column');
function add_views_sortable_column($sortable_columns)
{
    $sortable_columns['mesto_provedeniya'] = 'views_views';

    return $sortable_columns;
}

// изменяем запрос при сортировке колонки
add_filter('pre_get_posts', 'add_column_views_request');
function add_column_views_request($object)
{
    if ($object->get('orderby') != 'mesto_provedeniya')
        return;

    $object->set('meta_key', 'mesto_provedeniya');
    $object->set('orderby', 'meta_value_num');
}




