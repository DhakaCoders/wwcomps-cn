<?php

/**
 * Fired during plugin update
 *
 * @link       http://wpgenie.org
 * @since      1.0.0
 *
 * @package    Wc_Lottery_Pn
 * @subpackage Wc_Lottery_Pn/includes
 */
class Wc_Lottery_Pn_Update {

    public $dbversion;

    public function __construct() {
      add_action('admin_init', array($this, 'update'));
    }

    /**
     * Activation function
     *
     * Create tables for WC lottery plugin
     *
     * @since    1.0.0
     */
    public static function update() {     

        global $wpdb;
        global $wp_version;
        
        if (get_site_option('woocommerce_lottery_database_version') != WC_LOTTERY_PN_DB ) {
          $data_table = $wpdb->prefix . "wc_lottery_pn_reserved";
          $sql = "  CREATE TABLE $data_table (
                 `lottery_id` bigint(20) UNSIGNED NOT NULL,
                 `ticket_number` bigint(20) UNSIGNED NOT NULL,
                 `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                 `session_key` char(32) NOT NULL
               ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
              );";

          require_once ABSPATH . 'wp-admin/includes/upgrade.php';
          dbDelta($sql);



          update_option('woocommerce_lottery_database_version', WC_LOTTERY_PN_DB);
        }

    }
}
new Wc_Lottery_Pn_Update;
