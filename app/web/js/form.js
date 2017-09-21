jQuery(document).on("beforeSubmit", "form", function () {

    var form = jQuery(this);
    if (form.find('.has-error').length) {
        return false;
    }
    jQuery.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function (response) {

            var form = jQuery('form');
            var btn = form.find('button');

            form.find('#message-message').val('');
            if(response.hasOwnProperty('username')) {
                if(response.username !== null) {
                    Storage.set('username', response.username);
                    var input = document.getElementById('user-username');
                        input.type = 'hidden';
                        input.value = response.username;
                }
            }
            btn.attr('disabled', 'disabled');
            setTimeout(function () {
                btn.removeAttr('disabled');
            }, Message.disableTime);

            form.yiiActiveForm('remove', 'user-user');
            var getupdatedata = jQuery(response).find('#filter_id_test');
            jQuery('#yiiikap').html(getupdatedata);
        },
        error: function () {
            console.log('internal server error');
        }
    });

    return false;
});