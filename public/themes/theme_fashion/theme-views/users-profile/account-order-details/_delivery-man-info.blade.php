@extends('theme-views.layouts.app')

@section('title', translate('my_order_details_delivery_man_info').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')
            <div class="card bg-section border-0">
                <div class="card-body p-lg-4">
                    @include('theme-views.partials._order-details-head',['order'=>$order])

                    <div class="mt-4 card border-0 bg-body">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center gap-4 flex-wrap">
                                @if($order->delivery_type == 'self_delivery' && isset($order->deliveryMan))
                                    <div class="media align-items-center gap-3">
                                        <div class="width-7-312rem">
                                            <img loading="lazy" alt="{{ translate('deliveryman') }}" class="rounded w-100"
                                                 src="{{ getValidImage(path: 'storage/app/public/delivery-man/'.($order->deliveryMan->image), type:'avatar')}}">
                                        </div>
                                        <div class="media-body d-flex flex-column gap-2">
                                            <h4 class="text-capitalize">{{$order->deliveryMan->f_name}}&nbsp{{$order->deliveryMan->l_name}}</h4>
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="text-star">
                                                    @php($avg_rating = isset($order->deliveryMan->rating[0]->average) ? $order->deliveryMan->rating[0]->average : 0)
                                                    @for($inc=1;$inc<=5;$inc++)
                                                        @if ($inc <= (int)$avg_rating)
                                                            <i class="bi bi-star-fill"></i>
                                                        @elseif ($avg_rating != 0 && $inc <= (int)$avg_rating + 1.1 && $avg_rating > ((int)$avg_rating))
                                                            <i class="bi bi-star-half"></i>
                                                        @else
                                                            <i class="bi bi-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-muted fw-semibold">({{number_format($avg_rating,1)}})</span>
                                            </div>
                                            <ul class="list-unstyled list-inline-dot fs-12 ">
                                                <li>{{$delivered_count}} {{translate('delivered_orders')}}</li>
                                                <li>{{$order->deliveryMan->review_count}} {{translate('reviews')}} </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-3">
                                        <button  class="btn btn-base" data-bs-toggle="modal" data-bs-target="#chatModal">
                                            <i class="bi bi-chat-square-fill"></i>
                                            {{translate('chat_with_delivery_man')}}
                                        </button>
                                        @if($order->order_type == 'default_type' && $order->order_status=='delivered' && $order->delivery_man_id)
                                            <button  class="btn btn-base"
                                                    data-bs-toggle="modal" data-bs-target="#reviewModal">
                                                <i class="bi bi-chat-square-fill"></i>
                                                {{translate('review')}}
                                            </button>
                                        @endif
                                    </div>
                                    <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                                        <div class="modal-dialog ">
                                            <div class="modal-content">
                                                <div class="modal-header px-sm-5">
                                                    <h1 class="modal-title fs-5" id="reviewModalLabel">{{translate('write_something')}}</h1>

                                                </div>
                                                <div class="modal-body px-sm-5">
                                                    <form action="{{route('messages_store')}}" method="post" id="chat-form" data-message="{{ translate('send_successfully') }}">
                                                        @csrf
                                                        @if($order->deliveryMan->id != 0)
                                                            <input value="{{$order->deliveryMan->id}}" name="delivery_man_id" hidden>
                                                        @endif
                                                        <textarea name="message" class="form-control" required></textarea>
                                                        <br>
                                                        @if($order->deliveryMan->id != 0)
                                                        <button class="btn btn-base m-0">{{translate('send')}}</button>
                                                        @else
                                                            <button class="btn btn-base m-0"
                                                            disabled>{{translate('send')}}</button>
                                                        @endif
                                                    </form>
                                                </div>
                                                <div class="modal-footer gap-3 py-4 px-sm-5 ">
                                                    <a href="{{route('chat',['type' => 'delivery-man'])}}" class="btn btn-base m-0">
                                                        {{translate('go_to_chatbox')}}
                                                    </a>
                                                    <button type="button" class="btn btn-base secondary-color m-0"
                                                            data-bs-dismiss="modal">{{translate('close')}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header px-sm-5">
                                                    <h1 class="modal-title fs-5" id="reviewModalLabel">{{translate('submit_a_review')}}</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{route('submit-deliveryman-review')}}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body px-sm-5">
                                                        <div class="form-group mb-4">
                                                            <label for="rating">{{translate('rating')}}</label>
                                                            <select name="rating" id="rating" class="form-select custom-transparent-bg">
                                                                <option value="1">{{translate('1')}}</option>
                                                                <option value="2">{{translate('2')}}</option>
                                                                <option value="3">{{translate('3')}}</option>
                                                                <option value="3">{{translate('4')}}</option>
                                                                <option value="4">{{translate('5')}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group mb-4">
                                                            <label for="comment">{{translate('comment')}}</label>
                                                            <input name="order_id" value="{{$order->id}}" hidden>

                                                            <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="{{translate('leave_a_comment')}}"></textarea>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer gap-3 py-4 px-sm-5 ">
                                                        <a href="{{ url()->previous() }}" class="btn btn-base secondary-color m-0" data-bs-dismiss="modal">{{translate('back')}}</a>
                                                        <button type="submit" class="btn btn-base m-0">{{translate('submit')}}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @elseif($order->delivery_type == 'third_party_delivery')
                                    <div class="media align-items-center gap-3">
                                        <div class="media-body d-flex flex-column gap-2">
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="card-body">
                                                    <h4 class="py-3">{{translate('3rd_party_delivery_information')}}</h4>
                                                    <address>
                                                        <dl class="mb-0 flexible-grid sm-down-1 d-flex text-nowrap cs-width-15rem">
                                                            <dt>{{translate('3rd_Party_Provider_Name')}}</dt>
                                                            <dd class="mx-2">{{$order->delivery_service_name}}</dd>
                                                        </dl>
                                                        @if($order->third_party_delivery_tracking_id !=null)
                                                        <dl class="mb-0 flexible-grid sm-down-1 d-flex text-nowrap cs-width-15rem">
                                                            <dt>{{translate('track_ID')}}</dt>
                                                            <dd class="mx-2">{{$order->third_party_delivery_tracking_id}}</dd>
                                                        </dl>
                                                        @endif
                                                    </address>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center pt-5 w-100">
                                        <div class="text-center mb-5">
                                            <img loading="lazy" src="{{ theme_asset('assets/img/icons/DeliveryMan.svg') }}" alt="{{ translate('deliveryman') }}">
                                            <h5 class="my-3 pt-1 text-muted">
                                                @if ($order->order_type == "POS")
                                                    <span>{{translate('this_order_is_a_POS_order.delivery_man_is_not_assigned_to_POS_orders')}}</span>
                                                @else
                                                    @if ($order->product_type_check =='digital')
                                                        {{translate('this_order_contains_one_or_more_digital_products.')}}
                                                        {{translate('delivery_man_is_not_assigned_for_digital_products')}}!
                                                    @else
                                                        {{translate('no_delivery_man_assigned_yet')}}!
                                                    @endif
                                                @endif
                                            </h5>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($order->delivery_type == 'self_delivery' && isset($order->deliveryMan))
                        @if (count($order->verificationImages)>0 && $order->verification_status == 1)
                            <div class="w-100 mt-4">
                                <div class="card border-0 bg-body">
                                    <div class="card-body">
                                        <h5 class="text-muted mb-3">
                                            <span class="text-base me-2"><i class="bi bi-camera"></i></span>
                                            {{ translate('picture_Upload_by') }} {{$order->deliveryMan->f_name}}&nbsp{{$order->deliveryMan->l_name}}
                                        </h5>

                                        <div class="d-flex flex-wrap gap-3">
                                            @foreach ($order->verificationImages as $image)
                                                @if(file_exists(base_path("storage/app/public/delivery-man/verification-image/".$image->image)))
                                                <a href="{{asset("storage/app/public/delivery-man/verification-image/".$image->image)}}" class="custom-image-popup">
                                                        <img loading="lazy" src="{{asset("storage/app/public/delivery-man/verification-image/".$image->image)}}"
                                                        height="100" alt="{{ translate('verification') }}">
                                                </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </section>
@endsection
