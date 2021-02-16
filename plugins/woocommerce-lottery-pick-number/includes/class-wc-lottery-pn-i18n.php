<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://wpgenie.org
 * @since      1.0.0
 *
 * @package    Wc_Lottery_Pn
 * @subpackage Wc_Lottery_Pn/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wc_Lottery_Pn
 * @subpackage Wc_Lottery_Pn/includes
 * @author     wpgenie <info@wpgenie.org>
 */
class Wc_Lottery_Pn_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = 'wc-lottery-pn';
		$langdir = dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages';		
		load_plugin_textdomain( $domain, false, $langdir.'/');

	}



}
