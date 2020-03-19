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
    if ($posttype == 'docs') {
        $i = 1;
    } else {
        $i = 2;
    }
    foreach ($files as $item) {
        if ($i > 1) {
            $title = $item['attachment_title'];
        }
        echo '<p><a href="/wp-content/plugins/download-attachments/includes/download.php?id=' . esc_attr($item['attachment_id']) . '" ><img src="' . $item['attachment_icon_url'] . '" > скачать</a></p>';

        $i++;
    }
//    da_display_download_attachments();
    ?>
</div><!-- #post-## -->