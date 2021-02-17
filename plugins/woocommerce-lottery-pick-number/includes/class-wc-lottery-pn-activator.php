<?php

/**
 * Fired during plugin activation
 *
 * @link       http://wpgenie.org
 * @since      1.0.0
 *
 * @package    Wc_Lottery_Pn
 * @subpackage Wc_Lottery_Pn/includes
 */
class Wc_Lottery_Pn_Activator {
    /**
     * Activation function
     *
     * Create tables for WC lottery plugin
     *
     * @since    1.0.0
     */
    public static function activate() {     

        global $wpdb;
        global $wp_version;

        $flag = false;
        $wp = '4.0';    // min WordPress version
        $php = '5.5';   // min PHP version

        if ( version_compare( PHP_VERSION, $php, '<' ) ) {
            $flag = 'PHP';
        } elseif ( version_compare( $wp_version, $wp, '<' ) ) {
            $flag = 'WordPress';
        } 

        if($flag){       
          $version = $php;
          if ('WordPress'==$flag) {
              $version = $wp;
          }

   				deactivate_plugins( basename( __FILE__ ) );
   				wp_die('<p>The <strong>WooCommerce Lottery Pick Numbers</strong> plugin requires '.$flag.'  version '.$version.' or greater. If you need secure hosting with all requirements for this plugin contact us at <a href="mailto:info@wpgenie.org">info@wpgenie.org</a></p>','Plugin Activation Error',  array( 'response'=>200, 'back_link'=>TRUE ) );
        }  

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $data_table = $wpdb->prefix."wc_lottery_pn_log";
        $data_table2 = $wpdb->prefix."wc_lottery_pn_reserved";

        $sql = " CREATE TABLE IF NOT EXISTS $data_table (
                  `log_id` bigint(20) UNSIGNED NOT NULL,
                  `order_id` bigint(20) UNSIGNED NOT NULL,
                  `ticket_number` bigint(20) UNSIGNED DEFAULT NULL,
                  `answer_id` bigint(20) UNSIGNED DEFAULT NULL,
                  `lottery_id` bigint(20) UNSIGNED NOT NULL,
                  PRIMARY KEY (`log_id`)
                ); ";


        
        dbDelta( $sql );

        $sql2 = " 
                CREATE TABLE IF NOT EXISTS $data_table2 (
                  `lottery_id` bigint(20) UNSIGNED NOT NULL,
                  `ticket_number` bigint(20) UNSIGNED NOT NULL,
                  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  UNIQUE KEY `index` (`lottery_id`,`ticket_number`)
                );

                ";
        dbDelta( $sql2 );
        
    }
}
