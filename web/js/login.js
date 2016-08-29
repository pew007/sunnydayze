$(function () {
    $('#login-submit').on('click', function (e) {
        e.preventDefault();

        var button = $(this);
        var form = $('#login-form');
        var url = form.attr('action');

        button.button('loading');

        $.ajax({
           method: 'POST',
           url: url,
           data: form.serialize()
        }).done(function (data) {
            button.button('reset');

            var action = data.action;
            var url = data.url;

            if (action == 'redirect') {
                window.location = url;
            }
        }).fail(function (xhr) {
            var action = xhr.responseJSON.action;
            var message = xhr.responseJSON.message;

            displayMessage('error', message);
            button.button('reset');

            if (action == 'resetPassword') {
                $('#resetPasswordModal').modal();
            }
        });
    });

    $('#request-password-reset-submit').on('click', function (e) {
        e.preventDefault();

        var form = $('#requestPasswordResetForm');
        var url = form.attr('action');

        $.ajax({
                   method: 'POST',
                   url: url,
                   data: form.serialize()
               }).done(function (data) {
            var email = data.email;
            displayMessage('notice', "Password reset instruction was sent to " + email);
        }).fail(function (xhr) {
            var error = xhr.responseJSON.message;
            displayMessage('error', error);
        }).always(function () {
            $('#resetPasswordModal').modal('hide');
        });
    });

    function displayMessage(type, message) {
        var alertClass = type == 'notice' ? 'alert-success' : 'alert-danger';
        var htmlContent =
                "<div class='alert " + alertClass + "'>" +
                "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" +
                message +
                "</div>";

        $('.aresFlashMessage').html(htmlContent);
    }
});
