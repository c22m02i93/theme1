<?php

/* ---------------------------------------------------------------- */
/* Register plugins
/* ---------------------------------------------------------------- */
add_action('tgmpa_register', 'mpcth_install_require_plugins');
function mpcth_install_require_plugins() {
	$plugins = array(
		array(
			'name'		=> 'MPC Extensions',
			'slug'		=> 'mpc-extensions',
			'source'	=> 'https://products.mpcthemes.net/blaszok/plugins/mpc-extensions.zip',
			'required'	=> true,
			'version'	=> '3.9.9.1',
		),
		array(
			'name'		=> 'MPC Widgets',
			'slug'		=> 'mpc-widgets',
			'source'	=> 'https://products.mpcthemes.net/blaszok/plugins/mpc-widgets.zip',
			'required'	=> true,
			'version'	=> '3.9.5',
		),
		array(
			'name'		=> 'MPC Shortcodes',
			'slug'		=> 'mpc-shortcodes',
			'source'	=> 'https://products.mpcthemes.net/blaszok/plugins/mpc-shortcodes.zip',
			'required'	=> true,
			'version'	=> '3.8.2',
		),
		array(
			'name'		=> 'MPC Importer',
			'slug'		=> 'mpc-importer',
			'source'	=> 'https://products.mpcthemes.net/blaszok/plugins/mpc-importer.zip',
			'required'	=> false,
			'version'	=> '3.9.9.3',
		),
		array(
			'name'		=> 'ACF Repeater',
			'slug'		=> 'acf-repeater',
			'source'	=> 'https://products.mpcthemes.net/blaszok/plugins/acf-repeater.zip',
			'required'	=> true,
		),
		array(
			'name'		=> 'ACF Gallery',
			'slug'		=> 'acf-gallery',
			'source'	=> 'https://products.mpcthemes.net/blaszok/plugins/acf-gallery.zip',
			'required'	=> true,
		),
		array(
			'name'			=> 'WPBakery Page Builder',
			'slug'			=> 'js_composer',
			'source'		=> 'https://products.mpcthemes.net/blaszok/plugins/js_composer.zip',
			'required'		=> true,
			'version'		=> '6.0.5',
		),
		array(
			'name'			=> 'Revolution Slider',
			'slug'			=> 'revslider',
			'source'		=> 'https://products.mpcthemes.net/blaszok/plugins/revslider.zip',
			'required'		=> false,
			'version'		=> '6.1.0',
		),
		array(
			'name'			=> 'Essential Grid',
			'slug'			=> 'essential-grid',
			'source'		=> 'https://products.mpcthemes.net/blaszok/plugins/essential-grid.zip',
			'required'		=> false,
			'version'		=> '2.3.3',
		),
		array(
			'name'			=> 'Woocommerce Quickview',
			'slug'			=> 'jck_woo_quickview',
			'source'		=> 'https://products.mpcthemes.net/blaszok/plugins/jck_woo_quickview.zip',
			'required'		=> false,
			'version'		=> '3.9.7.2',
		),
		array(
			'name'			=> 'LayerSlider',
			'slug'			=> 'LayerSlider',
			'source'		=> 'https://products.mpcthemes.net/blaszok/plugins/layerslider_wp.zip',
			'required'		=> false,
			'version'		=> '6.9.1',
		),
		array(
			'name'			=> 'CSS3 Pricing Tables Grids',
			'slug'			=> 'css3_web_pricing_tables_grids',
			'source'		=> 'https://products.mpcthemes.net/blaszok/plugins/css3_web_pricing_tables_grids.zip',
			'required'		=> false,
			'version'		=> '11.0',
		),
		array(
			'name'			=> 'MPC Mega Menu',
			'slug'			=> 'jquery-mega-menu',
			'source'		=> 'https://products.mpcthemes.net/blaszok/plugins/jquery-mega-menu.zip',
			'required'		=> false,
			'version'		=> '3.9.7',
		),
		array(
			'name'		=> 'Subscribe2',
			'slug'		=> 'subscribe2',
			'required'	=> false,
			'version'	 => '10.30',
		),
		array(
			'name'		=> 'Advanced Custom Fields',
			'slug'		=> 'advanced-custom-fields',
			'required'	=> true,
		),
		array(
			'name'		=> 'Contact Form 7',
			'slug'		=> 'contact-form-7',
			'required'	=> false,
		),
		array(
			'name'		=> 'WooCommerce',
			'slug'		=> 'woocommerce',
			'version'	=> '3.0.6',
			'required'	=> false,
		),
		array(
			'name'		=> 'YITH Wishlist',
			'slug'		=> 'yith-woocommerce-wishlist',
			'required'	=> false,
		),
	);

	$config = array(
		'domain'			=> 'mpcth',
		'default_path'		=> '',
		'menu'				=> 'install-required-plugins',
		'has_notices'		=> true,
		'is_automatic'		=> false,
		'message'			=> '',
		'strings'			=> array(
			'page_title'						=> __( 'Install Required Plugins', 'mpcth' ),
			'menu_title'						=> __( 'Install Plugins', 'mpcth' ),
			'installing'						=> __( 'Installing Plugin: %s', 'mpcth' ),
			'oops'								=> __( 'Something went wrong with the plugin API.', 'mpcth' ),
			'notice_can_install_required'		=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ),
			'notice_can_install_recommended'	=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ),
			'notice_cannot_install'				=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ),
			'notice_can_activate_required'		=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ),
			'notice_can_activate_recommended'	=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ),
			'notice_cannot_activate'			=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ),
			'notice_ask_to_update'				=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ),
			'notice_cannot_update'				=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ),
			'install_link'						=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link'						=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'							=> __( 'Return to Required Plugins Installer', 'mpcth' ),
			'plugin_activated'					=> __( 'Plugin activated successfully.', 'mpcth' ),
			'complete'							=> __( 'All plugins installed and activated successfully. %s', 'mpcth' ),
			'nag_type'							=> 'updated'
		)
	);

	tgmpa( $plugins, $config );
}

// Remove default VC updater
add_filter('upgrader_pre_download', 'removeUpgradeFilterFromEnvato', 20);
function removeUpgradeFilterFromEnvato($reply) {
	if (is_wp_error($reply) && ! empty($reply->errors['no_credentials'])) {
		return false;
	}

	return $reply;
}

add_action('in_plugin_update_message-js_composer/js_composer.php', 'mpcth_add_VC_wrap');
function mpcth_add_VC_wrap( $args ) {
	echo '<span class="mpc-hide-url"></span>';
}