<?php
/**
 * WP_Places Meta_boxes
 *
 * @since   NEXT
 * @package WP_Places
 */

/**
 * WP_Places Meta_boxes.
 *
 * @since NEXT
 */

class WPP_Meta_boxes {

	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Metabox id
	 */
	protected $metabox_id = 'wp_places';

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
		if ( $this->plugin->settings->places_api_key() ) {
			add_action( 'cmb2_init', array( $this, 'add_meta_box' ) );
			add_action( 'admin_head', array( $this, 'legacy_transition' ) );
		}
	}

	/**
	 * Add metabox to posts.
	 *
	 * @author Gary Kovar
	 *
	 * @since  2.0.0
	 *
	 * @return void
	 */
	public function add_metabox() {

		$cmb = new_cmb2_box( array(
			'id'           => $this->metabox_id,
			'title'        => 'Place Location',
			'object_types' => $this->plugin->settings->selected_post_types(),
		) );

		$cmb->add_field( array(
			'name'            => __( 'Place Address', 'wp_places' ),
			'id'              => '_wp_places',
			'type'            => 'text',
			'sanitization_cb' => array( $this, 'get_google_place_id' ),
			'escape_cb'       => array( $this, 'display_place_information' ),
		) );

	}

	/**
	 * Search for the places id
	 *
	 * @author Gary Kovar
	 *
	 * @since  2.0.0
	 *
	 * @param $value
	 * @param $field_args
	 * @param $field
	 *
	 * @return mixed
	 */
	public function get_google_place_id( $value, $field_args, $field ) {
		return $this->plugin->google_places_api->search( $value );
	}

	/**
	 * Get information about the place
	 *
	 * @author Gary Kovar
	 *
	 * @since  2.0.0
	 *
	 * @param $value
	 * @param $field_args
	 * @param $field
	 *
	 * @return mixed
	 */
	public function display_place_information( $value, $field_args, $field ) {
		return $this->plugin->place_data->get_standard_address( $value );
	}

	/**
	 * If the old post_meta is set, copy to the new meta key.
	 *
	 * @author Gary Kovar
	 *
	 * @since  2.0.0
	 *
	 * @return null
	 */
	public function legacy_transition() {
		global $post;
		if ( ! isset( $post )  ) {
			return;
		}
		if ( $old_meta = get_post_meta( $post->ID, '_WP_Places_meta_Google_response', true ) ) {
			update_post_meta( $post->ID, '_wp_places', $old_meta );
			delete_post_meta( $post->ID, '_WP_Places_meta_Google_response' );
		}

	}

}
