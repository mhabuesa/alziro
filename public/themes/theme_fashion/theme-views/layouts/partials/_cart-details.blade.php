@php
    $shippingMethod = getWebConfig(name: 'shipping_method');
    $cart = \App\Models\Cart::where(['customer_id' => (auth('customer')->check() ? auth('customer')->id() : session('guest_id'))])->with(['seller','allProducts.category'])->get()->groupBy('cart_group_id');
@endphp

@if( $cart->count() > 0)
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8 pe-lg-0">
                <div class="cart-title-area text-capitalize mb-2">
                    <h6 class="title">{{translate('all_cart_product_list')}}
                        <span class="btn-status">({{count(\App\Utils\CartManager::get_cart())}})</span>
                    </h6>
                    <span type="button" class="text-text-2 route_alert_function"
                          data-routename="{{ route('cart.remove-all') }}"
                          data-message="{{ translate('want_to_clear_all_cart?') }}"
                          data-typename="">{{translate('remove_all')}}</span>
                </div>

                <div class="table-responsive d-none d-md-block overflow-hidden">
                    <table class="table __table vertical-middle cart-list-table-custom">

                        <thead class="word-nobreak">
                        <tr>
                            <th>
                                <label class="form-check m-0">
                                    <span class="form-check-label">{{translate('product')}}</span>
                                </label>
                            </th>
                            <th class="text-center">
                                {{translate('discount')}}
                            </th>
                            <th class="text-center">
                                {{translate('quantity')}}
                            </th>
                            <th class="text-center">
                                {{translate('total')}}
                            </th>
                        </tr>
                        </thead>
                    </table>
                    @foreach($cart as $group_key=>$group)
                        @php
                            $physical_product = false;
                            $total_shipping_cost = 0;
                            foreach ($group as $row) {
                                if ($row->product_type == 'physical') {
                                    $physical_product = true;
                                }
                                if ($row->product_type == 'physical' && $row->shipping_type != "order_wise") {
                                    $total_shipping_cost += $row->shipping_cost;
                                }
                            }

                        @endphp

                        @foreach($group as $cart_key=>$cartItem)

                            @if ($shippingMethod=='inhouse_shipping')
                                    <?php

                                    $admin_shipping = \App\Models\ShippingType::where('seller_id', 0)->first();
                                    $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';

                                    ?>
                            @else
                                    <?php
                                    if ($cartItem->seller_is == 'admin') {
                                        $admin_shipping = \App\Models\ShippingType::where('seller_id', 0)->first();
                                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                                    } else {
                                        $seller_shipping = \App\Models\ShippingType::where('seller_id', $cartItem->seller_id)->first();
                                        $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                                    }
                                    ?>
                            @endif
                            @if($cart_key==0)
                                <div class="--bg-6 border-0 rounded py-2 px-2 px-sm-3 ">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between ">

                                        @php
                                            $verify_status = \App\Utils\OrderManager::minimum_order_amount_verify($request, $group_key);
                                        @endphp

                                        <div class="min-w-180 d-flex">
                                            @if($cartItem->seller_is=='admin')
                                                <a href="{{route('shopView',['id'=>0])}}" class="cart-shop">
                                                    <img loading="lazy" alt="{{ translate('logo') }}"
                                                         src="{{ getValidImage(path: "storage/app/public/company/".($web_config['fav_icon']->value), type: 'shop') }}">
                                                    <h6 class="text-base">{{$web_config['name']->value}}</h6>
                                                </a>
                                            @else
                                                <a href="{{route('shopView',['id'=>$cartItem->seller_id])}}"
                                                   class="cart-shop">
                                                    <img loading="lazy" alt="{{ translate('shop') }}"
                                                         src="{{ getValidImage(path: 'storage/app/public/shop/'.($cartItem->seller->shop->image ?? ''), type: 'shop') }}">
                                                    <h6 class="text-base">{{ $cartItem->seller->shop->name}}</h6>
                                                </a>
                                            @endif

                                            @if ($verify_status['minimum_order_amount'] > $verify_status['amount'])
                                                <span class="ps-2 text-danger pulse-button minimum_Order_Amount_message"
                                                      data-bs-toggle="tooltip" data-bs-placement="right"
                                                      data-bs-custom-class="custom-tooltip"
                                                      data-bs-title="{{ translate('minimum_Order_Amount') }} {{ \App\Utils\Helpers::currency_converter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{ getWebConfig(name: 'company_name') }} @else {{ \App\Utils\get_shop_name($cartItem['seller_id']) }} @endif">
                                                    <i class="bi bi-info-circle"></i>
                                                </span>
                                            @endif
                                        </div>

                                        @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                            @php
                                                $choosen_shipping=\App\Models\CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first()
                                            @endphp

                                            @if(isset($choosen_shipping)==false)
                                                @php $choosen_shipping['shipping_method_id']=0 @endphp
                                            @endif

                                            @php
                                                $shippings=\App\Utils\Helpers::get_shipping_methods($cartItem['seller_id'],$cartItem['seller_is'])

                                            @endphp
                                            @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                                <div
                                                    class=" bg-white select-method-border rounded  py-2 position-relative">
                                                    <div class="d-flex ">
                                                        <div
                                                            class="flex-middle flex-nowrap fw-semibold text-dark mx-3 text-capitalize">
                                                            <i class="bi bi-truck"></i>
                                                            {{ translate('shipping_method') }} :
                                                        </div>

                                                        <div class="dropdown">
                                                            <a class="text-dark" href="javascript:" data-bs-toggle="dropdown">
                                                                    <?php
                                                                    $shippings_title = translate('choose_shipping_method');
                                                                    foreach ($shippings as $shipping) {
                                                                        if ($choosen_shipping['shipping_method_id'] == $shipping['id']) {
                                                                            $shippings_title = ucfirst($shipping['title']) . ' ( ' . $shipping['duration'] . ' ) ' . \App\Utils\Helpers::currency_converter($shipping['cost']);
                                                                        }
                                                                    }
                                                                    ?>
                                                                {{ $shippings_title }}
                                                                <i class="ms-1 text-small bi bi-chevron-down"></i>
                                                            </a>
                                                            <div class="dropdown-menu __dropdown-menu">
                                                                <ul class="">
                                                                    @foreach($shippings as $shipping)
                                                                        <li class="cursor-pointer text-dark px-3 py-1 set_shipping_id_function"
                                                                            data-id="{{$shipping['id']}}"
                                                                            data-cartgroup="{{$cartItem['cart_group_id']}}"
                                                                        >
                                                                            {{ucfirst($shipping['title']).' ( '.$shipping['duration'].' ) '.\App\Utils\Helpers::currency_converter($shipping['cost'])}}
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            @if ($shipping_type != 'order_wise')
                                                <div class=" bg-white select-method-border rounded  py-2">
                                                    <div class="d-flex ">
                                                        <div
                                                            class="flex-middle flex-nowrap fw-semibold text-dark mx-3 text-capitalize">
                                                            <i class="bi bi-truck"></i>
                                                            {{ translate('shipping_cost') }} :
                                                        </div>
                                                        <div class="">
                                                            <span>{{\App\Utils\Helpers::currency_converter($total_shipping_cost)}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endif

                        @endforeach
                        @php
                            $physical_product = false;
                            foreach ($group as $row) {
                                if ($row->product_type == 'physical') {
                                    $physical_product = true;
                                }
                            }
                        @endphp
                        @php($product_null_status = 0)
                        @php($total_amount = 0)
                        @foreach($group as $key=>$cartItem)
                            @php($product = $cartItem->allProducts)

                            @if (!$product)
                                @php($product_null_status = 1)
                            @endif


                            <form class="cart add_to_cart_form{{$cartItem['id']}}"
                                  id="add_to_cart_form_web{{$cartItem['id']}}"
                                  action="{{route('cart.update-variation')}}"
                                  data-redirecturl="{{route('checkout-details')}}"
                                  data-varianturl="{{ route('cart.variant_price') }}"
                                  data-errormessage="{{translate('please_choose_all_the_options')}}"
                                  data-outofstock="{{translate('sorry').', '.translate('out_of_stock')}}.">
                                @csrf
                                <table class="table __table vertical-middle cart-list-table-custom">

                                    <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" name="id" value="{{ $cartItem->id }}" hidden>
                                            <input type="text" name="product_id" value="{{ $product->id }}" hidden>
                                            <div class="cart-product  align-items-center">
                                                <label class="form-check position-relative overflow-hidden">
                                                    @if ($product->status == 1)
                                                        <img loading="lazy" alt="{{ translate('product') }}"
                                                             src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
                                                    @elseif($product->status == 0)
                                                        <img loading="lazy" alt="{{ translate('product') }}"
                                                             src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
                                                        <span
                                                            class="temporary-closed position-absolute text-center p-2">
                                                                    <span>{{translate('not_available')}}</span>
                                                                </span>
                                                    @else
                                                        <img loading="lazy"
                                                             src="{{ theme_asset('assets/img/image-place-holder.png') }}"
                                                             alt="{{ translate('product') }}">
                                                        <span
                                                            class="temporary-closed position-absolute text-center p-2">
                                                                    <span>{{translate('not_available')}}</span>
                                                                </span>
                                                    @endif
                                                </label>


                                                <div class="cont {{ $product->status == 0 ? 'blur-section':'' }}">
                                                    <a href="{{ $product->status == 1 ? route('product',$product['slug']) : 'javascript:' }}"
                                                       class="name text-title">
                                                        {{ $product->name }}
                                                    </a>
                                                    <div class="d-flex column-gap-1">
                                                        <span>{{ translate('price') }}</span> <span>:</span> <strong
                                                            class="product_price{{$cartItem['id']}}">{{ \App\Utils\Helpers::currency_converter($cartItem->price) }}</strong>
                                                    </div>
                                                    <div class="d-flex column-gap-1">
                                                        @if (isset($product->category))
                                                            <span>{{ translate('category') }} </span> <span>:</span>
                                                            <strong>{{ isset($product->category) ? $product->category->name:'' }}</strong>
                                                        @endif
                                                    </div>
                                                    @if ($product)
                                                        @if ($product->product_type == "physical")
                                                            <div class="d-flex flex-wrap column-gap-3">
                                                                @if (!empty(json_decode($product->colors)))
                                                                    <div class="d-flex column-gap-1">
                                                                        <span>{{ translate('color') }} </span>
                                                                        <span>:</span>
                                                                        <select
                                                                            class="no-border-select variants-class{{$key}} update_add_to_cart_by_variation_web"
                                                                            data-id="{{$cartItem['id']}}"
                                                                            name="color">
                                                                            @foreach (json_decode($product->colors) as $k=>$value)
                                                                                <option
                                                                                    value="{{ $value }}" {{ $cartItem->color == $value ? 'selected':'' }}>{{\App\Utils\get_color_name($value)}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                @endif

                                                                @php($variations = json_decode($cartItem->variations,true))
                                                                @foreach (json_decode($product->choice_options) as $k => $choice)
                                                                    <div class="d-flex column-gap-1">
                                                                        <span> {{ translate( $choice->title )}} </span>
                                                                        <span>:</span>
                                                                        <select
                                                                            class="no-border-select variants-class{{$key}} update_add_to_cart_by_variation_web"
                                                                            data-id="{{$cartItem['id']}}"
                                                                            name="{{$choice->name}}">
                                                                            @foreach ($choice->options as $value)
                                                                                <option
                                                                                    value="{{ trim($value) }}" {{in_array(trim($value),$variations,true) ? 'selected' : ''}}>{{ ucwords($value) }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            @if ( $shipping_type != 'order_wise')
                                                                <div class="d-flex column-gap-1">
                                                                    <span>{{ translate('shipping_cost') }}</span> <span>:</span>
                                                                    <strong
                                                                        class="">{{ \App\Utils\Helpers::currency_converter($cartItem['shipping_cost']) }}</strong>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if ($cartItem['discount'] > 0)
                                                <span class="badge badge-soft-base product_discount{{$cartItem['id']}}">-{{ \App\Utils\Helpers::currency_converter($cartItem['discount']*$cartItem['quantity']) }}</span>
                                            @else
                                                <span
                                                    class="badge text-capitalize badge-soft-secondary discount{{$cartItem['id']}}">{{translate('no_discount')}}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php($minimum_order=\App\Utils\ProductManager::get_product($cartItem['product_id']))

                                            @if($minimum_order)
                                                <div class="quantity __quantity">
                                                    <input type="number"
                                                           class="quantity__qty cart-qty-input cart-quantity-web{{$cartItem['id']}} form-control cartQuantity{{$cartItem['id']}} updateCartQuantityList_cart_data"
                                                           value="{{$cartItem['quantity']}}" name="quantity"
                                                           id="cartQuantityWeb{{$cartItem['id']}}"
                                                           data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                           data-cart="{{ $cartItem['id'] }}" data-value="0"
                                                           data-action=""
                                                           data-min="{{ isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1 }}">
                                                    <div>
                                                        <div
                                                            class="quantity__plus cart-qty-btn updateCartQuantityList_cart_data"
                                                            data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                            data-cart="{{ $cartItem['id'] }}" data-value="1"
                                                            data-action=""
                                                        >
                                                            <i class="bi bi-plus "></i>
                                                        </div>
                                                        <div
                                                            class="quantity__minus cart-qty-btn updateCartQuantityList_cart_data"
                                                            data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                            data-cart="{{ $cartItem['id'] }}" data-value="-1"
                                                            data-action="{{ $cartItem['quantity'] == $minimum_order->minimum_order_qty ? 'delete':'minus' }}"
                                                        >
                                                            <i class="{{ $cartItem['quantity'] == (isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1) ? 'bi bi-trash3-fill text-danger fs-10' : 'bi bi-dash-lg' }}"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="quantity __quantity">
                                                    <input type="text"
                                                           class="quantity__qty cart-qty-input cart-quantity-web{{$cartItem['id']}} form-control cartQuantity{{$cartItem['id']}}"
                                                           name="quantity" id="cartQuantity{{$cartItem['id']}}"
                                                           data-min="{{$cartItem['quantity']}}"
                                                           value="{{$cartItem['quantity']}}" readonly>
                                                    <div>
                                                        <div class="cart-qty-btn disabled"
                                                             title="{{ translate('product_not_available') }}">
                                                            <i class="bi bi-exclamation-circle text-danger"></i>
                                                        </div>
                                                        <div class="cart-qty-btn updateCartQuantityList_cart_data"
                                                             data-minorder="{{$cartItem['quantity']+1}}"
                                                             data-cart="{{ $cartItem['id'] }}"
                                                             data-value="-{{$cartItem['quantity']}}"
                                                             data-action="delete">
                                                            <i class="bi bi-trash3-fill text-danger fs-10}"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        @php($total_amount = $total_amount + ($cartItem['price']-$cartItem['discount'])*$cartItem['quantity'])
                                        <td class="text-center">{{ \App\Utils\Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity']) }}</td>

                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                            @endforeach
                            </table>

                            @php($free_delivery_status = \App\Utils\OrderManager::free_delivery_order_amount($group[0]->cart_group_id))

                            @if ($free_delivery_status['status'] && (session()->missing('coupon_type') || session('coupon_type') !='free_delivery'))
                                <div class="free-delivery-area px-3 mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <img loading="lazy"
                                            src="{{ theme_asset('assets/img/free-shipping.svg') }}"
                                            alt="{{ translate('free_shipping') }}" width="40">
                                        @if ($free_delivery_status['amount_need'] <= 0)
                                            <span
                                                class="text-muted fs-16">{{ translate('you_Get_Free_Delivery_Bonus') }}</span>
                                        @else
                                            <span
                                                class="need-for-free-delivery font-bold">{{ \App\Utils\Helpers::currency_converter($free_delivery_status['amount_need']) }}</span>
                                            <span
                                                class="text-muted fs-16">{{ translate('add_more_for_free_delivery') }}</span>
                                        @endif
                                    </div>
                                    <div class="progress free-delivery-progress">
                                        <div class="progress-bar" role="progressbar"
                                             style="width: {{ $free_delivery_status['percentage'] }}%"
                                             aria-valuenow="{{ $free_delivery_status['percentage'] }}" aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>
                            @endif

                        @endforeach
                </div>


                <div class="d-flex d-md-none flex-column mt-4 gap-3">
                    @foreach($cart as $group_key=>$group)
                            <?php
                            $physical_product = false;
                            $total_shipping_cost = 0;
                            foreach ($group as $row) {
                                if ($row->product_type == 'physical') {
                                    $physical_product = true;
                                }
                                if ($row->product_type == 'physical' && $row->shipping_type != "order_wise") {
                                    $total_shipping_cost += $row->shipping_cost;
                                }
                            }
                            ?>
                        @foreach($group as $cart_key=>$cartItem)
                            @if ($shippingMethod=='inhouse_shipping')
                                    <?php
                                    $admin_shipping = \App\Models\ShippingType::where('seller_id', 0)->first();
                                    $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                                    ?>
                            @else
                                    <?php
                                    if ($cartItem->seller_is == 'admin') {
                                        $admin_shipping = \App\Models\ShippingType::where('seller_id', 0)->first();
                                        $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                                    } else {
                                        $seller_shipping = \App\Models\ShippingType::where('seller_id', $cartItem->seller_id)->first();
                                        $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                                    }
                                    ?>
                            @endif
                            @if($cart_key==0)
                                <div class="--bg-6 border-0 rounded py-2 px-2 px-sm-3 ">
                                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between ">
                                        <div class="flex-grow-1">
                                            <div class="d-flex">
                                                @if($cartItem->seller_is=='admin')
                                                    <a href="{{route('shopView',['id'=>0])}}" class="cart-shop">
                                                        <img loading="lazy" alt="{{ translate('shop') }}"
                                                             src="{{ getValidImage(path: "storage/app/public/company/".$web_config['fav_icon']->value, type: 'logo')}}">
                                                        <h6 class="name text-base text-nowrap w-100">{{$web_config['name']->value}}</h6>
                                                    </a>
                                                @else
                                                    <a href="{{route('shopView',['id'=>$cartItem->seller_id])}}"
                                                       class="cart-shop">
                                                        <img loading="lazy" alt="{{ translate('shop') }}"
                                                             src="{{ getValidImage(path: 'storage/app/public/shop/'.($cartItem->seller->shop->image ?? ''), type: 'shop') }}">
                                                        <h6 class="name text-base text-nowrap w-100">{{ $cartItem->seller->shop->name}}</h6>
                                                    </a>
                                                @endif

                                                    <?php
                                                    $verify_status = \App\Utils\OrderManager::minimum_order_amount_verify($request, $group_key);
                                                    ?>

                                                @if ($verify_status['minimum_order_amount'] > $verify_status['amount'])
                                                    <span
                                                        class="ps-2 text-danger pulse-button minimum_Order_Amount_message"
                                                        data-bs-title="{{ translate('minimum_Order_Amount') }} {{ \App\Utils\Helpers::currency_converter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{ getWebConfig(name: 'company_name') }} @else {{ \App\Utils\get_shop_name($cartItem['seller_id']) }} @endif">
                                                    <i class="bi bi-info-circle"></i>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                            @php($choosen_shipping=\App\Models\CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first())
                                            @if(isset($choosen_shipping)==false)
                                                @php ( $choosen_shipping['shipping_method_id']= 0 )
                                            @endif

                                            @php($shippings=\App\Utils\Helpers::get_shipping_methods($cartItem['seller_id'],$cartItem['seller_is']))

                                            @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                                <div class="max-sm-100px bg-white select-method-border rounded  py-2">
                                                    <div class="d-flex overflow-hidden">
                                                        <div class="flex-middle flex-nowrap fw-semibold text-dark mx-2">
                                                            <i class="bi bi-truck"></i>
                                                        </div>

                                                        <div class="dropdown">
                                                            <a class="text-dark text-truncate" href="javascript:" data-bs-toggle="dropdown">
                                                                    <?php
                                                                    $shippings_title = translate('choose_shipping_method');
                                                                    foreach ($shippings as $shipping) {
                                                                        if ($choosen_shipping['shipping_method_id'] == $shipping['id']) {
                                                                            $shippings_title = ucfirst($shipping['title']) . ' ( ' . $shipping['duration'] . ' ) ' . \App\Utils\Helpers::currency_converter($shipping['cost']);
                                                                        }
                                                                    }
                                                                    ?>
                                                                {{ $shippings_title }}
                                                                <i class="ms-1 text-small bi bi-chevron-down"></i>
                                                            </a>
                                                            <div class="dropdown-menu __dropdown-menu">
                                                                <ul class="">
                                                                    @foreach($shippings as $shipping)
                                                                        <li class="cursor-pointer text-dark px-3 py-1 set_shipping_id_function"
                                                                            data-id="{{$shipping['id']}}"
                                                                            data-cartgroup="{{$cartItem['cart_group_id']}}"
                                                                        >
                                                                            {{ucfirst($shipping['title']).' ( '.$shipping['duration'].' ) '.\App\Utils\Helpers::currency_converter($shipping['cost'])}}
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class=" bg-white select-method-border rounded  py-2">
                                                <div class="d-flex ">
                                                    <div
                                                        class="flex-middle flex-nowrap fw-semibold text-dark mx-3 text-capitalize">
                                                        <i class="bi bi-truck"></i>
                                                        {{ translate('shipping_cost') }} :
                                                    </div>
                                                    <div class="">
                                                        <span>{{\App\Utils\Helpers::currency_converter($total_shipping_cost)}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                        @endforeach
                            <?php
                            $physical_product = false;
                            foreach ($group as $row) {
                                if ($row->product_type == 'physical') {
                                    $physical_product = true;
                                }
                            }
                            ?>
                        @foreach($group as $key=>$cartItem)
                            @php($product = $cartItem->product)

                            <form class="cart add_to_cart_form{{$cartItem['id']}}"
                                  id="add_to_cart_form_mobile{{$cartItem['id']}}"
                                  action="{{route('cart.update-variation')}}"
                                  data-redirecturl="{{route('checkout-details')}}"
                                  data-varianturl="{{ route('cart.variant_price') }}"
                                  data-errormessage="{{translate('please_choose_all_the_options')}}"
                                  data-outofstock="{{translate('sorry').', '.translate('out_of_stock')}}.">
                                @csrf
                                <div class="d-flex gap-3 pb-3 border-bottom justify-content-between align-items-center">
                                    <input type="text" name="id" value="{{ $cartItem->id }}" hidden>
                                    <input type="text" name="product_id"
                                           value="{{ isset($product) ? $product->id : ''}}" hidden>
                                    <div class="cart-product">
                                        <label class="form-check">
                                            @if (isset($product))
                                                <img loading="lazy" alt="{{ translate('product') }}"
                                                     src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
                                            @else
                                                <img loading="lazy"
                                                    src="{{ theme_asset('assets/img/image-place-holder.png') }}"
                                                    alt="{{ translate('product') }}">

                                            @endif
                                        </label>
                                        <div class="cont d-flex flex-column gap-1">
                                            <a href="{{ isset($product) ? route('product',$product['slug']) : 'javascript:'}}"
                                               class="name text-title">{{ isset($product) ? $product->name : 'N/a' }}</a>
                                            <div class="d-flex column-gap-1">
                                                <span>{{ translate('price') }}</span> <span>:</span> <strong
                                                    class="product_price{{$cartItem['id']}}">{{ \App\Utils\Helpers::currency_converter($cartItem->price) }}</strong>
                                            </div>
                                            <div class="d-flex column-gap-1">
                                                @if (isset($product->category))
                                                    <span>{{ translate('category') }} </span> <span>:</span>
                                                    <strong>{{ isset($product->category) ? $product->category->name:'' }}</strong>
                                                @endif
                                            </div>
                                            @if (isset($product))
                                                <div class="d-flex column-gap-1">
                                                    @if ($cartItem['discount'] > 0 )
                                                        <span>{{ translate('discount') }}</span> <span>:</span>
                                                        <strong
                                                            class="product_discount{{$cartItem['id']}}">{{ \App\Utils\Helpers::currency_converter($cartItem['discount']*$cartItem['quantity']) }}</strong>
                                                    @else
                                                        <span>{{ translate('discount') }}</span> <span>:</span>
                                                        <span
                                                            class="badge text-capitalize badge-soft-secondary discount{{$cartItem['id']}}">{{translate('no_discount')}}</span>
                                                    @endif
                                                </div>
                                                <div class="d-flex column-gap-1">
                                                    <span>{{ translate('total_price') }}</span> <span>:</span>
                                                    <strong>{{ \App\Utils\Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity']) }}</strong>
                                                </div>

                                                @if ($product->product_type == "physical")
                                                    @if ( $shipping_type != 'order_wise' )
                                                        <div class="d-flex column-gap-1">
                                                            <span>{{ translate('shipping_cost') }}</span> <span>:</span>
                                                            <strong>{{ \App\Utils\Helpers::currency_converter($cartItem['shipping_cost']) }}</strong>
                                                        </div>
                                                    @endif
                                                    <div class="column-gap-3">
                                                        @if (!empty(json_decode($product->colors)))
                                                            <div class="d-flex column-gap-1">
                                                                <span>{{ translate('color') }} </span> <span>:</span>
                                                                <select
                                                                    class="no-border-select text-title variants-class{{$key}} update_add_to_cart_by_variation_mobile"
                                                                    data-id="{{$cartItem['id']}}" name="color">
                                                                    @foreach (json_decode($product->colors) as $k=>$value)
                                                                        <option
                                                                            value="{{ $value }}"{{ $cartItem->color == $value ? 'selected':'' }}>{{\App\Utils\get_color_name($value)}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif
                                                        @php($variations = json_decode($cartItem->variations,true))
                                                        @foreach (json_decode($product->choice_options) as $k => $choice)
                                                            <div class="d-flex column-gap-1">
                                                                <span> {{ translate( $choice->title )}} </span>
                                                                <span>:</span>
                                                                <select
                                                                    class="no-border-select text-title variants-class{{$key}} update_add_to_cart_by_variation_mobile"
                                                                    data-id="{{$cartItem['id']}}"
                                                                    name="{{$choice->name}}">
                                                                    @foreach ($choice->options as $value)
                                                                        <option
                                                                            value="{{ trim($value) }}" {{in_array(trim($value),$variations,true) ? 'selected' : ''}}>{{ ucwords($value) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @else
                                                N/a
                                            @endif
                                        </div>
                                    </div>
                                    @php($minimum_order=\App\Utils\ProductManager::get_product($cartItem['product_id']))
                                    @if($minimum_order)
                                        <div class="quantity quantity--style-two d-flex flex-column align-items-center">
                                            <div
                                                class="quantity__minus cart-qty-btn updateCartQuantityListMobile_cart_data"
                                                data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                data-cart="{{ $cartItem['id'] }}" data-value="-1"
                                                data-action="{{ $cartItem['quantity'] == $minimum_order->minimum_order_qty ? 'delete':'minus' }}"
                                            >
                                                <i class="{{ $cartItem['quantity'] == (isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1) ? 'bi bi-trash3-fill text-danger fs-10' : 'bi bi-dash-lg' }}"></i>
                                            </div>
                                            <input type="text"
                                                   class="updateCartQuantityListMobile_cart_data quantity__qty cart-qty-input form-control cart-quantity-mobile{{$cartItem['id']}} cartQuantity{{$cartItem['id']}}"
                                                   value="{{$cartItem['quantity']}}" name="quantity"
                                                   id="cartQuantityMobile{{$cartItem['id']}}"
                                                   data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                   data-cart="{{ $cartItem['id'] }}" data-value="0" data-action=""
                                                   data-min="{{ isset($cartItem->product->minimum_order_qty) ? $cartItem->product->minimum_order_qty : 1 }}">

                                            <div
                                                class="quantity__plus cart-qty-btn updateCartQuantityListMobile_cart_data"
                                                data-minorder="{{ $minimum_order->minimum_order_qty }}"
                                                data-cart="{{ $cartItem['id'] }}" data-value="1" data-action="">
                                                <i class="bi bi-plus "></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="quantity quantity--style-two d-flex flex-column align-items-center">
                                            <div class="cart-qty-btn updateCartQuantityList_cart_data"
                                                 data-minorder="{{ $cartItem['quantity']+1 }}"
                                                 data-cart="{{ $cartItem['id'] }}"
                                                 data-value="-{{$cartItem['quantity']}}" data-action="delete">
                                                <i class="bi bi-trash3-fill text-danger fs-10"></i>
                                            </div>
                                            <input type="text"
                                                   class="quantity__qty cart-qty-input form-control cart-quantity-mobile{{$cartItem['id']}} cartQuantity{{$cartItem['id']}} updateCartQuantityList_cart_data"
                                                   data-minorder="{{ $minimum_order->minimum_order_qty ?? 1 }}"
                                                   data-cart="{{ $cartItem['id'] }}" data-value="0" data-action=""
                                                   value="{{$cartItem['quantity']}}" name="quantity"
                                                   id="cartQuantityMobile{{$cartItem['id']}}"
                                                   data-min="{{$cartItem['quantity']}}" disabled>
                                            <div class="cart-qty-btn" disabled
                                                 title="{{ translate('product_not_available') }}">
                                                <i class="bi bi-exclamation-circle text-danger"></i>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </form>
                        @endforeach

                        @if ($free_delivery_status['status'] && (session()->missing('coupon_type') || session('coupon_type') !='free_delivery'))
                            <div class="free-delivery-area px-3 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img loading="lazy"
                                        src="{{ theme_asset('assets/img/free-shipping.svg') }}"
                                         alt="{{ translate('free_shipping') }}" width="40">
                                    @if ($free_delivery_status['amount_need'] <= 0)
                                        <span
                                            class="text-muted fs-16">{{ translate('you_Get_Free_Delivery_Bonus') }}</span>
                                    @else
                                        <span
                                            class="need-for-free-delivery font-bold">{{ \App\Utils\Helpers::currency_converter($free_delivery_status['amount_need']) }}</span>
                                        <span
                                            class="text-muted fs-16">{{ translate('add_more_for_free_delivery') }}</span>
                                    @endif
                                </div>
                                <div class="progress free-delivery-progress">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{ $free_delivery_status['percentage'] }}%"
                                         aria-valuenow="{{ $free_delivery_status['percentage'] }}" aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>


                <div>
                    @if($shippingMethod=='inhouse_shipping')
                            <?php
                            $physical_product = false;
                            foreach ($cart as $group_key => $group) {
                                foreach ($group as $row) {
                                    if ($row->product_type == 'physical') {
                                        $physical_product = true;
                                    }
                                }
                            }

                            $admin_shipping = \App\Models\ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                            ?>
                        @if ($shipping_type == 'order_wise' && $physical_product)
                            @php($shippings=\App\Utils\Helpers::get_shipping_methods(1,'admin'))
                            @php($choosen_shipping=\App\Models\CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first())

                            @if(isset($choosen_shipping)==false)
                                @php($choosen_shipping['shipping_method_id']=0)
                            @endif
                            <div class="row">
                                <div class="col-12">
                                    <select
                                        class="form-control bg-transparent text-dark text-truncate outline-custom-remove form-select set_shipping_onchange">
                                        <option>{{translate('choose_shipping_method')}}</option>
                                        @foreach($shippings as $shipping)
                                            <option
                                                value="{{$shipping['id']}}" {{$choosen_shipping['shipping_method_id']==$shipping['id']?'selected':''}}>
                                                {{$shipping['title'].' ( '.$shipping['duration'].' ) '.\App\Utils\Helpers::currency_converter($shipping['cost'])}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    @endif
                    <form method="get">
                        <div class="form-group mt-3">
                            <div class="row">
                                <div class="col-12">
                                    <label for="order_note" class="form--label mb-2">{{translate('order_note')}} <span
                                            class="form-label">({{translate('optional')}})</span></label>
                                    <textarea class="form-control w-100" rows="5" id="order_note"
                                              name="order_note">{{ session('order_note')}}</textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="col-lg-4 ps-lg-4 ps-xl-5">
                @include('theme-views.partials._total-cost', ['product_null_status'=>$product_null_status])
            </div>

        </div>
    </div>
@else
    <div class="d-flex justify-content-center align-items-center">
        <h4 class="text-danger text-capitalize">{{ translate('cart_empty') }}</h4>
    </div>
@endif

@push('script')
    <script src="{{ theme_asset('assets/js/cart-details.js') }}"></script>
@endpush

