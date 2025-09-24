<?php
/**
 * Template Name: Full width with sidebar
 * The Page base for MPC Themes
 *
 * Displays single page.
 *
 * @package WordPress
 * @subpackage MPC Themes
 * @since 1.0
 */

get_header();

global $page_id;
global $paged;

$header_content = get_field('mpc_header_content');
$hide_title = get_field('mpc_hide_title');

if (function_exists('is_account_page') && is_account_page()) {
	$url = $_SERVER['REQUEST_URI'];

	if (strpos($url, 'edit-address') !== false)
		$hide_title = true;
}

?>

<div id="mpcth_main">
	<?php if ($header_content != '') { ?>
	<div class="mpcth-page-custom-header">
		<?php echo do_shortcode($header_content); ?>
	</div>
	<?php } ?>
								</header>
							<?php } ?>
							<section class="mpcth-page-content">
								<?php the_content(); ?>
							</section>
							<footer class="mpcth-page-footer">
								<?php if (comments_open()) { ?>
									<div id="mpcth_comments">
										<?php comments_template('', true); ?>
									</div>
								<?php } ?>
							</footer>
						</article>
					<?php endwhile; ?>
				<?php endif; ?>
			</div><!-- end #mpcth_content -->
		</div><!-- end #mpcth_content_wrap -->
	</div><!-- end #mpcth_main_container -->
</div><!-- end #mpcth_main -->

<?php get_footer();