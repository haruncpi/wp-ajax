<?php
/**
 * Database
 *
 * @package Ajax
 * @author Harun <harun.cox@gmail.com>
 */

namespace Ajax;

/**
 * Class Database
 */
class Database {

	/**
	 * Register hooks
	 */
	public function __construct() {
		global $wpdb;
		$wpdb->ajax_lists = $wpdb->prefix . 'ajax_lists';

		register_activation_hook( AJAX_FILE, array( $this, 'create_tables' ) );
	}

	/**
	 * Create tables
	 *
	 * @return void
	 */
	public static function create_tables() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$list_table = "CREATE TABLE IF NOT EXISTS {$wpdb->ajax_lists} (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			user_id bigint(20) NOT NULL,
			category_id bigint(20) DEFAULT NULL,
			title text DEFAULT NULL,
			payload text DEFAULT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $list_table );
	}
}
