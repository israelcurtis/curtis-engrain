<?php

/**
 *
 * @link              https://github.com/israelcurtis
 * @since             1.0.0
 * @package           Curtis_Engrain
 *
 * @wordpress-plugin
 * Plugin Name:       Curtis Engrain Dev Assessment
 * Plugin URI:        https://github.com/israelcurtis/curtis-engrain
 * Description:       A simple plugin to demonstrate my skills as your next Senior Backend Developer
 * Version:           1.0.0
 * Author:            Israel Curtis
 * Author URI:        https://github.com/israelcurtis
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       curtis-engrain
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'CURTIS_ENGRAIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-curtis-engrain-activator.php
 */
function activate_curtis_engrain() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-curtis-engrain-activator.php';
	Curtis_Engrain_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-curtis-engrain-deactivator.php
 */
function deactivate_curtis_engrain() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-curtis-engrain-deactivator.php';
	Curtis_Engrain_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_curtis_engrain' );
register_deactivation_hook( __FILE__, 'deactivate_curtis_engrain' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-curtis-engrain.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_curtis_engrain() {
	$plugin = new Curtis_Engrain();
	$plugin->run();

}
run_curtis_engrain();
