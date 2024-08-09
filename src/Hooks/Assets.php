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

		wp_enqueue_script(
			'ajax-react-app',
			$this->admin_asset_url . '/js/react-app.js',
			array( 'wp-element' ),
			$this->plugin_version,
			true
		);

		wp_localize_script(
			'ajax-react-app',
			'_ajax',
			array(
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'pluginUrl' => plugin_dir_url( AJAX_FILE ),
			)
		);
	}
}
