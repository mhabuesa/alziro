"use strict";

$(document).ready(function() {
    countdown();
});

$('#customer_auth_resend_otp_reset_password').click(function(){
    $('input.otp-field').val('');
    let identity = $('#request_identity').val();
    let form_url = $(this).data('route');
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
    });
    $.ajax({
        url: form_url,
        method: 'POST',
        dataType: 'json',
        data: {'identity': identity,},
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.status == 1) {

                let new_counter = $(".verifyCounter");
                let new_seconds = data.new_time;
                function new_tick() {
                    let m = Math.floor(new_seconds / 60);
                    let s = new_seconds % 60;
                    new_seconds--;
                    new_counter.html(m + ":" + (s < 10 ? "0" : "") + String(s));
                    if (new_seconds > 0) {
                        setTimeout(new_tick, 1000);
                        $('.resend-otp-button').attr('disabled', true);
                        $(".resend_otp_custom").slideDown();
                    }
                    else {
                        $('.resend-otp-button').removeAttr('disabled');
                        $(".verifyCounter").html("0:00");
                        $(".resend_otp_custom").slideUp();
                    }
                }
                new_tick();

                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
});
