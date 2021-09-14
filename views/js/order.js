// var url_link = document.getElementById('thecoderpsshipping_id').dataset.thecoderpsshipping_url;
$(document).ready(function(){
    var ajax_url = $('#thecoderpsshipping_id').data('thecoderpsshipping_url');
    var thecoderpsshipping_id = $('#thecoderpsshipping').data('thecoder_carrier_id');

    $('#carrier_city_list .carrier_city_item input[type=radio]').click(function(e){
        $.ajax({
            type: 'POST',
            url: ajax_url,
            cache: false,
            dataType: 'JSON',
            data: {
                action: 'SaveShippingCost',
                ajax: true,
                thecoder_id_shipping: $(this).data('thecoderpsshipping_id'),
            },
            success: function(data) {
                console.log(data);
            //     if(data.return) {
            //         prestashop.emit('updateCart', {reason: {linkAction: 'refresh'}, resp: {}})

            //         // $('label[for="delivery_option_'+ thecoderpsshipping_id +'"] .carrier-price').html(data.)
            //     }
            // },
            // error: function(jqXHR, textStatus, errorThrown) {
	        //     console.log(textStatus + ' ' + errorThrown);
	        }
        })
    })

})
