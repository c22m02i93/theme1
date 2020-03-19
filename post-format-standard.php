<?php
/**
 * The Standard post header base for MPC Themes
 *
 * Displays the thumbnail for posts in the Standard post format.
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */

global $sidebar_position;
global $blog_layout;

$default = 1;
if ($blog_layout == 'small')
	$default += 1;
else if ($blog_layout == 'masonry')
	$default = 2;
	$post_id = $post->ID; // current post id
	$thumb_id = get_post_thumbnail_id($post_id);
if (has_post_thumbnail()) {
	if (is_single()) {

		$url = wp_get_attachment_image_src($thumb_id, 'full');
		?>
<div class="mpcth-post-thumbnail">
  <a href="<?php echo $url[0]; ?>" title="<?php the_title(); ?>" class="fancybox">
    <?php the_post_thumbnail('full'); ?>
  </a>
</div>
<?php
	}

	else {
		$url = wp_get_attachment_image_src($thumb_id, 'medium');
		?>
<div class="mpcth-post-thumbnail">
  <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="fancybox">
    <?php the_post_thumbnail('news'); ?>
  </a>
</div>
<?php
	}

}