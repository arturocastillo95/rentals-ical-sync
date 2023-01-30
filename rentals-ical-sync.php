<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://arturocastillo.com.mx
 * @since             1.0.0
 * @package           Rentals_Ical_Sync
 *
 * @wordpress-plugin
 * Plugin Name:       Traveler SyncRentals
 * Plugin URI:        https://lovebnb.mx
 * Description:       The "SyncRentals" Wordpress plugin seamlessly integrates with the popular Traveler theme and allows you to easily synchronize your vacation rental listings across multiple platforms, including Airbnb and Booking.com, using the iCalendar format. This plugin eliminates the need for manual updates and ensures that your availability calendar is always up-to-date and accurate. With the "SyncRentals" plugin, you can easily manage your vacation rental listings from one central location, and ensure that your guests always have access to the most up-to-date information.
 * Version:           1.0.0
 * Author:            Arturo Castillo
 * Author URI:        https://arturocastillo.com.mx
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rentals-ical-sync
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RENTALS_ICAL_SYNC_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rentals-ical-sync-activator.php
 */
function activate_rentals_ical_sync() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rentals-ical-sync-activator.php';
	Rentals_Ical_Sync_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rentals-ical-sync-deactivator.php
 */
function deactivate_rentals_ical_sync() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rentals-ical-sync-deactivator.php';
	Rentals_Ical_Sync_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rentals_ical_sync' );
register_deactivation_hook( __FILE__, 'deactivate_rentals_ical_sync' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rentals-ical-sync.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rentals_ical_sync() {

	$plugin = new Rentals_Ical_Sync();
	$plugin->run();

}

function combine_consecutive_dates( $dates ) {
	// Sort the dates by the check in date
	usort( $dates, function( $a, $b ) {
		return $a['check_in'] - $b['check_in'];
	} );
	// Combine the dates that are consecutive
	$combined_dates = array();
	$combined_dates[] = $dates[0];
	$dates_count = count( $dates );
	for ( $i = 1; $i < $dates_count; $i++ ) {
		$combined_dates_count = count( $combined_dates );
		$combined_dates_last_index = $combined_dates_count - 1;
		$combined_dates_last = $combined_dates[ $combined_dates_last_index ];
		$combined_dates_last_check_out = $combined_dates_last['check_out'];
		$dates_current = $dates[ $i ];
		$dates_current_check_in = $dates_current['check_in'];
		// Check if the current check in is the next day of the last check out
		if ( $dates_current_check_in == $combined_dates_last_check_out + 86400 ) {
			// If it is then combine the dates
			$combined_dates[ $combined_dates_last_index ]['check_out'] = $dates_current['check_out'];
		} else {
			// If it is not then add the current date to the combined dates
			$combined_dates[] = $dates_current;
		}
	}
	return $combined_dates;
}

function rentals_ical_sync_admin_page() {
	// Get all the posts from the database with table st_rental
	global $wpdb;
	$rentals = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'st_rental'" );
	// Show the list
	echo '<h1>SyncRentals</h1>';
	echo '<p>Here you can see the availability of the listing</p>';
	// Check if the submit button was clicked
	if ( isset( $_POST['submit'] ) ) {
		// Get the rental object selected in the dropdown list
		$rental_id = $_POST['rental_id'];
	
		// Get all the related st_rental_availability from the database with the rental id only get the ones that have status as unavailable
		$rental_availability = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}st_rental_availability WHERE post_id = $rental_id AND status = 'unavailable'", ARRAY_A );
		// Combine in chunks with the earliest and latest date that are consecutive dates
		$rental_availability = combine_consecutive_dates( $rental_availability );
		// Output to the console the array
		echo '<script>console.log(' . json_encode( $rental_availability ) . ');</script>';

		// Display the rental name and the id
		echo '<h2>' . get_the_title( $rental_id ) . '</h2>';
		echo '<p>Listing ID: ' . $rental_id . '</p>';
		// Create a calendar of all the st_rental_availability
		echo '<h2>Availability</h2>';
		echo '<div id="calendar"></div>';
		// Create the javascript array with all the st_rental_availability
		echo '<script>';
		echo 'var events = [';
		foreach ( $rental_availability as $availability ) {
			echo '{';
			echo 'title: "Unavailable",';
			// Convert the unix time stamp to the fullcalendar format
			echo 'start: "' . date( 'Y-m-d', $availability['check_in'] ) . '",';
			echo 'end: "' . date( 'Y-m-d', $availability['check_out'] ) . '",';
			echo '},';
		}
		echo '];';
		echo 'console.log(events);';
		echo '</script>';
	} else {
		// Load all the rentals name in a dropdown list
		echo '<form action="" method="post">';
		echo '<select name="rental_id">';
		if ( $rentals ) {
			foreach ( $rentals as $rental ) {
				echo '<option value="' . $rental->ID . '">' . $rental->post_title . '</option>';
			}
		}
		echo '</select>';
		echo '<input type="submit" name="submit" value="Sync" />';
		echo '</form>';
	}
}

run_rentals_ical_sync();