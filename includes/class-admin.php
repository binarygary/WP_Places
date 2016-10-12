<?php
/**
 * WP_Places Admin
 *
 * @since NEXT
 * @package WP_Places
 */

/**
 * WP_Places Admin.
 *
 * @since NEXT
 */
class WPP_Admin {
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
	 *
	 * @param  object $plugin Main plugin object.
	 *
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
		if ( is_array( $this->plugin->settings->selected_post_types() ) ) {
			$this->add_admin_views();
		}
	}


	/**
	 * Add admin views for each post type selected.
	 *
	 * @author Gary Kovar
	 *
	 * @since 2.0.0
	 *
	 * @return null
	 */
	public function add_admin_views() {
		foreach ( $this->plugin->settings->selected_post_types() as $post_type ) {

			if ( 'post' == $post_type ) {
				add_filter( 'manage_posts_columns', array( $this, 'add_wpplaces_column' ) );
				add_action( 'manage_posts_custom_column', array( $this, 'wp_places_show_column' ) );

			} else if ( 'page' == $post_type ) {
				add_filter( 'manage_pages_columns', array( $this, 'add_wpplaces_column' ) );
				add_action( 'manage_pages_custom_column', array( $this, 'wp_places_show_column' ) );
			} else {
				add_filter( 'manage_' . $post_type . '_posts_columns', array( $this, 'add_wpplaces_column' ) );
				add_action( 'manage_' . $post_type . 'posts_custom_column', array( $this, 'wp_places_show_column' ) );
			}

		}
	}


	/**
	 * Add a column for WP Places data.
	 *
	 * @author Gary Kovar
	 *
	 * @since 2.0.0
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function add_wpplaces_column( $columns ) {

		$columns[ 'wp_places' ] = 'WP Places';

		return $columns;

	}


	/**
	 * Show the data in the new column.
	 *
	 * @author Gary Kovar
	 *
	 * @since 2.0.0
	 *
	 * @param $name
	 */
	function wp_places_show_column( $name ) {
		global $post;
		switch ( $name ) {
			case 'wp_places':
				if ( ! null == get_post_meta( $post->ID, '_WP_Places_meta_Google_response', true ) ) {
					$googleResponse = $this->plugin->google_places_api->placeDetails( get_post_meta( $post->ID, '_WP_Places_meta_Google_response', true ) );
					echo $googleResponse[ 'name' ] . "<BR>";
					echo $googleResponse[ 'formattedAddress' ];
				}
		}
	}

}
