<?php
/**
 * Single Product Thumbnails
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.1
 */

defined( 'ABSPATH' ) || exit;

global $post, $product, $woocommerce, $mpcth_options;

$woo_single_product_default = $mpcth_options[ 'mpcth_single_product_additional' ];

if ( empty( $woo_single_product_default ) ) :

	$attachment_ids = $product->get_gallery_image_ids();

	if ( $attachment_ids ) : ?>
		<div id="main_thumbs" class="flexslider">
			<ul class="slides"><?php
				if ( $product->get_image_id() ) {
					$attachment_id = get_post_thumbnail_id( $post->ID );
					$image         = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
					$image_title   = esc_attr( get_the_title( $attachment_id ) );
					$image_alt     = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
					$image_alt     = ( ! empty( $image_alt ) ? $image_alt : $image_title );

					echo '<li><img src="' . $image[ 0 ] . '" width="' . $image[ 1 ] . '" height="' . $image[ 2 ] . '" alt="' . $image_alt . '" title="' . $image_title . '" /></li>';
				}

				foreach ( $attachment_ids as $attachment_id ) {
					$image       = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
					$image_title = esc_attr( get_the_title( $attachment_id ) );
					$image_alt   = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
					$image_alt   = ( ! empty( $image_alt ) ? $image_alt : $image_title );

					if ( ! $image[ 0 ] ) {
						continue;
					}

					echo '<li><img src="' . $image[ 0 ] . '" width="' . $image[ 1 ] . '" height="' . $image[ 2 ] . '" alt="' . $image_alt . '" title="' . $image_title . '" /></li>';
				}

				?></ul>
		</div>
	<?php endif;

else :
	
	// Default Woo template goes here
	// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
	if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
		return;
	}
	$attachment_ids = $product->get_gallery_image_ids();

	if ( $attachment_ids && $product->get_image_id() ) {
		foreach ( $attachment_ids as $attachment_id ) {
			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id  ), $attachment_id );
		}
	}

endif;