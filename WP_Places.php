<?php
/**
 * Plugin Name: WP_Places
 * Plugin URI:  https://www.binarygary.com/
 * Description: Add location data to your WordPress.
 * Version:     2.0.0
 * Author:      Gary Kovar
 * Author URI:  https://www.binarygary.com/
 * Donate link: https://www.binarygary.com/
 * License:     GPLv2
 * Text Domain: wp-places
 * Domain Path: /languages
 *
 * @link https://www.binarygary.com/
 *
 * @package WP_Places
 * @version 2.0.0
 */

/**
 * Copyright (c) 2016 Gary Kovar (email : plugins@binarygary.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using generator-plugin-wp
 */


/**
 * Autoloads files with classes when needed
 *
 * @since  NEXT
 *
 * @param  string $class_name Name of the class being requested.
 *
 * @return void
 */
function wp_places_autoload_classes( $class_name ) {
	if ( 0 !== strpos( $class_name, 'WPP_' ) ) {
		return;
	}

	$filename = strtolower( str_replace(
		'_', '-',
		substr( $class_name, strlen( 'WPP_' ) )
	) );

	WP_Places::include_file( 'includes/class-' . $filename );
}

spl_autoload_register( 'wp_places_autoload_classes' );

/**
 * Main initiation class
 *
 * @since  NEXT
 */
final class WP_Places {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  NEXT
	 */
	const VERSION = '2.0.0';

	/**
	 * URL of plugin directory
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $url = '';

	/**
	 * Path of plugin directory
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $path = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $basename = '';

	/**
	 * Singleton instance of plugin
	 *
	 * @var WP_Places
	 * @since  NEXT
	 */
	protected static $single_instance = null;

	/**
	 * Instance of WPP_Settings
	 *
	 * @since NEXT
	 * @var WPP_Settings
	 */
	protected $settings;

	/**
	 * Instance of WPP_Meta_boxes
	 *
	 * @since NEXT
	 * @var WPP_Meta_boxes
	 */
	protected $meta_boxes;

	/**
	 * Instance of WPP_Shortcodes
	 *
	 * @since NEXT
	 * @var WPP_Shortcodes
	 */
	protected $shortcodes;

	/**
	 * Instance of WPP_Google_places_api
	 *
	 * @since NEXT
	 * @var WPP_Google_places_api
	 */
	protected $google_places_api;

	/**
	 * Instance of WPP_Content
	 *
	 * @since NEXT
	 * @var WPP_Content
	 */
	protected $content;

	/**
	 * Instance of WPP_Admin
	 *
	 * @since NEXT
	 * @var WPP_Admin
	 */
	protected $admin;

	/**
	 * Instance of WPP_Place_Data
	 *
	 * @since NEXT
	 * @var WPP_Place_Data
	 */
	protected $place_data;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  NEXT
	 * @return WP_Places A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  NEXT
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function plugin_classes() {
		// Attach other plugin classes to the base plugin class.
		$this->settings          = new WPP_Settings( $this );
		$this->meta_boxes        = new WPP_Meta_boxes( $this );
		$this->shortcodes        = new WPP_Shortcodes( $this );
		$this->google_places_api = new WPP_Google_places_api( $this );
		$this->content           = new WPP_Content( $this );
		$this->admin             = new WPP_Admin( $this );
		$this->place_data = new WPP_Place_Data( $this );
		require( self::dir( 'includes/class-map.php' ) );
		require( self::dir( 'includes/class-hours.php' ) );
	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'admin_init' ), 0);
		add_action( 'init', array( $this, 'init' ), 0 );
		require_once( $this->path . 'includes/map-shortcode.php' );
	}

	/**
	 * Activate the plugin
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function _activate() {
		// Make sure any rewrite functionality has been loaded.
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function _deactivate() {
	}

	/**
	 * Admin Init Hooks
	 */
	public function admin_init() {
		$map_plugin = WP_PLUGIN_DIR . 'wp-places-map/wp-places-map.php';
		if( is_plugin_active( $map_plugin ) ) {
			deactivate_plugin( $map_plugin );
		}
	}

	/**
	 * Init hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function init() {
		if ( $this->check_requirements() ) {
			load_plugin_textdomain( 'wp-places', false, dirname( $this->basename ) . '/languages/' );
			$this->plugin_classes();
		}
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  NEXT
	 * @return boolean result of meets_requirements
	 */
	public function check_requirements() {
		if ( ! $this->meets_requirements() ) {

			// Add a dashboard notice.
			add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

			// Deactivate our plugin.
			add_action( 'admin_init', array( $this, 'deactivate_me' ) );

			return false;
		}

		return true;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function deactivate_me() {

		// We do a check for deactivate_plugins before calling it, to protect
		// any developers from accidentally calling it too early and breaking things.
		if ( function_exists( 'deactivate_plugins' ) ) {
			deactivate_plugins( $this->basename );
		}
	}

	/**
	 * Check that all plugin requirements are met
	 *
	 * @since  NEXT
	 * @return boolean True if requirements are met.
	 */
	public function meets_requirements() {
		// Do checks for required classes / functions
		// function_exists('') & class_exists('').
		// We have met all requirements.
		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function requirements_not_met_notice() {
		// Output our error.
		echo '<div id="message" class="error">';
		echo '<p>' . sprintf( __( 'WP_Places is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'wp-places' ), admin_url( 'plugins.php' ) ) . '</p>';
		echo '</div>';
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  NEXT
	 *
	 * @param string $field Field to get.
	 *
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
			case 'settings':
			case 'meta_boxes':
			case 'shortcodes':
			case 'google_places_api':
			case 'content':
			case 'admin':
			case 'place_data':
				return $this->$field;
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory
	 *
	 * @since  NEXT
	 *
	 * @param  string $filename Name of the file to be included.
	 *
	 * @return bool   Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( $filename . '.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}

		return false;
	}

	/**
	 * This plugin's directory
	 *
	 * @since  NEXT
	 *
	 * @param  string $path (optional) appended path.
	 *
	 * @return string       Directory and path
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );

		return $dir . $path;
	}

	/**
	 * This plugin's url
	 *
	 * @since  NEXT
	 *
	 * @param  string $path (optional) appended path.
	 *
	 * @return string       URL and path
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );

		return $url . $path;
	}
}

/**
 * Grab the WP_Places object and return it.
 * Wrapper for WP_Places::get_instance()
 *
 * @since  NEXT
 * @return WP_Places  Singleton instance of plugin class.
 */
function wp_places() {
	return WP_Places::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( wp_places(), 'hooks' ) );

register_activation_hook( __FILE__, array( wp_places(), '_activate' ) );
register_deactivation_hook( __FILE__, array( wp_places(), '_deactivate' ) );
