/**
 *
 * Author: Robert Holden
 * Project: Snow Capture
 *
 */
var forgotPassword = {
    forgot: function(email, callbacks) {
        ajax.connect({
            url: '/api/user/forgotten_password',
            type: 'POST',
            data: { email: email },
            dataType: 'json',
            loader: true,
            before: callbacks.before,
            done: function(data) {
                callbacks.done(data);
            },
            error: callbacks.fail,
            always: callbacks.always
        });
    },

    reset: function(obj, callbacks) {
        ajax.connect({
            url: '/api/user/forgotten_password',
            type: 'POST',
            data: obj,
            dataType: 'json',
            loader: true,
            before: callbacks.before,
            done: function(data) {
                callbacks.done(data);
            },
            error: callbacks.fail,
            always: callbacks.always
        });
    }
};

$(document).ready(function() {
    //Event to forget password
    $('#forgotten-form').on('submit', function(e) {
        var validated = form.validate($(this));
        if (!validated) {
            functions.toast('Please enter a valid email', false);
            $('#forgot-email').focus();
            return false;
        }

        // Build ajax callbacks
        var callbacks = ajax.callbacks();

        callbacks.done = function(data) {
            if (typeof data.error === 'undefined') {
                functions.toast(data.success, true);
                $('#forgotten-email').val('');
                $('#forgotten-email').blur();
                $('#forgotten-email')
                    .parent('.validate-group')
                    .removeClass('has-error');
            } else {
                functions.toast(data.error, false);
                $('#forgotten-email')
                    .parent('.validate-group')
                    .addClass('has-error');
            }
        };

        forgotPassword.forgot($('#forgotten-email').val(), callbacks);

        e.preventDefault();
    });

    // Event to reset password
    $('#reset-form').on('submit', function(e) {
        var validated = validate($(this));
        var passwordCheck = passwordConfirmCheck();

        if (!(validated && passwordCheck)) return;

        // Build ajax callbacks
        var callbacks = ajax.callbacks();

        callbacks.done = function(data) {
            if (typeof data.error === 'undefined') {
                functions.toast(data.success, true);
                setTimeout(function(e) {
                    location.href = '/sign-in';
                }, 1000);
            } else {
                functions.toast(data.error, false);
            }
        };

        var obj = {
            password: $('#password').val(),
            confirm_password: $('#confirm-password').val(),
            token: $('#token').val()
        };

        forgotPassword.reset(obj, callbacks);

        e.preventDefault();
    });
});
