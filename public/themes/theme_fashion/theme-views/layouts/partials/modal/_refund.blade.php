<div class="modal fade" id="refundModal{{$id}}" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header px-sm-5">
                <h1 class="modal-title fs-5 text-capitalize" id="refundModalLabel">{{translate('refund_request')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 pt-4 span--inline">
                <div class="d-flex flex-column flex-sm-row flex-wrap gap-4 justify-content-between mb-4">
                    <div class="cart-product">
                        <label class="form-check">
                            <img loading="lazy" alt="{{ translate('product') }}"
                                 src="{{ getValidImage(path: 'storage/app/public/product/thumbnail/'.$product['thumbnail'], type: 'product') }}">
                        </label>
                        <div class="cont">
                            <a href="{{route('product',[$product['slug']])}}" class="name text-title">
                                {{isset($product['name']) ? Str::limit($product['name'],40) : ''}}
                            </a>
                            <div class="d-flex column-gap-1">
                                <span>{{translate('price')}}</span> <span>:</span>
                                <strong>{{\App\Utils\Helpers::currency_converter($order_details->price)}}</strong>
                            </div>
                            @if ($product['product_type'] == "physical" && !empty(json_decode($detail['variation'])))
                                <div class="d-flex flex-wrap column-gap-3">
                                    @foreach (json_decode($detail['variation']) as $key => $value)
                                        <div class="d-flex column-gap-1">
                                            <span>{{ translate($key)}} </span>
                                            <span>:&nbsp;{{ucwords($value)}}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-1 fs-12">
                        <span>
                            <span>{{ translate('Qty')}}</span>
                            <span>:</span>
                            <span>{{$order_details->qty}}</span>
                        </span>
                        <span>
                            <span>{{ translate('Price')}}</span>
                            <span>:</span>
                            <span>{{\App\Utils\Helpers::currency_converter($order_details->price)}}</span>
                        </span>
                        <span>
                            <span>{{ translate('Discount')}}</span>
                            <span>:</span>
                            <span>{{\App\Utils\Helpers::currency_converter($order_details->discount)}}</span>
                        </span>
                        <span>
                            <span>{{ translate('Tax')}}</span>
                            <span>:</span>
                            <span>{{\App\Utils\Helpers::currency_converter($order_details->tax)}}</span>
                        </span>
                    </div>
                    <?php
                    $total_product_price = 0;
                    foreach ($order->details as $key => $or_d) {
                        $total_product_price += ($or_d->qty * $or_d->price) + $or_d->tax - $or_d->discount;
                    }
                    $refund_amount = 0;
                    $subtotal = ($order_details->price * $order_details->qty) - $order_details->discount + $order_details->tax;

                    $coupon_discount = ($order->discount_amount * $subtotal) / $total_product_price;

                    $refund_amount = $subtotal - $coupon_discount;
                    ?>
                    <div class="d-flex flex-column gap-1 fs-12">
                        <span>
                            <span>{{translate('subtotal')}}</span>
                            <span>:</span>
                            <span>{{\App\Utils\Helpers::currency_converter($subtotal)}}</span>
                        </span>
                        <span>
                            <span>{{translate('coupon_discount')}}</span>
                            <span>:</span>
                            <span>{{\App\Utils\Helpers::currency_converter($coupon_discount)}}</span>
                        </span>
                        <span>
                            <span>{{translate('total_refundable_amount')}}</span>
                            <span>:</span>
                            <span> {{\App\Utils\Helpers::currency_converter($refund_amount)}}</span>
                        </span>
                    </div>
                </div>
                <form action="{{route('refund-store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="comment" class="form--label mb-2">{{translate('refund_reason')}}</label>
                        <input type="hidden" name="order_details_id" value="{{$order_details->id}}">
                        <input type="hidden" name="amount" value="{{$refund_amount}}">
                        <textarea name="refund_reason" id="comment" class="form-control" rows="4"
                                  placeholder="{{translate('refund_reason')}}"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form--label mb-2">{{translate('attachment')}}</label>
                        <div class="d-flex flex-column gap-3">
                            <div class="row coba_refund"></div>

                            <div class="text-muted">{{translate('file_type_jpg_jpeg_png')}} .
                                {{translate('maximum_size_2MB')}}</div>
                        </div>
                    </div>
                    <div class="modal-footer gap-3 px-sm-5 py-4">
                        <button type="button" class="btn btn-base secondary-color m-0"
                                data-bs-dismiss="modal">{{translate('close')}}</button>
                        <button type="submit" class="btn btn-base m-0">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
