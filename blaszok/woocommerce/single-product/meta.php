<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $mpcth_options, $post, $product;

$sku = isset( $mpcth_options[ 'mpcth_disable_sku' ] ) ? $mpcth_options[ 'mpcth_disable_sku' ] : false;
$categories = isset( $mpcth_options[ 'mpcth_disable_categories' ] ) ? $mpcth_options[ 'mpcth_disable_categories' ] : false;
$tags = isset( $mpcth_options[ 'mpcth_disable_tags' ] ) ? $mpcth_options[ 'mpcth_disable_tags' ] : false;

?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( !$sku && wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
		<span class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'n/a', 'woocommerce' ); ?></span>.</span>
	<?php endif; ?>

	<?php if( !$categories ) : ?>
		<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>
	<?php endif; ?>

	<?php if( !$tags ) : ?>
		<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>