$args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'tax_query' => array(
        array(
            'taxonomy' => 'category',
            'field' => 'term_id',
            'terms' => get_queried_object_id 90,
        ),
    ),
    'date_query' => array(
        array(
            'after' => $start_date,
            'before' => $end_date,
        ),
    ),
);


$query = new WP_Query( $args );

// Ваш код для вывода результатов
if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $query->the_post();
        $month = get_the_date('F Y');
        echo '<h3>'.$month.'</h3>';
        the_content();
    }
    wp_reset_postdata();
} else {
    echo 'No posts found';
}