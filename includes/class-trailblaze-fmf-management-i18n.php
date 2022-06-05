<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://trailblazecreative.com/
 * @since      1.0.0
 *
 * @package    Trailblaze_Fmf_Management
 * @subpackage Trailblaze_Fmf_Management/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Trailblaze_Fmf_Management
 * @subpackage Trailblaze_Fmf_Management/includes
 * @author     TrailBlaze Creative <info@trailblazecreative.com>
 */
class Trailblaze_Fmf_Management_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'trailblaze-fmf-management',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
