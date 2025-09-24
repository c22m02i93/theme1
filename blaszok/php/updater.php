<?php

if ( class_exists( 'MPC_Theme_Updater' ) ) {
	return;
}

class MPC_Theme_Updater {
	public $slug;
	private $short_slug;
	private $theme_data;
	private $theme_file;
	private $theme_update;
	private $update_urls = array();
	private $purchase_code;

    /*
    *   Theme Updater constructor function
    *
    *   $plugin_file absolute path tho plugin main file
    *   $slug i.e. 'blaszok'
    *   $short_slug i.e. 'blaszok'
    *
    */
	function __construct( $theme_file ) {
		if ( is_admin() ) {
            $this->theme_file = $theme_file;
			$this->slug       = wp_basename( $this->theme_file );

            $this->short_slug = 'blaszok';

            if ( $this->slug === '' ){
                return;
			}

			$this->set_purchase_code();

			if( $this->purchase_code === '' || strlen( $this->purchase_code ) !== 36 ) {
				return;
			}

            add_filter( 'pre_set_site_transient_update_themes', array( $this, 'set_transient' ) );
			add_filter( 'themes_api', array( $this, 'set_theme_info' ), 1000, 3 );

			add_action( 'in_theme_update_message-' . $this->slug, array( $this, 'purchase_code_notice' ), 10, 2 );
		}
	}

	private function init_theme_data() {
		if( empty( $this->theme_data ) ) {

			$this->update_urls = array(
				'main' => 'https://products.mpcthemes.net/api/updates/' . $this->short_slug . '/update.json'
			);

			if( function_exists( 'wp_get_theme' ) ) {
				$this->theme_data = wp_get_theme( $this->short_slug );
			}
		}
	}

	public function purchase_code_notice( $theme_data, $theme_update, $return = false ) {
		$purchase_code = $this->purchase_code;

		if( $purchase_code !== '' && strlen( $purchase_code ) === 36 ) {
			return '';
		}

		$notice = '<br/><strong>'. __( 'Automatic update is possible only with valid purchase code. Please include your purchase code at ', 'rfbwp' ) . '<a href="' . get_admin_url(). 'admin.php?page=rfbwp_cooperation_page_options">' . __( 'Flip Books -> License & Advanced Settings', 'rfbwp' ) . '.</a></strong>';

		if( $return ) {
			return $notice;
		}

		echo $notice;
		return '';
	}

	public function get_update_info() {
		$http_args = array(
			'timeout' => 15,
		);

        $download_link = 'main';

		$request = wp_remote_get( $this->update_urls[ 'main' ], $http_args );

		if ( is_wp_error( $request ) ) {
			$res = new WP_Error( 'themes_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), $request->get_error_message() );
		} else {
			$res = json_decode( wp_remote_retrieve_body( $request ), true );

			if ( ! is_array( $res ) ) {
				$res = new WP_Error( 'themes_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), wp_remote_retrieve_body( $request ) );
			}

			$res[ 'external' ] = true;

			$purchase_code = $this->purchase_code;

			if( isset( $purchase_code ) && $purchase_code != '' ) {
				$res[ 'package' ] = str_replace( '/'. $this->short_slug . '/update.json', '/download.php', $this->update_urls[ $download_link ] );
				$res[ 'package' ] = add_query_arg( array( 'key' => $purchase_code, 'product' => $this->short_slug ), $res[ 'package' ] );
			} else {
				$res[ 'package' ] = 'not_authorized';
			}
		}

        $this->theme_update = $res;
	}

	public function set_transient( $transient ) {

		if ( isset( $transient->response[ $this->slug ] ) ) {
			return $transient;
		}

		$this->init_theme_data();
		$this->get_update_info();

		if( isset( $this->theme_update[ 'version' ] ) && isset( $this->slug ) && isset( $this->theme_data[ 'Version' ] ) ) {
			$do_update = version_compare( $this->theme_update[ 'version' ], $this->theme_data[ 'Version' ] );
		} else {
			return $transient;
		}

		if ( $do_update === 1 ) {
			$this->theme_update[ 'theme' ]          = $this->theme_update[ 'slug' ];
			$this->theme_update[ 'url' ]            = $this->theme_update[ 'homepage' ];
			$this->theme_update[ 'new_version' ]    = $this->theme_update[ 'version' ];
			$this->theme_update[ 'version' ]        = $this->theme_data[ 'Version' ];
			$this->theme_update[ 'package' ]        = $this->theme_update[ 'package' ];
			$this->theme_update[ 'upgrade_notice' ] = $this->purchase_code_notice( null, null, true );

			$transient->response[ $this->slug ] = $this->theme_update;
		}

		return $transient;
	}

	public function set_theme_info( $res, $action, $args ) {
        $this->init_theme_data();

		if( $action !== 'theme_information' || false === strpos( $this->slug, $args->slug ) ) {
			return $res;
		}

		$this->get_update_info();

		if( $this->theme_update ) {
			$res = $this->theme_update;
		}

		return $res;
	}

	public function set_purchase_code() {
		global $mpcth_options;

		$this->purchase_code = isset( $mpcth_options[ 'mpcth_purchase_code' ] ) ? $mpcth_options[ 'mpcth_purchase_code' ] : '';
	}
}