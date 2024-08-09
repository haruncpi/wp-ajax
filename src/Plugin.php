<?php
/**
 * Main Plugin Class
 *
 * @package Ajax
 * @author Harun <harun.cox@gmail.com>
 */

namespace Ajax;

use Ajax\Hooks\AdminMenu;
use Ajax\Hooks\Ajax;
use Ajax\Hooks\Assets;
use Ajax\Updater\Updater;

/**
 * Class Plugin
 */
class Plugin {
	/**
	 * Plugin instance
	 *
	 * @var self
	 */
	private static $instance;

	/**
	 * Prevent to create instance.
	 */
	private function __construct(){}

	/**
	 * Get plugin instance
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->bootstrap();
		}

		return self::$instance;
	}

	/**
	 * Load plugin required classes.
	 *
	 * @return void
	 */
	private function bootstrap() {
		new Database();
		new AdminMenu();
		new Assets();
		new Ajax();

		$config = array(
			'update_url'      => 'https://raw.githubusercontent.com/haruncpi/wp-ajax/master/src/Updater/plugin.json',
			'plugin_file'     => AJAX_FILE,
			'current_version' => AJAX_VERSION,
		);

		Updater::setup( $config );
	}
}
