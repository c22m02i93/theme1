<?php
/**
 * The Header base for MPC Themes (optimized version)
 *
 * Displays all of the <head> section and everything up till <div id="mpcth_main">
 *
 * @package WordPress
 * @subpackage MPC Themes
 */

global $post;
global $page_id;
global $paged;
global $mpcth_options;
global $sidebar_position;
global $content_width;

if ( isset( $post ) && ! is_archive() ) {
	$page_id = $post->ID;
} else {
	$page_id = 0;
}

$paged = ( get_query_var( 'paged' ) )
	? get_query_var( 'paged' )
	: ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );

$single_page_layout = '';
if ( $page_id ) {
	$single_page_layout = get_field( 'mpc_site_layout', $page_id );
}
if ( $single_page_layout && $single_page_layout !== 'default' ) {
	$mpcth_options['mpcth_boxed_type'] = $single_page_layout;
}

// Опции темы
$use_advance_colors             = ! empty( $mpcth_options['mpcth_use_advance_colors'] );
$disable_responsive             = ! empty( $mpcth_options['mpcth_disable_responsive'] );
$disable_menu_indicators        = ! empty( $mpcth_options['mpcth_disable_menu_indicators'] );
$smart_search                   = ! empty( $mpcth_options['mpcth_enable_smart_search'] );
$simple_menu                    = ! empty( $mpcth_options['mpcth_enable_simple_menu'] );
$simple_menu_label              = ! empty( $mpcth_options['mpcth_enable_simple_menu_label'] );
$disable_mobile_slider_nav      = ! empty( $mpcth_options['mpcth_disable_mobile_slider_nav'] );
$slider_revolution_original_nav = ! empty( $mpcth_options['mpcth_rev_nav_original'] );
$disable_product_cart           = ! empty( $mpcth_options['mpcth_disable_product_cart'] );
$accordion_mobile_menu          = ! empty( $mpcth_options['mpcth_enable_accordion_menu'] );
$disable_product_price          = $disable_product_cart && ! empty( $mpcth_options['mpcth_disable_product_price'] );

$enable_full_width_header = ! empty( $mpcth_options['mpcth_header_full_width'] );
if ( ! $enable_full_width_header ) {
	$enable_full_width_header = get_field( 'mpc_force_header_full_width', $page_id );
}

$enable_transparent_header = get_field( 'mpc_enable_transparent_header', $page_id );
$force_simple_buttons      = $enable_transparent_header && get_field( 'mpc_force_simple_buttons', $page_id );
$vertical_center_elements  = $enable_transparent_header ? get_field( 'mpc_vertical_center_elements', $page_id ) : false;

// Настройка под WooCommerce
$masonry_shop   = false;
$load_more_shop = false;
if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
	if ( ! empty( $_GET['masonry'] ) ) {
		if ( $_GET['masonry'] == 1 ) {
			$masonry_shop   = true;
			$load_more_shop = false;
		} elseif ( $_GET['masonry'] == 2 ) {
			$masonry_shop   = true;
			$load_more_shop = true;
		}
	} else {
		$masonry_shop   = function_exists( 'is_woocommerce' ) && is_woocommerce() && ! is_product() && ! empty( $mpcth_options['mpcth_enable_masonry_shop'] );
		$load_more_shop = $masonry_shop && ! empty( $mpcth_options['mpcth_enable_shop_load_more'] );
	}
}

