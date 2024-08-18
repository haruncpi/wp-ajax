<?php
/**
 * Assets
 *
 * @package Ajax
 * @author Harun <harun.cox@gmail.com>
 */

namespace Ajax\Hooks;

use Ajax\Core\BasePlugin;

/**
 * Class Assets
 */
class Assets extends BasePlugin {
	/**
	 * Register hooks
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_assets' ) );
	}

	/**
	 * Load assets
	 *
	 * @return void
	 */
	public function load_admin_assets() {
		wp_enqueue_style( 'ajax-admin-css', $this->admin_asset_url . '/css/style.css', array(), $this->plugin_version );

		wp_enqueue_script( 'ajax-angularjs', $this->admin_asset_url . '/libs/angular.min.js', array(), $this->plugin_version, true );
		wp_enqueue_style( 'ajax-json-viewer-css', $this->admin_asset_url . '/libs/json-viewer/jquery.json-viewer.css', array(), $this->plugin_version );
		wp_enqueue_script( 'ajax-json-viewer-js', $this->admin_asset_url . '/libs/json-viewer/jquery.json-viewer.js', array(), $this->plugin_version, true );

		wp_enqueue_script( 'ajax-admin-js', $this->admin_asset_url . '/js/angular-app.js', array( 'ajax-angularjs' ), $this->plugin_version, true );

		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );

		wp_localize_script(
			'ajax-admin-js',
			'_ajax',
			array(
				'version'           => AJAX_VERSION,
				'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
				'updateUrl'         => AJAX_UPDATE_URL,
				'pluginUrl'         => plugin_dir_url( AJAX_FILE ),
				'pluginUpdateNonce' => wp_create_nonce( 'updates' ),
			)
		);
	}
}
