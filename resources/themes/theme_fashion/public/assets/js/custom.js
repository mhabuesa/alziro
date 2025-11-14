$('.currency_change_function').on('click',function(){
    let currency_code = $(this).data('currencycode');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: $('#currency-route').data('currency-route'),
        data: {
            currency_code: currency_code
        },
        success: function (data) {
            toastr.success(data.message);
            location.reload();
        }
    });
});
$('.change-language').on('click',function (){
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "POST",
        url: $(this).data('action'),
        data: {
            language_code: $(this).data('language-code'),
        },
        success: function (data) {
            toastr.success(data.message);
            location.reload();
        },
    });
})

// Product View Image Slider || Start
function renderFocusPreviewImageByColor() {
    $('.focus_preview_image_by_color').on('click', function (){
        let id = $(this).data('colorid');
        $(`.color_variants_${id}`).click();
    })
}
renderFocusPreviewImageByColor()
// Product View Image Slider || End


function checkAddToCartValidity(form_id) {
    var names = {};
    $('.'+form_id +' input:radio').each(function () { // find unique names
        names[$(this).attr('name')] = true;
    });
    var count = 0;
    $.each(names, function () { // then count them
        count++;
    });
    if ($('.'+form_id +' input:radio:checked').length == count) {
        return true;
    }
    return false;
}

// quick view
function quickView(product_id, url = null) {

    let action_url = $('#quick_view_url').data('url');
    $.get({
        url: action_url,
        dataType: "json",
        data: {
            product_id: product_id,
        },
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            $("#quickViewModal_content").empty().html(data.view);
            owl_carousel_quick_view();
            inc_dec_btn_quick_view();
            $("#quickViewModal").modal("show");
            $('#quickViewModal .modal-dialog .modal-content').css('opacity', '0')
            setTimeout(()=>{
                $('#quickViewModal .modal-dialog .modal-content').css('opacity', '1')
            }, 500)
            social_share_function();
            renderFocusPreviewImageByColor()
            getPlaceHolderImages()
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}

// Product Buy Now Button Action || Start
function buy_now(form_id, redirect_status, url=null) {
    addToCart(form_id, redirect_status, url);
    if (redirect_status == true) {

    }else{
        $('#quickViewModal').modal('hide');
        $('#SignInModal').modal('show');
    }
}
// Product Buy Now Button Action || End

$('.add_to_cart_form input').on('change', function () {
    stock_check();
});

$('.add_to_cart_form').on('submit', function (e) {
    e.preventDefault();
});

function stock_check(){
    minValue = parseInt($('.product_quantity__qty').attr('min'));
    maxValue = parseInt($('.product_quantity__qty').attr('max'));
    valueCurrent = parseInt($('.product_quantity__qty').val());
    outofstock = $(".add_to_cart_form").data('outofstock');
    minimum_order_quantity_msg = $(".minimum_order_quantity_msg").data('text');

    if (minValue > valueCurrent) {
        $('.product_quantity__qty').val(minValue);
        toastr.error(minimum_order_quantity_msg +' '+ minValue);
    }
    if (valueCurrent > maxValue && maxValue != 0) {
        toastr.warning("Sorry, stock limit exceeded");
        $(".product_quantity__qty").val(maxValue);
    }
    if (valueCurrent > maxValue && maxValue == 0) {
        $(".product_quantity__qty").val(minValue);
    }
    getVariantPrice();
}

function addToCart(form_id, redirect_to_checkout = false, url = null) {
    if (checkAddToCartValidity(form_id) && $('#' + form_id +' input[name=quantity]').val() != 0) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.post({
            url: $(`#`+form_id).attr('action'),
            data: $('#' + form_id).serializeArray(),
            beforeSend: function () {

            },
            success: function (response) {
                if (response.status == 1) {
                    updateNavCart();
                    toastr.success(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                        timeOut: 2000 // duration
                    });
                    if (redirect_to_checkout == true) {
                        setTimeout(function() {
                            location.href = url;
                        }, 100);
                    }

                    $('#quickViewModal').modal('hide');
                    return false;
                } else if (response.status == 0) {
                    toastr.warning(response.message, {
                        CloseButton: true,
                        ProgressBar: true,
                        timeOut: 2000 // duration
                    });
                    return false;
                }
            },
            complete: function () {

            }
        });
    } else if($('#' + form_id +' input[name=quantity]').val() == 0) {
        toastr.warning($(`#`+form_id).data('outofstock'), {
            CloseButton: true,
            ProgressBar: true,
            timeOut: 2000 // duration
        });
    } else {
        toastr.info($(`#`+form_id).data('errormessage'), {
            CloseButton: true,
            ProgressBar: true,
            timeOut: 2000 // duration
        });
    }
}

function updateNavCart() {
    let url = $('#update_nav_cart_url').data('url');
    $.post(url, {
        _token: $('meta[name="_token"]').attr('content')
    },
    function (response) {
        $('#cart_items').html(response.data);
        $('#mobile_app_bar').html(response.mobile_nav);
        update_floating_nav_cart();
        updateCartQuantity_cart_data();
        addWishlist_function_view_page();
        customerLoginRegisterModalRender()
    });
}


function updateCartQuantity_cart_data() {
    $('.updateCartQuantity_cart_data').on('click', function () {
        let cart = $(this).data('cart');
        let product = $(this).data('product');
        let value = $(this).data('value');
        let action = $(this).data('action');
        updateCartQuantity(cart, product, value, action);
    });
}


function update_floating_nav_cart() {
    let url = $('#update_floating_nav_cart_url').data('url');
    $.post(url, {
            _token: $('meta[name="_token"]').attr('content')
        },
        function (response) {
            $('#floating_cart_items').html(response.floating_nav);
        });
}

