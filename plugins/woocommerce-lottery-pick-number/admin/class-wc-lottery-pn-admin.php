<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wpgenie.org
 * @since      1.0.0
 *
 * @package    Wc_Lottery_Pn
 * @subpackage Wc_Lottery_Pn/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Lottery_Pn
 * @subpackage Wc_Lottery_Pn/admin
 * @author     wpgenie <info@wpgenie.org>
 */
class Wc_Lottery_Pn_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-lottery-pn-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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
		$params = array(
			'add_lottery_answer_nonce'  => wp_create_nonce( 'add_lottery_answer_nonce' ),
			'save_lottery_answer_nonce' => wp_create_nonce( 'save_lottery_answer_nonce' ),
			'remove_wcsbs'              => __( 'Remove this answer?', 'wc-lottery-pn' ),
		);

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-lottery-pn-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'woocommerce_lottery_pn', $params );

	}
	public function add_ticket_numbers_to_order_items( $item, $cart_item_key, $values, $order ) {
		$product                    = $values['data'];
		if( 'yes' === get_post_meta( $product->get_id() , '_lottery_pick_numbers_random', true ) ){
			$values['lottery_tickets_number'] = wc_lottery_generate_random_ticket_numbers( $product->get_id(), $values['quantity'] );
		}
		if ( empty( $values['lottery_tickets_number'] ) ) {
				return;
		}

		foreach ( $values['lottery_tickets_number'] as $key => $ticket_number ) {
			$item->add_meta_data( __( 'Ticket number', 'wc-lottery-pn' ), $ticket_number );
		}

	}

	public function add_ticket_answer_to_order_items( $item, $cart_item_key, $values, $order ) {
		if ( empty( $values['lottery_answer'] ) ) {
				return;
		}

		$item->add_meta_data( __( 'Answer', 'wc-lottery-pn' ), $values['lottery_answer'] );

	}

	public function lottery_pn_order( $product_id, $user_id, $order_id, $log_ids, $item, $item_id ) {
		$order          = wc_get_order( $order_id );
		$item_meta      = function_exists( 'wc_get_order_item_meta' ) ? wc_get_order_item_meta( $item_id, '' ) : $order->get_item_meta( $item_id );
		$ticket_numbers = isset( $item_meta[ __( 'Ticket number', 'wc-lottery-pn' ) ] ) ? $item_meta[ __( 'Ticket number', 'wc-lottery-pn' ) ] : '';
		$answer         = isset( $item_meta[ __( 'Answer', 'wc-lottery-pn' ) ] ) ? intval( $item_meta[ __( 'Answer', 'wc-lottery-pn' ) ][0] ) : '';
		if ( $log_ids && ( $ticket_numbers || $answer ) ) {
			foreach ( $log_ids as $key => $log_id ) {
				$this->log_participant_extra_info( $log_id, $order_id, $product_id, $ticket_numbers[ $key ], $answer );
			}
		}

	}

	public function lottery_pn_order_canceled( $product_id, $user_id, $order_id, $log_ids, $item, $item_id ) {
		if ( $order_id ) {
			$this->del_lottery_extra_logs_by_order_id( $order_id );
		}

	}
	public function lottery_pn_ajax_delete_entry( $product_id, $log_id) {
		if ( $log_id ) {
			$this->delete_log_participant_extra_info( $log_id );
		}

	}

	/**
	* Log bid
	*
	* @param  int, int
	* @return void
	*
	*/
	public function log_participant_extra_info( $log_id, $order_id, $product_id, $ticket_number, $answer_id ) {

		global $wpdb;
		$log_bid = $wpdb->insert(
			$wpdb->prefix . 'wc_lottery_pn_log', array(
				'log_id'        => $log_id,
				'order_id'      => $order_id,
				'ticket_number' => $ticket_number,
				'answer_id'     => $answer_id,
				'lottery_id'    => $product_id,
			), array( '%d', '%d', '%d', '%d', '%d' )
		);

		return $log_bid;
	}

	/**
	* Delete single log by log id
	*
	* @param  int, int
	* @return void
	*
	*/
	public function delete_log_participant_extra_info( $log_id ) {

		global $wpdb;

		$result = $wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'wc_lottery_pn_log WHERE log_id = %d', $log_id ) );
		return $result;
	}

	/**
	* Delete extra log
	*
	* @param  int, int
	* @return void
	*
	*/
	public function del_lottery_extra_logs( $post_id ) {

		global $wpdb;

		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'wc_lottery_pn_reserved WHERE lottery_id = %d', $post_id ) );

		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'wc_lottery_pn_log WHERE lottery_id = %d', $post_id ) );

	}

	/**
	* Delete extra log by order id
	*
	* @param  int, int
	* @return void
	*
	*/
	public function del_lottery_extra_logs_by_order_id( $order_id ) {

		global $wpdb;

		$result = $wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'wc_lottery_pn_log WHERE order_id = %d', $order_id ) );

		return $result;
	}

	/**
	 * Adds the panel to the Product Data postbox in the product interface
	 *
	 * @return void
	 *
	 */
	public function add_extra_product_write_panel() {
		global $post;
		$product = wc_get_product( $post->ID );

		woocommerce_wp_checkbox(
			array(
				'id'            => '_lottery_use_pick_numbers',
				'wrapper_class' => '',
				'label'         => __( 'Allow ticket numbers?', 'wc-lottery-pn' ),
				'description'   => __( 'Allow customer to pick ticket number(s) ', 'wc-lottery-pn' ),
				'desc_tip'      => 'true',
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id'            => '_lottery_pick_numbers_random',
				'wrapper_class' => '',
				'label'         => __( 'Randomly assign ticket number(s) without ticket number picking', 'wc-lottery-pn' ),
				'description'   => __( 'Customer gets random assing ticket number', 'wc-lottery-pn' ),
				'desc_tip'      => 'false',
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id'            => '_lottery_pick_number_use_tabs',
				'wrapper_class' => '',
				'label'         => __( 'Sort tickets in tabs?', 'wc-lottery-pn' ),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'                => '_lottery_pick_number_tab_qty',
				'class'             => 'input_text',
				'size'              => '6',
				'label'         => __( 'Number of tickets per tab', 'wc-lottery-pn' ),
				'type'              => 'number',
				'custom_attributes' => array(
					'step' => '1',
					'min'  => '0',
				),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id'            => '_lottery_manualy_winners',
				'wrapper_class' => '',
				'label'         => __( 'Manualy pick winners', 'wc-lottery-pn' ),
				'description'   => __( 'Pick winners manualy when lottery has finished.', 'wc-lottery-pn' ),
				'desc_tip'      => 'true',
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id'            => '_lottery_use_answers',
				'wrapper_class' => '',
				'label'         => __( 'Force user to answer a question?', 'wc-lottery-pn' ),
				'description'   => __( 'Force user to answer a question before adding lottery number to cart', 'wc-lottery-pn' ),
				'desc_tip'      => 'true',
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id'            => '_lottery_only_true_answers',
				'wrapper_class' => '',
				'label'         => __( 'Only alow true answers.', 'wc-lottery-pn' ),
				'description'   => __( 'Only alow users to pick correct answers', 'wc-lottery-pn' ),
				'desc_tip'      => 'true',
			)
		);
		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/html-meta-box-answers.php';

		if ( 'lottery' === $product->get_type() && true === $product->is_closed() && 'yes' === get_post_meta( $post->ID, '_lottery_manualy_winners', true ) ) {
			$lottery_num_winners = intval( $product->get_lottery_num_winners() );
			$i                   = 1;
			echo '<p>';
			echo '<h3>' . esc_html__( 'Manualy pick winners' ) . '</h3>';
			while ( $i <= $lottery_num_winners ) {
				woocommerce_wp_text_input(
					array(
						'id'                => '_lottery_manualy_winner_' . $i,
						'wrapper_class'     => '',
						'description'       => sprintf( __( 'Enter number of winning ticket. Fom 1-%d', 'wc-lottery-pn' ), $product->get_max_tickets() ),
						'label'             => __( 'Winning ticket', 'wc_lottery' ),
						'type'              => 'number',
						'custom_attributes' => array(
							'step' => '1',
							'min'  => '1',
							'max'  => $product->get_max_tickets(),
						),
						'desc_tip'          => 'false',
					)
				);

				$i++;

			}
			echo '</p>';
			echo '<p>';
			woocommerce_wp_textarea_input(
				array(
					'id'            => '_lottery_manualy_pick_text',
					'wrapper_class' => '',
					'label'         => __( 'Manual pick text', 'wc-lottery-pn' ),
					'description'   => __( 'Some text explaining how mmanual winner picking is done.', 'wc-lottery-pn' ),
					'desc_tip'      => 'true',
				)
			);
			echo '</p>';
		}

	}
	/**
	 * Saves the data inputed into the product boxes, as post meta data
	 *
	 *
	 * @param int $post_id the post (product) identifier
	 * @param stdClass $post the post (product)
	 *
	 */
	public function product_save_data( $post_id, $post ) {

		if ( ! current_user_can( 'edit_products' ) ) {
			return;
		}

		$product_type = empty( $_POST['product-type'] ) ? 'simple' : sanitize_title( wc_clean( $_POST['product-type'] ) );

		$product = wc_get_product( $post_id );

		if ( $product_type == 'lottery' ) {
			if ( isset( $_POST['_lottery_use_pick_numbers'] ) && ! empty( $_POST['_lottery_use_pick_numbers'] ) ) {
				update_post_meta( $post_id, '_lottery_use_pick_numbers', 'yes' );
			} else {
				update_post_meta( $post_id, '_lottery_use_pick_numbers', 'no' );
			}

			if ( isset( $_POST['_lottery_pick_numbers_random'] ) && ! empty( $_POST['_lottery_pick_numbers_random'] ) &&  ( isset( $_POST['_lottery_use_pick_numbers'] ) && ! empty( $_POST['_lottery_use_pick_numbers'] ) ) ) {
				update_post_meta( $post_id, '_lottery_pick_numbers_random', 'yes' );
			} else {
				update_post_meta( $post_id, '_lottery_pick_numbers_random', 'no' );
			}

			if ( isset( $_POST['_lottery_pick_number_use_tabs'] ) && ! empty( $_POST['_lottery_pick_number_use_tabs'] ) ) {
				update_post_meta( $post_id, '_lottery_pick_number_use_tabs', 'yes' );
			} else {
				update_post_meta( $post_id, '_lottery_pick_number_use_tabs', 'no' );
			}
			if ( isset( $_POST['_lottery_pick_number_tab_qty'] ) ) {
				update_post_meta( $post_id, '_lottery_pick_number_tab_qty', intval( $_POST['_lottery_pick_number_tab_qty'] ) );
			} else{
				delete_post_meta( $post_id, '_lottery_pick_number_tab_qty' );
			}
			if ( isset( $_POST['_lottery_use_answers'] ) && ! empty( $_POST['_lottery_use_answers'] ) ) {
				update_post_meta( $post_id, '_lottery_use_answers', 'yes' );
			} else {
				update_post_meta( $post_id, '_lottery_use_answers', 'no' );
			}
			if ( isset( $_POST['_lottery_manualy_winners'] ) && ! empty( $_POST['_lottery_manualy_winners'] ) ) {
				update_post_meta( $post_id, '_lottery_manualy_winners', 'yes' );
			} else {
				update_post_meta( $post_id, '_lottery_manualy_winners', 'no' );
			}
			if ( isset( $_POST['_lottery_pick_numbers_random'] ) && ! empty( $_POST['_lottery_pick_numbers_random'] ) ) {
				update_post_meta( $post_id, '_lottery_pick_numbers_random', 'yes' );
			} else {
				update_post_meta( $post_id, '_lottery_pick_numbers_random', 'no' );
			}
			if ( isset( $_POST['_lottery_only_true_answers'] ) && ! empty( $_POST['_lottery_only_true_answers'] ) ) {
				update_post_meta( $post_id, '_lottery_only_true_answers', 'yes' );
			} else {
				update_post_meta( $post_id, '_lottery_only_true_answers', 'no' );
			}

			if ( 'yes' === get_post_meta( $post_id, '_lottery_manualy_winners', true ) && $product->is_closed() ) {

				$old_lotery_winners = get_post_meta( $post_id, '_lottery_winners' );

				delete_post_meta( $post_id, '_lottery_winners' );
				$winners             = array();
				$pn_winners          = array();
				$lottery_num_winners = isset( $_POST['_lottery_num_winners'] ) ? intval( $_POST['_lottery_num_winners'] ) : 1;

				$i = 1;
				while ( $i <= $lottery_num_winners ) {
					if ( isset( $_POST[ '_lottery_manualy_winner_' . $i ] ) && ( ! empty( $_POST['_lottery_manualy_winner_'. $i ] ) || $_POST['_lottery_manualy_winner_'. $i ] === '0' ) ) {
						update_post_meta( $post_id, '_lottery_manualy_winner_' . $i, intval( $_POST[ '_lottery_manualy_winner_' . $i ] ) );
						if ( isset( $_POST['_lottery_use_pick_numbers'] ) && ! empty( $_POST['_lottery_use_pick_numbers'] ) ) {
							$winners[ $i ]     = $this->get_user_id_by_ticket_number( intval( $_POST[ '_lottery_manualy_winner_' . $i ] ), $post_id );
							$pn_winners [ $i ] = $this->get_log_by_ticket_number( intval( $_POST[ '_lottery_manualy_winner_' . $i ] ), $post_id );

						} else {
							$participants  = apply_filters( 'woocommerce_lottery_participants', get_post_meta( $post_id, '_participant_id' ), $post_id, $product );
							$participants  = array_combine ( range( 1, count( $participants ) ), $participants );
							$winners[ $i ] = $participants[ intval( $_POST[ '_lottery_manualy_winner_' . $i ] ) ];
						}
					}
					$i++;
				}

				
				update_post_meta( $post_id, '_lottery_pn_winners', $pn_winners );
				foreach ( $winners as $key => $userid ) {
					add_post_meta( $post_id, '_lottery_winners', $userid );
					add_user_meta( $userid, '_lottery_win', $post_id );
					add_user_meta( $userid, '_lottery_win_' . $post_id . '_position', $key );
				}

				if ( $old_lotery_winners !== get_post_meta( $post_id, '_lottery_winners' ) ) {
					do_action('wc_lottery_close', $post_id);
					do_action('wc_lottery_won', $post_id);
				}

				if ( isset( $_POST['_lottery_manualy_pick_text'] ) && ! empty( $_POST['_lottery_manualy_pick_text'] ) ) {
					update_post_meta( $post_id, '_lottery_manualy_pick_text', $_POST['_lottery_manualy_pick_text'] );
				} else {
					delete_post_meta( $post_id, '_lottery_manualy_pick_text' );
				}


			}
		}
	}
	/**
	 * Add answer(s) via ajax.
	 * 
	 */
	public function ajax_add_answer_line() {
		ob_start();

		check_ajax_referer( 'add_lottery_answer_nonce', 'security' );

		if ( ! current_user_can( 'edit_products' ) ) {
			die( -1 );
		}

		$thepostid     = 0;
		$answer_key    = absint( $_POST['key'] );
		$position      = 0;
		$metabox_class = array();
		$answer        = array(
			'text' => '',
			'true' => 0,
		);

		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/html-product-lottery-answers.php';
		die();

	}
	/**
	 * Save discounts via ajax.
	 * 
	 */
	public static function save_answers( $post_id, $post ) {

		if ( ! current_user_can( 'edit_products' ) ) {
			return;
		}

		// Save Attributes
		$answers = array();

		$lottery_question = isset( $_POST['_lottery_question'] ) ? wp_kses_post( $_POST['_lottery_question'] ) : '';
		update_post_meta( $post_id, '_lottery_question', $lottery_question );

		if ( isset( $_POST['lottery_answer'] ) ) {

			$post_answers = isset( $_POST['lottery_answer'] ) ? wc_clean( $_POST['lottery_answer'] ) : array();
			$answers_true = isset( $_POST['lottery_answer_true'] ) ? wc_clean( $_POST['lottery_answer_true'] ) : array();

			foreach ( $post_answers as $key => $answer ) {
				if ( ! empty( $answer ) ) {
					$answers[ $key ]['text'] = $answer;
					$answers[ $key ]['true'] = isset( $answers_true[ $key ] ) ? 1 : 0;
				}
			}
		}

		update_post_meta( $post_id, '_lottery_pn_answers', $answers );

	}
	/**
	 * Get lottery participants.
	 * 
	 */	
	public function get_lottery_participants( $paricipants, $product_id, $product ) {
		global $wpdb;

		$wheredatefrom = '';

		$use_ticket_numbers = get_post_meta( $product_id, '_lottery_use_pick_numbers', true );

		if ( wc_lottery_use_answers( $product_id ) ) {

			$true_answers = wc_lottery_pn_get_true_answers( $product_id );
			$answers_ids  = array_keys( $true_answers );

			if ( $true_answers ) {
				$ids                = join( ',', $answers_ids );
				$true_answers_query = " AND answer_id IN ('" . $ids . "') ";
			}

			$relisteddate = get_post_meta( $product_id, '_lottery_relisted', true );

			if ( $relisteddate ) {
				$wheredatefrom = " AND CAST(date AS DATETIME) > '$relisteddate' ";
			}

			$paricipants = $wpdb->get_results( 'SELECT userid,ticket_number,answer_id, ' . $wpdb->prefix . 'wc_lottery_pn_log.log_id FROM ' . $wpdb->prefix . 'wc_lottery_log LEFT JOIN ' . $wpdb->prefix . 'wc_lottery_pn_log ON ' . $wpdb->prefix . 'wc_lottery_log.id = ' . $wpdb->prefix . 'wc_lottery_pn_log.log_id WHERE ' . $wpdb->prefix . 'wc_lottery_pn_log.lottery_id =' . $product_id . $wheredatefrom . $true_answers_query, ARRAY_A );

		} elseif ( 'yes' === $use_ticket_numbers ) {

			$paricipants = $wpdb->get_results( 'SELECT userid,ticket_number, ' . $wpdb->prefix . 'wc_lottery_pn_log.log_id , answer_id FROM ' . $wpdb->prefix . 'wc_lottery_log LEFT JOIN ' . $wpdb->prefix . 'wc_lottery_pn_log ON ' . $wpdb->prefix . 'wc_lottery_log.id = ' . $wpdb->prefix . 'wc_lottery_pn_log.log_id  WHERE ' . $wpdb->prefix . 'wc_lottery_pn_log.lottery_id =' . $product_id . $wheredatefrom, ARRAY_A );

		}
		return $paricipants;

	}
	/**
	 * Get lottery winners.
	 * 
	 */
	public function get_lottery_winners( $winners, $product_id, $product ) {

		$use_ticket_numbers = get_post_meta( $product_id, '_lottery_use_pick_numbers', true );
		$pn_winners         = false;


		if ( 'yes' === get_post_meta( $product_id, '_lottery_manualy_winners', true ) ) {
			return array();
		}

		if ( wc_lottery_use_answers( $product_id ) ) {
			$paricipants = $this->get_lottery_participants( '', $product_id, $product );
			if ( $paricipants ) {
				$pn_winners = $this->pick_lottery_winers_from_array( $paricipants, $product );
			}
		} elseif ( 'yes' === $use_ticket_numbers ) {
			$paricipants = $this->get_lottery_participants( '', $product_id, $product );
			if ( $paricipants ) {
				$pn_winners = $this->pick_lottery_winers_from_array( $paricipants, $product );
			}
		}
		if ( ! empty( $pn_winners ) ) {
			$winners = array_column( $pn_winners, 'userid' );

		}
		if ( $pn_winners ) {
			update_post_meta( $product_id, '_lottery_pn_winners', $pn_winners );
		}

		return $winners;

	}
	/**
	 * Pick lottery winners from array.
	 * 
	 */
	public function pick_lottery_winers_from_array( $participants, $product ) {

		$winners = array();

		if ( is_array( $participants ) ) {

			$i                   = 0;
			$lottery_num_winners = $product->get_lottery_num_winners() ? $product->get_lottery_num_winners() : 1;

			while ( $i <= ( $lottery_num_winners - 1 ) ) {
				$winner_id         = '';
				$winners_key[ $i ] = mt_rand( 0, count( $participants ) - 1 );
				$winners[]         = $participants[ $winners_key[ $i ] ];
				$winner_id         = $participants[ $winners_key[ $i ] ]['userid'];
				if ( $product->get_lottery_multiple_winner_per_user() == 'yes' ) {
					unset( $participants[ $winners_key[ $i ] ] );
				} else {
					foreach ( $participants as $key => $participant ) {
						if ( $participant['userid'] == $winner_id ) {
							unset( $participants[ $key ] );
						}
					}
				}
				$participants = array_values( $participants );
				$i++;
				if ( count( $participants ) < $i ) {
					break;
				}
			}
		}
		return $winners;
	}
	/**
	 * Lottery pick numbers specific settings.
	 * 
	 */
	public function custom_woocommerce_lottery_settings( $settings ) {

		// $custom_options[] =  array(
		// 	'title' 			=> __( "Use just sold tickets number for draw", 'wc-lottery-pn' ),
		// 	'type' 				=> 'checkbox',
		// 	'id'				=> 'lottery_use_sold_tickets',
		// 	'default' 			=> 'no'
		// );
		$settings[] = array(
			'title' => __( 'Lottery pick number and answer', 'wc-lottery-pn' ),
			'type'  => 'title',
			'id'    => 'lottery_pn',
		);
		$settings[] = array(
			'title'   => __( 'Show answers in history tab', 'wc-lottery-pn' ),
			'type'    => 'checkbox',
			'id'      => 'lottery_answers_in_history',
			'default' => 'yes',
		);
		$settings[] = array(
			'title'   => __( 'Show answers in history only when lottery is finished', 'wc-lottery-pn' ),
			'type'    => 'checkbox',
			'id'      => 'lottery_answers_in_history_finished',
			'default' => 'no',
		);
		$settings[] = array(
			'title'   => __( 'Reserve ticket number when user puts it in cart', 'wc-lottery-pn' ),
			'type'    => 'checkbox',
			'id'      => 'lottery_answers_reserved',
			'default' => 'no',
		);
		$settings[] = array(
			'title'   => __( 'Hold reserve tickets for n minutes', 'wc-lottery-pn' ),
			'type'    => 'number',
			'id'      => 'lottery_answers_reserved_minutes',
			'default' => '5',
		);
		$settings[] = array(
			'type' => 'sectionend',
			'id'   => 'lottery_pn',
		);
		return $settings;

	}
	/**
	 * Get user ID from ticket number.
	 * 
	 */
	public function get_user_id_by_ticket_number( $ticket_number, $product_id ) {
		global $wpdb;

		$user_id = $wpdb->get_var( $wpdb->prepare( 'SELECT userid FROM ' . $wpdb->prefix . 'wc_lottery_log LEFT JOIN ' . $wpdb->prefix . 'wc_lottery_pn_log ON ' . $wpdb->prefix . 'wc_lottery_log.id = ' . $wpdb->prefix . 'wc_lottery_pn_log.log_id    WHERE ' . $wpdb->prefix . 'wc_lottery_log.lottery_id = %d AND ' . $wpdb->prefix . 'wc_lottery_pn_log.ticket_number = %d', $product_id, $ticket_number ) );

		return intval( $user_id );
	}
	/**
	 * Get log from ticker number.
	 * 
	 */
	public function get_log_by_ticket_number( $ticket_number, $product_id ) {
		global $wpdb;

		$log = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'wc_lottery_log LEFT JOIN ' . $wpdb->prefix . 'wc_lottery_pn_log ON ' . $wpdb->prefix . 'wc_lottery_log.id = ' . $wpdb->prefix . 'wc_lottery_pn_log.log_id    WHERE ' . $wpdb->prefix . 'wc_lottery_log.lottery_id = %d AND ' . $wpdb->prefix . 'wc_lottery_pn_log.ticket_number = %d', $product_id, $ticket_number ), ARRAY_A );

		return $log;
	}
	/**
	 * Remove lottery metaboxes.
	 * 
	 */
	public function remove_lottery_metaboxes(){
		remove_meta_box( 'Lottery', 'product', 'normal' );
	}
	/**
	 * Add meta box to the product editing screen.
	 *
	 * @access public
	 *
	 */
	function woocommerce_simple_lottery_meta() {

		global $post;

		$product_data = wc_get_product( $post->ID );
		if ( $product_data ) {
			$product_data_type = method_exists( $product_data, 'get_type' ) ? $product_data->get_type() : $product_data->product_type;
			if ( $product_data_type == 'lottery' ) {
				add_meta_box( 'Lottery-pn', __( 'Lottery', 'wc_lottery' ), array( $this, 'woocommerce_simple_lottery_meta_callback' ), 'product', 'normal', 'default' );
			}
		}

	}

	/**
	 *  Callback for adding a meta box to the product editing screen used in woocommerce_simple_lottery_meta
	 *
	 * @access public
	 *
	 */
	function woocommerce_simple_lottery_meta_callback() {

		global $post;
		$product_data    = wc_get_product( $post->ID );
		$lottery_history = apply_filters( 'woocommerce__lottery_history_data', $product_data->lottery_history() );
		$heading         = esc_html( apply_filters( 'woocommerce_lottery_history_heading', __( 'Lottery History', 'wc_lottery' ) ) );
		$lottery_winers  = get_post_meta( $post->ID, '_lottery_winners' );
		$order_hold_on   = get_post_meta( $post->ID, '_order_hold_on' );
		$use_answers 	 = wc_lottery_use_answers( $post->ID );
		$use_ticket_numbers = get_post_meta( $post->ID , '_lottery_use_pick_numbers', true );
		$answers = maybe_unserialize( get_post_meta( $post->ID, '_lottery_pn_answers', true ) );

		if ( $order_hold_on ) {
			$orders_links_on_hold = '';
			echo '<p>';
			_e( 'Some on hold orders are preventing this lottery to end. Can you please check it! ', 'wc_lottery' );
			foreach ( array_unique( $order_hold_on ) as $key => $order_hold_on_id ) {
				$orders_links_on_hold .= "<a href='" . admin_url( 'post.php?post=' . $order_hold_on_id . '&action=edit' ) . "'>$order_hold_on_id</a>, ";
			}
			echo rtrim( $orders_links_on_hold, ', ' );
			echo "<form><input type='hidden' name='clear_on_hold_orders' value='1'>";
			echo " <br><button class='button button-primary clear_orders_on_hold' data-product_id='" . $product_data->get_id() . "'>" . __( 'Clear all on hold orders! ', 'wc_lottery' ) . '</button>';
			echo '</form>';
			echo '</p>';
		}

		$lottery_relisted = $product_data->get_lottery_relisted();

		if ( ! empty( $lottery_relisted ) ) {
		?>
		<p><?php esc_html_e( 'Lottery has been relisted on:', 'wc_lottery' ); ?> <?php echo date_i18n( get_option( 'date_format' ), strtotime( $lottery_relisted )).' '.date_i18n( get_option( 'time_format' ), strtotime( $lottery_relisted )); ?></p>
		
		<?php
		}

		if ( ( $product_data->is_closed() === true ) and ( $product_data->is_started() === true ) ) : ?>
			<p><?php _e( 'Lottery has finished', 'wc_lottery' ); ?></p>
			<?php
			if ( $product_data->get_lottery_fail_reason() == '1' ) {
				echo '<p>';
					_e( 'Lottery failed because there were no participants', 'wc_lottery' );
					echo '</p>';
			} elseif ( $product_data->get_lottery_fail_reason() == '2' ) {
				echo '<p>';
				_e( 'Lottery failed because there was not enough participants', 'wc_lottery' );
				echo " <button class='button button-primary do-api-refund' href='#' id='lottery-refund' data-product_id='" . $product_data->get_id() . "'>";
				_e( 'Refund ', 'wc_lottery' );
				echo '</button>';
				echo '<div id="refund-status"></div>';
				echo '<//p>';
			}
			if ( $lottery_winers ) {
			
				if ( count( $lottery_winers ) === 1 ) { 

					$winnerid = reset( $lottery_winers );
					if ( ! empty( $winnerid ) ) {
					?>
							<p><?php _e( 'Lottery winner is', 'wc_lottery' ); ?>: <span><a href='<?php get_edit_user_link( $winnerid ); ?>'><?php echo get_userdata( $winnerid )->display_name; ?></a></span></p>
					<?php } ?>
				<?php } else { ?>

				<p><?php _e( 'Lottery winners are', 'wc_lottery' ); ?>:
					<ul>
					<?php
					foreach ( $lottery_winers as $key => $winnerid ) {
						if ( $winnerid > 0 ) {
						?>
							<li><a href='<?php get_edit_user_link( $winnerid ); ?>'><?php echo get_userdata( $winnerid )->display_name; ?></a></li>
					<?php
						}
					}
					?>
					</ul>
				</p>

			<?php } ?>

			<?php } ?>

		<?php endif; ?>

		<h2><?php echo $heading; ?> pick number</h2>
		<table class="lottery-table">
			<thead>
				<tr>
					<th><?php _e( 'Date', 'wc_lottery' ); ?></th>
					<th><?php _e( 'User', 'wc_lottery' ); ?></th>
					<?php if ($use_ticket_numbers === 'yes' ) :?>
						<th><?php _e('Ticket number', 'wc-lottery-pn') ?></th>
					<?php endif; ?>
					<?php if ($use_answers === true) :?>
						<th><?php _e('Answer', 'wc-lottery-pn') ?></th>
					<?php endif; ?>
					<th><?php _e( 'Order', 'wc_lottery' ); ?></th>
					<th class="actions"><?php _e( 'Actions', 'wc_lottery' ); ?></th>
				</tr>
			</thead>

			<?php
			if ( $lottery_history ) :
				foreach ( $lottery_history as $history_value ) {

					if ( $history_value->date < $product_data->get_lottery_relisted() && ! isset( $displayed_relist ) ) {
						echo '<tr>';
						echo '<td class="date">' . date_i18n( get_option( 'date_format' ), strtotime( $product_data->get_lottery_dates_from() )).' '.date_i18n( get_option( 'time_format' ), strtotime( $product_data->get_lottery_dates_from() )) . '</td>';
						echo '<td colspan="4"  class="relist">';
						echo __( 'Lottery relisted', 'wc_lottery' );
						echo '</td>';
						echo '</tr>';
						$displayed_relist = true;
					}
					echo '<tr>';
					echo '<td class="date">' . date_i18n( get_option( 'date_format' ), strtotime( $history_value->date )).' '.date_i18n( get_option( 'time_format' ), strtotime( $history_value->date )) . '</td>';
					echo "<td class='username'><a href='" . get_edit_user_link( $history_value->userid ) . "'>" . get_userdata( $history_value->userid )->display_name . '</a></td>';
					if ($use_ticket_numbers === 'yes' ) {
						echo "<td class='ticket_number'>$history_value->ticket_number</td>";
					}						
					if ( $use_answers === true ){
						$answer = isset( $answers[$history_value->answer_id] ) ? $answers[$history_value->answer_id] : false;
						echo "<td class='answer'>";
						echo $answer !== true ? $answer['text'] : "" ;
						echo "</td>";
					}
					echo "<td class='orderid'><a href='" . admin_url( 'post.php?post=' . $history_value->orderid . '&action=edit' ) . "'>" . $history_value->orderid . '</a></td>';
					echo "<td class='action'> <a href='#' data-id='" . $history_value->id . "' data-postid='" . $post->ID . "'    >" . __( 'Delete', 'wc_lottery' ) . '</a></td>';
					echo '</tr>';
				}
			endif;
			?>
			<tr class="start">
				<?php

					echo '<td class="date">' . date_i18n( get_option( 'date_format' ), strtotime( $product_data->get_lottery_dates_from() )).' '.date_i18n( get_option( 'time_format' ), strtotime( $product_data->get_lottery_dates_from() )) . '</td>';

				if ( $product_data->is_started() === true ) {				
					echo '<td colspan="3" class="started">';
					echo apply_filters( 'lottery_history_started_text', __( 'Lottery started', 'wc_lottery' ), $product_data );
					echo '</td>';

				} else {
					echo '<td colspan="3" class="starting">';
					echo apply_filters( 'lottery_history_starting_text', __( 'Lottery starting', 'wc_lottery' ), $product_data );
					echo '</td>';
				}
				?>
			</tr>				
		</table>
		</ul>
		<?php
	}
}
