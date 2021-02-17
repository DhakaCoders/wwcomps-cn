<div class="woocommerce_lottery_answer_box wc-metabox closed" rel="<?php echo esc_attr( $answer_key ); ?>">
	<div class="woocommerce_answer_data">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tbody>
		<tr>
			<td>
				<label><?php _e( 'Answer', 'wc-lottery-pn' ); ?>:</label>

				<input type="text" class="lottery_answer" name="lottery_answer[<?php echo esc_attr( $answer_key ); ?>]" size="20" value="<?php echo esc_attr( $answer['text'] ); ?>" data-answer-id="<?php echo esc_attr( $answer_key ) ?>" />
			</td>

			<td class="answer_checkbox">
				<label><input type="checkbox" class="checkbox" <?php checked( $answer['true'], 1 ); ?> name="lottery_answer_true[<?php echo esc_attr( $answer_key ) ; ?>]" value="1" /> <?php esc_html_e( 'True', 'wc-lottery-pn' ); ?></label>
			</td>

			<td class="remove-answer"><a href="#" class="remove_row delete"><?php _e( 'Remove', 'woocommerce' ); ?></a></td>
		</tr>
		</tbody>
		</table>
	</div>
</div>
