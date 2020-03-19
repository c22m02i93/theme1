<?php
/**
 * Cross-sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

$crosssells = WC()->cart->get_cross_sells();

if ( sizeof( $crosssells ) == 0 ) return;

$crosssells_string = implode( ', ', $crosssells );
?>

<div class="cross-sells">

	<h4 class="mpcth-deco-header"><span class="mpcth-color-main-border"><?php _e( 'You may be interested in&hellip;', 'woocommerce' ) ?></span></h4>

	<?php echo do_shortcode( '[mpc_vc_products_slider type="custom" ids="' . $crosssells_string . '"]' ); ?>
</div>