function updateCartQuantity(cart_id, product_id, action, event) {
    let remove_url = $("#remove_from_cart_url").data("url");
    let update_quantity_url = $("#update_quantity_url").data("url");
    let token = $('meta[name="_token"]').attr("content");
    let product_qyt =
        parseInt($(`.cartQuantity${cart_id}`).val()) + parseInt(action);
    let cart_quantity_of = $(`.cartQuantity${cart_id}`);
    let segment_array = window.location.pathname.split("/");
    let segment = segment_array[segment_array.length - 1];

    if (cart_quantity_of.val() == 0) {
        toastr.info($('.cannot_use_zero').data('text'), {
            CloseButton: true,
            ProgressBar: true,
        });
        cart_quantity_of.val(cart_quantity_of.data("min"));
    }else if (
        (cart_quantity_of.val() == cart_quantity_of.data("min") &&
            event == "minus")
    ) {
        $.post(
            remove_url,
            {
                _token: token,
                key: cart_id,
            },
            function (response) {
                updateNavCart();
                toastr.info(response.message, {
                    CloseButton: true,
                    ProgressBar: true,
                });
                if (
                    segment === "shop-cart" ||
                    segment === "checkout-payment" ||
                    segment === "checkout-details"
                ) {
                    location.reload();
                }
            }
        );
    } else {
        if(cart_quantity_of.val() < cart_quantity_of.data("min")){
            let min_value = cart_quantity_of.data("min");
            toastr.error('Minimum order quantity cannot be less than '+min_value);
            cart_quantity_of.val(min_value)
            updateCartQuantity(cart_id, product_id, action, event)
        }else{
            $(`.cartQuantity${cart_id}`).html(product_qyt);
            $.post(
                update_quantity_url,
                {
                    _token: token,
                    key: cart_id,
                    product_id: product_id,
                    quantity: product_qyt,
                },
                function (response) {
                    update_floating_nav_cart()
                    if (response["status"] == 0) {
                        toastr.error(response["message"]);
                    } else {
                        toastr.success(response["message"]);
                    }
                    response["qty"] <= 1
                        ? $(`.quantity__minus${cart_id}`).html(
                            '<i class="bi bi-trash3-fill text-danger fs-10"></i>'
                        )
                        : $(`.quantity__minus${cart_id}`).html(
                            '<i class="bi bi-dash"></i>'
                        );

                    $(`.cartQuantity${cart_id}`).val(response["qty"]);
                    $(`.cartQuantity${cart_id}`).html(response["qty"]);
                    $(".cart_total_amount").html(response.total_price);
                    $(`.discount_price_of_${cart_id}`).html(
                        response["discount_price"]
                    );
                    $(`.quantity_price_of_${cart_id}`).html(
                        response["quantity_price"]
                    );

                    if (response["qty"] == cart_quantity_of.data("min")) {
                        cart_quantity_of
                            .parent()
                            .find(".quantity__minus")
                            .html(
                                '<i class="bi bi-trash3-fill text-danger fs-10"></i>'
                            );
                    } else {
                        cart_quantity_of
                            .parent()
                            .find(".quantity__minus")
                            .html('<i class="bi bi-dash"></i>');
                    }
                    if (
                        segment === "shop-cart" ||
                        segment === "checkout-payment" ||
                        segment === "checkout-details"
                    ) {
                        location.reload();
                    }
                }
            );
        }
    }
}

// Product Variant Function for details page & quick view
function getVariantPrice() {
    let qty_val = $("#add-to-cart-form input[name=quantity]").val();
    // alert(qty_val);
    if (qty_val > 0  && checkAddToCartValidity('class_name')) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: $('#add-to-cart-form').data('varianturl'),
            data: $('#add-to-cart-form').serializeArray(),
            success: function (data) {
                $('.total_price').html(data.price);
                $('.tax_status').html(data.update_tax);
                $('.product_delivery_cost').html(data.delivery_cost);
                $('.color_name').html(data.color_name);


                if(data.quantity > 0){
                    if(data.quantity <= data.stock_limit ){
                        $('.stock_status').addClass('d-none');
                        $('.out_of_stock_status').addClass('d-none');
                        $('.limited_status').removeClass('d-none');
                        $('.in_stock_status').html(data.quantity);
                    }else{
                        $('.stock_status').removeClass('d-none');
                        $('.out_of_stock_status').addClass('d-none');
                        $('.limited_status').addClass('d-none');
                        $('.in_stock_status').html(data.quantity);
                    }
                }

                if(data.quantity <= 0 || data.quantity < qty_val){
                    $('.stock_status').addClass('d-none');
                    $('.out_of_stock_status').removeClass('d-none');
                    $('.limited_status').addClass('d-none');
                }

                // end stock status
                if (data.quantity != 0 && data.quantity > $('#add-to-cart-form .product_qty').attr('max')) {
                    $('#add-to-cart-form .product_qty').attr('max', data.quantity);
                }else{
                    if(data.quantity <= 0){
                        $('#add-to-cart-form .product_qty').val(parseInt($('#add-to-cart-form .product_qty').attr('min')));
                        $('#add-to-cart-form .product_qty').attr('max', data.quantity);
                    }else{
                        $('#add-to-cart-form .product_qty').attr('max', data.quantity);
                    }
                }

            }
        });
    }

}

//compare list search 0 Index
function global_search_for_compare_list0() {
    global_search_for_compare_list_common(0)
}

//compare list search 1
function global_search_for_compare_list1() {
    global_search_for_compare_list_common(1)
}
//compare list search 2
function global_search_for_compare_list2() {
    global_search_for_compare_list_common(2)
}
function global_search_for_compare_list_common(key) {
    $(".search-card").css("display", "block");
    let name = $("#search_bar_input"+key).val();
    let compare_id = $("#compare_id"+key).val();
    let base_url = $('meta[name="base-url"]').attr("content");
    if (name.length > 0) {
        $.get({
            url: base_url + "/searched-products-for-compare",
            dataType: "json",
            data: {
                name,
                compare_id,
            },
            beforeSend: function () {
                $("#loading").addClass("d-grid");
            },
            success: function (data) {
                $(".search-result-box-compare-list"+key).empty().html(data.result);
            },
            complete: function () {
                $("#loading").removeClass("d-grid");
            },
        });
    } else {
        $(".search-result-box-compare-list"+key).empty();
    }
}

// End of product Compare List

// Chat with Seller Modal JS || Start
$('#contact_with_seller_form').on('submit', function (e) {
    e.preventDefault();
    let messages_form = $(this);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        type: "post",
        url: messages_form.attr('action'),
        data: messages_form.serialize(),
        success: function (respons) {
            toastr.success($('#contact_with_seller_form').data('success-message'), {
                CloseButton: true,
                ProgressBar: true
            });
            $('#contact_with_seller_form').trigger('reset');
            $('#contact_sellerModal').modal('hide');
        }
    });
});
// Chat with Seller Modal JS || End

$('.lightbox_custom').on('click',function(e){
    e.preventDefault();
    new lightbox(this);
});

// ShopView Review - View more button action
let load_review_for_shop_count = 1;
let loadShopReviewShowStatus = 1;

