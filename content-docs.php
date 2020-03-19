<?php
/**
 * @package komitet
 */
?>

<div class="fileitem">
    <?php
    $posttype = get_post_type();
    $terms = get_the_terms($post->ID, 'docscat');

    $title = get_the_title();
    $files = da_get_download_attachments();
	if ($files) {
	echo '<h3>Документы:</h3>';
	}
    if ($posttype == 'docs') {
        $i = 1;
    } else {
        $i = 2;
    }
    foreach ($files as $item) {
        if ($i > 1) {
            $title = $item['attachment_title'];
        }
        echo '<p><a href="/wp-content/plugins/download-attachments/includes/download.php?id=' . esc_attr($item['attachment_id']) . '" ><img src="' . $item['attachment_icon_url'] . '" > ' . $title . '</a></p>';
        echo '<div class="fileinfo" >' . $item['attachment_size']. ', скачиваний: '.$item['attachment_downloads'];
        if ($posttype == 'docs') {
            foreach ($terms as $term) {
                $link = get_term_link($term);
                echo ', <a href="'.$link.'" >' . $term->name . '</a>';
            }
        }
        echo '</div>';
        $i++;
    }
//    da_display_download_attachments();
    ?>
</div><!-- #post-## -->