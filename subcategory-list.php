<?php
                if (!empty($term_description)) :
                    printf('<div class="taxonomy-description">%s</div>', $term_description);
                endif;
                $cat_id = get_query_var('cat');
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
                    'echo' => 0,
                    'depth' => 1,
                    'current_category' => 0,
                    'pad_counts' => 0,
                    'taxonomy' => 'category',
                    'walker' => null
                );
                echo '<ul class="pagesubmenu" >' . wp_list_categories($args) . '</ul>';
                while (have_posts()) : the_post();
                    get_template_part('content', get_post_format());

                endwhile;
                custom_pagination();
                ?> 