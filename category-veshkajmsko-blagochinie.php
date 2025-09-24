<?php

/**
 * The Page base for MPC Themes
 
 * Displays single page.
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */

get_header(); ?>
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

            <?php
                    $args = array(
                        'category__in' => 61,
                        'post_type'      => 'post',
                        'posts_per_page' => 16,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    );
                    $q = new WP_Query($args);
                    ?>
            <?php if ($q->have_posts()) : ?>
            <?php while ($q->have_posts()) : $q->the_post(); ?>


            
                <div class="col-lg-4 mb-4 d-flex align-items-stretch">
                    <div class="card">
                        <a class="card-img-top" img" href="<?php the_permalink() ?>">
                            <?php the_post_thumbnail(); ?>
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </h5>
                            <p class="card-text"> <?= get_field("address"); ?>

                            </p>
                        </div>


                    </div>
                    
                </div>
                
                <?php endwhile; ?>
                <?php endif; ?>
                <div id="mpcth_pagination">
                <?php echo paginate_links( [
	'base'    => user_trailingslashit( wp_normalize_path( get_permalink() .'/%#%/' ) ),
	'current' => max( 1, get_query_var( 'page' ) ),
	'total'   => $q->max_num_pages,
] );?>
            </div>
        </div>
      
    </div>
</div>





<?php get_footer();