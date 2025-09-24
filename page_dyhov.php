<?php
/**
 * The Page base for MPC Themes
 *Template Name: Духовенство
 * Displays single page.
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */

get_header();

global $page_id;
global $paged;

$hide_title = get_field('mpc_hide_title');

if (function_exists('is_account_page') && is_account_page()) {
	$url = $_SERVER['REQUEST_URI'];

	if (strpos($url, 'edit-address') !== false)
		$hide_title = true;
}

?>

<div id="mpcth_main">
	<div id="mpcth_main_container">
		<?php get_sidebar(); ?>
		<div id="mpcth_content_wrap">
			<div id="mpcth_content">
				<?php if (have_posts()) : ?>
					<?php while (have_posts()) : the_post(); ?>
						<article id="page-<?php the_ID(); ?>" <?php post_class('mpcth-page'); ?> >
							<?php if (! $hide_title) { ?>
							<header class="mpcth-page-header">
								<?php
									if (function_exists('is_checkout') && is_checkout()) {
										$order_url = $_SERVER['REQUEST_URI'];
										$order_received = strpos($order_url, '/order-received');
										?>
										<div class="mpcth-order-path">
											<span><?php _e('Shopping Cart', 'mpcth'); ?></span>
											<i class="fa fa-angle-right"></i>
											<span <?php echo ! $order_received ? 'class="mpcth-color-main-color"' : ''; ?>><?php _e('Checkout Details', 'mpcth'); ?></span>
											<i class="fa fa-angle-right"></i>
											<span <?php echo $order_received ? 'class="mpcth-color-main-color"' : ''; ?>><?php _e('Order Complete', 'mpcth'); ?></span>
										</div>
									<?php }
								?>
								<?php mpcth_breadcrumbs(); ?>
								
							  <h1 id="mpcth_archive_title" class=" zagolovok_straniz mpcth-deco-header" ><?php echo the_title('', false); ?></h1>
                <div class="bat_kit">

                <a class=" bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/monashestvuyushhie" role=" bat_akt_col" style="
background: rgba(19, 65, 135, 0.8);
">Монашествующие</a>
             <a class=" bat_kit btn btn-primary"  href="https://mitropolia-simbirsk.ru/category/protoierei" role=" bat_akt_col" style="
background: rgba(19, 65, 135, 0.8);
">Протоиереи</a>
  <a class=" bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/ierei" role=" bat_akt_col" style="
background: rgba(19, 65, 135, 0.8);
">Иереи</a>
             <a class=" bat_kit btn btn-primary" href="https://mitropolia-simbirsk.ru/category/diakony" role=" bat_akt_col" style="
background: rgba(19, 65, 135, 0.8);
">Диаконы</a>


         </div>	
									
							</header>
							<?php } ?>
							<section class="mpcth-page-content" >
							

							
							</section>
							
						</article>
					<?php endwhile; ?>
				<?php endif; ?>
			</div><!-- end #mpcth_content -->
		</div><!-- end #mpcth_content_wrap -->
	</div><!-- end #mpcth_main_container -->
</div><!-- end #mpcth_main -->

<?php get_footer();