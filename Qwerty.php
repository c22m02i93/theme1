<?php
/*
Template Name: Шаблон страницы wpschool
*/
?>

<!-- Код шаблона -->
<get_header();

global $page_id;
global $paged;

$header_content = get_field('mpc_header_content');
$hide_title = get_field('mpc_hide_title');
?>
<div class="sliderarea">
  <div id="mpcth_main_container">
    <div class="row">
      <div class="col-sm-12 hidden-xs"><?php dynamic_sidebar('slider'); ?></div>
    </div>
  </div>
</div>
<div class="newsarea">
  <div id="mpcth_main_container">
    <div id="mpcth_content">
      <div class="row">
        <div class="col-sm-8"><?php dynamic_sidebar('news'); ?></div>
        <div class="col-sm-4">
          <div class="pravcal"><?php dynamic_sidebar('pravcalendar'); ?></div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-8"><?php dynamic_sidebar('block2'); ?></div>
        <div class="col-sm-4"><?php dynamic_sidebar('block3'); ?></div>
      </div>
      <div class="row">
        <div class="col-sm-6"><?php dynamic_sidebar('block4'); ?></div>
        <div class="col-sm-6"><?php dynamic_sidebar('block5'); ?></div>
      </div>
      <div class="row rssFeeds">
        <div class="col-sm-6"><?php dynamic_sidebar('block6'); ?></div>
        <div class="col-sm-6"><?php dynamic_sidebar('block7'); ?></div>
      </div>
    </div>
  </div>
</div>

<?php
get_footer();