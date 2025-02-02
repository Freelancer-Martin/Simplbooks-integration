<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       developerforwebsites.com
 * @since      1.0.0
 *
 * @package    Simplbooks_Integration
 * @subpackage Simplbooks_Integration/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Simplbooks_Integration
 * @subpackage Simplbooks_Integration/includes
 * @author     Freelancer Martin <developerforwebsites@gmail.com>
 */
class Simplbooks_Integration_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'simplbooks-integration',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
