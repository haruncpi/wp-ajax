<?php
/**
 * Admin Menu Register
 *
 * @package Ajax
 * @author Harun <harun.cox@gmail.com>
 */

namespace Ajax\Hooks;

use Ajax\Core\BasePlugin;
use Ajax\Core\Request;

/**
 * Class AdminMenu
 */
class AdminMenu extends BasePlugin {

	/**
	 * Register hooks
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	/**
	 * Admin menu for wp tinker.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		$admin_menu_text = 'Ajax Inspector';
		$parent_slug     = 'ajax';
		$position        = 10;

		add_menu_page(
			$admin_menu_text,
			$admin_menu_text,
			'manage_options',
			$parent_slug,
			array( $this, 'show_ajax_page' ),
			'dashicons-editor-code',
			$position
		);
	}

	/**
	 * Show ajax page.
	 *
	 * @return void
	 */
	public function show_ajax_page() {
		if ( Request::has( 'view' ) ) {
			$view      = Request::get( 'view' );
			$view_path = $this->view_dir . "/$view.php";
			if ( file_exists( $view_path ) ) {
				include_once $view_path;
			}

			return;
		}

		include_once $this->view_dir . '/index-angular.php';
	}
}
