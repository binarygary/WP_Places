<?php
/**
 * WP_Places Place Data
 *
 * @since NEXT
 * @package WP_Places
 */

/**
 * WP_Places Place Data.
 *
 * @since NEXT
 */
class WPP_Place_Data {
	/**
	 * Parent plugin class
	 *
	 * @var   WP_Places
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  WP_Places $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {
	}
}
