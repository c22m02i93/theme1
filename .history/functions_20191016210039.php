<?php

add_action('wp_enqueue_scripts', 'mpcth_child_enqueue_scripts');

function mpcth_child_enqueue_scripts() {
    wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/bootstrap/css/bootstrap.min.css');
    wp_enqueue_style('owl', get_stylesheet_directory_uri() . '/owl/owl.carousel.css');
    wp_enqueue_style('tmplstyle', get_stylesheet_directory_uri() . '/css/style.css');
    wp_enqueue_script('mpc-child-main-js', get_stylesheet_directory_uri() . '/js/main.js', array('jquery', 'mpc-theme-plugins-js'), '1.0', true);
    wp_enqueue_script('respond', get_stylesheet_directory_uri() . '/js/respond.min.js');
    wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/bootstrap/js/bootstrap.min.js');
    wp_enqueue_script('owl', get_stylesheet_directory_uri() . '/owl/owl.carousel.min.js');
}

add_image_size('gallerythumb', 300, 200, true); // (cropped)
add_image_size('facethumb', 200, 250, true); // (cropped)


function filter_jpeg_quality( $quality ) {  
	return 60;
}
add_filter( 'jpeg_quality', 'filter_jpeg_quality' );

/* ---------------------------------------------------------------- */
/* Add post meta
  /* ---------------------------------------------------------------- */

function mpcth_add_meta2() {
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

require get_stylesheet_directory() . '/inc/breadcrumbs.php';

//  FAVICON Фавикон add a favicon to your PNG 64x64

function blog_favicon() {
    echo '<link rel="Shortcut Icon" type="image/x-icon" href="' . get_stylesheet_directory_uri() . '/images/favicon.png" />';
}

// add_action('wp_head', 'blog_favicon');

// СВОЙ вывод подкатегорий

function get_category_parents2($id, $link = false, $separator = '/', $nicename = false, $visited = array()) {
    $separator = '<span class="sep">/</span>';
    $chain = '';
    $parent = get_term($id, 'category');
    if (is_wp_error($parent))
        return $parent;

    if ($nicename)
        $name = $parent->slug;
    else
        $name = $parent->name;

    if ($parent->parent && ( $parent->parent != $parent->term_id ) && !in_array($parent->parent, $visited)) {
        $visited[] = $parent->parent;
        $chain .= get_category_parents2($parent->parent, $link, $separator, $nicename, $visited);
    }


    $chain .= '<li><a href="' . esc_url(get_category_link($parent->term_id)) . '" title="' . $name . '" >' . wp_trim_words($name, 2) . '</a></li>';

    return $chain;
}

// Добавление виджетов widgets

function my_widgets_init() {
    register_sidebar(array(
        'name' => __('Слайдер'),
        'id' => 'slider',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Анонсы'),
        'id' => 'anons',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<div class="widgettitle" ><h3><a href="/category/novosti-media/anonsy/" >',
        'after_title' => ' </a></h3></div>',
    ));
    register_sidebar(array(
        'name' => __('Новости'),
        'id' => 'news',
        'description' => '',
        'before_widget' => '<div class="news" >',
        'after_widget' => '</div>',
        'before_title' => '<div class="widgettitle" ><h3>',
        'after_title' => '</h3></div>',
    ));
    register_sidebar(array(
        'name' => __('Календарь новостей'),
        'id' => 'calendar',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<div class="widgettitle" ><h3>',
        'after_title' => '</h3></div>',
    ));
    register_sidebar(array(
        'name' => __('Православный календарь'),
        'id' => 'pravcalendar',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<div class="widgettitle" ><h3>',
        'after_title' => '</h3></div>',
    ));
    register_sidebar(array(
        'name' => __('Footer1'),
        'id' => 'footer1',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Footer2'),
        'id' => 'footer2',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Footer3'),
        'id' => 'footer3',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Баннер на главной 1'),
        'id' => 'frontpagebanner1',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ));
    register_sidebar(array(
        'name' => __('Блок 2'),
        'id' => 'block2',
        'description' => '',
        'before_widget' => '<div class="col-sm-6">',
        'after_widget' => '</div>',
        'before_title' => '<div class="widgettitle" ><h3>',
        'after_title' => '</h3></div>'
    ));     
    register_sidebar(array(
        'name' => __('Блок 2-2'),
        'id' => 'block22',
        'description' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<div class="widgettitle" ><h3>',
        'after_title' => '</h3></div>'
    ));     
    register_sidebar(array(
        'name' => __('Блок 3'),
        'id' => 'block3',
        'description' => '',
        'before_widget' => '<div class="col-sm-6">',
        'after_widget' => '</div>',
        'before_title' => '<div class="widgettitle" ><h3>',
        'after_title' => '</h3></div>'
    ));    
}

add_action('widgets_init', 'my_widgets_init');

function raft_cat_archive($CATID) {
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
        'post_status' => 'publish');
    $posts_array = get_posts($args);
    if (is_array($posts_array) && count($posts_array) > 0) {
        foreach ($posts_array as $vp) {
            $SQL.= " ID='$vp->ID' OR";
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
        '12' => 'Декабрь');
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
            $li.="<div class='archnewsdate'>$DDD</div>$G";
        }
    }
    return "$li";
}

