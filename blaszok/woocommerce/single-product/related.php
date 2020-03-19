<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $mpcth_options;

$related = wc_get_related_products( $product->get_id(), 10 );

if ( sizeof( $related ) == 0 ) return;

$related_string = implode( ', ', $related );
$columns        = ( isset( $mpcth_options[ 'mpcth_related_columns' ] ) ) ? $mpcth_options[ 'mpcth_related_columns' ] : 'default';

?>

<div class="related">

	<h4 class="mpcth-deco-header"><span class="mpcth-color-main-border"><?php _e( 'Related products', 'woocommerce' ); ?></span></h4>

	<?php echo do_shortcode( '[mpc_vc_products_slider type="custom" columns="' . $columns . '" ids="' . $related_string . '"]' ); ?>

</div>