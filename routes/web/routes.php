<?php

use App\Enums\ViewPaths\Web\ProductCompare;
use App\Enums\ViewPaths\Web\ShopFollower;
use App\Http\Controllers\Web\ProductCompareController;
use App\Http\Controllers\Web\Shop\ShopFollowerController;
use Illuminate\Support\Facades\Route;
use App\Enums\ViewPaths\Web\Pages;
use App\Enums\ViewPaths\Web\Review;
use App\Enums\ViewPaths\Web\UserLoyalty;
use App\Http\Controllers\Web\CurrencyController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\ReviewController;
use App\Http\Controllers\Web\UserLoyaltyController;
use App\Http\Controllers\Payment_Methods\SslCommerzPaymentController;
use App\Http\Controllers\Payment_Methods\StripePaymentController;
use App\Http\Controllers\Payment_Methods\PaymobController;
use App\Http\Controllers\Payment_Methods\FlutterwaveV3Controller;
use App\Http\Controllers\Payment_Methods\PaytmController;
use App\Http\Controllers\Payment_Methods\PaypalPaymentController;
use App\Http\Controllers\Payment_Methods\PaytabsController;
use App\Http\Controllers\Payment_Methods\LiqPayController;
use App\Http\Controllers\Payment_Methods\RazorPayController;
use App\Http\Controllers\Payment_Methods\SenangPayController;
use App\Http\Controllers\Payment_Methods\MercadoPagoController;
use App\Http\Controllers\Payment_Methods\BkashPaymentController;
use App\Http\Controllers\Payment_Methods\PaystackController;
use App\Http\Controllers\FrontCustomController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('maintenance-mode', 'Web\WebController@maintenance_mode')->name('maintenance-mode');

Route::group(['namespace' => 'Web', 'middleware' => ['maintenance_mode', 'guestCheck']], function () {
    Route::group(['prefix' => 'product-compare', 'as' => 'product-compare.'], function () {
        Route::controller(ProductCompareController::class)->group(function () {
            Route::get(ProductCompare::INDEX[URI], 'index')->name('index');
            Route::post(ProductCompare::INDEX[URI], 'add');
            Route::get(ProductCompare::DELETE[URI], 'delete')->name('delete');
            Route::get(ProductCompare::DELETE_ALL[URI], 'deleteAllCompareProduct')->name('delete-all');
        });
    });
    Route::post(ShopFollower::SHOP_FOLLOW[URI], [ShopFollowerController::class, 'followOrUnfollowShop'])->name('shop-follow');
});

