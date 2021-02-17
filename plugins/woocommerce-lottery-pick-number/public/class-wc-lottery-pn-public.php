<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wpgenie.org
 * @since      1.0.0
 *
 * @package    Wc_Lottery_Pn
 * @subpackage Wc_Lottery_Pn/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wc_Lottery_Pn
 * @subpackage Wc_Lottery_Pn/public
 * @author     wpgenie <info@wpgenie.org>
 */
class Wc_Lottery_Pn_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Lottery_Pn_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Lottery_Pn_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-lottery-pn-public.css', array(), $this->version, 'all' );
		wp_enqueue_style('jquery-alertable', plugin_dir_url( __FILE__ ) . 'css/jquery.alertable.css', array($this->plugin_name), null, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Lottery_Pn_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Lottery_Pn_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-lottery-pn-public.js', array( 'jquery' ), $this->version, false ); 
		$data = array(
			'maximum_text'            =>  esc_js( __('You already have selected maximum number of tickets!' , 'wc-lottery-pn') ),
			'sold_text'            =>  esc_js( __('Ticket sold!' , 'wc-lottery-pn') ),
			'in_cart_text'            =>  esc_js( __('You have already this ticket in your cart!' , 'wc-lottery-pn') ),
			'logintext'            =>  sprintf( __('Sorry, you must be logged in to participate in this competition. <a href="%s" class="button">Login &rarr;</a>', 'wc-lottery-pn'), get_permalink(wc_get_page_id('myaccount') ) ),
			'please_pick'            =>  esc_js( __('Please pick a number. ' , 'wc-lottery-pn') ),
			'please_answer'            =>  esc_js( __('Please answer the question.' , 'wc-lottery-pn') ),
			'please_true_answer'            =>  esc_js( __('You must pick correct answer.' , 'wc-lottery-pn') ),
		);
		wp_localize_script( $this->plugin_name, 'wc_lottery_pn', $data );
		wp_enqueue_script( $this->plugin_name);
		wp_enqueue_script( 'jquery-alertable', plugin_dir_url( __FILE__ ) . 'js/jquery.alertable.min.js', array( 'jquery', $this->plugin_name ), null , false );
	}


	function woocommerce_locate_template( $template, $template_name, $template_path ) {

		$_template = $template;
		if ( ! $template_path ) $template_path = wc()->template_url;
			  $plugin_path  = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/';

		// Look within passed path within the theme - this is priority
		$template = locate_template(
			  array(
				$template_path . $template_name,
				$template_name
			  )
		);

		// Modification: Get the template from this plugin, if it exists
		if ( ! $template && file_exists( $plugin_path . $template_name ) )
			  $template = $plugin_path . $template_name;

		// Use default template
		if ( ! $template )
			  $template = $_template;

		// Return what we found
		return $template;
	}

	public function get_taken_numbers(){

		$response= null;

		$lottery_id = isset( $_GET['lottery_id'] ) ? intval( $_GET['lottery_id'] ) : false;

		if ( $lottery_id ) {
			$response['taken'] = wc_lottery_pn_get_taken_numbers($lottery_id);
			$response['in_cart'] = wc_lottery_pn_get_ticket_numbers_from_cart($lottery_id);
			if ( 'yes' === get_option('lottery_answers_reserved', 'no') ) {
				$response['reserved'] = wc_lottery_pn_get_reserved_numbers($lottery_id);
			}
		}
		
		wp_send_json( $response );
	}

	
	public function add_ticket_number_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
		$ticket_numbers = filter_input( INPUT_POST, 'lottery_tickets_number' );

		if ( empty( $ticket_numbers ) ) {
			return $cart_item_data;
		}
		$ticket_numbers = explode( ',', $ticket_numbers );

		foreach ($ticket_numbers as $ticket_number) {
			$cart_item_data['lottery_tickets_number'][] = $ticket_numbers;
		}

		$cart_item_data['lottery_tickets_number'] = $ticket_numbers;

		return $cart_item_data;
	}

	public function add_ticket_answer_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
		$lottery_tickets_answer = filter_input( INPUT_POST, 'lottery_answer' );

		if ( empty( $lottery_tickets_answer ) ) {
			return $cart_item_data;
		}

		$cart_item_data['lottery_answer'] = $lottery_tickets_answer;

		return $cart_item_data;
	}

	public function add_ticket_date_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
		
		$cart_item_data['lottery_added'] = current_time('mysql');

		return $cart_item_data;
	}

	public function display_ticket_numbers_cart( $item_data, $cart_item ) {

		if ( empty( $cart_item['lottery_tickets_number'] ) ) {
			return $item_data;
		}
		if ( is_array($cart_item['lottery_tickets_number'] ) )  {
			foreach ($cart_item['lottery_tickets_number'] as $ticket_number) {
				$item_data[] = array(
				'key'     => esc_html__( 'Ticket number', 'wc-lottery-pn' ),
				'value'   => wc_clean( $ticket_number ),
				'display' => '',
				);
			}
		} 

		return $item_data;
	}

	public function display_ticket_answer_cart( $item_data, $cart_item ) {

		if ( empty( $cart_item['lottery_answer'] ) ) {
			return $item_data;
		}

		$answers = maybe_unserialize( get_post_meta( $cart_item['product_id'], '_lottery_pn_answers', true ) );

		$item_data[] = array(
			'key'     => __( 'Answer', 'wc-lottery-pn' ),
			'value'   => wc_clean( $cart_item['lottery_answer'] ),
			'display' => isset( $answers[ $cart_item['lottery_answer'] ] ['text'] ) ? $answers[ $cart_item['lottery_answer'] ]['text']  : '',
		);

		return $item_data;
	}
	 
	 public function order_item_display_meta_value( $display_value, $meta, $order ) {

	 	if ( esc_html__( 'Answer', 'wc-lottery-pn' ) !== rawurldecode( (string) $meta->key ) ) {
	 		return $display_value;
	 	}

		$product = is_callable( array( $order, 'get_product' ) ) ? $order->get_product() : false;

		if(! $product ){
			return $display_value;
		}

		$answers = maybe_unserialize( get_post_meta( $product->get_id(), '_lottery_pn_answers', true ) );

	 	return isset( $answers[ $display_value ] ['text'] ) ? $answers[ $display_value ]['text']  : $display_value;

	 }

	public function check_cart_ticket_numbers( $session_data, $values, $key) {
		if ( ! empty( $session_data['lottery_tickets_number'] ) ) {

			$product = wc_get_product( $session_data['product_id'] );
			$ticket_numbers =  $session_data['lottery_tickets_number'];
			$taken_numbers = wc_lottery_pn_get_taken_numbers($session_data['product_id']);

			if( ! empty($taken_numbers) && ! empty($ticket_numbers) && ! empty( array_intersect( $ticket_numbers, $taken_numbers ) )   ) {
				wc_add_notice( sprintf( __( 'Product %1$s has been removed from your cart because someone purchase that ticket number. Please add it to your cart again by <a href="%2$s">clicking here</a>.', 'wc-lottery-pn' ), $product->get_name(), $product->get_permalink() ), 'error' );
				return false;
			}
			
		}
		return $session_data;
	}
	/**
	 * Add to cart validation
	 *
	 */
	public function add_to_cart_validation( $pass, $product_id, $quantity) {
		
		if ( false === $pass ) {
			return $pass;
		}

		$product = wc_get_product($product_id);

		if ( $product->get_type() !== 'lottery' ) {
			return $pass;
		}

		$use_ticket_numbers = get_post_meta( $product_id , '_lottery_use_pick_numbers', true );
		$random_ticket_numbers = get_post_meta( $product_id , '_lottery_pick_numbers_random', true );
		$use_answers        = wc_lottery_use_answers( $product_id );

		if( 'yes' === $use_ticket_numbers  ){
			if( ! empty( $_POST['lottery_tickets_number'] )  && ( is_user_logged_in() OR ( ! $product->get_max_tickets_per_user()  OR $product->get_max_tickets_per_user() == $product->get_max_tickets() ) ) ){
				$taken_numbers = wc_lottery_pn_get_taken_numbers();
				$tickets_in_cart = wc_lottery_pn_get_ticket_numbers_from_cart($product_id); 
				$ticket_numbers = explode( ',' , $_POST['lottery_tickets_number'] );

				if ( count( $ticket_numbers ) !== $quantity) {
					wc_add_notice( sprintf( __( 'Product %1$s has not been added to your cart. Please add it to your cart again.', 'wc-lottery-pn' ), $product->get_name() ), 'error' );
					$pass = false;
				}

				if( ! empty($taken_numbers) && ! empty($ticket_numbers) && ! empty( array_intersect( $ticket_numbers, $taken_numbers ) ) ) {
					wc_add_notice( sprintf( __( 'Product %1$s has not been added to your cart because someone puchased that ticket number. Please add it to your cart again.', 'wc-lottery-pn' ), $product->get_name() ), 'error' );
					$pass = false;
				}
				if( ! empty($tickets_in_cart) && ! empty($ticket_numbers) && ! empty( array_intersect( $ticket_numbers, $tickets_in_cart ) ) ) {
					wc_add_notice( sprintf( __( 'Product %1$s has not been added to your cart because there is already that product with same ticket number in cart.', 'wc-lottery-pn' ), $product->get_name() ), 'error' );
					$pass = false;
				}
				if ( 'yes' === get_option('lottery_answers_reserved', 'no') ) {
					$reserved = wc_lottery_pn_get_reserved_numbers( $product_id );
					if( ! empty($reserved) && ! empty($ticket_numbers) && ! empty( array_intersect( $ticket_numbers, $reserved ) ) ) {
						wc_add_notice( sprintf( __( 'Product %1$s has not been added to your cart because someone reserved that ticket number. Please add it to your cart again.', 'wc-lottery-pn' ), $product->get_name() ), 'error' );
						$pass = false;
					}
				}

			} elseif ( empty( $_POST['lottery_tickets_number'] ) && "yes" === $random_ticket_numbers){

			}elseif (  ! is_user_logged_in()  ) {
				wc_add_notice(sprintf(__('Sorry, you must be logged in to participate in this competition. <a href="%s" class="button">Login &rarr;</a>', 'wc-lottery-pn'), get_permalink(wc_get_page_id('myaccount'))), 'error');
				$pass = false;
			} else{
				wc_add_notice( sprintf( esc_html__( 'Product %1$s has not been added to your cart because you have to select ticket number!', 'wc-lottery-pn' ), $product->get_name()), 'error' );
				$pass = false;
			}
		}

		if( true === $use_answers ){
			
			if( ! empty( $_REQUEST['lottery_answer'] ) && ( is_user_logged_in() OR ( ! $product->get_max_tickets_per_user()  OR $product->get_max_tickets_per_user() == $product->get_max_tickets() ) ) ){
				$answers = maybe_unserialize( get_post_meta( $product_id, '_lottery_pn_answers', true ) );
				if ( is_array( $answers ) ){
					if ( ! array_key_exists( intval($_REQUEST['lottery_answer']), $answers) ){
						wc_add_notice( sprintf( esc_html__( 'Product %1$s has not been added to your cart because of problem with your answer!', 'wc-lottery-pn' ), $product->get_name()), 'error' );
						$pass = false;
					} 
					if( 'yes' === get_post_meta( $product_id , '_lottery_only_true_answers', true ) ){
						$true_answers = wc_lottery_pn_get_true_answers( $product_id );
						$true_answers_ids = array_keys( $true_answers );
						if ( is_array($true_answers_ids) && ! in_array($_REQUEST['lottery_answer'] , $true_answers_ids) ) {
							wc_add_notice( sprintf( esc_html__( 'Product %1$s has not been added to your cart because your answer is not correct. Please try it again!!', 'wc-lottery-pn' ), $product->get_name()), 'error' );
							$pass = false;
						}
					}
				} else {
					wc_add_notice( sprintf( esc_html__( 'Product %1$s has not been added to your cart because there is some problem with answers. Please contact us!', 'wc-lottery-pn' ), $product->get_name()), 'error' );
					$pass = false;
				}

			} elseif (  ! is_user_logged_in() ) {
				wc_add_notice(sprintf(__('Sorry, you must be logged in to participate in this competition. <a href="%s" class="button">Login &rarr;</a>', 'wc-lottery-pn'), get_permalink(wc_get_page_id('myaccount'))), 'error');
				$pass = false;
			} else{
				wc_add_notice( sprintf( esc_html__( 'Product %1$s has not been added to your cart because you have to answer question!', 'wc-lottery-pn' ), $product->get_name()), 'error' );
				$pass = false;
			}

		}

		return $pass;
		
	}

	/**
	 * Make reservation for ticket when adding to cart
	 *
	 */
	public function reserve_tickets( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
		if ( 'yes' === get_option('lottery_answers_reserved', 'no') ) {
			if ( isset( $cart_item_data['lottery_tickets_number'] ) ) {

				foreach ($cart_item_data['lottery_tickets_number'] as $ticket_number) {
					$this->save_reserved_ticket( $product_id, $ticket_number, WC()->session->get_customer_id() );
				}
				$minutes = get_option('lottery_answers_reserved_minutes', '5');
				$message = sprintf( esc_html__('Ticket numbers will be reseved for %d minutes. After that someone else could reserve or buy same ticket!' , 'wc-lottery-pn'  ), $minutes ); 
				wc_add_notice( $message, 'notice');
			}
		}

	}

	public function delete_ticket_reservations($cart_item_key, $cart) {
		if ( 'yes' === get_option('lottery_answers_reserved', 'no') ) {
			$cart_item_data = $cart->get_cart_item( $cart_item_key );
			if ( $cart_item_data ){
				if ( isset( $cart_item_data['lottery_tickets_number'] ) ){
					$product_id = $cart_item_data['product_id'];
					foreach ($cart_item_data['lottery_tickets_number'] as $ticket_number) {
						$this->delete_reserved_ticket( $product_id, $ticket_number, WC()->session->get_customer_id() );
					}
				}
			}
		}
	}

	/**
	* Save reserved ticket
	*
	* @param  int, int
	* @return void
	*
	*/
	public function save_reserved_ticket( $lottery_id, $ticket_number, $session_key) {
		global $wpdb;
		$log_bid = $wpdb -> insert($wpdb -> prefix . 'wc_lottery_pn_reserved', array('lottery_id' => $lottery_id, 'ticket_number' => $ticket_number, 'session_key' => $session_key ), array('%d', '%d', '%s'));
		return $log_bid;
	}

	/**
	* Delete reserved ticket
	*
	* @param  int, int
	* @return void
	*
	*/
	public function delete_reserved_ticket( $lottery_id, $ticket_number) {
		global $wpdb;
		$result = $wpdb->query( $wpdb->prepare( 'DELETE FROM '.$wpdb -> prefix .'wc_lottery_pn_reserved WHERE lottery_id = %d AND ticket_number = %d ', $lottery_id ,$ticket_number ) );
		return $result;
	}

	public function remove_notification_from_order_recieved_page(){
		global $wp;

		if ( ! empty( $wp->query_vars['order-received'] ) ) {
			wc_clear_notices();
		}
	}

}
