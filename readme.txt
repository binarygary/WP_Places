=== Plugin Name ===
Contributors: binarygary
Tags: Google Places, Business Information, Location, Google Places API Web Services
Stable Tag: 1.1.4
Requires at least: 4.0
Tested up to: 4.4
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl.html


WP_Places displays up-to-date information about almost any location or business you can think of to write about.

== Description ==

If you find you regularly write blog posts about (local?) businesses you might want to provide info such as hours, phone number, address to your users.  However, this can be difficult to keep current.  Fortunately Google offers an API called Google Places API Web Service.   Google Place API Web Service allows you to Add up-to-date information about millions of locations.  

WP_Places Plugin requires a Google Places API Web Service Key. However, at the time of writing the API key is free and provides up to 1,000 requests per 24 hour period. If you verify your identity (by providing Google a Credit Card) they will increase your daily request per 24 hours to 150,000.

Once Installed, WP_Places takes name and location and displays a DIV containing Business Name, Address, Hours, Phone Number, Website.

The following shortcodes are available:
[wp_places name] displays the Google Places name
[wp_places formattedAddress] displays the address in the regionally standardized way
[wp_places phoneNumber] displays the phone number in the regionally standardized way
[wp_places hours] displays the hours of operation in a list
[wp_places website] displays the website
[wp_places priceLevel] returns the pricing level as prescribed in google places API
[wp_places rating] returns the average rating as prescribed in google places API
[wp_places lat] lattitude
[wp_places lng] longitude
[wp_places openNow] returns 1 if open
[wp_places permanentlyClosed] returns 1 if permanently closed

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/WP_Places` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings page on the WP_Places page to add your Google Places API Web Services Key.
4. When writing a post about a business, add the business name and address to the WP_Places field.

== Changelog ==

= 1.1.5 =
* added column view to all posts
* added transient to prevent repeated shortcode use from causing repeat queries
* fixed longitude shortcode
* fixed hours display shortcode

= 1.1.4 =
* fixed bone-headed mistake that hid the div

= 1.1.3 =
* shortcodes made live
* fixed spelling errors

= 1.1.2 =
* Fixed labels again
* Shortcodes setup
* Added toggle for displaying the embedded DIV

= 1.1.1 =
* CSS is no longer hard-coded
* Fixed confusing label on new menu page

= 1.1.0 =
* Added menu page specifically for WP_Places
* checked to see if content has multiple paragraphs before inserting content at the beginning of the 2nd paragraph

= 1.0.6 =
* moved default location to the end of the 1st paragraph after frantically realizing embedding at the beginning meant that social sharing was funky

= 1.0.5 =
* removed confusing menu stub

= 1.0.4 =
* Now shows within the posts section the location name that was returned from Google
* "Open Now" status is working.

= 1.0.3 =
* I misspleeeldead services in teh 1.0.2 release
* Fixed image location url

= 1.0.2 =
* Fixed confusing field name

= 1.0.1 =
* Fixed launching with no settings visibility

= 1.0 =
* Initial Launch!
