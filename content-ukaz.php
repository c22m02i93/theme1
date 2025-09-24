<?php
/**
 * The template for displaying content of custom post type "Ukaz"
 *
 * @package WordPress
 * @subpackage Your_Theme_Name
 * @since 1.0
 */

$thumb_id = get_post_thumbnail_id($post->ID);
if (has_post_thumbnail()) {
    if (is_single()) {
        $url = wp_get_attachment_image_src($thumb_id, 'full');
        ?>
        <div class="zag_zaip" >
            <a href="<?php echo $url[0]; ?>" title="<?php the_title(); ?>" class="zag_zaip">
                <?php the_post_thumbnail('full', array('style' => 'border-radius: 10px;')); ?>
            </a>
        </div>
        <?php
    } else {
        $url = wp_get_attachment_image_src($thumb_id, 'medium');
        ?>
        <a style="float: left;margin: 6px 15px 5px 0px;" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php the_post_thumbnail('news'); ?> 
        </a>
        <?php
    }
}
?>
