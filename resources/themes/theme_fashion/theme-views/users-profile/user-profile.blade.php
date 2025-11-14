@extends('theme-views.layouts.app')

@section('title', translate('my_profile').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
<section class="user-profile-section section-gap pt-0">
    <div class="container">

        @include('theme-views.partials._profile-aside')

        <div class="tab-content">

            <div class="tab-pane fade show active" >
                <div class="personal-details mb-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center column-gap-4 row-gap-2 mb-4">
                        <h4 class="subtitle m-0 text-capitalize">{{ translate('personal_details') }}</h4>
                        <a href="{{route('user-account')}}" class="cmn-btn __btn-outline rounded-full text-capitalize" >
                            {{ translate('edit_profile') }}
                            @include('theme-views.partials.icons._profile-edit')
                        </a>
                    </div>
                    <div class="personal-details-info">
                        <table class="table align-middle __table table-borderless">
                            <tr class="mb-2" style="margin-bottom: 20px">
                                <td style="width: 80px">{{ translate('name') }} : </td>
                                <td>{{$customer_detail['name']}}</td>
                            </tr>
                            <tr>
                                <td style="width: 80px">{{ translate('phone') }} : </td>
                                <td>{{$customer_detail['phone']}}</td>
                            </tr>
                            <tr>
                                <td style="width: 80px">{{ translate('email') }} : </td>
                                <td>{{$customer_detail['email']}}</td>
                            </tr>
                            <tr>
                                <td style="width: 100px">Address : </td>
                                <td>{{$customer_detail['street_address']}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
