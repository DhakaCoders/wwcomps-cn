<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wpgenie.org
 * @since      1.0.0
 *
 * @package    Wc_Lottery_Pn
 * @subpackage Wc_Lottery_Pn/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wc_Lottery_Pn
 * @subpackage Wc_Lottery_Pn/includes
 * @author     wpgenie <info@wpgenie.org>
 */
class Wc_Lottery_Pn {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wc_Lottery_Pn_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->version     = WC_LOTTERY_PN;
		$this->plugin_name = 'wc-lottery-pn';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wc_Lottery_Pn_Loader. Orchestrates the hooks of the plugin.
	 * - Wc_Lottery_Pn_i18n. Defines internationalization functionality.
	 * - Wc_Lottery_Pn_Admin. Defines all hooks for the admin area.
	 * - Wc_Lottery_Pn_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc-lottery-pn-update.php';
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc-lottery-pn-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc-lottery-pn-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wc-lottery-pn-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wc-lottery-pn-public.php';

		$this->loader = new Wc_Lottery_Pn_Loader();

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wc-lottery-pn-functions.php';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wc_Lottery_Pn_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wc_Lottery_Pn_i18n();
		
		$this->loader->add_action( 'wp_loaded', $plugin_i18n, 'load_plugin_textdomain' );


	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wc_Lottery_Pn_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'wc_lottery_participate', $plugin_admin, 'lottery_pn_order', 10, 6);
		$this->loader->add_action( 'wc_lottery_cancel_participation', $plugin_admin, 'lottery_pn_order_canceled', 10, 6 );
		$this->loader->add_action( 'wc_lottery_delete_participate_entry', $plugin_admin, 'lottery_pn_ajax_delete_entry', 10, 2 );

		$this->loader->add_action( 'woocommerce_checkout_create_order_line_item', $plugin_admin, 'add_ticket_numbers_to_order_items', 10, 4 );
		$this->loader->add_action( 'woocommerce_checkout_create_order_line_item', $plugin_admin, 'add_ticket_answer_to_order_items', 10, 4 );

		$this->loader->add_action( 'woocommerce_product_options_lottery', $plugin_admin, 'add_extra_product_write_panel', 10 );
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'product_save_data',  80, 2 );
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'save_answers',  80, 2 );

		$this->loader->add_action( 'wp_ajax_woocommerce_add_lottery_answer', $plugin_admin, 'ajax_add_answer_line' );
		$this->loader->add_action( 'delete_post', $plugin_admin, 'del_lottery_extra_logs' );
		$this->loader->add_action( 'woocommerce_lottery_do_relist', $plugin_admin, 'del_lottery_extra_logs' );

		//$this->loader->add_filter( 'woocommerce_lottery_participants', $plugin_admin, 'get_lottery_participants', 90, 3 );
		$this->loader->add_filter( 'woocommerce_lottery_winners', $plugin_admin, 'get_lottery_winners', 90, 3 );


		$this->loader->add_filter( 'woocommerce_simple_lottery_settings', $plugin_admin, 'custom_woocommerce_lottery_settings', 90);

		$this->loader->add_action( 'do_meta_boxes', $plugin_admin, 'remove_lottery_metaboxes' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'woocommerce_simple_lottery_meta' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wc_Lottery_Pn_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wc_ajax_wc_lottery_get_taken_numbers', $plugin_public, 'get_taken_numbers' );
		$this->loader->add_action( 'woocommerce_add_to_cart', $plugin_public, 'reserve_tickets', 10, 6 );
		$this->loader->add_action( 'woocommerce_remove_cart_item', $plugin_public, 'delete_ticket_reservations', 10, 2 );
		$this->loader->add_action( 'get_header', $plugin_public, 'remove_notification_from_order_recieved_page', 99 );

		$this->loader->add_filter( 'woocommerce_locate_template', $plugin_public, 'woocommerce_locate_template', 10, 3 );
		$this->loader->add_filter( 'woocommerce_add_cart_item_data', $plugin_public, 'add_ticket_number_to_cart_item', 10, 3 );
		$this->loader->add_filter( 'woocommerce_add_cart_item_data', $plugin_public, 'add_ticket_answer_to_cart_item', 10, 3 );
		$this->loader->add_filter( 'woocommerce_add_cart_item_data', $plugin_public, 'add_ticket_date_to_cart_item', 10, 3 );
		$this->loader->add_filter( 'woocommerce_get_item_data', $plugin_public, 'display_ticket_numbers_cart', 10, 2 );
		$this->loader->add_filter( 'woocommerce_get_item_data', $plugin_public, 'display_ticket_answer_cart', 10, 2 );
		$this->loader->add_filter( 'woocommerce_order_item_display_meta_value', $plugin_public, 'order_item_display_meta_value', 10, 3 );
		$this->loader->add_filter( 'woocomerce_lottery_history', $this , 'get_lottery_history_with_extra_info', 10, 4 );
		$this->loader->add_filter( 'woocommerce_get_cart_item_from_session', $plugin_public , 'check_cart_ticket_numbers', 10, 4 );
		$this->loader->add_filter( 'woocommerce_add_to_cart_validation', $plugin_public, 'add_to_cart_validation', 10, 3 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wc_Lottery_Pn_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function get_lottery_history_with_extra_info( $history, $product_id, $user_id, $relisteddate ) {
		
		global $wpdb;
		$wheredatefrom = '';
		$datefrom      = FALSE;

		$relisteddate = get_post_meta( $product_id, '_lottery_relisted', true );
		if(!is_admin() && !empty($relisteddate)){
		    $datefrom = $relisteddate;
		}

		if($datefrom){
		    $wheredatefrom =" AND CAST(date AS DATETIME) > '$datefrom' ";
		}

		if($user_id){
		    $wheredatefrom =" AND wc_lottery_log.userid = $user_id";
		}
		
		$history = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'wc_lottery_log LEFT JOIN '.$wpdb->prefix.'wc_lottery_pn_log on '.$wpdb->prefix.'wc_lottery_log.id = '.$wpdb->prefix.'wc_lottery_pn_log.log_id WHERE '.$wpdb->prefix.'wc_lottery_log.lottery_id =' . $product_id . $wheredatefrom.' ORDER BY `date` DESC');

		return $history;
	}

}
