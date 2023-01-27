<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://arturocastillo.com.mx
 * @since      1.0.0
 *
 * @package    Rentals_Ical_Sync
 * @subpackage Rentals_Ical_Sync/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Rentals_Ical_Sync
 * @subpackage Rentals_Ical_Sync/includes
 * @author     Arturo Castillo <arturocastillosiller@gmail.com>
 */
class Rentals_Ical_Sync_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'rentals-ical-sync',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
