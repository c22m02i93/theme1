<?php
/**
 * Template Name: Календарь записей епархиальных отделов и благочиний
 *
 * @since 1.0
 */
get_header();

global $page_id;
global $paged;
global $mpcth_options;
?>

<div id="mpcth_main">
  <div id="mpcth_main_container">
    <?php get_sidebar(); ?>
    <div id="mpcth_content_wrap">
      <div id="mpcth_content">
        <?php mpcth_breadcrumbs(); ?>
        <h3 class="mpcth-deco-header">
          <span>
            <?php
                        $title = get_the_title();
                        echo $title;
                        ?>
          </span>
        </h3>

        <?php
                $args = array(
                    'category_name' => 'novosti-eparhialnyh-otdelov-i-blagochinij',
                    'publish' => true,
                    'paged' => get_query_var('paged'),
                    'nopaging' => true,
                    'order' => 'DESC',
                    'orderby' => 'date'
                );
                $query = new WP_Query($args);
                $year = '';
                $month = '';
                $level = 0;
                $m = false;
                $y = false;
                echo '<ul class="newsarhive" >';
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        if ($year != get_the_date('Y')) {
                            if ($y) {
                                echo '</ul></li></ul></li>';
                            }
                            $y = true;
                            $m = false;
                            $year = get_the_date('Y');
                            echo '<li class="year" >' . $year . '<ul class="month" >';
                        }
                        if ($month != get_the_date('F')) {
                            if ($m) {
                                echo '</ul></li>';
                            }
                            $m = true;
                            $month = get_the_date('F');
                            echo '<li class="month" ><span class="month" >' . $month . '</span><ul id="item' . get_the_ID() . '" class="items" >';
                        }
                        echo '<li class="day" ><span class="date" >' . get_the_date('j F') . '</span> <a href="' . get_the_permalink() . '" >' . get_the_title() . '</a></li>';
                    }
                }
                echo '</ul>';
                wp_reset_postdata(); // сбрасываем переменную $post   
                ?>
      </div><!-- end #mpcth_content -->
    </div><!-- end #mpcth_content_wrap -->
  </div><!-- end #mpcth_main_container -->
</div><!-- end #mpcth_main -->
<script>
jQuery(document).ready(function($) {
  $("span.month").click(function(a) {
    //$("ul.items").hide("fast");
    $(this).next("ul.items").toggle("fast");
  });
});
</script>
<?php
get_footer();