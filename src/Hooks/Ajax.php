<?php
/**
 * Handle Ajax Request
 *
 * @package Ajax
 * @author Harun <harun.cox@gmail.com>
 */

namespace Ajax\Hooks;

use Ajax\Core\Request;

/**
 * Class Ajax
 */
class Ajax {
	/**
	 * Register hooks.
	 */
	public function __construct() {
		add_action( 'wp_ajax_ajax_list', array( $this, 'ajax_list' ) );
		add_action( 'wp_ajax_ajax_list_save', array( $this, 'ajax_save_list' ) );
		add_action( 'wp_ajax_ajax_list_remove', array( $this, 'ajax_list_remove' ) );
	}

	/**
	 * Get list
	 *
	 * @return void
	 */
	public function ajax_list() {
		global $wpdb;

		$user_id = get_current_user_id();

		$lists = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->ajax_lists} WHERE user_id = %d ORDER BY id DESC",
				$user_id
			)
		);

		wp_send_json_success( $lists );
	}

	/**
	 * Create and update list.
	 *
	 * @return void
	 */
	public function ajax_save_list() {
		$is_update = false;
		$id        = (int) Request::get( 'id' );
		$title     = Request::get( 'title', '' );
		$payload   = Request::get( 'payload', '', 'sanitize_textarea_field' );

		if ( $id ) {
			$is_update = true;
		}

		global $wpdb;

		$data = array(
			'title'   => $title,
			'payload' => $payload,
		);

		if ( ! $is_update ) {
			$data['user_id'] = get_current_user_id();
			$wpdb->insert(
				$wpdb->ajax_lists,
				$data
			);
		} else {
			$wpdb->update(
				$wpdb->ajax_lists,
				$data,
				array( 'id' => $id )
			);
		}

		wp_send_json_success();
	}

	/**
	 * List remove
	 *
	 * @return void
	 */
	public function ajax_list_remove() {
		$id = (int) Request::get( 'id' );

		global $wpdb;

		$wpdb->delete(
			$wpdb->ajax_lists,
			array( 'id' => $id )
		);

		wp_send_json_success();
	}
}