// Вывод галереи

add_filter('post_gallery', 'my_post_gallery', 10, 2);

function my_post_gallery($output, $attr) {
    global $post;

    if (isset($attr['orderby'])) {
        $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
        if (!$attr['orderby'])
            unset($attr['orderby']);
    }

    extract(shortcode_atts(array(
        'order' => 'ASC',
        'orderby' => 'menu_order ID',
        'id' => $post->ID,
        'itemtag' => 'dl',
        'icontag' => 'dt',
        'captiontag' => 'dd',
        'columns' => 3,
        'size' => 'thumbnail',
        'include' => '',
        'exclude' => ''
                    ), $attr));

    $id = intval($id);
    if ('RAND' == $order)
        $orderby = 'none';

    if (!empty($include)) {
        $include = preg_replace('/[^0-9,]+/', '', $include);
        $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

        $attachments = array();
        foreach ($_attachments as $key => $val) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    }

    if (empty($attachments))
        return '';

    // Here's your actual output, you may customize it to your need
    $output = "<div class='clr' ></div><div class=\"kvgallery\"><div class='row'  >";
    $i = 1;
    // Now you loop through each attachment
    foreach ($attachments as $id => $attachment) {
        // Fetch the thumbnail (or full image, it's up to you)
//      $img = wp_get_attachment_image_src($id, 'medium');
//      $img = wp_get_attachment_image_src($id, 'my-custom-image-size');
        $img = wp_get_attachment_image_src($id, 'medium');
        $imglarge = wp_get_attachment_image_src($id, 'large');
        $output .= "<div class='col-sm-4' ><a href='$imglarge[0]' rel='ral" . get_the_ID() . "' class='mpcth-lightbox mpcth-lightbox-type-image gallitem' ><img src=\"{$img[0]}\" width=\"{$img[1]}\" height=\"{$img[2]}\" alt=\"\" /></a></div>";
        $i++;
        if ($i > 3) {
            $output .= "</div><div class='row' >";
            $i = 1;
        }
    }


    $output .= "</div></div><div class='clr'></div>";

    return $output;
}

function currentsubmenu_shortcode() {
    wp_nav_menu(array(
        'theme_location' => 'mpcth_menu',
        'sub_menu' => true
    ));
}

add_shortcode('currentsubmenu', 'currentsubmenu_shortcode');

// add hook
add_filter('wp_nav_menu_objects', 'my_wp_nav_menu_objects_sub_menu', 10, 2);

// filter_hook function to react on sub_menu flag
function my_wp_nav_menu_objects_sub_menu($sorted_menu_items, $args) {
    if (isset($args->sub_menu)) {
        $root_id = 0;

        // find the current menu item
        foreach ($sorted_menu_items as $menu_item) {
            if ($menu_item->current) {
                // set the root id based on whether the current menu item has a parent or not
                $root_id = ( $menu_item->menu_item_parent ) ? $menu_item->menu_item_parent : $menu_item->ID;
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
            } else if (!( isset($args->show_parent) && in_array($item->ID, $menu_item_parents) )) {
                // not part of sub-tree: away with it!
                unset($sorted_menu_items[$key]);
            }
        }

        return $sorted_menu_items;
    } else {
        return $sorted_menu_items;
    }
}