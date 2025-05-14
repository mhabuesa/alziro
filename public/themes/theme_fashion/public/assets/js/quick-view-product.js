"use strict";
$(document).ready(function () {
    getVariantPrice();
});

function stock_check_quick_view(){
    let minValue = parseInt($('.product_quantity__qty').attr('min'));
    let maxValue = parseInt($('.product_quantity__qty').attr('max'));
    let valueCurrent = parseInt($('.product_quantity__qty').val());
    let product_qty = $('.product_quantity__qty').val();
    let minimum_order_quantity_msg = $(".minimum_order_quantity_msg").data('text');

    if (minValue > valueCurrent) {
        $('.product_quantity__qty').val(minValue);
        toastr.error(minimum_order_quantity_msg +' '+ minValue);
    }

    if (valueCurrent > maxValue && maxValue == 0) {
        $(".product_quantity__qty").val(minValue);
    }

    getVariantPrice();
}

$('#add-to-cart-form input').on('change', function () {
    stock_check_quick_view();
});


$('#add-to-cart-form').on('submit', function (e) {
    e.preventDefault();
});
