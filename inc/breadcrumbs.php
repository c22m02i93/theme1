<?php

//breadcrumb
function kv_breadcrumb() {
    $post = get_post();
    $pagelink = '';
    ob_start();
    $separator = '';
    ?>

    <ol class="breadcrumb">
        <li>
            <a href="/" class="breadcrumb_home">Главная</a><?php echo $separator; ?>
        </li>


        <?php if (is_tag()) { ?>
            <li>Архив по тэгу <?php
                single_tag_title();
                ?>
            </li>
        <?php } elseif (is_day()) { ?>
            <li><?php esc_html_e('Материалы за', 'mpcth') ?> <?php the_time('F jS, Y'); ?></li>
        <?php } elseif (is_month()) { ?>
            <li><?php esc_html_e('Материалы за ', 'mpcth') ?> <?php the_time('F, Y'); ?></li>
        <?php } elseif (is_year()) { ?>
            <li><?php esc_html_e('Материалы за', 'mpcth') ?> <?php the_time('Y'); ?></li>
        <?php } elseif (is_search()) { ?>
            <li><?php esc_html_e('Поиск по', 'mpcth') ?> <?php the_search_query() ?></li>
        <?php } elseif (is_single()) { ?>
            <?php
            $category = get_the_category();
            if ($category) {

                echo get_category_parents2($category[0]->cat_ID);

                //echo ('<li><a href="' . esc_url($catlink) . '">' . esc_html($category[0]->cat_name) . '</a> ' . $separator . '</li>');
            }

            // Вывод дочерней страницы в зависимости от типа записи
            //var_dump(get_post_type($post));

            switch (get_post_type($post)) {
                case 'obruch' : $pagelink = 1745;
                    break;
                case 'docs' : $pagelink = 1796;
                    break;
                case 'gallerys' : $pagelink = 1761;
                    break;
            }
            if ($pagelink != '')
                echo '<li><a href="' . get_permalink($pagelink) . '" >' . wp_trim_words(get_the_title($pagelink), 2) . '</a></li>';
            echo '<li>' . wp_trim_words(get_the_title(), 5) . '</li>';
            ?>
            <?php
        } elseif (is_category()) {
            $current_cat = get_query_var('cat');
            echo get_category_parents2($current_cat);
        } elseif (is_tax()) {
            ?>
            <?php
            $zee_taxonomy_links = array();
            $zee_term = get_queried_object();
            $zee_term_parent_id = $zee_term->parent;
            $zee_term_taxonomy = $zee_term->taxonomy;

            while ($zee_term_parent_id) {
                $zee_current_term = get_term($zee_term_parent_id, $zee_term_taxonomy);
                $zee_taxonomy_links[] = '<li><a href="' . esc_url(get_term_link($zee_current_term, $zee_term_taxonomy)) . '" title="' . esc_attr($zee_current_term->name) . '">' . esc_html($zee_current_term->name) . '</a></li>';
                $zee_term_parent_id = $zee_current_term->parent;
            }

            if (!empty($zee_taxonomy_links))
                echo implode(' <span class="raquo">/</span> ', array_reverse($zee_taxonomy_links)) . ' <span class="raquo">/</span> ';

            echo '<li>' . esc_html(wp_trim_words($zee_term->name, 5)) . '</li>';
        } elseif (is_author()) {
            global $wp_query;
            $curauth = $wp_query->get_queried_object();

            esc_html_e('Posts by ', 'mpcth');
            echo ' ', $curauth->nickname;
        } elseif (is_page()) {
            echo '<li>' . get_the_title() . '</li>';
        } elseif (is_home()) {
            esc_html_e('Blog', 'mpcth');
        }
        ?>  

    </ol>
    <?php
    return ob_get_clean();
}
