<?php global $post, $thepostid; ?>

	<div id="wc_lotery_answers-tb" class="wc-metaboxes-wrapper" >
		<div class="toolbar toolbar-top">
			<p class="form-field">
			<?php woocommerce_wp_textarea_input( array(
				'id'          => '_lottery_question',
				'label'       => __( 'Question', 'wc-lottery-pn' ),
				'desc_tip'    => true,
				'description' => __( 'Ask user a question.', 'wc-lottery-pn' ),
			) );
			?>


			
			</p>
		</div>
		<div class="lotery_answers_wrapper wc-metaboxes answers">
			<?php
				// Product answers - taxonomies and custom, ordered, with visibility and variation answers set
				$answers = maybe_unserialize( get_post_meta( $thepostid, '_lottery_pn_answers', true ) );
				
				// Output All Set answers
				if ( ! empty( $answers ) ) {
					
					foreach ($answers as $answer_key => $answer) {

						$metabox_class = array();

						include(  plugin_dir_path( dirname( __FILE__ ) ) . 'partials/html-product-lottery-answers.php');;
					}
				}
			?>
		</div>
		<div class="toolbar">
			<button type="button" class="button add_lottery_answer"><?php _e( 'Add Answer', 'wc-lottery-pn' ); ?></button>
		</div>
	</div>
