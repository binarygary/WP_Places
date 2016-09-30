<?php
/**
 * WP_Places Google_places_api
 *
 * @since NEXT
 * @package WP_Places
 */

/**
 * WP_Places Google_places_api.
 *
 * @since NEXT
 */
class WPP_Google_places_api {
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
