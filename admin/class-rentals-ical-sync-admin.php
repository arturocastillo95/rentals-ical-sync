<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://arturocastillo.com.mx
 * @since      1.0.0
 *
 * @package    Rentals_Ical_Sync
 * @subpackage Rentals_Ical_Sync/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rentals_Ical_Sync
 * @subpackage Rentals_Ical_Sync/admin
 * @author     Arturo Castillo <arturocastillosiller@gmail.com>
 */
class Rentals_Ical_Sync_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rentals_Ical_Sync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rentals_Ical_Sync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rentals-ical-sync-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rentals_Ical_Sync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rentals_Ical_Sync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rentals-ical-sync-admin.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script('moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js', array('jquery'), '2.29.4', false);
		wp_enqueue_script('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.0/index.global.min.js', array('jquery'), '6.1.0', false);

	}

	public function add_menu() {
		add_menu_page( 
			'SyncRentals', // page title
			'SyncRentals', // menu title
			'manage_options', // capability
			'rentals-ical-sync', // menu slug
			'rentals_ical_sync_admin_page', // function
			'dashicons-calendar-alt', // icon url
			6  // position
		);
	}

}
