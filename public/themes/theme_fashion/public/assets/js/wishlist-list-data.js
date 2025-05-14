"use strict";

function stock_check_for_product(id,form) {
    let form_id = form;
    let minValue = parseInt($('.product_quantity__qty'+id).attr('min'));
    let maxValue = parseInt($('.product_quantity__qty'+id).attr('max'));
    let valueCurrent = parseInt($('.product_quantity__qty'+id).val());
    let outofstock = $('#'+form_id).data('outofstock');
    if (minValue >= valueCurrent) {
        $('.product_quantity__qty'+id).val(minValue);
    }
    if (valueCurrent <= maxValue) {
        get_variant_price(id,form_id);
    }else{
        toastr.warning(outofstock);
    }
}

$('.stock_check_for_product_web').on('change', function () {
    let id = $(this).data('id');
    let form = 'add_to_cart_form_web'+id;
    stock_check_for_product(id,form);
})

$('.stock_check_for_product_mobile').on('change', function () {
    let id = $(this).data('id');
    let form = 'add_to_cart_form_mobile'+id;
    stock_check_for_product(id,form);
})


$('.add_to_cart_web').on('click', function (){
    let id = $(this).data('id');
    let form = 'add_to_cart_form_web'+id;
    add_to_cart(id,form)
})

function get_variant_price(id,form) {
    let qty = $('.product_quantity__qty'+id).val();

    let no_discount = $('.text-custom-storage').data('text-no-discount');
    let stock_available = $('.text-custom-storage').data('stock-available');
    let stock_not_available = $('.text-custom-storage').data('stock-not-available');

    let form_id = form;
    if ( qty > 0 && checkAddToCartValidity(form_id)) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: $('#'+form_id).data('varianturl'),
            data: $('#'+form_id).serializeArray(),
            success: function (data) {
                $('.unit_price'+id).html(data.total_unit_price);
                $('.total_price'+id).html(data.unit_price);
                $('.tax'+id).html(data.tax);
                if(data.discount != 0)
                    $('.discount'+id).html('-'+data.discount);
                else{
                    $('.discount_status'+id).empty().html(`<span class="badge text-capitalize badge-soft-secondary">${no_discount}</span>`)
                }
                if (data.quantity > 0) {
                    $('.stock_status'+id).empty().html(`<span class="badge badge-soft-success text-capitalize">${stock_available}</span>`);
                }else{
                    $('.stock_status'+id).empty().html(`<span class="badge badge-soft-danger text-capitalize">${stock_not_available}</span>`);
                }
            }
        });
    }
}

function add_to_cart(id,form){
    let qty = $('.product_quantity__qty'+id).val();
    let form_id = form;
    if ( qty > 0 && checkAddToCartValidity(form_id)) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.post({
            url: $(`#`+form_id).attr('action'),
            data: $('#'+form_id).serializeArray(),
            beforeSend: function () {
            },
            success: function (response) {
                if (response.status == 1) {
                    updateNavCart();
                    toastr.success(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                        timeOut: 3000
                    });
                    return false;
                } else if (response.status == 0) {
                    toastr.warning(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                        timeOut: 2000
                    });
                    return false;
                }
            },
            complete: function () {
            }
        });
    } else if(qty == 0) {
        toastr.warning($(`#`+form_id).data('outofstock'), {
            CloseButton: true,
            ProgressBar: true,
            timeOut: 2000
        });
    } else {
        toastr.info($(`#`+form_id).data('errormessage'), {
            CloseButton: true,
            ProgressBar: true,
            timeOut: 2000
        });
    }
}
