jQuery(document).on("beforeSubmit", "form", function () {
    // send data to actionSave by ajax request.

    var form = jQuery(this);
    if (form.find('.has-error').length) {
        return false;
    }
    jQuery.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function (response) {
            console.log(response);
            var getupdatedata = jQuery(response).find('#filter_id_test');
            // jQuery.pjax.reload('#note_update_id'); for pjax update
            jQuery('#yiiikap').html(getupdatedata);
            //console.log(getupdatedata);
        },
        error: function () {
            console.log('internal server error');
        }
    });
    return false;
    return false; // Cancel form submitting.
});