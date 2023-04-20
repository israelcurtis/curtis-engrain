<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/israelcurtis
 * @since      1.0.0
 *
 * @package    Curtis_Engrain
 * @subpackage Curtis_Engrain/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Curtis_Engrain
 * @subpackage Curtis_Engrain/includes
 * @author     Israel Curtis <israel.curtis@gmail.com>
 */
class Curtis_Engrain_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'curtis-engrain',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