$('#load_review_for_shop').on('click', function(){
    let shop_id = $(this).data('shopid');

    let url_load_review = $('.see-more-details-review').data('routename');
    let onerror = $('.see-more-details-review').data('onerror');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        type: "post",
        url: url_load_review,
        data:{
            shop_id:shop_id,
            offset:load_review_for_shop_count
        },
        success: function (data) {
            $('#shop-review-list').append(data.productReview)
            readMoreCurrentReview()
            renderCustomImagePopup()
            if(data.not_empty == 0 && loadShopReviewShowStatus === 1){
                loadShopReviewShowStatus = 0;
                toastr.info(onerror, {
                    CloseButton: true,
                    ProgressBar: true
                });
            }

            if (data.checkReviews <= 1 ){
                if (data.not_empty <= 1 && $('.see-more-details-review').closest(".product-information").hasClass("active")) {
                    $('.see-more-details-review').html($('.see-more-details-review').data('afterextend'));
                }else{
                    $('.see-more-details-review').html($('.see-more-details-review').data('seemore'));
                }
                $('.see-more-details-review').removeAttr("onclick", true);
                $('.see-more-details-review').attr("onclick", "seemore()");
            }else {
                $('.see-more-details-review').closest(".product-information").addClass("active");
            }
        },complete: function(){
            $('.lightbox_custom').off('click').on('click', function(e) {
                e.preventDefault();
                new lightbox(this);
            });
        },
    });
    load_review_for_shop_count++
});

// Product Review - View more button action
var load_review_count = 1;
var loadReviewShowStatus = 1;
$('#load_review_function').on('click', function(){
    let productid = $(this).data('productid');

    let url_load_review = $('.see-more-details-review').data('routename');
    let onerror = $('.see-more-details-review').data('onerror');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        type: "post",
        url: url_load_review,
        data:{
            product_id:productid,
            offset:load_review_count
        },
        success: function (data) {
            $('#product-review-list').append(data.productReview)
            readMoreCurrentReview()
            renderCustomImagePopup()
            if(data.not_empty == 0 && loadReviewShowStatus === 1){
                loadReviewShowStatus = 0;
                toastr.info(onerror, {
                    CloseButton: true,
                    ProgressBar: true
                });
            }

            if (data.checkReviews <= 1 ){
                if (data.not_empty <= 1 && $('.see-more-details-review').closest(".product-information").hasClass("active")) {
                    $('.see-more-details-review').html($('.see-more-details-review').data('afterextend'));
                }else{
                    $('.see-more-details-review').html($('.see-more-details-review').data('seemore'));
                }
                $('.see-more-details-review').removeAttr("onclick", true);
                $('.see-more-details-review').attr("onclick", "seemore()");
            }else {
                $('.see-more-details-review').closest(".product-information").addClass("active");
            }
        },complete: function(){
            $('.lightbox_custom').off('click').on('click', function(e) {
                e.preventDefault();
                new lightbox(this);
            });
        },
    });
    load_review_count++
})


function seemore() {
    let reviewSeeMore = $('.see-more-details-review').data('seemore');
    let reviewSeeLess = $('.see-more-details-review').data('afterextend');

    if ($('.see-more-details-review').closest(".product-information").hasClass("active")) {
        $('.see-more-details-review').closest(".product-information").removeClass("active");
        $('#load_review_function').html(reviewSeeMore);
        console.log(reviewSeeMore)
        console.log('In step one');
    } else {
        $('.see-more-details-review').closest(".product-information").addClass("active");
        $('#load_review_function').html(reviewSeeLess);
        console.log(reviewSeeLess)
        console.log('In step two');
    }
}

$('.single_section_dual_tabs .single_section_dual_btn li').on('click', function(){
    let tabTarget = $(this).data('targetbtn');
    $(this).parent().parent().find('.single_section_dual_target a').addClass('d-none');
    $(this).parent().parent().find(`.single_section_dual_target a:eq(${tabTarget})`).removeClass('d-none');
});


// Shop Details Page JS || Start
$('.shop_follow_action').on('click', function(){
    let shop_id = $(this).data('shopid');

    let status = $(this).data('status');
    if (status == 1) {
        Swal.fire({
            title: $(this).data('titletext'),
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: $(this).data('titletext2'),
            cancelButtonText: $(this).data('titlecancel'),
          }).then((result) => {
            if (result.isConfirmed) {
                shopFollow(shop_id);
            }
          })
    } else {
        shopFollow(shop_id);
    }
})


function shopFollow(shop_id)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: $('#shop_follow_url').data('url'),
        method: 'POST',
        data: {
            'shop_id':shop_id
        },
        beforeSend: function () {
            $('#loading').addClass('d-grid');
        },
        success: function (data) {
            if (data.value == 1) {
                toastr.success(data.message);
                $('.follower_count').html(data.followers);
                $('.follow_button').html(data.text);
                $('.follow_button').data('status', '1');
            } else if (data.value == 2) {
                toastr.success(data.message);
                $('.follower_count').html(data.followers);
                $('.follow_button').html(data.text);
                $('.follow_button').data('status','0');
            } else {
                toastr.error(data.message);
                $('#loginModal').modal('show');
            }
        },
        complete: function () {
            $('#loading').removeClass('d-grid');
        },
    });
}
// Shop Details Page JS || End

/*========================
Background Image Use by data-bg-img (Attr)
==========================*/
var $bgImg = $("[data-bg-img]");
$bgImg
    .css("background-image", function () {
        return 'url("' + $(this).data("bg-img") + '")';
    })
    .removeAttr("data-bg-img");


// Seller Registration Page JS || Start
$('.go-step-2').on('click', function () {
    $('.step-2-data').fadeIn(300)
    $('.step-1-data').hide()
    $('.seller-reg-menu li').removeClass('active')
    $('.seller-reg-menu li.go-step-2').addClass('active')
})
$('.go-step-1').on('click', function () {
    $('.step-1-data').fadeIn(300)
    $('.step-2-data').hide()
    $('.seller-reg-menu li').removeClass('active')
    $('.seller-reg-menu li.go-step-1').addClass('active')
})

function validate_step_one() {
    password_keyup();
    let seller_f_name = $('#seller_f_name').val();
    let seller_l_name = $('#seller_l_name').val();
    let seller_email = $('#seller_email').val();
    let seller_phone = $('#seller_phone').val();
    let seller_password = $('#seller_password').val();
    let seller_repeat_password = $('#seller_repeat_password').val();
    let seller_profile_pic = $('#seller_profile_pic').val();
    if ((seller_f_name && seller_l_name && seller_email && seller_phone && seller_password && seller_repeat_password && seller_profile_pic) != '') {
        if (seller_password != '' && seller_repeat_password != '') {
            password_validation(seller_password, seller_repeat_password);
            if (seller_password !== seller_repeat_password) {
                $('.password_message').removeClass('d-none')
                $('.go-step-2').addClass('btn_disabled');
            } else {
                $('.go-step-2').removeClass('btn_disabled');
            }
        } else {
            $('.password_message').addClass('d-none');
            $('.go-step-2').removeClass('btn_disabled');
        }
    } else {
        $('.go-step-2').addClass('btn_disabled');
    }

    // Steps Two
    let shop_name = $('#shop_name').val();
    let shop_address = $('#shop_address').val();
    let shop_banner = $('#shop_banner').val();
    let store_Logo = $('#store_Logo').val();
    if ((shop_name && shop_address && shop_banner && store_Logo) != '' && $('#seller_terms_checkbox').is(':checked')) {
        $('#seller_apply_submit').removeClass('btn_disabled');
    } else {
        $('#seller_apply_submit').addClass('btn_disabled');
    }
}

