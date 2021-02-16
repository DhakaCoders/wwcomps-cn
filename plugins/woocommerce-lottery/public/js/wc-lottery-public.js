jQuery(document).ready(function($){

	$( ".lottery-time-countdown" ).each(function( index ) {
            
		var time 	= $(this).data('time');
		var format 	= $(this).data('format');
		
		if(format == ''){
			format = 'yowdHMS';
		}
		var etext ='';
		if($(this).hasClass('future') ){
			var etext = '<div class="started">'+wc_lottery_data.started+'</div>';	
		} else{
			var etext = '<div class="over">'+wc_lottery_data.finished+'</div>';
			
		}
		if(wc_lottery_data.compact_counter == 'yes'){
			compact	 = true;
		} else{
			compact	 = false;
		}
		
		$(this).wc_lotery_countdown({
			until:   $.wc_lotery_countdown.UTCDate(-(new Date().getTimezoneOffset()),new Date(time*1000)),
			format: format, 
			expiryText: etext,
			compact:  compact
		});
			 
	});
	
});


