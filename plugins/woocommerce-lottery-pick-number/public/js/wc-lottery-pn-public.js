jQuery(document).ready(function($){

	$('#wc-lottery-pn').on('click','ul:not(.working) li.tn:not(.taken, .in_cart)',function(e){

		var max_qty = $('input[name=max_quantity]').val();
		var current_number = $( this );
		
		if( max_qty <= 0 && ! current_number.hasClass('selected')){
			$.alertable.alert(wc_lottery_pn.maximum_text);
			return;
		}
		if($('#wc-lottery-pn').hasClass('guest')){
			$.alertable.alert(wc_lottery_pn.logintext, { 'html' : true } );
			return;
		}
		
		$( this ).addClass('working');

		var numbers = $( 'ul.tickets_numbers');
		var lottery_id = numbers.data( 'product-id' );
		var selected_number = $( this ).data( 'ticket-number' );
		var lottery_tickets_numbers = $('input[name=lottery_tickets_number]').val();
		var lottery_tickets_numbers_array = [];
		if( lottery_tickets_numbers ) {
			lottery_tickets_numbers_array = lottery_tickets_numbers.split(',');	
		}
		console.log(selected_number);
		$('html, body').css("cursor", "wait");
		numbers.addClass('working');

		jQuery.ajax({
			type : "get",
			url : woocommerce_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'wc_lottery_get_taken_numbers' ),
			data : { 'selected_number' : selected_number, 'lottery_id' : lottery_id},
			success: function(response) {

				numbers.children('li.tn').each(function(index, el) {
					if( jQuery.inArray( $( this ).data( 'ticket-number' ).toString(), response.taken ) !== -1){
						$( this ).addClass('taken');
					}
					if( jQuery.inArray( $( this ).data( 'ticket-number' ).toString(), response.in_cart ) !== -1){
						$( this ).addClass('in_cart');
					}
					if( jQuery.inArray( $( this ).data( 'ticket-number' ).toString(), response.reserved ) !== -1){
						$( this ).addClass('in_cart');
					}
				});
				if( jQuery.inArray( selected_number.toString(), response.taken ) > 0) {
					$.alertable.alert(wc_lottery_pn.sold_text);
					numbers.removeClass('working');
					current_number.removeClass('working');
					return;
				}
				
				if( jQuery.inArray( selected_number.toString(), response.in_cart ) > 0) {
					$.alertable.alert(wc_lottery_pn.in_cart_text);
					numbers.removeClass('working');
					current_number.removeClass('working');
					return;
				}

				if( jQuery.inArray( selected_number.toString(), response.taken ) === -1) {
					current_number.toggleClass('selected');
				}

				if (current_number.hasClass('selected') && (jQuery.inArray(selected_number , lottery_tickets_numbers_array ) === -1)) {
					lottery_tickets_numbers_array.push( parseInt(selected_number) );
					$('input[name=lottery_tickets_number]').val( lottery_tickets_numbers_array.join(',') );
					$('input[name=max_quantity]').val( parseInt(max_qty) - 1);
				} else {
					lottery_tickets_numbers_array.splice( $.inArray(selected_number,lottery_tickets_numbers_array) ,1 );
					$('input[name=lottery_tickets_number]').val( lottery_tickets_numbers_array.join(',') );
					$('input[name=max_quantity]').val( parseInt(max_qty) + 1);
				}

				$('input[name=quantity]').val( parseInt(lottery_tickets_numbers_array.length) );
				jQuery( document.body ).trigger('sa-wachlist-action',[response.taken,lottery_id, selected_number] );
				$('html, body').css("cursor", "auto");
				numbers.removeClass('working');
				current_number.removeClass('working');

				if ( $('input[name=quantity]').val() > 0) { 
					$(':input[name=add-to-cart]').removeClass('lottery-must-pick');
				} else { 
					$(':input[name=add-to-cart]').addClass('lottery-must-pick');
				}
			},
			error: function() {
				numbers.removeClass('working');
				current_number.removeClass('working');

			}
		});

	});

	$('.lottery-pn-answers').on('click','li',function(e){
		var answer_id = $(this).data('answer-id');
		if($(this).hasClass('selected')){
			answer_id = -1;
		}
		$('input[name=lottery_answer]').val( parseInt(answer_id) );
		$(this).siblings("li.selected").removeClass("selected").removeClass("false");
		$(this).toggleClass("selected");
		if ( $('input[name=lottery_true_answers]').val() ) {
			lottery_true_answers = $('input[name=lottery_true_answers]').val().split(',');
			
			if( jQuery.inArray( answer_id.toString(), lottery_true_answers ) === -1) {
				$(this).toggleClass('false');
				$(':input[name=add-to-cart]').addClass('lottery-must-answer-true');
			} else{
				$(':input[name=add-to-cart]').removeClass('lottery-must-answer-true');
			}
		}

		if ( $('input[name=lottery_answer]').val() > 0) {
			$(':input[name=add-to-cart]').removeClass('lottery-must-answer');
		} else { 
			$(':input[name=add-to-cart]').addClass('lottery-must-answer');
		}
	});

	$('.cart.pick-number').on('submit',function(e){
		var message = '';
		var pass = true;
		if ( $(':input[name=add-to-cart]').hasClass('lottery-must-pick') ){
			message = message + wc_lottery_pn.please_pick;
			pass = false;
		}
		if ( $(':input[name=add-to-cart]').hasClass('lottery-must-answer') ){
			message = message + wc_lottery_pn.please_answer;
			pass = false;
		}
		if ( $(':input[name=add-to-cart]').hasClass('lottery-must-answer-true') ){
			message = message + wc_lottery_pn.please_true_answer;
			pass = false;
		}
		if ( pass == false ){
			$.alertable.alert(message);
			e.preventDefault();
		}

	});
});
