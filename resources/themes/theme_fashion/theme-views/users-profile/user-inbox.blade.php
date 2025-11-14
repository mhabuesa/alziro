@extends('theme-views.layouts.app')

@section('title', translate('my_inbox').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')
            <div class="tab-pane" id="inbox">
                <div class="__chat-area">
                    <div class="__chat-menu">
                        <h5 class="title">{{ translate('messages') }}</h5>
                        <form class="position-relative search--group mb-2">
                            <button type="submit" class="btn floating-icon"><i class="bi bi-search"></i></button>
                            <input type="text" id="myInput" class="form-control btn-pill --block-size-35"
                                   placeholder="{{ translate('search') }}...">
                        </form>
                        <ul class="nav nav-tabs nav--tabs-3">
                            <li class="nav-item">
                                <a href="{{route('chat', ['type' => 'seller'])}}"
                                   class="nav-link {{Request::is('chat/seller')?'active':''}}">{{ translate('vendor') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('chat', ['type' => 'delivery-man'])}}"
                                   class="text-capitalize nav-link {{Request::is('chat/delivery-man')? 'active':''}}">{{ translate('delivery_man') }}</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active ">
                                <ul class="nav nav-tabs nav-tabs-menu">
                                    @if (isset($unique_shops))
                                        @foreach($unique_shops as $key=>$shop)
                                            @php($type = $shop->delivery_man_id ? 'delivery-man' : 'seller')
                                            @php($unique_id = $shop->delivery_man_id ?? $shop->shop_id)
                                            <div class="nav-item">
                                                <a href="javascript:"
                                                   data-linkpath="{{route('chat', ['type' => $type])}}/?id={{$unique_id}}"
                                                   class="thisIsALinkElement chat_list nav-link {{($last_chat->delivery_man_id==$unique_id || $last_chat->shop_id==$unique_id) ? 'active' : ''}}"
                                                   id="user_{{$unique_id}}">

                                                    @if($shop->delivery_man_id)
                                                        <img loading="lazy" class="img" alt="{{ translate('user') }}"
                                                             src="{{ getValidImage(path: 'storage/app/public/delivery-man/'.$shop->image, type: 'avatar') }}">
                                                    @else
                                                        <img loading="lazy" class="img" alt="{{ translate('user') }}"
                                                             src="{{ getValidImage(path: 'storage/app/public/shop/'.$shop->image, type: 'shop') }}">
                                                    @endif

                                                    <div class="content">
                                                        <div class="d-flex align-items-center">
                                                            <h5 class="name "
                                                                id="{{$unique_id}}">{{$shop->f_name? $shop->f_name. ' ' . $shop->l_name: $shop->name}}
                                                            </h5>
                                                            <span
                                                                class="date">{{date('M d',strtotime($shop->created_at))}}</span>
                                                        </div>
                                                        <div class="msg mt-1">
                                                            {{$shop->seller_email ?: $shop->email}}
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="__chat-content">
                        @if(isset($last_chat))
                            <div class="tab-content">
                                <div class="tab-pane fade show active ">
                                    <div class="__chat-content-header">
                                        <a href="javascript:" class="chat-author">
                                            @if($last_chat->deliveryMan)
                                                <img loading="lazy" class="img" alt="{{ translate('user') }}"
                                                     src="{{ getValidImage(path: 'storage/app/public/delivery-man/'.($last_chat->deliveryMan->image), type: 'avatar') }}">
                                            @else
                                                <img loading="lazy" class="img" alt="{{ translate('user') }}"
                                                     src="{{ getValidImage(path: 'storage/app/public/shop/'.($last_chat->shop->image), type: 'shop') }}">
                                            @endif

                                            <div class="content">
                                                <h5 class="name mt-11">{{$last_chat->deliveryMan?$last_chat->deliveryMan->f_name.' '.$last_chat->deliveryMan->l_name : $last_chat->shop->name  }}</h5>

                                            </div>
                                        </a>
                                    </div>
                                    <div class="__chat-content-body scroll_msg" id="show_msg">
                                        <ul class="__chat-content-body-messages msg_history">
                                            @if (isset($chattings))
                                                @foreach($chattings as $key => $chat)

                                                    @if ($chat->sent_by_seller?: $chat->sent_by_delivery_man)
                                                        <li class="incoming">
                                                            @if($chat->sent_by_delivery_man)
                                                                <img loading="lazy" class="img" alt="{{ translate('user') }}"
                                                                     src="{{ getValidImage(path: 'storage/app/public/delivery-man/'.$chat->image, type: 'avatar') }}">
                                                            @else
                                                                <img loading="lazy" class="img" alt="{{ translate('user') }}"
                                                                     src="{{ getValidImage(path: 'storage/app/public/shop/'.$chat->image, type: 'shop') }}">
                                                            @endif

                                                            <div class="msg-area">
                                                                @if($chat->message)
                                                                    <div class="msg">
                                                                        {{$chat->message}}
                                                                    </div>
                                                                @endif

                                                                @if (json_decode($chat['attachment']) !=null)
                                                                    <div class="d-flex flex-wrap g-2 gap-2 justify-content-start">
                                                                        @foreach (json_decode($chat['attachment']) as $index => $photo)
                                                                            @if(file_exists(base_path("storage/app/public/chatting/".$photo)))
                                                                                <img loading="lazy" src="{{ getValidImage(path: "storage/app/public/chatting/".$photo, type: 'product') }}" height="100"
                                                                                class="rounded" alt="{{ translate('verification') }}">
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                @endif

                                                                @if ($chat->created_at->diffInDays(\Carbon\Carbon::now()) < 7)
                                                                    <small class="date">{{ date('D h:i:A',strtotime($chat->created_at)) }}</small>
                                                                @else
                                                                    <small class="date">{{ date('d M Y h:i:A',strtotime($chat->created_at)) }}</small>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @else
                                                        <li class="outgoing" id="outgoing_msg">
                                                            <div class="msg-area">
                                                                @if($chat->message)
                                                                <div class="msg">
                                                                    {{$chat->message}}
                                                                </div>
                                                                @endif

                                                                @if (json_decode($chat['attachment']) !=null)
                                                                    <div class="d-flex flex-wrap g-2 gap-2 justify-content-start">
                                                                        @foreach (json_decode($chat['attachment']) as $index => $photo)
                                                                            @if(file_exists(base_path("storage/app/public/chatting/".$photo)))
                                                                                <img loading="lazy" src="{{ getValidImage(path: "storage/app/public/chatting/".$photo, type: 'product') }}" height="100"
                                                                                class="rounded" alt="{{ translate('verification') }}">
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                @endif

                                                                @if ($chat->created_at->diffInDays(\Carbon\Carbon::now()) < 7)
                                                                    <small class="date">{{ date('D h:i:A',strtotime($chat->created_at)) }}</small>
                                                                @else
                                                                    <small class="date">{{ date('d M Y h:i:A',strtotime($chat->created_at)) }}</small>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @endif
                                                @endForeach
                                                <div id="down"></div>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="__chat-content-footer">
                                <form id="myForm">
                                    @csrf
                                    <div id="view" class="view-img"></div>
                                    <div class="d-flex align-items-center">
                                        @if( Request::is('chat/seller') )
                                            <input type="text" id="hidden_value" hidden
                                                   value="{{$last_chat->shop_id}}" name="">
                                            @if($last_chat->shop)
                                                <input type="text" id="seller_value" hidden
                                                       value="{{$last_chat->shop->seller_id}}" name="">
                                            @endif
                                        @elseif( Request::is('chat/delivery-man') )
                                            <input type="text" id="hidden_value_dm" hidden
                                                   value="{{$last_chat->delivery_man_id}}" name="">
                                        @endif

                                        <textarea class="form-control ps-4 w-0 flex-grow-1" id="msgInputValue"
                                                  placeholder="{{translate('start_a_new_message')}}"></textarea>

                                        <button type="submit" class="btn ms-1" id="msgSendBtn">
                                            <img loading="lazy" src="{{ theme_asset('assets/img/icons/reply.png') }}" alt="{{ translate('reply') }}">
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="text-center pt-5 w-100">
                                <div class="text-center mb-5">
                                    <img loading="lazy" src="{{ theme_asset('assets/img/icons/nodata.svg') }}" alt="{{ translate('no_conversation_found') }}">
                                    <h5 class="my-3 pt-1 text-muted">
                                        {{translate('no_conversation_found')}}!
                                    </h5>
                                </div>
                            </div>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <span class="messages-storage"
        data-messagesroute="{{ route('messages_store') }}"
        data-textnow="{{ translate('now') }}">
    </span>
@endsection

@push('script')
<script src="{{ theme_asset('assets/js/user-inbox.js') }}"></script>
@endpush
