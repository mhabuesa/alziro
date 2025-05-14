@extends('theme-views.layouts.app')

@section('title', translate('support_ticket_inbox').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')
            <div class="card ov-hidden border-0 shadow-sm">
                <div class="bg-section rounded d-flex gap-3 flex-wrap align-items-start justify-content-between p-3 ">
                    <div class="media flex-wrap gap-3">
                        <div class="rounded-circle overflow-hidden width-5-312rem">
                            <img loading="lazy"
                                 src="{{getValidImage(path: 'storage/app/public/profile'.\App\Utils\customer_info()->image,type: 'avatar')}}"
                                 class="rounded w-100 other-store-logo " alt="{{ translate('products') }}">
                        </div>
                        <div class="media-body">
                            <div class="d-flex flex-column gap-1">
                                <div class="d-flex gap-2 align-items-center">
                                    <h6 class="">{{ \App\Utils\customer_info()->f_name }}
                                        &nbsp{{ \App\Utils\customer_info()->l_name }}</h6>
                                    <span
                                        @if($ticket->priority == 'Urgent')
                                            class="badge bg-danger rounded-pill"
                                        @elseif($ticket->priority == 'High')
                                            class="badge bg-warning rounded-pill"
                                        @elseif($ticket->priority == 'Medium')
                                            class="badge bg-primary rounded-pill"
                                        @else
                                            class="badge bg-base rounded-pill"
                                        @endif
                                        >{{ translate($ticket->priority) }}</span>
                                </div>
                                <div class="fs-12 text-muted">{{ \App\Utils\customer_info()['email'] }}</div>
                                <div class="d-flex flex-wrap align-items-center column-gap-4">
                                    <div class="d-flex align-items-center gap-2 gap-md-3">
                                        <div class="fw-bold">{{translate('status')}}:</div>
                                        <span
                                            class="{{$ticket->status ==  'open' ? ' text-info ' : 'text-danger'}} fw-semibold">{{ translate($ticket->status) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 gap-md-3">
                                        <div class="fw-bold">{{translate('priority')}}:</div>
                                        <span
                                            @if($ticket->priority == 'Urgent')
                                                class="text-danger fw-bold"
                                            @elseif($ticket->priority == 'High')
                                                class="text-warning fw-bold "
                                            @elseif($ticket->priority == 'Medium')
                                                class="text-primary fw-bold"
                                            @else
                                                class="text-success fw-bold"
                                            @endif> {{ translate($ticket->priority) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($ticket->status != 'close')
                        <a href="{{route('support-ticket.close',[$ticket['id']])}}"
                           class="btn btn-outline-danger">{{ translate('close_this_ticket') }}</a>
                    @endif
                </div>

                <div class="__chat-content-body msg_history">
                    <ul class="__chat-content-body-messages ">
                        <li class="outgoing">
                            <div class="msg-area">
                                <div class="msg">
                                    {{ $ticket['description']}}
                                </div>
                                <small class="date">{{ date('D h:i:A',strtotime($ticket['created_at'])) }}</small>
                            </div>
                        </li>
                        @foreach($ticket->conversations as $conversation)
                            @if($conversation['admin_id'] != null)
                                <li class="incoming">
                                    @php($admin=$conversation->adminInfo)
                                    <img loading="lazy"
                                        src="{{ getValidImage(path: 'storage/app/public/admin/'.($admin['image'] ?? ''), type: 'avatar') }}"
                                        class="img mb-2" alt="{{ translate('user') }}">
                                    <div class="msg-area">
                                        @if($conversation['admin_message'])
                                            <div class="msg">
                                                {{ $conversation['admin_message']}}
                                            </div>
                                        @endif
                                        @if ($conversation['attachment'] !=null && count(json_decode($conversation['attachment'])) > 0)
                                            <div class="d-flex flex-wrap g-2 gap-2 justify-content-start">
                                                @foreach (json_decode($conversation['attachment']) as $key => $photo)
                                                    @if(file_exists(base_path("storage/app/public/support-ticket/".$photo)))
                                                    <img src="{{ getValidImage(path: 'storage/app/public/support-ticket/'.$photo, type: 'product') }}"
                                                    height="100" class="rounded" alt="{{ translate('ticket') }}">
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif

                                        <small class="date">{{ date('D h:i:A',strtotime($conversation['created_at'])) }}</small>
                                    </div>
                                </li>
                            @else
                                <li class="outgoing">
                                    <div class="msg-area">
                                        @if($conversation['customer_message'])
                                        <div class="msg">
                                            {{ $conversation['customer_message']}}
                                        </div>
                                        @endif
                                        @if ($conversation['attachment'] !=null && count(json_decode($conversation['attachment'])) > 0)
                                            <div class="d-flex flex-wrap g-2 gap-2 justify-content-end">
                                                @foreach (json_decode($conversation['attachment']) as $key => $photo)
                                                    @if(file_exists(base_path("storage/app/public/support-ticket/".$photo)))
                                                    <img src="{{ getValidImage(path: 'storage/app/public/support-ticket/'.$photo, type: 'product') }}"
                                                    height="100" class="rounded" alt="{{ translate('ticket') }}">
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        <small class="date">{{ date('D h:i:A',strtotime($conversation['created_at'])) }}</small>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="bg-section p-3">
                    <form action="{{route('support-ticket.comment',[$ticket['id']])}}" method="post">
                        @csrf
                        <div id="view" class="view-img"></div>
                        <div class="d-flex align-items-center">
                            <textarea
                                class="form-control ps-4 w-0 flex-grow-1" id="msgInputValueTicket" name="comment"
                                placeholder="{{translate('start_typing')}}"></textarea>
                            <button type="submit" class="btn ms-1">
                                <img loading="lazy" src="{{theme_asset('assets/img/icons/reply.png')}}" alt="{{ translate('comment') }}">
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
@endsection

@push('script')
<script src="{{ theme_asset('assets/js/ticket-view.js') }}"></script>
@endpush



