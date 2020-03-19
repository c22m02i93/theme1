<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

global $post, $woocommerce, $product, $mpcth_options;

$woo_single_product_default = $mpcth_options[ 'mpcth_single_product_additional' ];

if ( empty( $woo_single_product_default ) ) :
	$attachment_ids = $product->get_gallery_image_ids();
	$display_arrows         = $attachment_ids ? 'true' : 'false';

	?>
	<div class="images mpcth-post-thumbnail">
		<div class="flexslider-wrap">
			<div id="main_slider" class="flexslider" data-arrows="<?php echo $display_arrows; ?>">
				<ul class="slides">

					<?php
					if ( $product->get_image_id() ) {
						$attachment_id = get_post_thumbnail_id( $post->ID );
						$image         = wp_get_attachment_image_src( $attachment_id, 'shop_single' );
						$image_full    = wp_get_attachment_image_src( $attachment_id, 'full' );
						$image_title   = esc_attr( get_the_title( $attachment_id ) );
						$image_alt     = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
						$image_alt     = ( ! empty( $image_alt ) ? $image_alt : $image_title );

						echo '<li class="woocommerce-product-gallery__image"><a class="mpcth-lightbox mpcth-lightbox-type-image" href="' . $image_full[ 0 ] . '" title="' . $image_title . '"><img class="wp-post-image" width="' . $image[ 1 ] . '" height="' . $image[ 2 ] . '" alt="' . $image_alt . '" title="' . $image_title . '" src="' . $image[ 0 ] . '" /><i class="fa fa-fw fa-expand"></i></a></li>';

					} else {
						echo '<li class="woocommerce-product-gallery__image--placeholder">';
						echo sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
						echo '</li>';
					}

					if ( $attachment_ids ) {
						foreach ( $attachment_ids as $attachment_id ) {
							$image       = wp_get_attachment_image_src( $attachment_id, 'shop_single' );
							$image_full  = wp_get_attachment_image_src( $attachment_id, 'full' );
							$image_title = esc_attr( get_the_title( $attachment_id ) );
							$image_alt   = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
							$image_alt   = ( ! empty( $image_alt ) ? $image_alt : $image_title );

							if ( ! $image[ 0 ] ) {
								continue;
							}

							echo '<li class="woocommerce-product-gallery__image"><a class="mpcth-lightbox mpcth-lightbox-type-image" href="' . $image_full[ 0 ] . '" title="' . $image_title . '"><img class="wp-post-image" width="' . $image[ 1 ] . '" height="' . $image[ 2 ] . '" alt="' . $image_alt . '" title="' . $image_title . '" src="' . $image[ 0 ] . '" /><i class="fa fa-fw fa-expand"></i></a></li>';
						}
					}
					?>

				</ul>
			</div>
		</div>

		<?php do_action( 'woocommerce_product_thumbnails' ); ?>
		<?php woocommerce_show_product_sale_flash(); ?>
	</div>

<?php else:
	// Default Woo Template goes here
	// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
	if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
		return;
	}

	$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
	$post_thumbnail_id = $product->get_image_id();
	$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $product->get_image_id() ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	) );
?>
	<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
		<figure class="woocommerce-product-gallery__wrapper">
			<?php
			if ( $product->get_image_id() ) {
				$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
			} else {
				$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
				$html .= '</div>';
			}

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

			do_action( 'woocommerce_product_thumbnails' );
			?>
		</figure>
		<?php woocommerce_show_product_sale_flash(); ?>
	</div>

<?php endif; ?>