<?php
/**
 * Base Plugin Class
 *
 * @package Ajax
 * @author Harun <harun.cox@gmail.com>
 */

namespace Ajax\Core;

/**
 * Class BasePlugin
 */
class BasePlugin {
    //phpcs:disable
	protected $plugin_dir;
	protected $plugin_version;
	protected $view_dir;
	protected $admin_asset_url;
    //phpcs:enable

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->plugin_version  = AJAX_VERSION;
		$this->plugin_dir      = AJAX_DIR;
		$this->view_dir        = $this->plugin_dir . '/src/Views';
		$this->admin_asset_url = AJAX_URL . 'assets/admin';
	}
}
