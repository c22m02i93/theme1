<?php
/**
 * The Footer base for MPC Themes
 *
 * Displays all of the <footer> section and everything up till </html>
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */
global $mpcth_options;
global $page_id;
?>
<?php if (!is_front_page()) { ?>
<div id="mpcth_main_container">
  <div class="row">
    <div class="col-sm-12 hidden-xs banner">
      <?php dynamic_sidebar('frontpagebanner1'); ?>
    </div>
  </div>
</div>
<?php } ?>

<footer id="mpcth_footer">
  <div id="mpcth_footer_container">
    <div class="mpcth-footer-wrap">
      <div class="row">
        <div class="col-sm-3 hidden-xs"><?php dynamic_sidebar('footer201'); ?></div>
        <div class="col-sm-6"><?php dynamic_sidebar('footer21'); ?></div>
        <div class="col-sm-3"><?php dynamic_sidebar('footer3'); ?></div>
      </div>
    </div>
  </div><!-- end #mpcth_footer_container -->
</footer><!-- end #mpcth_footer -->
</div><!-- end #mpcth_page_wrap -->
<?php
$back_to_top_position = 'left';
if (isset($mpcth_options['mpcth_back_to_top_position']))
    $back_to_top_position = $mpcth_options['mpcth_back_to_top_position'];

if ($back_to_top_position != 'none')
    echo '<a href="#" id="mpcth_back_to_top" class="mpcth-back-to-top-position-' . $back_to_top_position . '"><i class="fa fa-angle-up"></i></a>';
?>
<?php wp_footer(); ?>
<?php function salaider() {
    wp_register_script('slaqder', home_url() . '/sl/chiefslider.min.js', true );
    wp_enqueue_script('slaider');
}
 ?>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function(m, e, t, r, i, k, a) {
  m[i] = m[i] || function() {
    (m[i].a = m[i].a || []).push(arguments)
  };
  m[i].l = 1 * new Date();
  k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
})
(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

ym(56015641, "init", {
  clickmap: true,
  trackLinks: true,
  accurateTrackBounce: true
});
</script>
<noscript>
  <div><img src="https://mc.yandex.ru/watch/56015641" style="position:absolute; left:-9999px;" alt="" /></div>
</noscript>
<!-- /Yandex.Metrika counter -->
</body>

</html>