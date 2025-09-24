<?php
/**
 * Single Product Up-Sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $mpcth_options;

if (empty($product))
	return;

$upsells = $product->get_upsell_ids();

if ( sizeof( $upsells ) == 0 ) return;

$upsells_string = implode( ', ', $upsells );
?>

<div class="upsells">

	<h4 class="mpcth-deco-header"><span class="mpcth-color-main-border"><?php _e( 'You may also like&hellip;', 'woocommerce' ) ?></span></h4>

	<?php echo do_shortcode( '[mpc_vc_products_slider type="custom" ids="' . $upsells_string . '"]' ); ?>

</div>

