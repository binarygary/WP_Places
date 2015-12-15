<?php
/**
 * Plugin Name: WP_Places
 * Version: 0.1-alpha
 * Description: Given location name saved with a Post search Google Places API Web Service and displays address, hours, phone number and link to website
 * Author: Gary Kovar
 * Author URI: http://binarygary.com
 * Plugin URI: http://www.binarygary.com/plugins/wp_places-plugin/
 * Text Domain: WP_PlacesReviews
 * @package WP_Places
 * License: GPL v3
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once(dirname(__FILE__) . "/includes/googlePlaces.php");


//Get the users google places key
function WP_Places_add_settings_field() {
	register_setting('general', 'WP_Places_Google_Id_Setting', 'esc_attr');
	add_settings_field('WP_Places_Google_Id_Setting', '<label for="WP_Places_Google_Id_Setting">'.__('Google ID' , 'WP_Places_Google_Id_Setting' ).'</label>' , 'print_custom_field', 'general');

}
function print_custom_field()
{
    $value = get_option( 'WP_Places_Google_Id_Setting', '' );
    echo '<input type="text" id="WP_Places_Google_Id_Setting" name="WP_Places_Google_Id_Setting" value="' . $value . '" />';
}
add_action ( 'admin_init', 'WP_Places_add_settings_field' );


/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function WP_Places_add_meta_box() {

	$screens = array( 'post', 'page' );

	foreach ( $screens as $screen ) {

		add_meta_box(
			'WP_Places_sectionid',
			__( 'WP Places', 'WP_Places_textdomain' ),
			'WP_Places_meta_box_callback',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'WP_Places_add_meta_box' );

/**
 * Prints the box content.
 */
function WP_Places_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'WP_Places_save_meta_box_data', 'WP_Places_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, '_WP_Places_meta_value_key', true );

	echo '<label for="WP_Places_new_field">';
	_e( 'Name and Address', 'WP_Places_textdomain' );
	echo '</label> ';
	echo '<input type="text" id="WP_Places_new_field" name="WP_Places_new_field" value="' . esc_attr( $value ) . '" size="25" />';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function WP_Places_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['WP_Places_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['WP_Places_meta_box_nonce'], 'WP_Places_save_meta_box_data' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['WP_Places_new_field'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['WP_Places_new_field'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_WP_Places_meta_value_key', $my_data );
	
	//Check with the Google and grab the meta
	//_WP_Places_meta_places_id, _WP_Places_meta_hours, _WP_Places_meta_reviews, _WP_Places_meta_closed, _WP_Places_meta_lat, _WP_Places_meta_lon, 
	$result=search($my_data);
	//print_r($result);
	update_post_meta( $post_id, '_WP_Places_meta_Google_response', $result);
	
	
	
}
add_action( 'save_post', 'WP_Places_save_meta_box_data' );



function WP_Places_add_before_content($content) {
	$locationPlace=get_post_meta(get_the_ID(),'_WP_Places_meta_Google_response', true);
	//print_r($array);
	//let's go ahead and cache this
	
	if ( false === ( $placeArray = get_transient( '_Wp_Places_$locationPlace' ) ) ) {
	     $placeArray = placeDetails($locationPlace);
	     set_transient( "_Wp_Places_$locationPlace", $placeArray, DAY_IN_SECONDS );
	}
	
	
	if(!NULL==$placeArray[name]) {
		$WpPlaces.="<DIV style=\"float: right; border: 1px black solid; bgcolor=#f1f1f1; padding: 10px; background-color: #cccccc; font-size: 12px; max-width: 250px; margin: auto;\">";
		if (isset($placeArray[permanentlyClosed])) {
			$WpPlaces.="<span style=\"color: red;\">PERMANENTLY CLOSED</SPAN><BR>";
		}
		if (isset($placeArray[name])) {
			$WpPlaces.="<B>$placeArray[name]</B><BR>";
		}
		if (isset($placeArray[formattedAddress])) {
			$WpPlaces.="$placeArray[formattedAddress]<BR>";
		}
		if (isset($placeArray[phoneNumber])) {
			$WpPlaces.="$placeArray[phoneNumber]<BR>";
		}
		if (isset($placeArray[hours])) {
			//the hell happened with open now?
			foreach ($placeArray[hours] as $day) {
				$WpPlaces.="$day<BR>";
			}
		}
		if (isset($placeArray[website])) {
			$WpPlaces.="<a href=$placeArray[website]>website</a><BR>";
		}
		$WpPlaces.="<img src=".plugins_url('img/powered_by_google_on_white.png', __FILE__) . "/>";
		$WpPlaces.="</DIV>";
	}
	if (is_single()) {
		return $WpPlaces.$content;
	} else {
	    return $content;
	}
}
add_filter('the_content', 'WP_Places_add_before_content');