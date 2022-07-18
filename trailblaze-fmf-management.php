<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://trailblazecreative.com/
 * @since             1.0.0
 * @package           Trailblaze_Fmf_Management
 *
 * @wordpress-plugin
 * Plugin Name:       TrailBlaze FMF Management
 * Plugin URI:        https://github.com/jefferykarbowski/TrailBlaze-FMF-Managment
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            TrailBlaze Creative
 * Author URI:        https://trailblazecreative.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       trailblaze-fmf-management
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
define( 'TRAILBLAZE_FMF_MANAGEMENT_VERSION', '1.0.0' );

if (!function_exists('write_log')) {

    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

}


require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-trailblaze-fmf-management-activator.php
 */
function activate_trailblaze_fmf_management() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-trailblaze-fmf-management-activator.php';
	Trailblaze_Fmf_Management_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-trailblaze-fmf-management-deactivator.php
 */
function deactivate_trailblaze_fmf_management() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-trailblaze-fmf-management-deactivator.php';
	Trailblaze_Fmf_Management_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_trailblaze_fmf_management' );
register_deactivation_hook( __FILE__, 'deactivate_trailblaze_fmf_management' );

// require_once plugin_dir_path( __FILE__ ) . 'includes/config.class.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require plugin_dir_path( __FILE__ ) . 'includes/class-trailblaze-fmf-management.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_trailblaze_fmf_management() {

	$plugin = new Trailblaze_Fmf_Management();
	$plugin->run();

}
run_trailblaze_fmf_management();

require plugin_dir_path( __FILE__ ) . 'vendors/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/jefferykarbowski/TrailBlaze-FMF-Management/',
    __FILE__,
    'trailblaze-fmf-management'
);
$myUpdateChecker->setBranch('main');