$("#seller-registration input").on('keyup', function () {
    validate_step_one();
});
$("#seller-registration input").on('change', function () {
    validate_step_one();
});

$('#seller_terms_checkbox').on('click', function () {
    validate_step_one();
});

function password_keyup() {
    if ($("#seller_password").val() != '' && $("#seller_repeat_password").val() != '') {
        $('.password_message').removeClass('d-none');
        password_validation($("#seller_password").val(), $("#seller_repeat_password").val());
    } else {
        $('.password_message').addClass('d-none')
    }
}

function password_validation(password_one, password_two) {
    let password_characters_limit = $('.text-custom-storage').data('password-characters-limit');
    let password_not_match = $('.text-custom-storage').data('password-not-match');

    let message = '';
    if (password_one.length < 8 || password_two.length < 8) {
        message = password_characters_limit;
    }else if (password_one !== password_two) {
        message = password_not_match;
    }
    $('.password_message').html(message);
}

// Seller Registration Page JS || End

// Fashion Products List Form JS || Start
$('#fashion_products_list_form input').on('change',function(){
    inputTypeNumberClick(1);
    fashion_products_list_form_common();
});
function fashion_products_list_form_common(){
    $('.products_navs_list li input').removeAttr('checked');
    $('#filter_by_all').attr('checked', true);
    $('.products_navs_list li label').removeClass('active');
    $('.filter_by_all').addClass('active');
}

$('#fashion_products_list_form input').on('keyup',function(){
    $('#fashion_products_list_form').submit();
});

$('.filter_by_product_list_web').on('change', function (){
    let value = $(this).val();
    let option = '<option value=" '+value+' " selected></option>'
    $('.filter_by_product_list_mobile').append(option);
    inputTypeNumberClick(1);
    fashion_products_list_form_common()
    productCommonActionForViewEvents()
})

$('.filter_by_product_list_mobile').on('change', function (){
    let value = $(this).val();
    let option = '<option value=" '+value+' " selected></option>'
    $('.filter_by_product_list_web').append(option);
    inputTypeNumberClick(1);
    fashion_products_list_form_common()
});

function inputTypeNumberClick(key, slider=null)
{
    if (slider != null) {
        setTimeout(function(){
            $('#fashion_products_list_form').submit();
        },500);
    }else{
        $('.paginate_btn').removeAttr('checked', true);
        $('.paginate_btn_id'+key).attr('checked', true);
        $('#fashion_products_list_form').submit();
    }
}

$('.inputTypeNumberClick').on('click', function (){
    inputTypeNumberClick($(this).data('page'));
});

function set_shipping_id_function() {
    $('.set_shipping_id_function').on('click', function(){
        let id = $(this).data('id');
        let cart_group_id = $(this).data('cartgroup');
        set_shipping_id(id, cart_group_id);
    })

    $('.set_shipping_onchange').on('change', function(){
        let id = $(this).val();
        set_shipping_id(id, 'all_cart_group');
    })

    function set_shipping_id(id, cart_group_id) {
        $.get({
            url: $('#set_shipping_url').data('url'),
            dataType: 'json',
            data: {
                id: id,
                cart_group_id: cart_group_id
            },
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function (data) {
                location.reload();
            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            },
        });
    }
}

set_shipping_id_function();

// Product Buy Now Button Action || Start
$('.buy_now_function').on('click', function (){
    let form_id = $(this).data('formid');
    let redirect_status = $(this).data('authstatus');
    let url = $(this).data('route');

    addToCart(form_id, redirect_status, url);
    if (redirect_status == true) {
    }else{
        $('#quickViewModal').modal('hide');
        $('#SignInModal').modal('show');
    }
})
// Product Buy Now Button Action || End

// Product Compare list
function addCompareList(product_id) {
    let action_url = $('#store_compare_list_url').data('url');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        url: action_url,
        method: 'POST',
        data: {
            product_id
        },
        beforeSend: function () {
            $('#loading').addClass('d-grid');
        },
        success: function (data) {
            if (data.value == 1) {
                toastr.success(data.success);
                $(`.text-base`).removeClass("text-base").focusout();
                $(`.compare_list_icon_active`).removeClass("compare_list_icon_active").focusout();

                $.each(data.compare_product_ids, function(key, id) {
                    $(`.compare_list-${id}`).addClass('compare_list_icon_active').focusout();
                    $(`.compare_list_icon-${id}`).addClass('text-base').focusout();
                });
            } else if (data.value == 2) {
                $(`.text-base`).removeClass("text-base").focusout();
                $(`.compare_list_icon_active`).removeClass("compare_list_icon_active").focusout();
                $.each(data.compare_product_ids, function(key, id) {
                    $(`.compare_list-${id}`).addClass('compare_list_icon_active').focusout();
                    $(`.compare_list_icon-${id}`).addClass('text-base').focusout();
                });
            } else {
                toastr.error(data.error);
                $("#quickViewModal").modal("hide");
                $("#loginModal").modal("show");
            }
        },
        complete: function () {
            $('#loading').removeClass('d-grid');
        },
    });
}

$('.add_to_cart_button').on('click',function (){
    let form_id = $(this).data('form-id');
    addToCart(form_id);
});

$('.add_to_cart_mobile').on('click',function (){
    let id = $(this).data('id');
    let form = 'add_to_cart_form_mobile'+id;
    add_to_cart(id,form)
});
// ==== Product Share Link Generator JS || Start ====
function social_share_function() {
    $('.social_share_function').on('click',function (){
        let url = $(this).data('url');
        let social = $(this).data('social');

        var width = 600,
            height = 400,
            left = (screen.width - width) / 2,
            top = (screen.height - height) / 2;
        window.open(
            "https://" + social + encodeURIComponent(url),
            "Popup",
            "toolbar=0,status=0,width=" +
            width +
            ",height=" +
            height +
            ",left=" +
            left +
            ",top=" +
            top
        );
    });
}
social_share_function();
// ==== Product Share Link Generator JS || End ====


