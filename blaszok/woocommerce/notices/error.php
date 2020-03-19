<?php
/**
 * Show error messages
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
<ul class="woocommerce-error" role="alert">
	<li class="message-icon"><i class="fa fa-fw fa-frown-o"></i></li>
	<?php foreach ( $messages as $message ) : ?>
		<li>
			<?php
			if ( function_exists( 'wc_kses_notice' ) ) { // WC 3.3 backward compatibility
				echo wc_kses_notice( $message );
			} else {
				echo wp_kses_post( $message );
			}
			?>
		</li>
	<?php endforeach; ?>
</ul>