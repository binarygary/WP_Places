<?php
/**
 * WP_Places Place Data
 *
 * @since   NEXT
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
	 *
	 * @param  WP_Places $plugin Main plugin object.
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
	}

	public function load_place( $place_id ) {
		if ( false === ( $this->raw_results = get_transient( "_WP_Places_$place_id" ) ) ) {
			$this->raw_results = $this->plugin->google_places_api->placeDetails( $place_id );
			set_transient( "_WP_Places_$place_id", $this->raw_results, MINUTE_IN_SECONDS );
		}
		$this->hours              = isset( $this->raw_results[ 'result' ][ 'opening_hours' ][ 'weekday_text' ] ) ? $this->raw_results[ 'result' ][ 'opening_hours' ][ 'weekday_text' ] : '';//
		$this->open_now           = isset( $this->raw_results[ 'result' ][ 'opening_hours' ][ 'open_now' ] ) ? $this->raw_results[ 'result' ][ 'opening_hours' ][ 'open_now' ] : '';
		$this->priceLevel         = isset( $this->raw_results[ 'result' ][ 'price_level' ] ) ? $this->raw_results[ 'result' ][ 'price_level' ] : '';
		$this->name               = isset( $this->raw_results[ 'result' ][ 'name' ] ) ? $this->raw_results[ 'result' ][ 'name' ] : '';//
		$this->rating             = isset( $this->raw_results[ 'result' ][ 'rating' ] ) ? $this->raw_results[ 'result' ][ 'rating' ] : '';
		$this->phone_number       = isset( $this->raw_results[ 'result' ][ 'formatted_phone_number' ] ) ? $this->raw_results[ 'result' ][ 'formatted_phone_number' ] : '';//
		$this->website            = isset( $this->raw_results[ 'result' ][ 'website' ] ) ? $this->raw_results[ 'result' ][ 'website' ] : '';//
		$this->lat                = isset( $this->raw_results[ 'result' ][ 'geometry' ][ 'location' ][ 'lat' ] ) ? $this->raw_results[ 'result' ][ 'geometry' ][ 'location' ][ 'lat' ] : '';
		$this->lng                = isset( $this->raw_results[ 'result' ][ 'geometry' ][ 'location' ][ 'lng' ] ) ? $this->raw_results[ 'result' ][ 'geometry' ][ 'location' ][ 'lng' ] : '';
		$this->formatted_address  = isset( $this->raw_results[ 'result' ][ 'formatted_address' ] ) ? $this->raw_results[ 'result' ][ 'formatted_address' ] : '';//
		$this->permanently_closed = isset( $this->raw_results[ 'result' ][ 'permanently_closed' ] ) ? $this->raw_results[ 'result' ][ 'permanently_closed' ] : '';
		$this->reviews            = isset( $this->raw_results[ 'result' ][ 'reviews' ] ) ? $this->raw_results[ 'result' ][ 'reviews' ] : '';
		$this->photos             = isset( $this->raw_results[ 'result' ][ 'photos' ] ) ? $this->raw_results[ 'result' ][ 'photos' ] : '';
	}

	public function get_standard_address( $place_id ) {
		if (!isset($this->raw_results)){
			$this->load_place($place_id);
		}
		return $this->name . ' ' . $this->formatted_address;
	}
}
