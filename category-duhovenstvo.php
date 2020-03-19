<?php
/**
 * The Archive base for MPC Themes
 *
 * Displays all archive posts.
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */
get_header();

global $page_id;
global $paged;
global $wp_query;
global $mpcth_options;

$query = $wp_query;

$blog_layout = $mpcth_options['mpcth_archive_style'];

if (get_field('mpc_blog_layout'))
    $blog_layout = get_field('mpc_blog_layout');

$blog_columns = 3;
if (get_field('mpc_blog_columns'))
    $blog_columns = get_field('mpc_blog_columns');

if ($blog_layout == 'small') {

    function mpcth_excerpt_length($length) {
        return 125;
    }

    add_filter('excerpt_length', 'mpcth_excerpt_length', 999);
}
if ($blog_layout == 'masonry' || $blog_layout == 'masonry_load_more') {

    function mpcth_excerpt_length($length) {
        return 40;
    }

    add_filter('excerpt_length', 'mpcth_excerpt_length', 999);
}

$blog_load_more = false;
if ($blog_layout == 'masonry_load_more') {
    $blog_layout = 'masonry';
    $blog_load_more = true;
}

//if (isset($mpcth_options['mpcth_enable_large_archive_thumbs']) && $mpcth_options['mpcth_enable_large_archive_thumbs']) $layout = 'full';
?>

<div id="mpcth_main">
  <div id="mpcth_main_container">
    <?php get_sidebar(); ?>
    <div id="mpcth_content_wrap"
      class="<?php if ($blog_layout == 'masonry') echo 'mpcth-masonry-blog' ?> <?php if ($blog_load_more) echo 'mpcth-load-more' ?>">
      <header id="mpcth_archive_header">
        <?php mpcth_breadcrumbs(); ?>
        <h1 id="mpcth_archive_title" class="mpcth-deco-header"><?php echo single_cat_title('', false); ?></h1>
      </header>
      <div id="mpcth_content"
        class="mpcth-blog-layout-<?php echo $blog_layout; ?><?php if ($blog_layout == 'masonry') echo ' mpcth-blog-columns-' . $blog_columns; ?>">
        <?php
                $cat_id = get_query_var('cat');
                echo category_description($cat_id);
                $args = array(
                    'show_option_all' => '',
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'style' => 'list',
                    'show_count' => 0,
                    'hide_empty' => 0,
                    'use_desc_for_title' => 1,
                    'child_of' => $cat_id,
                    'feed' => '',
                    'feed_type' => '',
                    'feed_image' => '',
                    'exclude' => '',
                    'exclude_tree' => '',
                    'include' => '',
                    'hierarchical' => 1,
                    'title_li' => '',
                    'show_option_none' => __(''),
                    'number' => null,
                    'echo' => 1,
                    'depth' => 1,
                    'current_category' => 0,
                    'pad_counts' => 0,
                    'taxonomy' => 'category',
                    'walker' => null
                );
                $cats = get_categories($args);
                if ($cats AND ! is_day()) {
                    ?>

        <div class="shadow-plane padding marginbottom">
          <ul class="subcats">
            <?php
                            //the_archive_description('<div class="taxonomy-description">', '</div>');
                            ?>
            <?php
                            wp_list_categories($args);
                            ?>
          </ul>
        </div>
        <?php
                }
                ?>
        <?php if (have_posts()) : ?>
        <?php
                    while (have_posts()) : the_post();
                        global $more;
                        $more = 0;

                        $post_meta = get_post_custom($post->ID);
                        $post_format = get_post_format();

                        if ($post_format === false)
                            $post_format = 'standard';

                        $url = get_permalink();
                        $link = get_field('mpc_link_url');
                        $banner = get_field('mpc_use_as_banner');

                        if ($post_format == 'link' && isset($link))
                            $url = $link;
                        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('mpcth-post mpcth-waypoint'); ?>>
          <div class="row">

            <?php if (!( $post_format == 'link' && $banner == 'true' )) { ?>
            <section class="mpcth-post-content">
              <?php
                                        $largesrc = wp_get_attachment_url(get_post_thumbnail_id(null, 'large'));
                                        $col2 = 'col-sm-12';
                                        if (has_post_thumbnail()) {
                                         $col2 = 'col-sm-9';
                                        ?>
              <div class="col-sm-3">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('news'); ?></a>
              </div>
              <?php } ?>
              <div class="<?= $col2 ?>">
                <h4 class="mpcth-post-title">
                  <a href="<?php echo esc_url($url); ?>" class="mpcth-color-main-color-hover mpcth-color-main-border"
                    title="<?php the_title(); ?>"><?php the_title(); ?><?php echo $post_format == 'link' ? '<i class="fa fa-external-link"></i>' : ''; ?></a>
                </h4>
                <?php the_excerpt(); ?>
                <footer class="mpcth-post-footer">
                  <a class="mpcth-read-more mpcth-color-main-background-hover"
                    href="<?php the_permalink(); ?>"><?php _e('Continue Reading', 'mpcth'); ?><i
                      class="fa fa-angle-<?php echo is_rtl() ? 'left' : 'right'; ?>"></i></a>
                </footer>
              </div>
            </section>
            <?php } ?>
          </div>
        </article>
        <?php endwhile; ?>
        <?php else : ?>

        <?php endif; ?>
      </div><!-- end #mpcth_content -->
      <?php if ($query->max_num_pages > 1) { ?>
      <div id="mpcth_pagination">
        <?php
                    if ($blog_load_more)
                        mpcth_display_load_more($query);
                    else
                        mpcth_display_pagination($query);
                    ?>
      </div>
      <?php } ?>
    </div><!-- end #mpcth_content_wrap -->
  </div><!-- end #mpcth_main_container -->
</div><!-- end #mpcth_main -->

<?php
get_footer();