<?php
/**
 * Flexible Posts Widget: Default widget template
 * 
 * @since 3.4.0
 *
 * This template was added to overcome some often-requested changes
 * to the old default template (widget.php).
 */
// Block direct requests
if (!defined('ABSPATH'))
    die('-1');

echo $before_widget;

if (!empty($title))
    echo $before_title . $title . $after_title;

if ($flexible_posts->have_posts()):
    $i = 1;
    $j = 1;
    ?>

<div id="news2">


  <?php
    while ($flexible_posts->have_posts()) : $flexible_posts->the_post();
        global $post;
        ?>
  <div class="item">
    <?php if ($thumbnail == true) { ?>
    <div class="smalnews-thumbnail">
      <a href="<?php the_permalink(); ?>" class="thumb"
        rel="bookmark"><?php the_post_thumbnail('gallerythumb', array('alt' => get_the_title())); ?></a>
    </div>
    <?php } ?>
    <div class="date"><i class="fa fa-calendar"></i> <?php echo get_the_date(); ?>
      <?php
                $video = get_field('video');
                if ($video) {
                    echo '<a href="' . get_the_permalink() . '#video" class="video" ><i class="fas fa-video"></i> Видео</a>';
                }
                ?>
    </div>
    <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
  </div>
  <?php
            endwhile;
            ?>
</div>
<?php
endif; // End have_posts()

echo $after_widget;
?>

<script>
jQuery("#news2").owlCarousel({
  navigation: true, // Show next and prev buttons
  slideSpeed: 300,
  paginationSpeed: 400,
  items: 3,
  lazyLoad: true,
  rewindNav: false,
  navigationText: ['<i class="fa fa-long-arrow-left"></i>', '<i class="fa fa-long-arrow-right"></i>']
});
jQuery("#news2 .item").removeClass('hidden');
</script>