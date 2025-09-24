<?php
/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $messages ) {
	return;
}

?>

<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-message" role="alert">
		<i class="fa fa-fw fa-smile-o"></i>
		<?php
		if ( function_exists( 'wc_kses_notice' ) ) { // WC 3.3 backward compatibility
			echo wc_kses_notice( $message );
		} else {
			echo wp_kses_post( $message );
		}
		?>
	</div>
<?php endforeach; ?>