$('#fashion_products_list_form').on('submit',function(event){
    event.preventDefault();
    $('.product_view_title').text($('.product_view_title').data('allproduct'));
    let form = $(this);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: form.serialize(),
        beforeSend: function () {
            $('#loading').addClass('d-grid');
        },
        success: function (data) {
            var tabId = '.scroll_to_form_top';
            // Using scrollTop() method
            var tabTopPosition = $(tabId).offset().top - 80;
            $('html, body').scrollTop(tabTopPosition);

            $('#ajax_products_section').empty().html(data.html_products);
            $('#selected_filter_area').empty().html(data.html_tags);
            productCommonActionForViewEvents();
        },
        complete: function () {
            $('#loading').removeClass('d-grid');
        },
    });
})

// Product Add To Wishlist || Start
function addWishlist_function(product_id)
{
    let action_url = $('#store_wishlist_url').data('url');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        url: action_url,
        method: 'POST',
        data: {
            product_id
        },
        success: function (data) {
            if (data.value == 1) {
                toastr.success(data.success);
                $(`.wishlist_${product_id}`).removeClass('bi-heart').addClass('bi-heart-fill text-danger');
                $('.wishlist_count_status').html(parseInt($('.wishlist_count_status').html())+1);
                $('.product_wishlist_count_status').html(parseInt($('.product_wishlist_count_status').html())+1);
            } else if (data.value == 2) {
                $(`.wishlist_${product_id}`).removeClass('bi-heart-fill text-danger').addClass('bi-heart');
                $('.wishlist_count_status').html(parseInt($('.wishlist_count_status').html())-1);
                $('.product_wishlist_count_status').html(parseInt($('.product_wishlist_count_status').html())-1);
                toastr.success(data.error);
            } else {
                toastr.error(data.error);
                $('#SignInModal').modal('show');
                $('#quickViewModal').modal('hide');
            }
        }
    });
}

function quickViewActionRender() {
    $('.quickView_action').on('click', function () {
        let id = $(this).data('id');
        quickView(id);
    });
}

function addWishlist_function_view_page() {
    $('.addWishlist_function_view_page').on('click', function () {
        let id = $(this).data('id');
        addWishlist_function(id);
    });
}

$('.addCompareList_view_page').on('click', function () {
    let id = $(this).data('id');
    addCompareList(id);
});

function productCommonActionForViewEvents() {
    $('.remove_tags_Category').on('click',function() {
        let id = $(this).data('id');
        $('.category_class_for_tag_'+id).click();
    })

    $('.remove_tags_Brand').on('click', function() {
        let id = $(this).data('id');
        $('.brand_class_for_tag_'+id).click();
    })

    $('.remove_tags_review').on('click', function() {
        let id = $(this).data('id');
        $('.review_class_for_tag_'+id).click();
    })

    $('.remove_tags_sortBy').on('click', function() {
        $('.filter_by_product_list_web').val(['default']).trigger('change');
    })

    $('.store_vacation_check_function').on('click', function (){
        let id = $(this).data('id');
        let added_by = $(this).data('added_by');
        let user_id = $(this).data('user_id');
        let action_url = $(this).data('action_url');
        let product_cart_id = $(this).data('product_cart_id');
        store_vacation_check(id,added_by,user_id,action_url, product_cart_id);
    });

    quickViewActionRender();
    addWishlist_function_view_page();
}
$(window).on('load', function (){
    productCommonActionForViewEvents();
})

$('.fashion_products_list_form_reset').on('click', function() {
    $('.filter_by_product_list_web').val(['default']).trigger('change');
    $(".filter_select_input_div .select2-selection__rendered").text($(".filter_select_input").data("primary_select"));
    $('#fashion_products_list_form').trigger('reset');
    $('#fashion_products_list_form').submit();
    $('.form-check-subgroup').css('display','none');
    $('.search_input_store').val('');
    var formatSlider = document.getElementById("input-slider");
    formatSlider.noUiSlider.set(["0", "1000000"]);
})

$('.remove_wishlist_theme_fashion').on('click', function (){
    let url = $('#delete_wishlist_url').data('url');
    let product_id = $(this).data('productid');

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        url: url,
        method: "POST",
        data: {
            id: product_id,
        },
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            Swal.fire({
                type: "success",
                title: $('.text-wishList').data('text'),
                text: data.success,
            });
            $('.row_id'+product_id).hide();
            $('.wishlist_count_status').html(parseInt($('.wishlist_count_status').html())-1);
            let currentRoute = $('#get-current-route-name').data('route');
            if (data.count <= 0 && currentRoute && currentRoute.toString() === 'wishlists') {location.reload()}
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
})

$('.action-global-search-mobile').on('keyup', function() {
    let value = $('#input-value-mobile').val();
    let id = $('#search_category_value_mobile').val();
    let class_name = 'search-result-box-mobile';
    global_search(value,id,class_name);
})

$('#hide_search_toggle').on('click', function(){
    let value = 0;
    let id = null;
    let class_name = 'search-result-box-mobile';
    global_search(value,id,class_name);
});

$('#input-value-web').on('keyup', function(){
    let value = $(this).val();
    let id = $('#search_category_value_web').val();
    let class_name = 'search-result-box-web';
    global_search(value, id, class_name);
    $('.search_input_name').val(value);
})

// Search Field Popup Actions || Start
function global_search(value,id,class_name){
    $(".search-card").removeClass("d-none").addClass("d-block");
    let name = value;
    let category_id = id;
    let class__name = class_name;
    let base_url= $('meta[name="base-url"]').attr('content');
    if (name.length > 0) {
        $.get({
            url: base_url+"/searched-products",
            dataType: 'json',
            data: {
                name,
                category_id
            },
            beforeSend: function () {
                $('#loading').addClass('d-grid');
            },
            success: function (data) {
                $('.'+class__name).empty().html(data.result)
            },
            complete: function () {
                $('#loading').removeClass('d-grid');
            },
        });
    } else {
        $('.'+class__name).empty().removeClass("d-block").addClass("d-none");
    }
}

// Search Field Popup Actions || End
$('.activeFilterNav').on('click', function(){
    let key = $(this).data('key');
    $('#fashion_products_list_form').trigger('reset');
    // inputTypeNumberClick(1);
    $('.products_navs_list li input').removeAttr('checked');
    $('#'+key).attr('checked', true);
    $('.products_navs_list li label').removeClass('active');
    $('.'+key).addClass('active');
    $('#fashion_products_list_form').submit();
    $('.form-check-subgroup').css('display','none');
})

