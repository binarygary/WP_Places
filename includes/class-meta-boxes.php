<?php
/**
 * WP_Places Meta_boxes
 *
 * @since NEXT
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
		add_action( 'cmb2_init', array( $this, 'setup_meta_box' ) );
	}

	/**
	 * Check and see if this page should have the wp_places metabox
	 *
	 * @author Gary Kovar
	 *
	 * @since 2.0.0
	 *
	 * @return null
	 */
	public function setup_meta_box() {
		if ( in_array( $this->get_current_post_type(), $this->plugin->settings->selected_post_types() ) ) {
			$this->add_metabox();
		}
	}

	/**
	 * Add metabox to posts.
	 *
	 * @author Gary Kovar
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function add_metabox() {

		$cmb = new_cmb2_box( array(
			'id'           => $this->metabox_id,
			'title'        => 'Place Location',
			'object_types' => array( 'post' ),
		) );

		$cmb->add_field( array(
			'name'            => __( 'Place Address', 'wp_places' ),
			'id'              => '_wp_places',
			'type'            => 'text',
			'sanitization_cb' => array( $this, 'get_google_place_id' ),
			'escape_cb'       => array( $this, 'display_place_information' ),
		) );

	}


	public function get_google_place_id( $value, $field_args, $field ) {
		return $value;
	}

	public function display_place_information( $value, $field_args, $field ) {
		return $value;
	}


	/**
	 * Get the current post type.
	 *
	 * @author DomenicF on github (https://gist.github.com/DomenicF/3ebcf7d53ce3182854716c4d8f1ab2e2)
	 *
	 * @since 2.0.0
	 *
	 * @return false|null|string
	 */
	function get_current_post_type() {
		global $post, $typenow, $current_screen;
		//we have a post so we can just get the post type from that
		if ( $post && $post->post_type ) {
			return $post->post_type;
		} //check the global $typenow - set in admin.php
		elseif ( $typenow ) {
			return $typenow;
		} //check the global $current_screen object - set in sceen.php
		elseif ( $current_screen && $current_screen->post_type ) {
			return $current_screen->post_type;
		} //check the post_type querystring
		elseif ( isset( $_REQUEST[ 'post_type' ] ) ) {
			return sanitize_key( $_REQUEST[ 'post_type' ] );
		} //lastly check if post ID is in query string
		elseif ( isset( $_REQUEST[ 'post' ] ) ) {
			return get_post_type( $_REQUEST[ 'post' ] );
		}

		//we do not know the post type!
		return null;
	}

}
