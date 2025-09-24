<?php
/**
 * Template Name: Галереи с фильтром (3 в ряд)
 * Описание: Шаблон, совмещающий структуру «Монашествующие» и функционал галерей с фильтром, с исправленной пагинацией.
 *
 * Displays single page with AJAX фильтрацией галерей.
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */

global $paged;
if ( empty($paged) ) {
    $paged = 1;
}

get_header();
?>

<div id="mpcth_main">
    <?php dynamic_sidebar("Datatim"); ?>
    <?php mpcth_print_blog_archives_custom_header(); ?>

    <div id="mpcth_main_container">
        <?php get_sidebar(); ?>

        <div class="container-fluid">
            <div class="row">
                <header id="mpcth_archive_header">
                    <?php mpcth_breadcrumbs(); ?>
                    <h1 id="mpcth_archive_title" class="zagolovok_straniz mpcth-deco-header">
                        <?php the_title(); ?>
                    </h1>
                </header>

                <!-- Блок с фильтрами -->
                <div class="col-12">
                    <div class="mb-4">
                        <div id="year-filter" class="mb-3">
                            <?php
                            global $wpdb;
                            $years = $wpdb->get_col("
                                SELECT DISTINCT YEAR(post_date) as year
                                FROM {$wpdb->posts}
                                WHERE post_type = 'post'
                                  AND post_status = 'publish'
                                  AND post_content LIKE '%[gallery%'
                                ORDER BY post_date DESC
                            ");
                            if ( $years ) {
                                foreach ( $years as $year ) {
                                    echo '<button type="button" class="btn btn-primary year-button mr-4 mb-4" style="margin-right:20px;">'
                                         . esc_html( $year )
                                         . '</button>';
                                }
                            }
                            ?>
                        </div>
                        <div id="date-range-filter" class="form-inline mb-3">
                            <div class="form-group mr-2 mb-2">
                                <label for="start-date" class="mr-2">Начало:</label>
                                <input type="date" id="start-date" name="start_date" class="form-control">
                            </div>
                            <div class="form-group mr-2 mb-2">
                                <label for="end-date" class="mr-2">Конец:</label>
                                <input type="date" id="end-date" name="end_date" class="form-control">
                            </div>
                            <button type="button" id="filter-date-range" class="btn btn-primary mb-2">Фильтр</button>
                        </div>
                    </div>
                </div>

                <!-- Основной контейнер для галерей -->
                <div id="galleries-container" class="col-12">
                    <?php
                    // Начальная выборка постов
                    $args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 16,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'paged'          => $paged,
                    );
                    $query = new WP_Query( $args );
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
                                                $webp_thumbnail_url = function_exists('otf_webp_get_thumbnail') ? otf_webp_get_thumbnail( $attachment_id, $width, $height, $crop, $rotate_angle ) : false;
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
                    ?>
                </div>

                <!-- Пагинация -->
                <div id="mpcth_pagination">
                    <?php
                    if ( function_exists('mpcth_display_load_more') && $blog_load_more ) {
                        mpcth_display_load_more( $query );
                    } elseif ( function_exists('mpcth_display_pagination') ) {
                        mpcth_display_pagination( $query );
                    }
                    ?>
                </div>
            </div><!-- .row -->
        </div><!-- .container-fluid -->
    </div><!-- #mpcth_main_container -->
</div><!-- #mpcth_main -->

<!-- JS для AJAX-фильтра -->
<script>
jQuery(document).ready(function($) {
    function updateGalleries(data) {
        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(response) {
                if(response.success) {
                    $('#galleries-container').html(response.data.content);
                    $('#mpcth_pagination').html(response.data.pagination);
                } else {
                    console.error("Ошибка: " + response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Ошибка: " + status + " " + error);
            }
        });
    }

    // Фильтрация по году
    $('.year-button').on('click', function(e) {
        e.preventDefault();
        var year = $(this).text();
        updateGalleries({
            action: 'filter_galleries_by_year',
            year: year,
            paged: 1
        });
    });

    // Фильтрация по диапазону дат
    $('#filter-date-range').on('click', function(e) {
        e.preventDefault();
        var start_date = $('#start-date').val();
        var end_date   = $('#end-date').val();
        updateGalleries({
            action: 'filter_galleries_by_date_range',
            start_date: start_date,
            end_date: end_date,
            paged: 1
        });
    });

    // Обработка клика по ссылкам пагинации (делегирование события)
    $(document).on('click', '.ajax-pagination', function(e) {
        e.preventDefault();
        var paged = $(this).data('paged');
        var action = $(this).data('action');
        var data = {
            action: action,
            paged: paged
        };
        if(action === 'filter_galleries_by_year') {
            data.year = $(this).data('year');
        } else if(action === 'filter_galleries_by_date_range') {
            data.start_date = $(this).data('start_date');
            data.end_date   = $(this).data('end_date');
        }
        updateGalleries(data);
    });
});
</script>

<?php get_footer(); ?>