function applyCouponThemeFashion() {
    $('#coupon_code_theme_fashion').on('click', function(){
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
            },
        });
        $.ajax({
            type: "POST",
            url: $("#coupon-apply").data("url"),
            data: $("#coupon-code-ajax").serializeArray(),
            success: function (data) {
                if (data.status == 1) {
                    let ms = data.messages;
                    ms.forEach(function (m, index) {
                        toastr.success(m, index, {
                            CloseButton: true,
                            ProgressBar: true,
                        });
                    });
                } else {
                    let ms = data.messages;
                    ms.forEach(function (m, index) {
                        toastr.error(m, index, {
                            CloseButton: true,
                            ProgressBar: true,
                        });
                    });
                }
                setTimeout(function () {
                    location.reload();
                }, 2000);
            },
        });
    })
}

applyCouponThemeFashion()

function goToPageBasedSelectValue(link){
    location.href=link;
}

function formResetByClassOrID(ClassOrIDName)
{
    $(ClassOrIDName).trigger('reset');
    $(ClassOrIDName + ' input').val('');
    $('.search_input_store').val('');
}

$('.form--check-inner input[type="checkbox"]').change(function() {
    var isChecked = $(this).prop('checked');
    var $subgroup = $(this).closest('.form--check-inner').siblings('.form-check-subgroup');
    if (!$(this).prop('checked')) {
        $subgroup.find('input[type="checkbox"]').prop('checked', false);
    }
    // $('#fashion_products_list_form').submit();
});

function from_reset_by_className(classname, redirect_url=null)
{
    $(`.${classname} input`).val('');
    (redirect_url != null ? window.location.href=redirect_url : '');
}

// ==== start owl carousel for images ====
function owl_carousel_quick_view(){
    var sync1 = $("#sync1");
    var sync2 = $("#sync2");
    var thumbnailItemClass = ".owl-item";
    var slides = sync1
        .owlCarousel({
            startPosition: 12,
            items: 1,
            loop: false,
            margin: 0,
            mouseDrag: true,
            touchDrag: true,
            pullDrag: false,
            scrollPerPage: true,
            autoplayHoverPause: false,
            nav: false,
            dots: false,
        })
        .on("changed.owl.carousel", syncPosition);
        function syncPosition(el) {
            $owl_slider = $(this).data("owl.carousel");
            var loop = $owl_slider.options.loop;

            if (loop) {
                var count = el.item.count - 1;
                var current = Math.round(
                    el.item.index - el.item.count / 2 - 0.5
                );
                if (current < 0) {
                    current = count;
                }
                if (current > count) {
                    current = 0;
                }
            } else {
                var current = el.item.index;
            }

            var owl_thumbnail = sync2.data("owl.carousel");
            var itemClass = "." + owl_thumbnail.options.itemClass;

            var thumbnailCurrentItem = sync2
                .find(itemClass)
                .removeClass("synced")
                .eq(current);
            thumbnailCurrentItem.addClass("synced");

            if (!thumbnailCurrentItem.hasClass("active")) {
                var duration = 500;
                sync2.trigger("to.owl.carousel", [current, duration, true]);
            }
        }

        var thumbs = sync2
        .owlCarousel({
            startPosition: 12,
            items: 6,
            loop: false,
            margin: 10,
            autoplay: false,
            nav: false,
            dots: false,
            // rtl: true,
            responsive: {
                576: {
                    items: 4,
                },
                768: {
                    items: 5,
                },
                992: {
                    items: 5,
                },
                1200: {
                    items: 6,
                },
                1400: {
                    items: 7,
                },
            },
            onInitialized: function (e) {
                var thumbnailCurrentItem = $(e.target)
                    .find(thumbnailItemClass)
                    .eq(this._current);
                thumbnailCurrentItem.addClass("synced");
            },
        })
        .on("click", thumbnailItemClass, function (e) {
            e.preventDefault();
            var duration = 500;
            var itemIndex = $(e.target).parents(thumbnailItemClass).index();
            sync1.trigger("to.owl.carousel", [itemIndex, duration, true]);
        })
        .on("changed.owl.carousel", function (el) {
            var number = el.item.index;
            $owl_slider = sync1.data("owl.carousel");
            $owl_slider.to(number, 500, true);
        });
    sync1.owlCarousel();
}
// ==== end owl carousel for images ====

// ==== start increment decrement btn ====
function inc_dec_btn_quick_view(){

    var CartPlusMinus = $(".inc-inputs");
    CartPlusMinus.prepend(
        '<div class="dec qtyBtn text-base"><i class="bi bi-dash-lg"></i></div>'
    );
    CartPlusMinus.append(
        '<div class="inc qtyBtn text-base"><i class="bi bi-plus-lg"></i></div>'
    );
    $(".qtyBtn").on("click", function () {
        var $button = $(this);
        var oldValue = parseFloat($button.parent().find("input").val());
        var oldMaxValue = parseInt(
            $button.parent().find("input").attr("max")
        );
        var oldMinValue = parseInt(
            $button.parent().find("input").attr("min")
        );
        var outofstock = $(".add_to_cart_form").data("outofstock");
        var newVal = oldValue;
        if ($(this).hasClass("inc")) {
            if (oldValue < oldMaxValue) {
                newVal = oldValue + 1;
                $('.qtyBtn').removeClass('disabled')
                $('.qtyBtn').addClass('text-base')
            } else {
                $('.qtyBtn').addClass('disabled')
                $('.qtyBtn').removeClass('text-base')
                toastr.warning(outofstock);
            }
        } else {
            if (oldValue > oldMinValue) {
                $('.qtyBtn').removeClass('disabled')
                $('.qtyBtn').addClass('text-base')
                newVal = oldValue - 1;
            } else {
                newVal = oldMinValue;
                minimum_order_quantity_msg = $(".minimum_order_quantity_msg").data('text');
                toastr.error(minimum_order_quantity_msg +' '+ oldMinValue);
            }
        }
        $button.parent().find("input").val(newVal);
        stock_check();
    });
}
// ==== end increment decrement btn ====

// ==== Product Share Link Generator JS || Start ====
function shareOnFacebook(url, social) {
    var width = 600,
        height = 400,
        left = (screen.width - width) / 2,
        top = (screen.height - height) / 2;
    window.open(
        "https://" + social + encodeURIComponent(url),
        "Popup",
        "toolbar=0,status=0,width=" +
            width +
            ",height=" +
            height +
            ",left=" +
            left +
            ",top=" +
            top
    );
}
// ==== Product Share Link Generator JS || End ====

