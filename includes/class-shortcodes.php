<?php
/**
 * WP_Places Shortcodes
 *
 * @since   NEXT
 * @package WP_Places
 */


//@TODO need to test...maybe some issues with the meta naming?
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
		add_shortcode( 'wp_places_search', array( $this, 'WP_Places_search_shortcode' ) );
		add_shortcode( 'wp_places', array( $this, 'WP_Places_shortcode' ) );
	}


	function WP_Places_search_shortcode( $attr ) {
		$post_id = get_the_ID();
		foreach ( $attr as $word => $value ) {
			if ( $word == 'lat' ) {
				update_post_meta( $post_id, '_WP_Places_meta_value_lat', $value );
			} elseif ( $word == 'lon' ) {
				update_post_meta( $post_id, '_WP_Places_meta_value_lon', $value );
			} else {
				$phrase = $phrase . " " . $value;
			}
		}
		update_post_meta( $post_id, '_wp_places', $phrase );
		if ( get_post_meta( get_the_ID(), '_WP_Places_meta_value_lat', true ) ) {
			$result = $this->google_places_api->searchGPS( $phrase, get_post_meta( get_the_ID(), '_WP_Places_meta_value_lat', true ), get_post_meta( get_the_ID(), '_WP_Places_meta_value_lon', true ) );
		} else {
			$result = $this->google_places_api->search( $phrase );
		}
		update_post_meta( $post_id, '_wp_places', $result );
	}


	function WP_Places_shortcode( $attr ) {
		$apikey          = $this->plugin->settings->get_api_key();
		$locationPlace   = get_post_meta( get_the_ID(), '_wp_places', true );
		$placeArray      = $this->plugin->google_places_api->placeDetails( $locationPlace );
		$attributesArray = array(
			"openNow",
			"openNowText",
			"permanentlyClosed",
			"name",
			"formattedAddress",
			"phoneNumber",
			"hours",
			"website",
			"priceLevel",
			"rating",
			"lat",
			"lng",
			"reviews",
			"photos",
		);
		foreach ( $attr as $index => $key ) {
			if ( in_array( $key, $attributesArray ) ) {
				if ( "hours" == $key ) {
					$hoursList = "<UL>";
					if ( count( $placeArray[ $key ] ) > 1 ) {
						foreach ( $placeArray[ $key ] as $hoursOfOperation ) {
							$hoursList .= "<LI>$hoursOfOperation";
						}
					}
					$hoursList .= "</UL>";

					return $hoursList;
				} elseif ( "reviews" == $key ) {
					if ( ! is_array( $placeArray[ 'reviews' ] ) ) {
						return null;
					}
					$reviewData = "<UL>";
					foreach ( $placeArray[ 'reviews' ] as $review ) {
						$reviewData .= "<LI><B>" . $review[ 'rating' ] . " out of 5</B> " . $review[ 'text' ] . " by <i><a href=$review[author_url]>$review[author_name]</i></a></LI>";
					}
					$reviewData .= "<UL>";

					return $reviewData;
				} elseif ( "photos" == $key ) {
					if ( ! is_array( $placeArray[ 'photos' ] ) ) {
						return null;
					}
					$photoList ='';
					foreach ( $placeArray[ 'photos' ] as $photo ) {
						$photoList .= "<img src=https://maps.googleapis.com/maps/api/place/photo?photoreference=$photo[photo_reference]&maxwidth=1024&key=$apikey>";
					}

					return $photoList;
				} elseif ( "openNowText" == $key ) {

					if ( $placeArray[ 'openNow' ] == 1 ) {
						return "Open Now!";
					}

				} else {
					return $placeArray[ $key ];
				}
			}
		}
	}


}
