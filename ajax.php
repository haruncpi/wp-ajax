<?php
/**
 * Plugin Name: Ajax
 * Description: Ajax request testing
 * Author: haruncpi
 * Version: 1.0.6
 * Author URI: https://github.com/haruncpi
 * Requires PHP: 7.4
 * Requires at least: 5.3
 * Tested up to: 6.6
 * License: GPLv2 or later
 *
 * @package Ajax
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/bootstrap.php';

Ajax\Plugin::get_instance();
