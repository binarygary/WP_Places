<?php
/**
 * WP_Places Shortcodes
 *
 * @since NEXT
 * @package WP_Places
 */

/**
 * WP_Places Shortcodes.
 *
 * @since NEXT
 */
class WPP_Shortcodes {
	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
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
