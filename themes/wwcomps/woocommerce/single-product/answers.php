<?php
/**
 * Tickets numbers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tickets-numbers.php.
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;
$answers = maybe_unserialize( get_post_meta( $product->get_id(), '_lottery_pn_answers', true ) );
$lottery_question =  get_post_meta( $product->get_id(), '_lottery_question', true );
if ( empty( $lottery_question ) || empty( $answers ) ) {
	return;
}
?>

<h3 style="display: none"><?php esc_html_e( 'Answer the question:' , 'wc-lottery-pn' )?></h3>
<p class="lottery-question"><?php echo wp_kses_post( $lottery_question ) ?></p>
<input type="hidden" name="lottery_question" value="<?php echo wp_kses_post( $lottery_question ) ?>">
<?php if ( is_array( $answers ) ){
	echo '<ul class="lottery-pn-answers">';
	foreach ($answers as $key => $answer) {
		echo '<li data-answer-id='. intval( $key ) .' >' . wp_kses_post( $answer['text'] ) . '</li>';
	}
	echo '</ul>';

}


