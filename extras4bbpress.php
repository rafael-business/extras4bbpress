<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://rafael.business
 * @since             1.0.0
 * @package           Extras4bbpress
 *
 * @wordpress-plugin
 * Plugin Name:       Extras for bbPress
 * Plugin URI:        https://codash.com.br/produto/extras4bbpress/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Codash
 * Author URI:        https://rafael.business
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       extras4bbpress
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
define( 'EXTRAS4BBPRESS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-extras4bbpress-activator.php
 */
function activate_extras4bbpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-extras4bbpress-activator.php';
	Extras4bbpress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-extras4bbpress-deactivator.php
 */
function deactivate_extras4bbpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-extras4bbpress-deactivator.php';
	Extras4bbpress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_extras4bbpress' );
register_deactivation_hook( __FILE__, 'deactivate_extras4bbpress' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-extras4bbpress.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_extras4bbpress() {

	$plugin = new Extras4bbpress();
	$plugin->run();

}
run_extras4bbpress();
