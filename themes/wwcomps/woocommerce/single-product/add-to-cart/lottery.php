<?php
/**
 * Lottery add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/lottery.php.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product );

if ( ! $product->is_in_stock() ) {
	return;
}



$use_ticket_numbers = get_post_meta( $product->get_id() , '_lottery_use_pick_numbers', true );
$random_ticket_numbers = get_post_meta( $product->get_id() , '_lottery_pick_numbers_random', true );
$use_answers        = wc_lottery_use_answers( $product->get_id() );
do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="cart pick-number" action="<?php echo esc_url( get_permalink() ); ?>" method="post" enctype='multipart/form-data'>
<?php
			if ( 'yes' === $use_ticket_numbers && "yes" !== $random_ticket_numbers ): ?>
			<?php if( get_post_meta( $product->get_id() , '_lottery_pick_number_use_tabs', true ) === 'yes' ) {
				wc_get_template('single-product/tickets-numbers-tabbed.php' );
			} else {
				wc_get_template('single-product/tickets-numbers.php' );
			}?>
			<input type="hidden" value="" name='lottery_tickets_number'  >
			<input type="hidden" name='quantity' value= "" >
			<?php if ( $product->get_max_purchase_quantity() ) {?>
				<input type="hidden" name='max_quantity' value= "<?php echo intval( $product->get_max_purchase_quantity() ) ?>" >
			<?php } ?>

		<?php endif; ?>
		
		<?php if( true === $use_answers ): ?>
			
			<?php wc_get_template('single-product/answers.php' );  ?>
			<input type="hidden" value="" name='lottery_answer'>
			<?php if ( 'yes' === get_post_meta( $product->get_id() , '_lottery_only_true_answers', true ) ) {

				$true_answers = wc_lottery_pn_get_true_answers( $product->get_id() );
				if( is_array($true_answers) ) {
					$true_answers_value = implode(",", array_keys($true_answers));
				}
				echo '<input type="hidden" value="' . esc_attr( $true_answers_value ) . '" name="lottery_true_answers">';

			}?>

		<?php endif; ?>

	<div class="pro-single-btm clearfix">
	<div class="qty-icon">
	<span class="icon-btn"></span>
	<div class="qty">
	<button class="minus" type="button"><img src="<?php echo THEME_URI; ?>/assets/images/up.png"></button>
	<?php
		/**
		 * @since 2.1.0.
		 */
		do_action( 'woocommerce_before_add_to_cart_button' );

		do_action( 'woocommerce_before_add_to_cart_quantity' );

		// if( 'yes' !== $use_ticket_numbers || "yes" === $random_ticket_numbers ) {
			woocommerce_quantity_input( array(
				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
				'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $product->get_min_purchase_quantity(),
			), $product);
		// }

		/**
		 * @since 3.0.0.
		 */
		do_action( 'woocommerce_after_add_to_cart_quantity' );


?>
<button class="plus" type="button"><img src="<?php echo THEME_URI; ?>/assets/images/down.png"></button>
</div>
</div>
	<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt <?php echo 'yes' === $use_ticket_numbers && "yes" !== $random_ticket_numbers ? ' lottery-must-pick ' : ''; echo true === $use_answers ? ' lottery-must-answer ' : '' ; ?>" >Enter now</button>

	<?php
		/**
		 * @since 2.1.0.
		 */
		do_action( 'woocommerce_after_add_to_cart_button' );
	?>
	</div>

	</div>
</div>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
