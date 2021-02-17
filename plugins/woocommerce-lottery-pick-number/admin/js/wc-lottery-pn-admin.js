jQuery(function($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     */
    $('button.add_lottery_answer').on('click', function() {

        var key = $('.lotery_answers_wrapper input.lottery_answer:last').data('answer-id');
        if (typeof key == 'undefined') {
            key = 1;
        } else {
            key = parseInt(key) + 1;
        }
        var $wrapper = $('#wc_lotery_answers-tb');
        var $attributes = $wrapper.find('.answers');
        var product_type = $('select#product-type').val();
        var data = {
            action: 'woocommerce_add_lottery_answer',
            security: woocommerce_lottery_pn.add_lottery_answer_nonce,
            key: key,
        };

        $wrapper.block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });

        $.post(woocommerce_admin_meta_boxes.ajax_url, data, function(response) {
            $attributes.append(response);

            $(document.body).trigger('wc-enhanced-select-init');

            $wrapper.unblock();

            $(document.body).trigger('woocommerce_added_add_lottery_answer');
        });

        return false;
    });


    $('.lotery_answers_wrapper').on('click', '.remove_row', function() {
        if (window.confirm(woocommerce_lottery_pn.remove_wcsbs)) {
            var $parent = $(this).parent().parent();

            $parent.find('select, input').val('');
            $parent.hide();

        }
        return false;
    });



});

jQuery(document).ready(function($) {
    if (typeof $('#_lottery_use_answers') !== 'undefined' && $('#_lottery_use_answers').is(':checked')) {
        $('#wc_lotery_answers-tb').show();
        $('.form-field._lottery_only_true_answers_field').show();
        $("#_lottery_question").prop('required', true);
    }
    if (typeof $('#_lottery_use_pick_numbers') !== 'undefined' && $('#_lottery_use_pick_numbers').length) {
        if ($('#_lottery_use_pick_numbers').is(':checked')) {
            document.getElementById("_lottery_pick_numbers_random").disabled = false;
        } else {
            document.getElementById("_lottery_pick_numbers_random").disabled = true;
        }
    }
    $("#_lottery_use_pick_numbers").on('change', function() {
        if (this.checked) {
            document.getElementById("_lottery_pick_numbers_random").disabled = false;
        } else {
            document.getElementById("_lottery_pick_numbers_random").disabled = true;
        }
    });

    if (typeof $('#_lottery_pick_numbers_random') !== 'undefined' && $('#_lottery_pick_numbers_random').length) {

        if ($('#_lottery_pick_numbers_random').is(':checked')) {
            document.getElementById("_lottery_pick_number_use_tabs").disabled = true;
            document.getElementById("_lottery_pick_number_tab_qty").disabled = true;
            $('._lottery_pick_number_use_tabs_field').hide();
            $('._lottery_pick_number_tab_qty_field ').hide();
        } else {
            document.getElementById("_lottery_pick_number_use_tabs").disabled = false;
            document.getElementById("_lottery_pick_number_tab_qty").disabled = false;
            $('#_lottery_pick_number_use_tabs-tb').show();
            $('._lottery_pick_number_tab_qty_field ').show();
        }
    }
    $("#_lottery_pick_numbers_random").on('change', function() {
        if (this.checked) {
            document.getElementById("_lottery_pick_number_use_tabs").disabled = true;
            document.getElementById("_lottery_pick_number_tab_qty").disabled = true;
            $('._lottery_pick_number_use_tabs_field').hide();
            $('._lottery_pick_number_tab_qty_field ').hide();
        } else {
            document.getElementById("_lottery_pick_number_use_tabs").disabled = false;
            document.getElementById("_lottery_pick_number_tab_qty").disabled = false;
            $('._lottery_pick_number_use_tabs_field').show();
            $('._lottery_pick_number_tab_qty_field ').show();
        }
    });


    $("#_lottery_use_answers").on('change', function() {
        if (this.checked) {
            $('#wc_lotery_answers-tb').slideDown('fast');
            $("#_lottery_question").prop('required', true);
            $('.form-field._lottery_only_true_answers_field').show();
        } else {
            $('#wc_lotery_answers-tb').slideUp('fast');
            $("#_lottery_question").prop('required', false);
            $('.form-field._lottery_only_true_answers_field').hide();
        }
    });
});