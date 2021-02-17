<?php

if ( ! function_exists( 'wc_lottery_pn_get_taken_numbers' ) ) {
	function wc_lottery_pn_get_taken_numbers( $product_id = false, $user_id = false ) {
		global $product;

		$wheredatefrom = '';

		if ( ! $product_id && $product ) {
				$product_id = $product->get_id();
		}
		global $wpdb;

		$relisteddate = get_post_meta( $product_id, '_lottery_relisted', true );

		if ( $relisteddate ) {
			$wheredatefrom = ' AND CAST(' . $wpdb->prefix . "wc_lottery_log.date AS DATETIME) > '$relisteddate' ";
		}

		$result = $wpdb->get_col( $wpdb->prepare( 'SELECT ' . $wpdb->prefix . 'wc_lottery_pn_log.ticket_number FROM ' . $wpdb->prefix . 'wc_lottery_pn_log LEFT JOIN ' . $wpdb->prefix . 'wc_lottery_log ON ' . $wpdb->prefix . 'wc_lottery_log.id = ' . $wpdb->prefix . 'wc_lottery_pn_log.log_id WHERE ' . $wpdb->prefix . 'wc_lottery_pn_log.lottery_id = %d ' . $wheredatefrom, $product_id ) );

		return $result;
	}
}

if ( ! function_exists( 'wc_lottery_pn_get_reserved_numbers' ) ) {
	function wc_lottery_pn_get_reserved_numbers( $product_id = false, $user_id = false ) {
		global $product;

		if ( ! $product_id && $product ) {
				$product_id = $product->get_id();
		}
		global $wpdb;

		$minutes = get_option( 'lottery_answers_reserved_minutes', '5' );

		$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . 'wc_lottery_pn_reserved WHERE date < (NOW() - INTERVAL %d MINUTE)', $minutes ) );

		$result = $wpdb->get_col( $wpdb->prepare( 'SELECT ' . $wpdb->prefix . 'wc_lottery_pn_reserved.ticket_number FROM ' . $wpdb->prefix . 'wc_lottery_pn_reserved  WHERE ' . $wpdb->prefix . 'wc_lottery_pn_reserved.lottery_id = %d ', $product_id ) );

		return $result;
	}
}


if ( ! function_exists( 'wc_lottery_pn_get_true_answers' ) ) {
	function wc_lottery_pn_get_true_answers( $product_id = false ) {
		global $product;

		$answers_id = array();

		if ( ! $product_id && $product ) {
				$product_id = $product->get_id();
		}

		$answers = maybe_unserialize( get_post_meta( $product_id, '_lottery_pn_answers', true ) );

		if ( $answers ) {
			foreach ( $answers as $key => $answer ) {
				if ( 1 === $answer['true'] ) {
						$answers_id[ $key ] = $answer['text'];
				}
			}
		}

		return $answers_id;
	}
}


if ( ! function_exists( 'wc_lottery_pn_get_ticket_numbers_from_cart' ) ) {
	function wc_lottery_pn_get_ticket_numbers_from_cart( $product_id = false ) {
		$items          = WC()->cart->get_cart();
		$ticket_numbers = array();
		foreach ( $items as $key => $value ) {
			if ( isset( $ticket_numbers[ $value['product_id'] ] ) ) {
				$ticket_numbers[ $value['product_id'] ] = array_merge( $ticket_numbers[ $value['product_id'] ], $value['lottery_tickets_number'] );
			} elseif ( isset( $value['lottery_tickets_number'] ) ) {
				$ticket_numbers[ $value['product_id'] ] = $value['lottery_tickets_number'];
			}
		}
		if ( $product_id ) {
			return isset( $ticket_numbers[ $product_id ] ) ? $ticket_numbers[ $product_id ] : array();
		}
		return $ticket_numbers;
	}
}


if ( ! function_exists( 'wc_lottery_use_answers' ) ) {
	function wc_lottery_use_answers( $product_id = false ) {

		global $product;

		if ( ! $product_id && $product ) {
				$product_id = $product->get_id();
		}

		$use_answers = get_post_meta( $product_id, '_lottery_use_answers', true );

		if ( 'yes' !== $use_answers ) {
			return false;
		}

		$lottery_question = get_post_meta( $product_id, '_lottery_question', true );

		if ( ! $lottery_question ) {
			return false;
		}

		$answers = maybe_unserialize( get_post_meta( $product_id, '_lottery_pn_answers', true ) );
		if ( ! $answers ) {
			return false;
		}

		return true;
	}
}
if ( ! function_exists( 'wc_lottery_generate_random_ticket_numbers' ) ) {
	function wc_lottery_generate_random_ticket_numbers( $product_id, $qty ) {
		$taken_numbers = wc_lottery_pn_get_taken_numbers( $product_id );
		$max_tickets   = intval( get_post_meta( $product_id, '_max_tickets', true ) );

		$random_tickets = array();
		$i              = 1;

		while ( $i <= $qty ) {
			do {
				$n = mt_rand( 1, $max_tickets );
			} while ( in_array( $n, $taken_numbers ) );
				if ( !in_array( $n, $random_tickets ) ){
					$random_tickets[] = $n;
					$i++;
				}
		}

		return $random_tickets;

	}
}



