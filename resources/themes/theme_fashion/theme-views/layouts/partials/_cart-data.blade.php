@foreach($cart as  $cartItem)
    @php($product=\App\Models\Product::active()->find($cartItem['product_id']))

    <li class="d-flex justify-content-between align-items-center gap-3 mb-3">
        <a href="{{route('product',$cartItem['slug'])}}" class="media gap-2 w-0 flex-grow-1">
            <div class="position-relative overflow-hidden rounded">
                @if ($product)
                    <img loading="lazy" width="60" alt="{{ translate('product') }}"
                         src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$cartItem['thumbnail'], type: 'product') }}">
                @else
                    <img loading="lazy" width="60" alt="{{ translate('product') }}"
                         src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$cartItem['thumbnail'], type: 'product') }}">
                    <span class="temporary-closed position-absolute d-flex align-content-center justify-content-center">
                        <span>{{translate('N/a')}}</span>
                    </span>
                @endif
            </div>

            <div class="info {{ !isset($product) ? 'blur-section':'' }}">
                <h6 class="name text-text-2 thisIsALinkElement"
                    data-linkpath="{{route('product',$cartItem['slug'])}}">{{Str::limit($cartItem['name'],30)}}</h6>
                <div class="text-secondary fs-12 lh-1.4"><span>{{ translate('price') }} : <strong
                                class="discount_price_of_{{ $cartItem['id']}}">{{\App\Utils\Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*(int)$cartItem['quantity'])}}</strong></span>
                    <div class="align-items-center column-gap-2">
                        @php($variations_index = 1)
                        @foreach (json_decode($cartItem['variations']) as $key=>$item)
                            @if ($variations_index <= 2)
                                <span>{{ ucfirst($key) }} : {{ucfirst($item)}}</span>
                                @php($variations_index += 1)
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </a>
        @if( isset($product->status) && $product->status == 1)
            <div class="quantity quantity--style-two d-flex align-items-center">
                <div class="quantity__minus cart-qty-btn updateCartQuantity_cart_data"
                     data-cart="{{ $cartItem['id'] }}" data-product="{{ $cartItem['product_id'] }}" data-value="-1"
                     data-action="minus">
                    <i class="{{ $cartItem['quantity'] == (isset($product->minimum_order_qty) ? $product->minimum_order_qty : 1) ? 'bi bi-trash3-fill text-danger fs-10' : 'bi bi-dash' }}"></i>
                </div>
                <input type="number"
                       class="quantity__qty cart-qty-input form-control cartQuantity{{$cartItem['id']}} updateCartQuantity_cart_data"
                       value="{{$cartItem['quantity']}}" name="quantity" id="cartQuantity{{$cartItem['id']}}"
                       data-cart="{{ $cartItem['id'] }}" data-product="{{ $cartItem['product_id'] }}" data-value="0"
                       data-action=""
                       data-min="{{ isset($product->minimum_order_qty) ? $product->minimum_order_qty : 1 }}"
                       autocomplete="off" required>

                <div class="quantity__plus cart-qty-btn updateCartQuantity_cart_data"
                     data-cart="{{ $cartItem['id'] }}" data-product="{{ $cartItem['product_id'] }}" data-value="1"
                     data-action="">
                    <i class="bi bi-plus "></i>
                </div>
            </div>
        @else
            <div class="quantity quantity--style-two d-flex align-items-center">
                <div class="cart-qty-btn updateCartQuantity_cart_data"
                     data-cart="{{ $cartItem['id'] }}" data-product="{{ $cartItem['product_id'] }}"
                     data-value="{{$cartItem['quantity']}}" data-action="minus">
                    <i class="bi bi-trash3-fill text-danger fs-10"></i>
                </div>
            </div>
        @endif
    </li>
@endforeach

@push('script')
    <script>
        "use strict";
        updateCartQuantity_cart_data();
    </script>
@endpush
