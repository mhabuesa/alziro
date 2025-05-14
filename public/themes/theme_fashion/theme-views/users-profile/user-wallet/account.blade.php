@extends('theme-views.layouts.app')

@section('title', translate('account').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
<section class="user-profile-section section-gap pt-0">
    <div class="container">
        @include('theme-views.partials._profile-aside')
        <div class="tab-content">
            @include('theme-views.users-profile.user-wallet._partial-my-wallet-nav-tab')
            <div class="tab-content">
                <div class="tab-pane fade show active" >
                    <div class="wallet-card">
                        <div class="wallet-card-header">
                            <div class="left">
                                <img loading="lazy" src="{{ theme_asset('assets/img/checkout/card-pos.png') }}" alt="">
                                <span>{{ translate('Bank_Card') }}</span>
                            </div>
                            <a href="#add-new-card" data-bs-toggle="modal" class="text-base" type="button">+ {{ translate('Add_New_Card') }}</a>
                        </div>
                        <div class="wallet-card-body">
                            <div class="table-responsive">
                                <table class="table __table __wallet-card-table __table-sm-break">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="d-flex align-items-center wallet-card-name min-w-180">
                                                    <span class="me-2 me-md-4">{{ translate('1') }}.</span>
                                                    <img loading="lazy" src="{{ theme_asset('assets/img/icons/visa.png') }}" alt="">
                                                    <div>{{ translate('Visa_Card') }}</div>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="ps-max-sm-5">
                                                    {{ ('02342 ***** 4234') }}
                                                </div>
                                            </th>
                                            <th class="action-buttons">
                                                <div class=" d-flex align-items-center justify-content-end column-gap-4">
                                                    <div type="button" data-bs-toggle="modal" data-bs-target="#add-new-card">
                                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/edit2.png') }}" alt="">
                                                    </div>
                                                    <div type="button">
                                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/trash.png') }}" alt="">
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                <div class="d-flex align-items-center wallet-card-name min-w-180">
                                                    <span class="me-2 me-md-4">{{ translate('2') }}.</span>
                                                    <img loading="lazy" src="{{ theme_asset('assets/img/icons/mastercard.png') }}" alt="">
                                                    <div>{{ translate('Master_Card') }}</div>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="ps-max-sm-5">
                                                    {{ ('02342 ***** 4234') }}
                                                </div>
                                            </th>
                                            <th class="action-buttons">
                                                <div class=" d-flex align-items-center justify-content-end column-gap-4">
                                                    <div type="button" data-bs-toggle="modal" data-bs-target="#add-new-card">
                                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/edit2.png') }}" alt="">
                                                    </div>
                                                    <div type="button">
                                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/trash.png') }}" alt="">
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="wallet-card">
                        <div class="wallet-card-header">
                            <div class="left">
                                <img loading="lazy" src="{{ theme_asset('assets/img/icons/digital-wallet.png') }}" alt="">
                                <span>{{ ('Digital_Wallet') }}</span>
                            </div>
                            <a href="#add-new-card" data-bs-toggle="modal" class="text-base" type="button">+ {{ translate('Add_New_Card') }}</a>
                        </div>
                        <div class="wallet-card-body">
                            <div class="table-responsive">
                                <table class="table __table __wallet-card-table __table-sm-break">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="d-flex align-items-center wallet-card-name min-w-180">
                                                    <span class="me-2 me-md-4">{{ translate('1') }}.</span>
                                                    <img loading="lazy" src="{{ theme_asset('assets/img/icons/bkash.png') }}" alt="">
                                                    <div>{{ translate('Bkash') }}</div>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="ps-max-sm-5">
                                                    {{ translate('02342 ***** 4234') }}
                                                </div>
                                            </th>
                                            <th class="action-buttons">
                                                <div class=" d-flex align-items-center justify-content-end column-gap-4">
                                                    <div type="button" data-bs-toggle="modal" data-bs-target="#add-new-card">
                                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/edit2.png') }}" alt="">
                                                    </div>
                                                    <div type="button">
                                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/trash.png') }}" alt="">
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                <div class="d-flex align-items-center wallet-card-name min-w-180">
                                                    <span class="me-2 me-md-4">{{ translate('2') }}.</span>
                                                    <img loading="lazy" src="{{ theme_asset('assets/img/icons/nagad.png') }}" alt="">
                                                    <div>{{ translate('Nagad') }}</div>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="ps-max-sm-5">
                                                    {{ translate('02342 ***** 4234') }}
                                                </div>
                                            </th>
                                            <th class="action-buttons">
                                                <div class=" d-flex align-items-center justify-content-end column-gap-4">
                                                    <div type="button" data-bs-toggle="modal" data-bs-target="#add-new-card">
                                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/edit2.png') }}" alt="">
                                                    </div>
                                                    <div type="button">
                                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/trash.png') }}" alt="">
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="add-new-card" class="modal fade __modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="m-0">{{ translate('Add_New_Card') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="form-label">{{ translate('Card_Holder_Name') }}</label>
                                        <input type="text" class="form-control" placeholder="{{ translate('ex') }}: {{translate('Abu_Raihan_Rafuj')}}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">{{ translate('Card_Number') }}</label>
                                        <input type="number" class="form-control" placeholder="{{ translate('ex') }}: {{translate('4444_5555_2222_1111')}}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">{{ translate('CVV') }}</label>
                                        <input type="number" class="form-control" placeholder="{{ translate('ex') }}: {{translate('123')}}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">{{ translate('Expiry_Date') }}</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="btn--container">
                                            <button class="btn btn-reset" type="reset">{{ translate('Reset') }}</button>
                                            <button class="btn btn-base" type="submit">{{ translate('Add_Card') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
