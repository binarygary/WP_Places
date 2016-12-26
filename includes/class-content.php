<?php
/**
 * WP_Places Content
 *
 * @since   NEXT
 * @package WP_Places
 */

// @TODO need to test...maybe some issues with the meta naming?
/**
 * WP_Places Content.
 *
 * @since NEXT
 */
class WPP_Content {

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
		if ( $this->plugin->settings->embed_div() ) {
			add_filter( 'the_content', array( $this, 'WP_Places_add_before_content' ) );
		}
	}

	function WP_Places_add_before_content( $content ) {

		global $post;

		// This is to handle legacy.
		if ( $old_meta = get_post_meta( $post->ID, '_WP_Places_meta_Google_response', true ) ) {
			update_post_meta( $post->ID, '_wp_places', $old_meta );
			delete_post_meta( $post->ID, '_WP_Places_meta_Google_response' );
		}
		$locationPlace = get_post_meta( get_the_ID(), '_wp_places', true );


		// Let's go ahead and cache this
		if ( false === ( $placeArray = get_transient( "_Wp_Places_$locationPlace" ) ) ) {
			$placeArray = $this->plugin->google_places_api->placeDetails( $locationPlace );
			set_transient( "_Wp_Places_$locationPlace", $placeArray, MINUTE_IN_SECONDS );
		}


		if ( ! is_array( $placeArray ) ) {
			return $content;
		}

		if ( array_key_exists( 'name', $placeArray ) ) {
			$style    = get_option( 'wp_places_settings', '' );
			$WpPlaces = '<DIV style="' . $style['style'] . '">';

			if ( array_key_exists( 'openNow', $placeArray ) ) {
				if ( $placeArray['openNow'] == 1 ) {
					$WpPlaces .= "<span style=\"color: red;\">Open Now</SPAN><BR>";
				}
			}


			if ( array_key_exists( 'permanentlyClosed', $placeArray ) ) {
				if ( $placeArray['permanentlyClosed'] == 1 ) {
					$WpPlaces .= "<span style=\"color: red;\">PERMANENTLY CLOSED</SPAN><BR>";
				}
			}

			$WpPlaces .= "<B>$placeArray[name]</B><BR>";

			if ( array_key_exists( 'formattedAddress', $placeArray ) ) {
				$WpPlaces .= "<div itemprop=address itemscope itemtype=http://schema.org/PostalAddress>$placeArray[formattedAddress]<BR></div>";
			}
			if ( array_key_exists( 'phoneNumber', $placeArray ) ) {
				$WpPlaces .= "<span itemprop=telephone>$placeArray[phoneNumber]</span><BR>";
			}
			if ( array_key_exists( 'hours', $placeArray ) ) {
				if ( is_array( $placeArray['hours'] ) ) {
					foreach ( $placeArray['hours'] as $day ) {
						$WpPlaces .= "$day<BR>";
					}
				}
			}
			if ( array_key_exists( 'website', $placeArray ) ) {
				$WpPlaces .= "<a href=$placeArray[website] itemprop=url>website</a><BR>";
			}
			if ( get_option( 'WP_Places_Google_Attr_Setting_check' ) == 'googlecheck' ) {
				$WpPlaces .= "<img src=" . plugins_url( 'img/powered_by_google_on_white.png', __FILE__ ) . ">";
			}
			$WpPlaces .= "</DIV>";
		}
		if ( is_single() ) {
			$contents = explode( "</p>", $content );
			if ( is_array( $contents ) ) {
				$added = 0;
				foreach ( $contents as $paragraph ) {
					if ( $added != 1 ) {
						$paragraph = $paragraph . $WpPlaces;
						$added     = 1;
						$content   = null;
					}
					$content .= $paragraph . "</p>";
				}
			} else {
				$content = $WpPlaces . $content;
			}


			return $content;
		} else {
			return $content;
		}
	}
}
