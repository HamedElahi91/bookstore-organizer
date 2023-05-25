<?php

/**
 * Plugin Name:       Bookstore Organizer
 * Description:       A plugin that categorize a bookstore
 * Version:           1.0.2
 * Author:            Hamed Elahi
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
**/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//Load Plugin File autoload
include_once dirname(__FILE__) . '/vendor/autoload.php';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BOOKSTORE_ORGANIZER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bookstore-organizer-activator.php
 */
function activate_bookstore_organizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bookstore-organizer-activator.php';
	Bookstore_Organizer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bookstore-organizer-deactivator.php
 */
function deactivate_bookstore_organizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bookstore-organizer-deactivator.php';
	Bookstore_Organizer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bookstore_organizer' );
register_deactivation_hook( __FILE__, 'deactivate_bookstore_organizer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bookstore-organizer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function bookstore_organizer() {

	$plugin = new Bookstore_Organizer();
	$plugin->run();

}
bookstore_organizer();
