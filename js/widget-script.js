jQuery("#ch_form").submit(function(event) {

    /* stop form from submitting normally */
    event.preventDefault();

    /* get the action attribute from the form element */
    var url = jQuery( this ).attr( 'action' );

    /* Send the data using post */
    jQuery.ajax({
        type: 'POST',
        url: url,
        data: {
            action: jQuery('#cp_action').val(),
            phone_number: jQuery('#cp_phone_number').val(), 
            nonce: jQuery('#ch_nonce_field').val()
        },
        success: function (data, textStatus, XMLHttpRequest) {
            alert(data);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
  
});


