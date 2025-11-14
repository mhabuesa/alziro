@extends('theme-views.layouts.app')

@section('title', translate('my_support_tickets').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')
            <div class="tab-pane " id="support">
                <div class="my-wallet-card">
                    <div class="d-flex left gap-2 justify-content-between">
                        <div class="media gap-3"></div>
                        <button class="btn btn-base border-base btn-outline-base mb-4" data-bs-toggle="modal"
                                data-bs-target="#reviewModal">{{translate('create_a_support_tickets')}}</button>
                    </div>
                    @foreach($supportTickets as $key=>$supportTicket)
                        <div class="ticket-card cursor-pointer thisIsALinkElement" data-linkpath="{{route('support-ticket.index',$supportTicket['id'])}}">
                            <div class="ticket-card-header">
                                <div class="ticket-card-header-author">
                                    <a class="rounded-circle overflow-hidden"
                                       href="{{route('support-ticket.index',$supportTicket['id'])}}">
                                        <img loading="lazy" alt="{{ translate('user') }}"
                                             src="{{ getValidImage(path: 'storage/app/public/profile/'.(auth('customer')->user()->image), type: 'avatar') }}">
                                    </a>

                                    <div class="content">
                                        <a class="d-flex" href="{{route('support-ticket.index',$supportTicket['id'])}}">
                                            <h6>{{ auth('customer')->user()->f_name }}
                                                &nbsp{{ auth('customer')->user()->l_name }}</h6>
                                        </a>
                                        <a href="javascript:">
                                            <small>{{ auth('customer')->user()['email'] }}</small>
                                        </a>
                                    </div>
                                    <div class="d-flex flex-wrap gap-1 mt-3 mt-md-1 w-100 ms-md-5 ps-md-4">
                                        <span
                                            @if($supportTicket->priority == 'Urgent')
                                                class="badge btn-pill --badge badge-soft-danger  "
                                            @elseif($supportTicket->priority == 'High')
                                                class="badge btn-pill --badge badge-soft-warning "
                                            @elseif($supportTicket->priority == 'Medium')
                                                class="badge btn-pill --badge badge-soft-success"
                                            @else
                                                class="badge btn-pill --badge badge-soft-base"
                                        @endif

                                        >{{ translate($supportTicket->priority) }}</span>
                                        <span
                                            class="badge btn-pill --badge badge-soft-base {{$supportTicket->status ==  'open' ? 'badge-soft-base' : 'badge-soft-danger'}}">
                                            {{ translate($supportTicket->status) }}
                                        </span>
                                        <span
                                            class="badge btn-pill --badge text-title">{{ translate($supportTicket->type) }}</span>
                                    </div>
                                </div>
                                @if($supportTicket->status != 'close')
                                    <a href="{{route('support-ticket.close',[$supportTicket['id']])}}">
                                        <div class="btn border-danger btn-outline-danger rounded ">
                                            <span
                                                class="font-semibold word-nobreak">{{ translate('close_ticket') }} </span>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            <div class="ticket-card-body text-text-2">
                                <small
                                    class="date text-start d-md-none">{{date('d M, Y H:i A',strtotime($supportTicket->created_at))}}</small>
                                <div class="info cs-width-90ch">{{ $supportTicket->subject }}</div>
                                <small
                                    class="date d-none d-md-block">{{date('d M, Y H:i A',strtotime($supportTicket->created_at))}}</small>
                            </div>
                        </div>
                    @endforeach
                    @if($supportTickets->count()>0)
                        <div class="d-flex justify-content-end w-100 overflow-auto mt-3" id="paginator-ajax">
                            {{$supportTickets->links() }}
                        </div>
                    @else
                        <div class="d-flex justify-content-center py-5">
                            <div>
                                <img loading="lazy" src="{{ theme_asset('assets/img/icons/nodata.svg') }}" alt="{{ translate('empty') }}">
                                <h6 class="text-muted pt-4 text-center">{{ translate('no_Ticket_Found') }}</h6>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header px-sm-5">
                        <h1 class="modal-title fs-5" id="reviewModalLabel">{{translate('submit_new_ticket')}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-sm-5">
                        <div class="mb-2">
                            <label class="form--label">{{translate('you_will_get_response_soon')}}.</label>
                        </div>
                        <form action="{{route('ticket-submit')}}" id="open-ticket" method="post">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="rating" class="form--label mb-2">{{ translate('subject') }}</label>
                                <input type="text" class="form-control" id="ticket-subject" name="ticket_subject"
                                       required>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 mb-4">
                                    <label for="rating" class="form--label mb-2">{{ translate('type') }}</label>
                                    <select id="ticket-type" name="ticket_type"
                                            class="form-select custom-transparent-bg" required>
                                        <option value="Website problem">{{translate('website_problem')}}</option>
                                        <option value="Partner request">{{translate('partner_request')}}</option>
                                        <option value="Complaint">{{translate('Complaint')}}</option>
                                        <option value="Info inquiry">{{translate('info_inquiry')}} </option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 mb-4">
                                    <label for="rating" class="form--label mb-2">{{ translate('priority') }}</label>
                                    <select id="ticket-priority" name="ticket_priority"
                                            class="form-select custom-transparent-bg" required>
                                        <option value>{{translate('choose_priority')}}</option>
                                        <option value="Urgent">{{translate('urgent')}}</option>
                                        <option value="High">{{translate('high')}}</option>
                                        <option value="Medium">{{translate('medium')}}</option>
                                        <option value="Low">{{translate('low')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="comment"
                                       class="form--label mb-2">{{translate('describe_your_issue')}}</label>
                                <textarea class="form-control" rows="4" id="ticket-description"
                                          name="ticket_description" placeholder="{{translate('leave_your_issue')}}"></textarea>
                            </div>
                            <div class="modal-footer gap-3 py-4 px-sm-5 ">
                                <button type="button" class="btn btn-danger m-0"
                                        data-bs-dismiss="modal">{{translate('close')}}</button>
                                <button type="submit" class="btn btn-primary m-0">{{ translate('submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