Route::group(['namespace' => 'Web', 'middleware' => ['maintenance_mode', 'guestCheck']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('quick-view', 'WebController@getQuickView')->name('quick-view');
    Route::get('searched-products', 'WebController@searched_products')->name('searched-products');

    Route::group(['middleware' => ['customer']], function () {
        Route::controller(ReviewController::class)->group(function () {
            Route::post(Review::ADD[URI], 'add')->name('review.store');
            Route::post(Review::ADD_DELIVERYMAN_REVIEW[URI], 'addDeliveryManReview')->name('submit-deliveryman-review');
            Route::post(Review::DELETE_REVIEW_IMAGE[URI], 'deleteReviewImage')->name('delete-review-image');
        });
    });

    Route::get('checkout-details', 'WebController@checkout_details')->name('checkout-details');
    Route::get('checkout-shipping', 'WebController@checkout_shipping')->name('checkout-shipping');
    Route::get('checkout-payment', 'WebController@checkout_payment')->name('checkout-payment');
    Route::get('checkout-review', 'WebController@checkout_review')->name('checkout-review');
    // Route::get('checkout-complete', 'WebController@checkout_complete')->name('checkout-complete');
    Route::post('offline-payment-checkout-complete', 'WebController@offline_payment_checkout_complete')->name('offline-payment-checkout-complete');
    Route::get('order-placed', 'WebController@order_placed')->name('order-placed');
    Route::get('shop-cart', 'WebController@shop_cart')->name('shop-cart');
    Route::post('order_note', 'WebController@order_note')->name('order_note');
    Route::get('digital-product-download/{id}', 'WebController@getDigitalProductDownload')->name('digital-product-download');
    Route::post('digital-product-download-otp-verify', 'WebController@getDigitalProductDownloadOtpVerify')->name('digital-product-download-otp-verify');
    Route::post('digital-product-download-otp-reset', 'WebController@getDigitalProductDownloadOtpReset')->name('digital-product-download-otp-reset');
    Route::get('pay-offline-method-list', 'WebController@pay_offline_method_list')->name('pay-offline-method-list')->middleware('guestCheck');

    //wallet payment
    Route::get('checkout-complete-wallet', 'WebController@checkout_complete_wallet')->name('checkout-complete-wallet');

    Route::post('subscription', 'WebController@subscription')->name('subscription');
    Route::get('search-shop', 'WebController@search_shop')->name('search-shop');

    Route::get('categories', 'WebController@all_categories')->name('categories');
    Route::get('category-ajax/{id}', 'WebController@categories_by_category')->name('category-ajax');

    Route::get('brands', 'WebController@all_brands')->name('brands');
    Route::get('vendors', 'WebController@all_sellers')->name('vendors');
    Route::get('seller-profile/{id}', 'WebController@seller_profile')->name('seller-profile');

    Route::get('flash-deals/{id}', 'WebController@flash_deals')->name('flash-deals');

    Route::controller(PageController::class)->group(function () {
        Route::get(Pages::ABOUT_US[URI], 'getAboutUsView')->name('about-us');
        Route::get(Pages::CONTACTS[URI], 'getContactView')->name('contacts');
        Route::get(Pages::HELP_TOPIC[URI], 'getHelpTopicView')->name('helpTopic');
        Route::get(Pages::REFUND_POLICY[URI], 'getRefundPolicyView')->name('refund-policy');
        Route::get(Pages::RETURN_POLICY[URI], 'getReturnPolicyView')->name('return-policy');
        Route::get(Pages::PRIVACY_POLICY[URI], 'getPrivacyPolicyView')->name('privacy-policy');
        Route::get(Pages::CANCELLATION_POLICY[URI], 'getCancellationPolicyView')->name('cancellation-policy');
        Route::get(Pages::TERMS_AND_CONDITION[URI], 'getTermsAndConditionView')->name('terms');
    });

    Route::get('/product/{slug}', 'ProductDetailsController@index')->name('product');
    Route::get('products', 'ProductListController@products')->name('products');
    Route::post('ajax-filter-products', 'ShopViewController@ajax_filter_products')->name('ajax-filter-products'); // Theme fashion, ALl purpose
    Route::get('orderDetails', 'WebController@orderdetails')->name('orderdetails');
    Route::get('discounted-products', 'WebController@discounted_products')->name('discounted-products');
    Route::post('/products-view-style', 'WebController@product_view_style')->name('product_view_style');

    Route::post('review-list-product', 'WebController@review_list_product')->name('review-list-product');
    Route::post('review-list-shop', 'WebController@review_list_shop')->name('review-list-shop'); // theme fashion
    //Chat with seller from product details
    Route::get('chat-for-product', 'WebController@chat_for_product')->name('chat-for-product');

    Route::get('wishlists', 'WebController@viewWishlist')->name('wishlists')->middleware('customer');
    Route::post('store-wishlist', 'WebController@storeWishlist')->name('store-wishlist');
    Route::post('delete-wishlist', 'WebController@deleteWishlist')->name('delete-wishlist');
    Route::get('delete-wishlist-all', 'WebController@delete_wishlist_all')->name('delete-wishlist-all')->middleware('customer');

    Route::controller(CurrencyController::class)->group(function () {
        Route::post('/currency', 'changeCurrency')->name('currency.change');
    });

    // // end theme_aster compare list
    Route::get('searched-products-for-compare', 'WebController@searched_products_for_compare_list')->name('searched-products-compare'); // theme fashion compare list

    //profile Route
    Route::get('user-profile', 'UserProfileController@user_profile')->name('user-profile')->middleware('customer'); //theme_aster
    Route::get('user-account', 'UserProfileController@user_account')->name('user-account')->middleware('customer');
    Route::post('user-account-update', 'UserProfileController@user_update')->name('user-update');
    Route::post('user-account-picture', 'UserProfileController@user_picture')->name('user-picture');
    Route::get('account-address-add', 'UserProfileController@account_address_add')->name('account-address-add');
    Route::get('account-address', 'UserProfileController@account_address')->name('account-address');
    Route::post('account-address-store', 'UserProfileController@address_store')->name('address-store');
    Route::get('account-address-delete', 'UserProfileController@address_delete')->name('address-delete');
    ROute::get('account-address-edit/{id}', 'UserProfileController@address_edit')->name('address-edit');
    Route::post('account-address-update', 'UserProfileController@address_update')->name('address-update');
    Route::get('account-payment', 'UserProfileController@account_payment')->name('account-payment');
    Route::get('account-oder', 'UserProfileController@account_order')->name('account-oder')->middleware('customer');
    Route::get('account-order-details', 'UserProfileController@account_order_details')->name('account-order-details')->middleware('customer');
    Route::get('account-order-details-vendor-info', 'UserProfileController@account_order_details_seller_info')->name('account-order-details-vendor-info')->middleware('customer');
    Route::get('account-order-details-delivery-man-info', 'UserProfileController@account_order_details_delivery_man_info')->name('account-order-details-delivery-man-info')->middleware('customer');
    Route::get('account-order-details-reviews', 'UserProfileController@account_order_details_reviews')->name('account-order-details-reviews')->middleware('customer');
    Route::get('generate-invoice/{id}', 'UserProfileController@generate_invoice')->name('generate-invoice');
    Route::get('account-wishlist', 'UserProfileController@account_wishlist')->name('account-wishlist'); //add to card not work
    Route::get('refund-request/{id}', 'UserProfileController@refund_request')->name('refund-request');
    Route::get('refund-details/{id}', 'UserProfileController@refund_details')->name('refund-details');
    Route::post('refund-store', 'UserProfileController@store_refund')->name('refund-store');
    Route::get('account-tickets', 'UserProfileController@account_tickets')->name('account-tickets');
    Route::get('order-cancel/{id}', 'UserProfileController@order_cancel')->name('order-cancel');
    Route::post('ticket-submit', 'UserProfileController@submitSupportTicket')->name('ticket-submit');
    Route::get('account-delete/{id}', 'UserProfileController@account_delete')->name('account-delete');
    Route::get('refer-earn', 'UserProfileController@refer_earn')->name('refer-earn')->middleware('customer');
    Route::get('user-coupons', 'UserProfileController@user_coupons')->name('user-coupons')->middleware('customer');
    // Chatting start
    Route::get('chat/{type}', 'ChattingController@chat_list')->name('chat')->middleware('customer');
    Route::get('messages', 'ChattingController@messages')->name('messages');
    Route::post('messages-store', 'ChattingController@messages_store')->name('messages_store');
    // chatting end

    //Support Ticket
    Route::group(['prefix' => 'support-ticket', 'as' => 'support-ticket.'], function () {
        Route::get('{id}', 'UserProfileController@single_ticket')->name('index');
        Route::post('{id}', 'UserProfileController@comment_submit')->name('comment');
        Route::get('delete/{id}', 'UserProfileController@support_ticket_delete')->name('delete');
        Route::get('close/{id}', 'UserProfileController@support_ticket_close')->name('close');
    });

    Route::get('wallet-account', 'UserWalletController@my_wallet_account')->name('wallet-account'); //theme fashion
    Route::get('wallet', 'UserWalletController@index')->name('wallet')->middleware('customer');

    Route::controller(UserLoyaltyController::class)->group(function () {
        Route::get(UserLoyalty::LOYALTY[URI], 'index')->name('loyalty')->middleware('customer');
        Route::post(UserLoyalty::EXCHANGE_CURRENCY[URI], 'getLoyaltyExchangeCurrency')->name('loyalty-exchange-currency');
        Route::get(UserLoyalty::GET_CURRENCY_AMOUNT[URI], 'getLoyaltyCurrencyAmount')->name('ajax-loyalty-currency-amount');
    });

    Route::group(['prefix' => 'track-order', 'as' => 'track-order.'], function () {
        Route::get('', 'UserProfileController@track_order')->name('index');
        Route::get('result-view', 'UserProfileController@track_order_result')->name('result-view');
        Route::get('last', 'UserProfileController@track_last_order')->name('last');
        Route::any('result', 'UserProfileController@track_order_result')->name('result');
        Route::get('order-wise-result-view', 'UserProfileController@track_order_wise_result')->name('order-wise-result-view');
    });

    //sellerShop
    Route::get('shopView/{id}', 'ShopViewController@seller_shop')->name('shopView');
    Route::get('ajax-shop-vacation-check', 'ShopViewController@ajax_shop_vacation_check')->name('ajax-shop-vacation-check'); //theme fashion
    Route::post('shopView/{id}', 'WebController@seller_shop_product');
    // Route::post('shop-follow', 'ShopFollowerController@shop_follow')->name('shop_follow');

    //top Rated
    Route::get('top-rated', 'WebController@top_rated')->name('topRated');
    Route::get('best-sell', 'WebController@best_sell')->name('bestSell');
    Route::get('new-product', 'WebController@new_product')->name('newProduct');

    Route::group(['prefix' => 'contact', 'as' => 'contact.'], function () {
        Route::post('store', 'WebController@contact_store')->name('store');
        Route::get('/code/captcha/{tmp}', 'WebController@captcha')->name('default-captcha');
    });
});

