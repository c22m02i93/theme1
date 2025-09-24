//Архипастырское 
<?php
$args = array(
  'category__in' => 57,
  'post_type'      => 'post',
  'posts_per_page' => 4,
  'orderby'        => 'date',
  'order'          => 'DESC',
);
$q = new WP_Query($args);
?>

<?php if ( $q->have_posts() ) : ?>
    <?php while ( $q->have_posts() ) : $q->the_post(); ?>
      
      
      <div class="kitimg patr"> <a class="kitimg"href="<?php the_permalink() ?>"> <?php the_post_thumbnail( array(90,60)); ?>
 </a> 
    <div class="time-item"> 
      <div class="actual-item-title"><a class="actual-item-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
      <div class="news-item-text"> <?php do_excerpt(get_the_excerpt(), 10); ?> </div>
      <div class="kittim">
 <span class="ico-svg ico-time-grey"><?php the_time('j.m.Y'); ?></span>  </div>
      
    </div> </div>
  <?php endwhile; ?>
<?php endif; ?>