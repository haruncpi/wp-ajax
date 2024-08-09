<?php
/**
 * Plugin Name: Ajax
 * Description: Ajax request testing
 * Author: haruncpi
 * Version: 1.0.0
 * Author URI: https://github.com/haruncpi
 * Requires PHP: 7.4
 * Requires at least: 5.3
 * Tested up to: 6.6
 * License: GPLv2 or later
 *
 * @package Ajax
 */

use Ajax\Plugin;

require_once __DIR__ . '/vendor/autoload.php';

define( 'AJAX_VERSION', '1.0.0' );
define( 'AJAX_FILE', __DIR__ );
define( 'AJAX_DIR', plugin_dir_path( __FILE__ ) );
define( 'AJAX_URL', plugin_dir_url( __FILE__ ) );

Plugin::get_instance();