//check done
Route::group(['prefix' => 'cart', 'as' => 'cart.', 'namespace' => 'Web'], function () {
    Route::post('variant_price', 'CartController@variant_price')->name('variant_price');
    Route::post('add', 'CartController@addToCart')->name('add');
    Route::post('update-variation', 'CartController@update_variation')->name('update-variation'); //theme fashion
    Route::post('remove', 'CartController@removeFromCart')->name('remove');
    Route::get('remove-all', 'CartController@remove_all_cart')->name('remove-all'); //theme fashion
    Route::post('nav-cart-items', 'CartController@updateNavCart')->name('nav-cart');
    Route::post('floating-nav-cart-items', 'CartController@update_floating_nav')->name('floating-nav-cart-items'); // theme fashion floating nav
    Route::post('updateQuantity', 'CartController@updateQuantity')->name('updateQuantity');
    Route::post('updateQuantity-guest', 'CartController@updateQuantity_guest')->name('updateQuantity.guest');
    Route::post('order-again', 'CartController@order_again')->name('order-again')->middleware('customer');
});

//Seller shop apply
Route::group(['prefix' => 'coupon', 'as' => 'coupon.', 'namespace' => 'Web'], function () {
    Route::post('apply', 'CouponController@apply')->name('apply');
    Route::get('remove', 'CouponController@removeCoupon')->name('remove');
});
//check done

