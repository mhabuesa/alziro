"use strict";

$( document ).ready(function() {
    countdown();
});

$('#customer_verify').on('submit', function (event) {
    event.preventDefault();
    let login_form = $(this);
    $.ajax({
        url: login_form.attr('action'),
        method: 'POST',
        dataType: "json",
        data: login_form.serialize(),
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.status === 'success') {
                $('#otp_form_section').addClass('d-none');
                $('#success_message').removeClass('d-none');
                $('#loginModal').modal('show');
                toastr.success(data.message);
            }else{
                toastr.error(data.message);
            }

        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
});

