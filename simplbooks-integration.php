<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              developerforwebsites.com
 * @since             1.0.0
 * @package           Simplbooks_Integration
 *
 * @wordpress-plugin
 * Plugin Name:       Simplbooks Integration
 * Plugin URI:        wp-liides.ee
 * Description:       You do not need to install SimplBooks on your computer, but you can start using it online. Web-based access allows you to access whatever 					 your location and device. Speed dials make business software faster Presets and account plan have been made.
 * Version:           1.0.0
 * Author:            Freelancer Martin
 * Author URI:        developerforwebsites.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simplbooks-integration
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
define( 'SIMPLBOOKS_INTEGRATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-simplbooks-integration-activator.php
 */
function activate_simplbooks_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simplbooks-integration-activator.php';
	Simplbooks_Integration_Activator::activate();
}

require_once plugin_dir_path( __FILE__ ) . 'admin/exopite-simple-options/exopite-simple-options-framework-class.php';

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-simplbooks-integration-deactivator.php
 */
function deactivate_simplbooks_integration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simplbooks-integration-deactivator.php';
	Simplbooks_Integration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simplbooks_integration' );
register_deactivation_hook( __FILE__, 'deactivate_simplbooks_integration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-simplbooks-integration.php';



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_simplbooks_integration() {

	$plugin = new Simplbooks_Integration();
	$plugin->run();

}
run_simplbooks_integration();
