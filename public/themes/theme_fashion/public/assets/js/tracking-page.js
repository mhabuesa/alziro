"use strict";

function digital_product_download(link)
{
    $.ajax({
        type: "GET",
        url: link,
        responseType: 'blob',
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.status == 1 && data.file_path) {
                const a = document.createElement('a');
                a.href = data.file_path;
                a.download = data.file_name;
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(data.file_path);
            } else if(data.status == 2) {
                $('#order_details').modal('hide');
                $('#digital_product_order_otp_verify .modal-body').empty().html(data.view);
                $('#digital_product_order_otp_verify').modal('show');

                let new_counter = $(".verifyCounter");
                let new_seconds = new_counter.data('second');
                function new_tick() {
                    let m = Math.floor(new_seconds / 60);
                    let s = new_seconds % 60;
                    new_seconds--;
                    new_counter.html(m + ":" + (s < 10 ? "0" : "") + String(s));
                    if (new_seconds > 0) {
                        setTimeout(new_tick, 1000);
                        $('.resend-otp-button').attr('disabled', true);
                        $(".resend_otp_custom").slideDown();
                    }else {
                        $('.resend-otp-button').removeAttr('disabled');
                        $(".verifyCounter").html("0:00");
                        $(".resend_otp_custom").slideUp();
                    }
                }
                new_tick();
                otp_verify_events();
            }else if(data.status == 0) {
                toastr.error(data.message);
                $('#digital_product_order_otp_verify').modal('hide');
            }
        },
        error: function () {
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}

function otp_verify_events()
{
    $(".otp-form .submit-btn").attr("disabled", true);
    $(".otp-form .submit-btn").addClass("disabled");
    $(".otp-form *:input[type!=hidden]:first").focus();
    let otp_fields = $(".otp-form .otp-field"),
        otp_value_field = $(".otp-form .otp-value");
    otp_fields.on("input", function (e) {
        $(this).val($(this).val().replace(/[^0-9]/g, ""));
        let otp_value = "";
        otp_fields.each(function () {
            let field_value = $(this).val();
            if (field_value != "") otp_value += field_value;
        });
        otp_value_field.val(otp_value);
        if (otp_value.length === 4) {
            $(".otp-form .submit-btn").attr("disabled", false);
            $(".otp-form .submit-btn").removeClass("disabled");
        } else {
            $(".otp-form .submit-btn").attr("disabled", true);
            $(".otp-form .submit-btn").addClass("disabled");
        }
    })
        .on("keyup", function (e) {
            let key = e.keyCode || e.charCode;
            if (key == 8 || key == 46 || key == 37 || key == 40) {
                $(this).prev().focus();
            } else if (key == 38 || key == 39 || $(this).val() != "") {
                $(this).next().focus();
            }
        })
        .on("paste", function (e) {
            let paste_data = e.originalEvent.clipboardData.getData("text");
            let paste_data_splitted = paste_data.split("");
            $.each(paste_data_splitted, function (index, value) {
                otp_fields.eq(index).val(value);
            });
        });
}

function download_otp_verify(){
    let formData = $('.digital_product_download_otp_verify');
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
    });
    $.ajax({
        type: "POST",
        url: formData.attr('action'),
        data: formData.serialize(),
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.status == 1) {
                $('.verify-message').addClass('text-success').removeClass('text-danger');
                if(data.file_path){
                    const a = document.createElement('a');
                    a.href = data.file_path;
                    a.download = data.file_name;
                    a.style.display = 'none';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(data.file_path);
                }
                $('#digital_product_order_otp_verify').modal('hide');
            }else{
                $('.verify-message').addClass('text-danger').removeClass('text-success');
            }
            $('.verify-message').html(data.message).fadeIn();
        },
        error: function (error) {
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}

function download_resend_otp_verify(){
    $('input.otp-field').val('');
    $('.verify-message').fadeOut(300).empty();
    let formData = $('.digital_product_download_otp_verify');
    let product_otp_reset_route = $('#digital_product_download_otp_reset').data('route');
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
    });
    $.ajax({
        url: product_otp_reset_route,
        method: 'POST',
        data: formData.serialize(),
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
}

$('.digital_product_download_link').on('click', function () {
    let link = $(this).data('link');
    digital_product_download(link);
});