// Дополнительные классы для body
$body_classes  = '';
$body_classes .= ( $disable_product_cart ? ' mpcth-disable-add-to-cart ' : '' );
$body_classes .= ( $disable_product_price ? ' mpcth-disable-price ' : '' );
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php echo ( ! $disable_responsive ) ? 'class="mpcth-responsive"' : ''; ?>>
<head>
	<!-- Метаданные валидации -->
	<meta name="google-site-verification" content="2JAl1WFA3c3AF1kR5k2eQRGd41E4gfCwrLqs1s2yvuI" />
	<meta name="yandex-verification" content="73ad509ba44d9fc3" />
	<meta name="yandex-verification" content="b470f405d82619ae" />

	<!-- Charset / Viewport -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- OG Image (только для одиночных записей) -->
	<?php if ( is_single() ) : ?>
		<meta property="og:image" content="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>" />
	<?php endif; ?>

	<!-- Pingback -->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<!-- Favicon -->
	<?php if ( ! empty( $mpcth_options['mpcth_enable_fav_icon'] ) && ! empty( $mpcth_options['mpcth_fav_icon'] ) ) : ?>
		<link rel="icon" type="image/png" href="<?php echo esc_url( $mpcth_options['mpcth_fav_icon'] ); ?>">
	<?php endif; ?>

	<!-- Подключение Google Fonts (вместо @import) -->
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap" rel="stylesheet">

	<!-- Инлайн критический CSS (пример) -->
	<style>
		/* Пример минимума стилей для Above the Fold */
		html, body {
			margin: 0;
			padding: 0;
			font-family: 'Merriweather', serif;
		}
		/* Добавляйте сюда стили, без которых первый экран будет «прыгать» */
	</style>

	<!-- Подключаем FontAwesome с defer (чтобы не блокировать рендер) -->
	<script src="https://kit.fontawesome.com/5d8529aca4.js" crossorigin="anonymous" defer></script>

	<?php
	// Если нужны скрипты для комментариев на одиночных постах
	if ( is_singular() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Стандартный вызов wp_head() – подключение стилей/скриптов темы и плагинов
	wp_head();

	// Вставка счетчиков аналитики, если включено
	if ( ! empty( $mpcth_options['mpcth_enable_analytics'] ) && ! empty( $mpcth_options['mpcth_analytics_code'] ) ) {
		?>
		<script>
			<?php echo stripslashes( $mpcth_options['mpcth_analytics_code'] ); ?>
		</script>
		<?php
	}
	?>
</head>

<body <?php body_class( 'mpcth-sidebar-' . mpcth_get_sidebar_position() . $body_classes ); ?>>
<?php
// Если у вас есть кастомный вывод bg_cover (фон) – выводим
if ( isset( $bg_cover ) ) {
	echo $bg_cover;
}
?>

<div id="mpcth_page_wrap"
	class="
	<?php
		echo ( ! empty( $mpcth_options['mpcth_boxed_type'] ) && $mpcth_options['mpcth_boxed_type'] !== 'fullwidth' ) ? 'mpcth-boxed ' : '';
		echo ( ! empty( $mpcth_options['mpcth_boxed_type'] ) && $mpcth_options['mpcth_boxed_type'] === 'floating_boxed' ) ? 'mpcth-floating-boxed ' : '';
		echo ( ! empty( $mpcth_options['mpcth_theme_skin'] ) && $mpcth_options['mpcth_theme_skin'] === 'skin_dark' ) ? 'mpcth-skin-dark ' : '';
		echo ( $masonry_shop ) ? 'mpcth-masonry-shop ' : '';
		echo ( $load_more_shop ) ? 'mpcth-load-more-shop ' : '';
		echo ( is_rtl() ) ? 'mpcth-rtl ' : '';
		echo ( $disable_mobile_slider_nav ? 'mpcth-no-mobile-slider-nav ' : '' );
		echo ( ! $slider_revolution_original_nav ? 'mpcth-rev-nav-original ' : '' );
		echo ( $use_advance_colors ? 'mpcth-use-advance-colors ' : '' );
		echo ( $enable_transparent_header ? 'mpcth-transparent-header ' : '' );
		echo ( $enable_full_width_header ? 'mpcth-full-width-header ' : '' );
		echo ( $accordion_mobile_menu ? ' mpcth-accordion-menu ' : '');
	?>
	">

	<?php if ( ! $simple_menu ) : ?>
		<a id="mpcth_toggle_mobile_menu" class="mpcth-color-main-color-hover" href="#">
			<i class="fa fa-bars"></i><i class="fa fa-times"></i>
		</a>
		<div id="mpcth_mobile_nav_wrap">
			<nav id="mpcth_nav_mobile" role="navigation">
				<?php
				// Меню для мобильной версии (если настроено)
				if ( has_nav_menu( 'mpcth_mobile_menu' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'mpcth_mobile_menu',
							'container'      => '',
							'menu_id'        => 'mpcth_mobile_menu',
							'menu_class'     => 'mpcth-mobile-menu',
							'link_before'    => '<span class="mpcth-color-main-border">',
							'link_after'     => '</span>',
						)
					);
				} elseif ( has_nav_menu( 'mpcth_menu' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'mpcth_menu',
							'container'      => '',
							'menu_id'        => 'mpcth_mobile_menu',
							'menu_class'     => 'mpcth-mobile-menu',
							'link_before'    => '<span class="mpcth-color-main-border">',
							'link_after'     => '</span>',
						)
					);
				} else {
					wp_nav_menu(
						array(
							'container'   => '',
							'menu_id'     => 'mpcth_mobile_menu',
							'menu_class'  => 'mpcth-mobile-menu',
							'link_before' => '<span class="mpcth-color-main-border">',
							'link_after'  => '</span>',
						)
					);
				}
				?>
			</nav>
		</div>
	<?php endif; ?>

	<?php
	// Если в теме предусмотрена некая "Header Area"
	if ( ! empty( $mpcth_options['mpcth_enable_header_area'] ) ) :
		$header_area_columns = ! empty( $mpcth_options['mpcth_header_area_columns'] )
			? $mpcth_options['mpcth_header_area_columns']
			: 3;
		// Выведите необходимые виджеты / секции, если нужно
	endif;
	?>

	<div id="mpcth_page_header_wrap_spacer"></div>
	<?php
	$enable_sticky_header         = ! empty( $mpcth_options['mpcth_enable_sticky_header'] );
	$enable_mobile_sticky_header  = ! empty( $mpcth_options['mpcth_enable_mobile_sticky_header'] ) && $enable_sticky_header;
	$enable_simple_buttons        = ! empty( $mpcth_options['mpcth_enable_simple_buttons'] );
	if ( $enable_transparent_header && $force_simple_buttons ) {
		$enable_simple_buttons = true;
	}
	$sticky_header_offset = ! empty( $mpcth_options['mpcth_sticky_header_offset'] )
		? $mpcth_options['mpcth_sticky_header_offset']
		: '75%';
	?>

	<header
		id="mpcth_page_header_wrap"
		class="
			<?php echo $enable_sticky_header ? 'mpcth-sticky-header-enabled ' : ''; ?>
			<?php echo $enable_mobile_sticky_header ? 'mpcth-mobile-sticky-header-enabled ' : ''; ?>
			<?php echo $enable_simple_buttons ? 'mpcth-simple-buttons-enabled ' : ''; ?>
			<?php echo $vertical_center_elements ? 'mpcth-vertical-center ' : ''; ?>
		"
		data-offset="<?php echo esc_attr( $sticky_header_offset ); ?>"
	>
		<div id="mpcth_page_header_container">
			<?php if ( ! empty( $mpcth_options['mpcth_enable_secondary_header'] ) && $mpcth_options['mpcth_header_secondary_position'] === 'top' ) : ?>
				<div id="mpcth_header_second_section">
					<div class="mpcth-header-wrap">
						<?php mpcth_display_secondary_header(); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php
			$header_order = ! empty( $mpcth_options['mpcth_header_main_layout'] )
				? $mpcth_options['mpcth_header_main_layout']
				: 'l_m_s';
			$header_order_items = explode( '_', $header_order );
			?>

			<div id="mpcth_header_section">
				<div class="mpcth-header-wrap">
					<div id="mpcth_page_header_content" class="mpcth-header-order-<?php echo esc_attr( $header_order ); ?>">
						<?php
						// Лого / Меню / Иконки – отрисовываются в порядке $header_order
						foreach ( $header_order_items as $item ) {
							if ( $item === 'l' || $item === 'tl' ) {
								?>
								<div id="mpcth_logo_wrap">
									<a id="mpcth_logo" href="<?php echo esc_url( home_url() ); ?>">
										<!-- ЛОГО: пример с использованием svg -->
										<img src="https://mitropolia-simbirsk.ru/wp-content/uploads/2025/07/001-3-2025_07_11_2243.jpg" alt="Logo">
									</a>
									<?php if ( ! empty( $mpcth_options['mpcth_text_logo_description'] ) ) : ?>
										<small><?php echo esc_html( get_bloginfo( 'description' ) ); ?></small>
									<?php endif; ?>
								</div>
								<?php
							}
							if ( $item === 'm' || $item === 'rm' || $item === 'cm' ) {
								// Центральный блок (меню или что-то ещё)
								// Если $header_order == 'tl_m_s', иногда здесь вставляют дополнительные обертки
								// Для упрощения оставим как есть
							}
							if ( $item === 's' || $item === 'cs' ) {
								?>
								<div id="mpcth_controls_wrap">
									<div id="mpcth_controls_container">
										<?php
										$header_search = ! empty( $mpcth_options['mpcth_enable_header_search'] );
										if ( $header_search ) {
											?>
											<a id="mpcth_search" href="#">
												<i class="fa fa-fw fa-search"></i>
											</a>
											<?php
											// Пример ссылок на соцсети (VK/Telegram/YouTube)
											?>
											<a href="https://vk.com/simbirskaya_mitropolia" title="Мы ВКонтакте">
												<i class="fa fa-vk"></i>
											</a>
											<a href="https://t.me/simbmit" title="Наш Telegram">
												<i class="fa fa-telegram"></i>
											</a>
											<a href="https://www.youtube.com/channel/UCS8USoK9sXYVSb6MQGxczKw" title="YouTube">
												<i class="fa fa-youtube-play"></i>
											</a>
											<?php
										}

										// Иконка корзины (WooCommerce)
										$disable_header_cart = ! empty( $mpcth_options['mpcth_disable_header_cart'] );
										if ( function_exists( 'is_woocommerce' ) && ! $disable_header_cart ) {
											?>
											<a
												id="mpcth_cart"
												href="<?php echo esc_url( wc_get_cart_url() ); ?>"
												class="<?php echo ( WC()->cart && count( WC()->cart->get_cart() ) > 0 ) ? 'active' : ''; ?>"
											>
												<i class="fa fa-fw fa-shopping-cart"></i>
											</a>
											<?php
										}

										if ( $simple_menu ) {
											?>
											<a id="mpcth_simple_menu" href="#">
												<?php if ( $simple_menu_label ) { echo esc_html__( 'Menu', 'mpcth' ); } ?>
												<i class="fa fa-fw fa-bars"></i>
											</a>
											<?php
										}

										// Поиск без "умного" (smart_search) – выводим форму
										if ( $header_search && ! $smart_search ) {
											?>
											<div id="mpcth_mini_search">
												<!-- Замените короткий код на ваш -->
												<?php echo do_shortcode('[wpdreams_ajaxsearchpro id=1]'); ?>
											</div>
											<?php
										}
										?>
									</div>
								</div><!-- #mpcth_controls_wrap -->
								<?php
							}
						}
						?>
					</div><!-- #mpcth_page_header_content -->
				</div><!-- .mpcth-header-wrap -->
			</div><!-- #mpcth_header_section -->

			<?php if ( ! empty( $mpcth_options['mpcth_enable_secondary_header'] ) && $mpcth_options['mpcth_header_secondary_position'] === 'bottom' ) : ?>
				<div id="mpcth_header_second_section">
					<div class="mpcth-header-wrap">
						<?php mpcth_display_secondary_header(); ?>
					</div>
				</div>
			<?php endif; ?>
		</div><!-- #mpcth_page_header_container -->

		<div id="topMenu">
			<div class="mpcth-header-wrap">
				<nav id="mpcth_nav" role="navigation">
					<?php
					if ( ! empty( $mpcth_options['mpcth_enable_mega_menu'] ) ) {
						echo '<ul id="mpcth_mega_menu">';
						dynamic_sidebar( 'mpcth_main_menu' );
						echo '</ul>';
					} else {
						if ( has_nav_menu( 'mpcth_menu' ) ) {
							wp_nav_menu(
								array(
									'theme_location' => 'mpcth_menu',
									'container'      => '',
									'menu_id'        => 'mpcth_menu',
									'menu_class'     => 'mpcth-menu',
								)
							);
						} else {
							wp_nav_menu(
								array(
									'container' => '',
									'menu_id'   => 'mpcth_menu',
									'menu_class'=> 'mpcth-menu',
								)
							);
						}
					}
					?>
				</nav><!-- #mpcth_nav -->
			</div>
		</div><!-- #topMenu -->

		<div class="ornament"></div>

		<?php if ( $simple_menu ) : ?>
			<div id="mpcth_simple_mobile_nav_wrap">
				<nav id="mpcth_nav_mobile" role="navigation">
					<?php
					if ( has_nav_menu( 'mpcth_mobile_menu' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'mpcth_mobile_menu',
								'container'      => '',
								'menu_id'        => 'mpcth_mobile_menu',
								'menu_class'     => 'mpcth-mobile-menu',
								'link_before'    => '<span class="mpcth-color-main-border">',
								'link_after'     => '</span>',
							)
						);
					} elseif ( has_nav_menu( 'mpcth_menu' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'mpcth_menu',
								'container'      => '',
								'menu_id'        => 'mpcth_mobile_menu',
								'menu_class'     => 'mpcth-mobile-menu',
								'link_before'    => '<span class="mpcth-color-main-border">',
								'link_after'     => '</span>',
							)
						);
					} else {
						wp_nav_menu(
							array(
								'container'   => '',
								'menu_id'     => 'mpcth_mobile_menu',
								'menu_class'  => 'mpcth-mobile-menu',
								'link_before' => '<span class="mpcth-color-main-border">',
								'link_after'  => '</span>',
							)
						);
					}
					?>
				</nav>
			</div>
		<?php endif; ?>
	</header><!-- #mpcth_page_header_wrap -->
