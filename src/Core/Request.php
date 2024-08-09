<?php
/**
 * Request
 *
 * @package Ajax
 * @author Harun <harun.cox@gmail.com>
 */

namespace Ajax\Core;

/**
 * Request Class
 */
class Request {
	/**
	 * WP sanitizer functions
	 */
	const TEXT      = 'sanitize_text_field';
	const TEXTAREA  = 'sanitize_textarea_field';
	const KSES_POST = 'wp_kses_post';

	/**
	 * Check request has key.
	 *
	 * @param string $key key.
	 * @return boolean
	 */
	public static function has( $key ) {
		//phpcs:ignore
		return isset( $_REQUEST[ $key ] );
	}

	/**
	 * Retrieve value from request.
	 *
	 * @param string $key key.
	 * @param mixed  $default default value if value does not exist.
	 * @param string $sanitizer sanitizer function. Example sanitize_text_field.
	 *
	 * @return mixed
	 */
	public static function get( $key, $default = null, $sanitizer = self::TEXT ) {
		if ( ! self::has( $key ) ) {
			return $default;
		}

		//phpcs:ignore
		return call_user_func( $sanitizer, wp_unslash( $_REQUEST[ $key ] ) );
	}

}