/*Auth::routes();*/
Route::get('authentication-failed', function () {
    $errors = [];
    array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);
    return response()->json([
        'errors' => $errors
    ], 401);
})->name('authentication-failed');

Route::group(['namespace' => 'Customer', 'prefix' => 'customer', 'as' => 'customer.'], function () {

    Route::get('login', 'Auth\LoginController@login')->name('login');
    Route::get('sign-up', 'Auth\RegisterController@register')->name('sign-up');

    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('/code/captcha/{tmp}', 'LoginController@captcha')->name('default-captcha');
        Route::post('login/submit', 'LoginController@submit')->name('login.submit');
        Route::get('logout', 'LoginController@logout')->name('logout');
        Route::get('get-login-modal-data', 'LoginController@get_login_modal_data')->name('get-login-modal-data');

        Route::post('sign-up/submit', 'RegisterController@submit')->name('sign-up.submit');

        Route::get('check/{id}', 'RegisterController@check')->name('check');

        // Customer Default Verify
        Route::post('verify', 'RegisterController@verify')->name('verify');

        // Customer Ajax Verify for theme except default
        Route::post('ajax-verify', 'RegisterController@ajax_verify')->name('ajax_verify');
        Route::post('resend-otp', 'RegisterController@resend_otp')->name('resend_otp');

        Route::get('update-phone/{id}', 'SocialAuthController@editPhone')->name('update-phone');
        Route::post('update-phone/{id}', 'SocialAuthController@updatePhone');

        Route::get('login/{service}', 'SocialAuthController@redirectToProvider')->name('service-login');
        Route::get('login/{service}/callback', 'SocialAuthController@handleProviderCallback')->name('service-callback');

        Route::get('recover-password', 'ForgotPasswordController@reset_password')->name('recover-password');
        Route::post('forgot-password', 'ForgotPasswordController@reset_password_request')->name('forgot-password');
        Route::get('otp-verification', 'ForgotPasswordController@otp_verification')->name('otp-verification');
        Route::post('otp-verification', 'ForgotPasswordController@otp_verification_submit');
        Route::get('reset-password', 'ForgotPasswordController@reset_password_index')->name('reset-password');
        Route::post('reset-password', 'ForgotPasswordController@reset_password_submit');
        Route::post('resend-otp-reset-password', 'ForgotPasswordController@ajax_resend_otp')->name('resend-otp-reset-password');
    });

    Route::group([], function () {
        Route::get('set-payment-method/{name}', 'SystemController@set_payment_method')->name('set-payment-method');
        Route::get('set-shipping-method', 'SystemController@set_shipping_method')->name('set-shipping-method');
        Route::post('choose-shipping-address', 'SystemController@choose_shipping_address')->name('choose-shipping-address');
        Route::post('choose-shipping-address-other', 'SystemController@choose_shipping_address_other')->name('choose-shipping-address-other');
        Route::post('choose-billing-address', 'SystemController@choose_billing_address')->name('choose-billing-address');

        Route::group(['prefix' => 'reward-points', 'as' => 'reward-points.', 'middleware' => ['auth:customer']], function () {
            Route::get('convert', 'RewardPointController@convert')->name('convert');
        });
    });

    Route::post('/web-payment-request', 'PaymentController@payment')->name('web-payment-request');
    Route::post('/customer-add-fund-request', 'PaymentController@customer_add_to_fund_request')->name('add-fund-request');
});

