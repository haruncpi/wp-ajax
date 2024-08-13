<?php
/**
 * Plugin bootstrap
 *
 * @package Ajax
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

define( 'AJAX_FILE', __DIR__ . '/ajax.php' );
define( 'AJAX_VERSION', get_plugin_data( AJAX_FILE )['Version'] );
define( 'AJAX_DIR', plugin_dir_path( AJAX_FILE ) );
define( 'AJAX_URL', plugin_dir_url( AJAX_FILE ) );
