=== Plugin Name ===
Contributors: binarygary
Tags: Google Places, Business Information, Location, Google Places API Web Services
Stable Tag: 1.1.0
Requires at least: 4.0
Tested up to: 4.4
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl.html


WP_Places displays up-to-date information about almost any location or business you can think of to write about.

== Description ==

If you find you regularly write blog posts about (local?) businesses you might want to provide info such as hours, phone number, address to your users.  However, this can be difficult to keep current.  Fortunately Google offers an API called Google Places API Web Service.   Google Place API Web Service allows you to Add up-to-date information about millions of locations.  

WP_Places Plugin requires a Google Places API Web Service Key. However, at the time of writing the API key is free and provides up to 1,000 requests per 24 hour period. If you verify your identity (by providing Google a Credit Card) they will increase your daily request per 24 hours to 150,000.

Once Installed, WP_Places takes name and location and displays a DIV containing Business Name, Address, Hours, Phone Number, Website.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/WP_Places` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings->General screen to add your Google Places API Web Services Key.
4. When writing a post about a business, add the business name and address to the WP_Places field.

== Changelog ==

= 1.1.0 =
* WP_Places now has its own navigation page created

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