$is_published = 0;
try {
    $full_data = include('Modules/Gateways/Addon/info.php');
    $is_published = $full_data['is_published'] == 1 ? 1 : 0;
} catch (\Exception $exception) {
}

if (!$is_published) {
    Route::group(['prefix' => 'payment'], function () {

        //SSLCOMMERZ
        Route::group(['prefix' => 'sslcommerz', 'as' => 'sslcommerz.'], function () {
            Route::get('pay', [SslCommerzPaymentController::class, 'index'])->name('pay');
            Route::post('success', [SslCommerzPaymentController::class, 'success'])
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
            Route::post('failed', [SslCommerzPaymentController::class, 'failed'])
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
            Route::post('canceled', [SslCommerzPaymentController::class, 'canceled'])
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        });

        //STRIPE
        Route::group(['prefix' => 'stripe', 'as' => 'stripe.'], function () {
            Route::get('pay', [StripePaymentController::class, 'index'])->name('pay');
            Route::get('token', [StripePaymentController::class, 'payment_process_3d'])->name('token');
            Route::get('success', [StripePaymentController::class, 'success'])->name('success');
        });

        //RAZOR-PAY
        Route::group(['prefix' => 'razor-pay', 'as' => 'razor-pay.'], function () {
            Route::get('pay', [RazorPayController::class, 'index']);
            Route::post('payment', [RazorPayController::class, 'payment'])->name('payment')
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        });

        //PAYPAL
        Route::group(['prefix' => 'paypal', 'as' => 'paypal.'], function () {
            Route::get('pay', [PaypalPaymentController::class, 'payment']);
            Route::any('success', [PaypalPaymentController::class, 'success'])->name('success')
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
            Route::any('cancel', [PaypalPaymentController::class, 'cancel'])->name('cancel')
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        });

        //SENANG-PAY
        Route::group(['prefix' => 'senang-pay', 'as' => 'senang-pay.'], function () {
            Route::get('pay', [SenangPayController::class, 'index']);
            Route::any('callback', [SenangPayController::class, 'return_senang_pay']);
        });

        //PAYTM
        Route::group(['prefix' => 'paytm', 'as' => 'paytm.'], function () {
            Route::get('pay', [PaytmController::class, 'payment']);
            Route::any('response', [PaytmController::class, 'callback'])->name('response')
                ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        });

        //FLUTTERWAVE
        Route::group(['prefix' => 'flutterwave-v3', 'as' => 'flutterwave-v3.'], function () {
            Route::get('pay', [FlutterwaveV3Controller::class, 'initialize'])->name('pay');
            Route::get('callback', [FlutterwaveV3Controller::class, 'callback'])->name('callback');
        });

        //PAYSTACK
        Route::group(['prefix' => 'paystack', 'as' => 'paystack.'], function () {
            Route::get('pay', [PaystackController::class, 'index'])->name('pay');
            Route::post('payment', [PaystackController::class, 'redirectToGateway'])->name('payment');
            Route::get('callback', [PaystackController::class, 'handleGatewayCallback'])->name('callback');
        });

        //BKASH

        Route::group(['prefix' => 'bkash', 'as' => 'bkash.'], function () {
            // Payment Routes for bKash
            Route::get('make-payment', [BkashPaymentController::class, 'make_tokenize_payment'])->name('make-payment');
            Route::any('callback', [BkashPaymentController::class, 'callback'])->name('callback');
        });

        //Liqpay
        Route::group(['prefix' => 'liqpay', 'as' => 'liqpay.'], function () {
            Route::get('payment', [LiqPayController::class, 'payment'])->name('payment');
            Route::any('callback', [LiqPayController::class, 'callback'])->name('callback');
        });

        //MERCADOPAGO
        Route::group(['prefix' => 'mercadopago', 'as' => 'mercadopago.'], function () {
            Route::get('pay', [MercadoPagoController::class, 'index'])->name('index');
            Route::post('make-payment', [MercadoPagoController::class, 'make_payment'])->name('make_payment');
        });

        //PAYMOB
        Route::group(['prefix' => 'paymob', 'as' => 'paymob.'], function () {
            Route::any('pay', [PaymobController::class, 'credit'])->name('pay');
            Route::any('callback', [PaymobController::class, 'callback'])->name('callback');
        });

        //PAYTABS
        Route::group(['prefix' => 'paytabs', 'as' => 'paytabs.'], function () {
            Route::any('pay', [PaytabsController::class, 'payment'])->name('pay');
            Route::any('callback', [PaytabsController::class, 'callback'])->name('callback');
            Route::any('response', [PaytabsController::class, 'response'])->name('response');
        });

        //Pay Fast
        Route::group(['prefix' => 'payfast', 'as' => 'payfast.'], function () {
            Route::get('pay', [PayFastController::class, 'payment'])->name('payment');
            Route::any('callback', [PayFastController::class, 'callback'])->name('callback');
        });
    });
}

Route::get('web-payment', 'Customer\PaymentController@web_payment_success')->name('web-payment-success');
Route::get('payment-success', 'Customer\PaymentController@success')->name('payment-success');
Route::get('payment-fail', 'Customer\PaymentController@fail')->name('payment-fail');

Route::get('/test', function () {
    return view('welcome');
});

// web custom controller
Route::post('/update-cart-quantity', [FrontCustomController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::post('/delete-cart-item', [FrontCustomController::class, 'deleteItem'])->name('cart.delete');
Route::post('/checkout', [FrontCustomController::class, 'checkout'])->name('checkout');
Route::get('/bkash/callback', [FrontCustomController::class, 'bkash_callBack'])->name('bkash-callBack');

Route::get('nagad/callback', [FrontCustomController::class,'nagad_callback']);
Route::get('invoice/{id}', [FrontCustomController::class, 'invoice'])->name('invoice');
Route::get('old/customer', [FrontCustomController::class, 'old_customer'])->name('old.customer');

