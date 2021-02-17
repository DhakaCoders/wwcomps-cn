<?php
/**
 * Winners block template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/winners.php.
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global  $product, $post;
$current_user       = wp_get_current_user();
$lottery_winers     = get_post_meta($post->ID, '_lottery_winners');
$lottery_pn_winers  = get_post_meta($post->ID, '_lottery_pn_winners',true);
$use_answers        = wc_lottery_use_answers( $post->ID );
$use_ticket_numbers = get_post_meta( $post->ID , '_lottery_use_pick_numbers', true );
$answers            = maybe_unserialize( get_post_meta( $post->ID, '_lottery_pn_answers', true ) );

?>


<?php if(get_post_meta($post->ID, '_order_hold_on')) { ?>

	<p><?php esc_html_e('Please be patient. We are waiting for some orders to be paid!','wc-lottery-pn') ?></p>

<?php } elseif('yes' === get_post_meta( $post->ID, '_lottery_manualy_winners', true ) && empty($lottery_winers) ){
		esc_html_e('Please be patient. We are picking winners!','wc-lottery-pn'); 
	} else { 
	
	if ($product->is_user_participating()) : ?>
			<?php if(in_array($current_user->ID, $lottery_winers)): ?>
					<p><?php esc_html_e('Congratulations! You are winner!','wc-lottery-pn') ?></p>
			<?php else: ?>
					<p><?php esc_html_e('Sorry, better luck next time.','wc-lottery-pn') ?></p>
			<?php endif; ?>		
	<?php endif;?>
	
<?php } 
if ( ! empty( $lottery_pn_winers ) ) {

	if (count($lottery_pn_winers) > 1) { ?>

		<h3><?php esc_html_e('Winners:','wc-lottery-pn') ?></h3>

		<ol class="lottery-winners">
		<?php 	

	        foreach ($lottery_pn_winers as $winner) {

	                echo "<li>";
	                if ( intval( $winner ) > 0){
	                echo get_userdata($winner['userid'])->display_name;
		                echo '<br>';
		                if( $use_ticket_numbers === 'yes'){
		                	echo "<span class='ticket-number'>";
		                	esc_html_e( 'Ticket number: ', 'wc-lottery-pn' );
		                	echo $winner['ticket_number'];
		                	echo " </span>";
		                }
		                if( $use_answers === true ){
		                	echo "<br><span class='ticket-answer'>";
		                	esc_html_e( 'Answer: ', 'wc-lottery-pn' );
		                	$answer = isset( $answers[$winner['answer_id']]['text'] ) ? $answers[$winner['answer_id']]['text'] : '';
		                	echo $answer;
		                	echo "</span>";
	                	}
	                }
	                echo "</li>";
	        }		
		?>
		</ol>

		<?php } elseif( 1 === count( $lottery_pn_winers )  ) { 

			$winner = reset($lottery_pn_winers);

			if ( ! empty ( $winner ) ) {
			?>
				<div class="lottery-winners">
				<h3><?php esc_html_e('Winner is:','wc-lottery-pn') ?> <?php echo get_userdata($winner['userid'])->display_name; ?></h3>
					<?php if( $use_ticket_numbers === 'yes'){
						echo " <span class='ticket-number'>";
						esc_html_e( 'Ticket number: ', 'wc-lottery-pn' );
						echo $winner['ticket_number'];
						echo "</span>";
					}
					if( $use_answers === true ){
						echo " <span class='ticket-answer'>";
						esc_html_e( 'Answer: ', 'wc-lottery-pn' );
						$answer = isset( $answers[$winner['answer_id']]['text'] ) ? $answers[$winner['answer_id']]['text'] : '';
						echo $answer;
						echo "</span>";
					}
				echo '</div>';
			} else {
				echo '<h3>';
				esc_html_e( 'There is no winner for this lottery', 'wc-lottery-pn' );
				echo '</h3>';
			}
			

		} else {
			echo '<h3>';
			esc_html_e( 'There is no winner for this lottery', 'wc-lottery-pn' );
			echo '</h3>';
		} 
	
} else {

	if( is_array($lottery_winers) && ! empty ( $lottery_winers ) ){ 

		if (count($lottery_winers) > 1) { ?>

			<h3><?php esc_html_e('Winners:','wc-lottery-pn') ?></h3>

			<ol class="lottery-winners">
			<?php 	

				foreach ($lottery_winers as $winner_id) {
						echo "<li>";
						echo intval($winner_id) > 0 ? get_userdata($winner_id)->display_name : esc_html_e( 'N/A ', 'wc-lottery-pn' );;
						echo "</li>";
				}		
			?>
			</ol>

		<?php } elseif ( isset( $lottery_winers[0] ) ) { ?>

			<h3><?php esc_html_e('Winner is:','wc-lottery-pn') ?> <?php echo get_userdata( $lottery_winers[0] )->display_name; ?></h3>
		
		<?php } ?>

	<?php } 
}

if ( get_post_meta($post->ID, '_lottery_manualy_pick_text', true ) ){
	echo '<p>';
	echo wp_kses_post( get_post_meta($post->ID, '_lottery_manualy_pick_text', true ) );
	echo '</p>';
}