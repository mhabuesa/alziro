"use strict";

function updateCartQuantityList_cart_data()
{
    $('.updateCartQuantityList_cart_data').on('click change', function () {
        let minorder = $(this).data('minorder');
        let cart = $(this).data('cart');
        let value = $(this).data('value');
        let action = $(this).data('action');
        updateCartQuantityList(minorder, cart, value, action);
    });
}
updateCartQuantityList_cart_data();

function updateCartQuantityListMobile_cart_data()
{
    $('.updateCartQuantityListMobile_cart_data').on('click change', function () {
        let minorder = $(this).data('minorder');
        let cart = $(this).data('cart');
        let value = $(this).data('value');
        let action = $(this).data('action');
        updateCartQuantityListMobile(minorder, cart, value, action);
    });
}
updateCartQuantityListMobile_cart_data();

function updateCartQuantityList(minimum_order_qty, key, incr, e) {
    let quantity =parseInt($("#cartQuantityWeb" + key).val())+parseInt(incr);
    let ex_quantity = $("#cartQuantityWeb" + key);
    updateCartCommon(minimum_order_qty, key, e, quantity, ex_quantity);
}

function updateCartQuantityListMobile(minimum_order_qty, key, incr, e) {
    let quantity = parseInt($("#cartQuantityMobile" + key).val())+parseInt(incr);
    let ex_quantity = $("#cartQuantityMobile" + key);
    updateCartCommon(minimum_order_qty, key, e, quantity, ex_quantity);
}

function updateCartCommon(minimum_order_qty, key, e, quantity, ex_quantity) {
    if(minimum_order_qty > quantity && e != 'delete' ) {
        toastr.error($('.minimum_order_quantity_msg').data('text')+' '+ minimum_order_qty);
        $(".cartQuantity" + key).val(minimum_order_qty);
        return false;
    }

    if (ex_quantity.val() == ex_quantity.data('min') && e == 'delete') {
        let item_has_been_removed_msg = $('.item_has_been_removed_from_cart').data('text');
        let remove_from_cart_url = $('#remove_from_cart_url').data('url');
        $.post(remove_from_cart_url, {
                _token: $('meta[name="_token"]').attr('content'),
                key: key
            },
            function (response) {
                updateNavCart();
                toastr.info(item_has_been_removed_msg, {
                    CloseButton: true,
                    ProgressBar: true
                });
                let segment_array = window.location.pathname.split('/');
                let segment = segment_array[segment_array.length - 1];
                if (segment === 'checkout-payment' || segment === 'checkout-details') {
                    location.reload();
                }
                $('#cart-summary').empty().html(response.data);
                initTooltip();
                set_shipping_id_function();
                updateCartQuantityList_cart_data();
                updateCartQuantityListMobile_cart_data();
                updateAddToCartByVariationWebCommon();
                applyCouponThemeFashion()
            });
    }else{
        let update_quantity_basic_url = $('#update_quantity_basic_url').data('url');
        $.post(update_quantity_basic_url, {
            _token: $('meta[name="_token"]').attr('content'),
            key,
            quantity
        }, function (response) {
            if (response.status == 0) {
                toastr.error(response.message, {
                    CloseButton: true,
                    ProgressBar: true
                });
                $(".cartQuantity" + key).val(response['qty']);
            } else {
                if (response['qty'] == ex_quantity.data('min')) {
                    ex_quantity.parent().find('.quantity__minus').html('<i class="bi bi-trash3-fill text-danger fs-10"></i>')
                } else {
                    ex_quantity.parent().find('.quantity__minus').html('<i class="bi bi-dash"></i>')
                }
                updateNavCart();
                $('#cart-summary').empty().html(response);
            }
            initTooltip();
            set_shipping_id_function();
            updateCartQuantityList_cart_data();
            updateCartQuantityListMobile_cart_data();
            updateAddToCartByVariationWebCommon();
            applyCouponThemeFashion()
        });
    }
}

function updateAddToCartByVariationWebCommon()
{
    $('.update_add_to_cart_by_variation_web').on('change', function () {
        let id = $(this).data('id');
        update_add_to_cart_by_variation_web(id);
    });
}
updateAddToCartByVariationWebCommon();

function update_add_to_cart_by_variation_web(id) {
    let cart_id = id;
    let quantity = parseInt($(".cart-quantity-web" + id).val());
    let form = 'add_to_cart_form_web' + id;
    update_add_to_cart_by_variation(cart_id, quantity, form);
}

function update_add_to_cart_by_variation_mobile(id) {
    let cart_id = id;
    let quantity = parseInt($(".cart-quantity-mobile" + id).val());
    let form = 'add_to_cart_form_mobile' + id;
    update_add_to_cart_by_variation(cart_id, quantity, form);
}

$('.update_add_to_cart_by_variation_mobile').on('change', function (){
    let id = $(this).data('id');
    update_add_to_cart_by_variation_mobile(id);
})

function update_add_to_cart_by_variation(id, quantity, form) {
    let qty = quantity;
    let form_id = form;
    if (checkAddToCartValidity(form_id && qty > 0)) {
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });
        $.post({
            url: $('#' + form_id).attr('action'),
            data: $('#' + form_id).serializeArray(),
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function (response) {
                if (response.status == 1) {
                    updateNavCart();
                    $('.product_price' + id).html(response.price)
                    $('.product_discount' + id).html('-' + response.discount)
                    $('#cart-summary').empty().html(response.data);
                    toastr.success(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                        timeOut: 2000
                    });
                    return false;
                } else if (response.status == 0) {
                    toastr.warning(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                        timeOut: 2000
                    });
                    $('#' + form_id).trigger('reset');
                    return false;
                }
                initTooltip();
                set_shipping_id_function();
                updateCartQuantityList_cart_data();
                updateCartQuantityListMobile_cart_data();
                updateAddToCartByVariationWebCommon();
                applyCouponThemeFashion()
                updateAddToCartByVariationWebCommon()
            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            }
        });
    } else if (qty == 0) {
        toastr.warning($(`#` + form_id).data('outofstock'), {
            CloseButton: true,
            ProgressBar: true,
            timeOut: 2000
        });
    } else {
        toastr.info($(`#` + form_id).data('errormessage'), {
            CloseButton: true,
            ProgressBar: true,
            timeOut: 2000
        });
    }
}