// ==== Start Otp Verification Js ====
$(document).ready(function () {
    $(".otp-form button[type=submit]").attr("disabled", true);
    $(".otp-form *:input[type!=hidden]:first").focus();
    let otp_fields = $(".otp-form .otp-field"),
        otp_value_field = $(".otp-form .otp-value");
    otp_fields
        .on("input", function (e) {
            $(this).val(
                $(this)
                    .val()
                    .replace(/[^0-9]/g, "")
            );
            let otp_value = "";
            otp_fields.each(function () {
                let field_value = $(this).val();
                if (field_value != "") otp_value += field_value;
            });
            otp_value_field.val(otp_value);
            // Check if all input fields are filled
            if (otp_value.length === 4) {
                $(".otp-form button[type=submit]").attr("disabled", false);
            } else {
                $(".otp-form button[type=submit]").attr("disabled", true);
            }
        })
        .on("keyup", function (e) {
            let key = e.keyCode || e.charCode;
            if (key == 8 || key == 46 || key == 37 || key == 40) {
                // Backspace or Delete or Left Arrow or Down Arrow
                $(this).prev().focus();
            } else if (key == 38 || key == 39 || $(this).val() != "") {
                // Right Arrow or Top Arrow or Value not empty
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
});
// ==== End Otp Verification Js ====

// ====Start Count Down Js====
function countdown() {
    var counter = $(".verifyCounter");
    var seconds = counter.data("second");
    function tick() {
        var m = Math.floor(seconds / 60);
        var s = seconds % 60;
        seconds--;
        counter.html(m + ":" + (s < 10 ? "0" : "") + String(s));
        if (seconds > 0) {
            setTimeout(tick, 1000);
            $(".resend-otp-button").attr("disabled", true);
            $(".resend_otp_custom").slideDown();
        } else {
            $(".resend-otp-button").removeAttr("disabled");
            $(".verifyCounter").html("0:00");
            $(".resend_otp_custom").slideUp();
        }
    }
    tick();
}


function store_vacation_check(id,added_by,user_id,action_url, product_cart_id){
    $.get({url:action_url},{id:id,added_by:added_by,user_id:user_id},(response)=>{

    }).then((response)=>{
        if(response.status === "active"){
        }else if(response.status == 1 || response.status == 0){
            response.status == 1 ? toastr.success(response.message) : toastr.error(response.message);
            updateNavCart();
        }else{
            toastr.error($('.text-custom-storage').data('textshoptemporaryclose'));
        }
    })
}

$('.order_again_function').on('click',function(){
    let order_id = $(this).data('orderid');

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.ajax({
        type: "POST",
        url: $("#order_again_url").data("url"),
        data: {
            order_id,
        },
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (response) {
            if (response.status === 1) {
                updateNavCart();
                toastr.success(response.message, {
                    CloseButton: true,
                    ProgressBar: true,
                    timeOut: 2000, // duration
                });
                $('#quickViewModal').modal('hide');
                location.href = response.redirect_url;
                return false;
            } else if (response.status === 0) {
                toastr.warning(response.message, {
                    CloseButton: true,
                    ProgressBar: true,
                    timeOut: 2000, // duration
                });
                return false;
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
})


toastr.options = {
    positionClass: "toast-top-right",
};


var errorMessages = {
    valueMissing: $('.please_fill_out_this_field').data('text')
};

$('input').each(function () {
    var $el = $(this);

    $el.on('invalid', function (event) {
        var target = event.target,
            validity = target.validity;
        target.setCustomValidity("");
        if (!validity.valid) {
            if (validity.valueMissing) {
                target.setCustomValidity($el.data('errorRequired') || errorMessages.valueMissing);
            }
        }
    });
});

$('textarea').each(function () {
    var $el = $(this);

    $el.on('invalid', function (event) {
        var target = event.target,
            validity = target.validity;
        target.setCustomValidity("");
        if (!validity.valid) {
            if (validity.valueMissing) {
                target.setCustomValidity($el.data('errorRequired') || errorMessages.valueMissing);
            }
        }
    });
});


$(document).on('click', '#cookie-accept', function () {
    document.cookie = '6valley_cookie_consent=accepted; max-age=' + 60 * 60 * 24 * 30;
    $('#cookie-section').hide();
});
$(document).on('click', '#cookie-reject', function () {
    document.cookie = '6valley_cookie_consent=reject; max-age=' + 60 * 60 * 24;
    $('#cookie-section').hide();
});

$(document).ready(function () {
    if (document.cookie.indexOf("6valley_cookie_consent=accepted") !== -1) {
        $('#cookie-section').hide();
    } else {
        $('#cookie-section').html(cookie_content).show();
    }
});

function route_alert(route, message,type=null) {
    if(type == 'order-cancel'){
        $('#reset_btn').empty().html($('.text-custom-storage').data('textno'))
        $('#delete_button').empty().html($('.text-custom-storage').data('textyes'))
    }
    $('#alert_message').empty().append(message);
    $('#delete_button').attr('href',route);
    $('#status-warning-modal').modal('show');
}

$('.route_alert_function').on('click',function(){
    let route_name = $(this).data('routename');
    let message = $(this).data('message');
    let type = $(this).data('typename');
    route_alert(route_name, message, type);
});

$(".select2-init2").select2({
    dropdownParent: $('#offcanvasRight')
});

$('.select2-init-js').select2({
    dropdownParent: $('.sidebar')
})

$(document).ready(function() {
    var prevScrollpos = $(window).scrollTop();
    $(window).scroll(function() {
        var currentScrollPos = $(window).scrollTop();
        if (prevScrollpos > currentScrollPos) {
            $(".app-bar").slideDown();
        } else {
            $(".app-bar").slideUp();
        }
        prevScrollpos = currentScrollPos;
    });
});


$('#add-fund-amount-input').on('keyup', function(){
    if($(this).val() == ''){
        $('#add-fund-list-area').slideUp();
    }else{
        $('#add-fund-list-area').slideDown();
    }
})

$(".add-fund-slider").owlCarousel({
    loop: true,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: true,
    smartSpeed: 800,
    items:1,
});

$('.click_to_copy_coupon_function').on('click',function() {
    let copied_text = $(this).data('copycode');
    let tempTextarea = $('<textarea>');
    $('body').append(tempTextarea);
    tempTextarea.val(copied_text).select();
    document.execCommand('copy');
    tempTextarea.remove();
    $('.couponid-hide').addClass("d-none");
    $('.couponid').removeClass("d-none");
    $('.couponid-'+copied_text).addClass("d-none");
    $('.couponhideid-'+copied_text).removeClass("d-none");
    toastr.success($('.text-custom-storage').data('textsuccessfullycopied'));
})

$('.click_to_copy_function').on('click',function() {
    let copied_text = $(this).data('copycode');
    let tempTextarea = $('<textarea>');
    $('body').append(tempTextarea);
    tempTextarea.val(copied_text).select();
    document.execCommand('copy');
    tempTextarea.remove();
    toastr.success($('.text-custom-storage').data('textsuccessfullycopied'));
});

$('.thisIsALinkElement').on('click',function(){
    if($(this).data('linkpath')){
        location.href = $(this).data('linkpath');
    }
});

$(".offer-bar-close").on("click", function (e) {
    $(this).parents(".offer-bar").slideUp("slow");
});

$('.minimum_Order_Amount_message').on('click', function(){
    let message = $(this).data('bs-title');
    toastr.warning(message, {
        CloseButton: true,
        ProgressBar: true
    });
});

$('#add_fund_to_wallet_form_btn').on('click', function(){
    if (!$("input[name='payment_method']:checked").val()) {
        toastr.error($('.text-custom-storage').data('textpleaseselectpaymentmethods'));
    }
})

$('#exchange-amount-input').on('keyup', function(){
    let input_val = $(this).val();
    let converted_amount = $(this).val() / $(this).data('loyaltypointexchangerate');
    if (converted_amount > 0) {
        $('.converted_amount_text').removeClass('d-none');
    }
    $.get($(this).data('route'), {amount: converted_amount}, (response) => {
        $('.converted_amount').empty().html(response)
    })
});

$('#chat-form').on('submit', function (event) {
    event.preventDefault();
    let message = $(this).data('message');
    $.ajax({
        type: "post",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (response) {
            $(this).trigger('reset');
            $('#chat-form [name="message"]').val('');
            $('#chatModal').modal('hide');
            toastr.success(message);
        }
    });
});

function review_message() {
    let message = $('.text-custom-storage').data('reviewmessage');
    toastr.info(message, {
        CloseButton: true,
        ProgressBar: true
    });
}

function refund_message() {
    let message = $('.text-custom-storage').data('refundmessage');
    toastr.info(message, {
        CloseButton: true,
        ProgressBar: true
    });
}

function checkout()
{
    let order_note = $('#order_note').val();
    $.post({
        url: $('#order_note_url').data('url'),
        data: {
            _token: $('meta[name="_token"]').attr('content'),
            order_note: order_note,

        },
        beforeSend: function () {
            $('#loading').addClass('d-grid');
        },
        success: function (data) {
            let url = $('#checkout_details_url').data('url');
            location.href = url;
        },
        complete: function () {
            $('#loading').removeClass('d-grid');
        },
    });
}

$('#customer_auth_resend_otp').on('click', function(){
    $('input.otp-field').val('');
    let user_id = $('#user_id').val();
    let form_url = $(this).data('url');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: form_url,
        method: 'POST',
        dataType: 'json',
        data: {
            'user_id': user_id,
        },
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.status == 1) {

                let newCounter = $(".verifyCounter");
                let new_seconds = data.new_time;
                function new_tick() {
                    let m = Math.floor(new_seconds / 60);
                    let s = new_seconds % 60;
                    new_seconds--;
                    newCounter.html(m + ":" + (s < 10 ? "0" : "") + String(s));
                    if (new_seconds > 0) {
                        setTimeout(new_tick, 1000);
                        $('.resend-otp-button').attr('disabled', true);
                        $(".resend_otp_custom").slideDown();
                    }
                    else {
                        $('.resend-otp-button').removeAttr('disabled');
                        newCounter.html("0:00");
                        $(".resend_otp_custom").slideUp();
                    }
                }
                new_tick();

                toastr.success($('.text-otp-related').data('otpsendagain'));
            } else {
                toastr.error($('.text-otp-related').data('otpnewcode'));
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
});

function initTooltip()
{
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    $('.minimum_Order_Amount_message').on('click', function(){
        let message = $(this).data('bs-title');
        toastr.warning(message, {
            CloseButton: true,
            ProgressBar: true
        });
    });
}

$('.goToPageBasedSelectValue').on('change', function (){
    let value = $(this).val();
    goToPageBasedSelectValue(value);
})

$('.checkout_action').on('click', function () {
    checkout();
});

function getPlaceHolderImages() {
    $('.onerror-placeholder-image').on('error', function () {
        let image = $('#onerror-placeholder-image').data('image-path');
        $(this).attr('src', image);
    });
}

getPlaceHolderImages()

function passwordToTextType() {
    $(".js-password-toggle").each(function () {
        $(this)
            .siblings("input:password")
            .css("padding-inline-end", "40px");

        if ($(this).hasClass("type-password")){
            $(this).html('<i class="bi fill"></i>');
        }else {
            $(this).html('<i class="bi bi-eye-slash-fill"></i>');
        }

        $(this).on("click", function () {
            const sibling = $(this).siblings("input");
            if (sibling.hasClass("type-password")) {
                sibling.removeClass("type-password");
                sibling.attr("type", "password");
                $(this).html('<i class="bi bi-eye-slash-fill"></i>');
            } else {
                sibling.addClass("type-password");
                sibling.attr("type", "text");
                $(this).html('<i class="bi bi-eye-fill"></i>');
            }
        });
    });
}

passwordToTextType()

function customerLoginRegisterModalRender() {
    $('.customer_login_register_modal').on('click', function () {
        $.ajax({
            url: $('#get-login-modal-data').data('route'),
            method: 'GET',
            beforeSend: function () {
                $("#loading").addClass("d-grid");
            },
            success: function (data) {
                $('#login-and-register-modal-section').html(data.login_modal);
                $('#login-and-register-modal-section').append(data.register_modal);
                $('#SignInModal').modal('show');
                passwordToTextType()
            },
            complete: function () {
                $("#loading").removeClass("d-grid");
            },
        });
    });
}
customerLoginRegisterModalRender()

document.addEventListener('click', function (event) {
    $('.search-result-box-web').addClass('d-none')
});

function readMoreCurrentReview() {
    $('.read-more-current-review').on('click', function() {
        let element = $(this).data('element');
        $(element).removeClass('max-height-fixed')
        $(element + '-primary').addClass('d--none')
        $(element + '-hidden').removeClass('d--none')
    })
}

readMoreCurrentReview()

function renderCustomImagePopup() {
    $('.custom-image-popup').magnificPopup({
        type: 'image',
        closeOnContentClick: false,
        closeBtnInside: false,
        mainClass: 'mfp-with-zoom mfp-img-mobile',
        image: {
            verticalFit: true,
            titleSrc: function(item) {
                return item.el.attr('title') + ' &middot; <a class="image-source-link" href="'+item.el.attr('data-source')+'" target="_blank">image source</a>';
            }
        },
        gallery: {
            enabled: true
        },
        zoom: {
            enabled: true,
            duration: 300,
            opener: function(element) {
                return element.find('img');
            }
        }
    });
}
renderCustomImagePopup()
