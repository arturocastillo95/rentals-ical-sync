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

function rentals_ical_sync_admin_page() {
	// Get all the posts from the database with table st_rental
	global $wpdb;
	$rentals = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'st_rental'" );
	// Show the list
	echo '<h1>SyncRentals</h1>';
	echo '<p>Here you can see all the listings in the database.</p>';
	// Check if the submit button was clicked
	if ( isset( $_POST['submit'] ) ) {
		// Check if the form nonce is valid
		if ( ! wp_verify_nonce( $_POST['rentals_ical_sync_nonce'], 'rentals_ical_sync' ) ) {
			echo '<p class="error">Invalid nonce</p>';
		} else {
			// Check if the rental_id exists in the rentals array
			if ( in_array( $_POST['rental_id'], wp_list_pluck( $rentals, 'ID' ) ) ) {
				// Load all the rentals name in a dropdown list
				echo '<form action="" method="post">';
				echo '<select name="rental_id">';
				foreach ( $rentals as $rental ) {
					echo '<option value="' . $rental->ID . '">' . $rental->post_title . '</option>';
				}
				echo '</select>';
				echo '<input type="submit" name="submit" value="Sync" />';
				echo '</form>';
			} else {
				echo '<p class="error">Invalid rental ID</p>';
			}
		}
	} else {
		// Load all the rentals name in a dropdown list
		echo '<form action="" method="post">';
		echo '<select name="rental_id">';
		foreach ( $rentals as $rental ) {
			echo '<option value="' . $rental->ID . '">' . $rental->post_title . '</option>';
		}
		echo '</select>';
		echo '<input type="submit" name="submit" value="Sync" />';
		echo '</form>';
	}
}

run_rentals_ical_sync();