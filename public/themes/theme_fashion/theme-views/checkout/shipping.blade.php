@extends('theme-views.layouts.app')

@section('title', translate('shopping_details').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')

<section class="breadcrumb-section pt-20px">
    <div class="container">
        <div class="section-title mb-4">
            <div
                class="d-flex flex-wrap justify-content-between row-gap-3 column-gap-2 align-items-center search-page-title">
                <ul class="breadcrumb">
                    <li>
                        <a href="{{route('home')}}">{{translate('home')}}</a>
                    </li>
                    <li>
                        <a href="{{route('shop-cart')}}">{{translate('cart')}}</a>
                    </li>
                    <li>
                        <a href="{{url()->current()}}" class="text-base custom-text-link">{{translate('checkout')}}</a>
                    </li>
                </ul>
                <div class="ms-auto ms-md-0">
                    <a href="{{route('shop-cart')}}" class="text-base custom-text-link">{{ translate('check_All_CartList') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="checkout-section pt-4 section-gap">
    <div class="container">
        <h3 class="mb-3 mb-lg-4 d-flex justify-content-center justify-content-sm-start">{{translate('checkout')}}</h3>
        <div class="row g-4">
            <div class="col-lg-7">
                <ul class="checkout-flow">
                    <li class="checkout-flow-item active">
                        <a href="javascript:">
                            <span class="serial">{{ translate('1') }}</span>
                            <span class="icon">
                                <i class="bi bi-check"></i>
                            </span>
                            <span class="text thisIsALinkElement" data-linkpath="{{route('shop-cart')}}">{{translate('cart')}}</span>
                        </a>
                    </li>
                    <li class="line"></li>
                    <li class="checkout-flow-item active current">
                        <a href="javascript:">
                            <span class="serial">{{ translate('2') }}</span>
                            <span class="icon">
                                <i class="bi bi-check"></i>
                            </span>
                            <span class="text text-capitalize">{{translate('shipping_details')}}</span>
                        </a>
                    </li>
                    <li class="line"></li>
                    <li class="checkout-flow-item">
                        <a href="javascript:">
                            <span class="serial">{{ translate('3') }}</span>
                            <span class="icon">
                                <i class="bi bi-check"></i>
                            </span>
                            <span class="text">{{translate('payment')}}</span>
                        </a>
                    </li>
                </ul>
                <input type="hidden" id="physical_product" name="physical_product" value="{{ $physical_product_view ? 'yes':'no'}}">
                <input type="hidden" id="billing_input_enable" name="billing_input_enable" value="{{ $billing_input_by_customer }}">
                <div class="delivery-information">
                    <h6 class="font-bold letter-spacing-0 title text-capitalize mb-20px">
                        {{ translate('delivery_information_details') }}
                    </h6>
                </div>


                @if($physical_product_view)
                    <form method="post" id="address-form">
                        <div class="delivery-information mt-32px">
                            <div class="d-flex flex-wrap row-gap-3 column-gap-4 mb-20px align-items-end">
                                <h6 class="font-bold letter-spacing-0 title m-0 text-capitalize">{{translate('delivery_address')}}</h6>
                                @if(auth('customer')->check())
                                    <div class="ms-auto text-base text-capitalize" type="button" data-bs-target="#shipping_addresses" data-bs-toggle="modal">
                                        {{translate('select_from_saved')}}
                                    </div>
                                @endif

                                <div class="@if(!auth('customer')->check()) ms-auto @endif text-base text-capitalize" type="button" data-bs-target="#set_shipping_address" data-bs-toggle="modal">
                                    {{translate('set_from_map')}} <i class="bi bi-geo-alt-fill"></i>
                                </div>

                                <div class="modal fade" id="set_shipping_address">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-capitalize">{{translate('set_delivery_address')}}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="modal-body">
                                                    <div class="product-quickview">

                                                        <input id="pac-input" class="controls rounded __map-input mt-1" title="{{translate('search_your_location_here')}}" type="text" placeholder="{{translate('search_here')}}"/>
                                                        <div class="dark-support rounded w-100 __h-14rem" id="location_map_canvas"></div>
                                                        <input type="hidden" id="latitude" name="latitude" class="form-control d-inline"
                                                             value="{{$default_location?$default_location['lat']:0}}" required>
                                                        <input type="hidden" name="longitude" class="form-control"
                                                            id="longitude" value="{{$default_location?$default_location['lng']:0}}" required >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer p-3">
                                                <button type="button" class="btn rounded btn-base secondary-color" data-bs-dismiss="modal">{{translate('close')}}</button>
                                                <button type="button" data-bs-dismiss="modal" class="btn rounded btn-base">{{translate('update')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-sm-@if(auth('customer')->check()) '6' @else '12' @endif">
                                    <input type="text" placeholder="{{translate('contact_person_name')}}" id="name" name="contact_person_name" class="form-control" {{$shipping_addresses->count()==0?'required':''}}>
                                </div>
                                <div class="col-sm-6">
                                    <input type="tel" placeholder="{{translate('phone')}}" name="phone" id="phone_number" class="form-control" {{$shipping_addresses->count()==0?'required':''}}>
                                </div>
                                @if(!auth('customer')->check())
                                    <div class="col-sm-6">
                                        <input type="email" name="email" id="email" class="form-control" placeholder="{{ translate('email') }}" required>
                                    </div>
                                @endif
                                <div class="col-sm-6">
                                    <select name="country" id="country" class="form-control select2-init">
                                        <option value="">{{translate('select_country')}}</option>
                                        @forelse($countries as $country)
                                            <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                                        @empty
                                            <option value="">{{translate('no_country_to_deliver') }}</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <input type="text" name="city" id="city" placeholder="{{translate('city')}}" class="form-control"  {{$shipping_addresses->count()==0?'required':''}}>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    @if($zip_restrict_status == 1)
                                    <select name="zip" id="zip" class="form-control select2-init" data-live-search="true" required>
                                        @forelse($zip_codes as $code)
                                            <option value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                        @empty
                                            <option value="">{{translate('no_zip_to_deliver') }}</option>
                                        @endforelse
                                    </select>
                                    @else
                                        <input type="text" class="form-control" name="zip" id="zip" placeholder="{{translate('zip_Code')}}" {{$shipping_addresses->count()==0?'required':''}}>
                                    @endif
                                </div>

                                <div class="col-sm-12">
                                    <select name="address_type" id="address_type" class="form-select form-control ">
                                        <option value="permanent">{{ translate('permanent')}}</option>
                                        <option value="home">{{ translate('home')}}</option>
                                        <option value="others">{{ translate('others')}}</option>
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-control p-0 rounded d-flex align-items-center force-border-input">
                                        <input type="text" name="address" id="address" placeholder="{{translate('address')}}" class="form-control border-0 bg-transparent p-3 outline-custom-remove" {{$shipping_addresses->count()==0?'required':''}} autocomplete="off">
                                        <div class="border-start p-3" data-bs-toggle="modal" data-bs-target="#set_shipping_address">
                                            <i class="bi bi-compass-fill cursor-pointer"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" >
                                    <label class="form-check m-0" id="save_address_label">
                                        <input type="hidden" name="shipping_method_id" id="shipping_method_id" value="0">
                                        @if(auth('customer')->check())
                                            <input type="checkbox" name="save_address" id="save_address" class="form-check-input dark-form-check-input">
                                            <span class="form-check-label">{{translate('save_this_address')}}</span>
                                        @endif
                                    </label>

                                </div>
                            </div>
                        </div>
                    </form>
                @endif

                @if($billing_input_by_customer)
                    <div class="delivery-information mt-32px {{ $billing_input_by_customer ? '':'d-none' }}">

                        <div class="col-md-12 g-4">
                            <div class="--bg-4 rounded p-3 d-flex row-gap-2 column-gap-3 align-items-center justify-content-between">
                                <h6 class="font-bold letter-spacing-0 title m-0 text-capitalize">{{translate('billing_address')}}</h6>

                                @if($physical_product_view)
                                <label class="form-check flow-reverse m-0">
                                    <span class="form-check-label text-capitalize">{{translate('same_as_delivery_address')}}</span>
                                    <input type="checkbox" name="same_as_shipping_address" id="same_as_shipping_address" class="form-check-input  dark-form-check-input" {{$billing_input_by_customer==1?'':'checked'}}>
                                </label>
                                @endif
                            </div>
                        </div>
                        <div id="billing-address" class="mt-20px">
                            <form method="post" id="billing-address-form">
                                <div class="delivery-information mt-32px" id="hide_billing_address">
                                    <div class="d-flex flex-wrap row-gap-3 column-gap-4 mb-20px align-items-end">
                                        @if(auth('customer')->check())
                                            <div class="ms-auto text-base" type="button" data-bs-target="#billing_addresses" data-bs-toggle="modal">
                                                {{translate('select_from_saved')}}
                                            </div>
                                        @endif

                                        <div class="text-base" type="button" data-bs-target="#set_billing_addresses" data-bs-toggle="modal">
                                            {{translate('set_from_map')}} <i class="bi bi-geo-alt-fill"></i>
                                        </div>

                                        <div class="modal fade" id="set_billing_addresses">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-capitalize">{{translate('set_delivery_address')}}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="modal-body">
                                                            <div class="product-quickview">

                                                                <input id="pac-input-billing" class="controls rounded __map-input mt-1" title="{{translate('search_your_location_here')}}" type="text" placeholder="{{translate('search_here')}}"/>
                                                                <div class="dark-support rounded w-100 __h-14rem" id="billing_location_map_canvas"></div>
                                                                <input type="hidden" id="billing_latitude" name="billing_latitude" class="form-control d-inline"
                                                                    value="{{$default_location?$default_location['lat']:0}}" required readonly>
                                                                <input type="hidden" name="billing_longitude" class="form-control"
                                                                    id="billing_longitude" value="{{$default_location?$default_location['lng']:0}}" required >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer p-3">
                                                        <button type="button" class="btn btn-base secondary-color"
                                                            data-bs-dismiss="modal">{{translate('close')}}</button>
                                                        <button type="button" data-bs-dismiss="modal" class="btn rounded btn-base">{{translate('Update')}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-4">
                                        <div class="col-sm-@if(auth('customer')->check()) '6' @else '12' @endif">
                                            <input type="text" placeholder="{{translate('contact_Person_Name')}}" id="billing_contact_person_name" name="billing_contact_person_name" class="form-control" {{$shipping_addresses->count()==0?'required':''}}>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="tel" placeholder="{{translate('phone')}}" name="billing_phone" id="billing_phone" class="form-control" {{$shipping_addresses->count()==0?'required':''}}>
                                        </div>
                                        @if(!auth('customer')->check())
                                            <div class="col-sm-6">
                                                <input type="text" name="billing_contact_email" id="billing_contact_email" class="form-control" placeholder="{{ translate('email') }}" required>
                                            </div>
                                        @endif
                                        <div class="col-sm-6">
                                            <select name="billing_country" id="billing_country" class="form-control select2-init">
                                                <option value="">{{translate('select_country')}}</option>
                                                @forelse($countries as $country)
                                                    <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                                                @empty
                                                    <option value="">{{ translate('no_country_to_deliver') }}</option>
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <input type="text" name="billing_city" id="billing_city" placeholder="{{translate('city')}}" class="form-control"  {{$shipping_addresses->count()==0?'required':''}}>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            @if($zip_restrict_status == 1)
                                            <select name="billing_zip" id="billing_zip" class="form-control select2-init" data-live-search="true" required>
                                                @forelse($zip_codes as $code)
                                                    <option value="{{ $code->zipcode }}">{{ $code->zipcode }}</option>
                                                @empty
                                                    <option value="">{{ translate('no_zip_to_deliver') }}</option>
                                                @endforelse
                                            </select>
                                            @else
                                                <input type="text" class="form-control" name="billing_zip" id="billing_zip" placeholder="{{translate('zip_Code')}}" {{$shipping_addresses->count()==0?'required':''}}>
                                            @endif
                                        </div>

                                        <div class="col-sm-12">
                                            <select name="billing_address_type" id="billing_address_type" class="form-select form-control ">
                                                <option value="permanent">{{ translate('permanent')}}</option>
                                                <option value="home">{{ translate('home')}}</option>
                                                <option value="others">{{ translate('others')}}</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-control p-0 rounded d-flex align-items-center force-border-input">
                                                <input type="text" id="billing_address" name="billing_address" placeholder="{{ translate('Address') }}" class="border-0 bg-transparent p-3 outline-custom-remove form-control" autocomplete="off">
                                                <div class="border-start p-3" data-bs-toggle="modal" data-bs-target="#set_billing_addresses">
                                                    <i class="bi bi-compass-fill cursor-pointer"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="billing_method_id" id="billing_method_id" value="0">
                                        @if(auth('customer')->check())
                                            <div class="col-sm-12" >
                                                <label class="form-check m-0" id="save-billing-address-label">
                                                    <input type="checkbox" name="save_address_billing" id="save_address_billing" class="form-check-input dark-form-check-input">
                                                    <span class="form-check-label">{{translate('save_this_Address')}}</span>
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-5">
                @include('theme-views.partials._order-summery')
            </div>

        </div>
    </div>
</section>

<div class="modal fade" id="shipping_addresses">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Saved_Addresses') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mapouter">
                    <div class="row ">
                        @if (auth('customer')->check() && $shipping_addresses->count()>0)
                            @foreach($shipping_addresses as $key=>$address)
                                <div class="col-md-12">
                                    <div class="address-card mb-20px ">
                                        <div class="address-card-header bg-transparent d-flex justify-content-between align-items-center">
                                            <label class="d-flex align-items-start gap-3 cursor-pointer mb-0 w-100">
                                                <input class="s-16px form-check-input mt-1" type="radio" name="shipping_method_id" value="{{$address['id']}}" {{$key==0?'checked':''}}>
                                                <div class="__border-base"></div>
                                                <div class="w-0 flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center column-gap-4">
                                                        <h6 class="text-capitalize">{{$address['address_type']}}</h6>
                                                        <a href="{{route('address-edit',$address->id)}}" >
                                                            <img loading="lazy" src="{{ theme_asset('assets/img/icons/edit.png') }}" alt="{{ translate('edit') }}">
                                                        </a>
                                                    </div>
                                                    <div class="address-card-body pb-0 px-0 text-start">
                                                        <ul>
                                                            <li>
                                                                <span class="form--label w-70px">{{ translate('name') }}</span>
                                                                <span class="info ps-2 shipping-contact-person">{{$address['contact_person_name']}}</span>
                                                            </li>
                                                            <li>
                                                                <span class="form--label w-70px">{{ translate('phone') }}</span>
                                                                <span class="info ps-2 shipping-contact-phone">{{$address['phone']}}</span>
                                                            </li>
                                                            <li>
                                                                <span class="form--label w-70px">{{ translate('address') }}</span>
                                                                <span class="info ps-2 shipping-contact-address">{{$address['address']}}</span>
                                                            </li>
                                                            <span class="shipping-contact-address d-none">{{ $address['address'] }}</span>
                                                            <span class="shipping-contact-city d-none">{{ $address['city'] }}</span>
                                                            <span class="shipping-contact-zip d-none">{{ $address['zip'] }}</span>
                                                            <span class="shipping-contact-country d-none">{{ $address['country'] }}</span>
                                                            <span class="shipping-contact-address_type d-none">{{ $address['address_type'] }}</span>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center">
                                <img loading="lazy" src="{{ theme_asset('assets/img/icons/address.svg') }}" alt="{{ translate('address') }}" class="w-25">
                                <h5 class="my-3 pt-1 text-muted">
                                    {{ translate('no_address_is_saved') }}!
                                </h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer p-3">
                <button type="button" class="btn rounded btn-reset text-title"
                    data-bs-dismiss="modal">{{translate('close')}}</button>
                <button type="button" data-bs-dismiss="modal" class="btn rounded btn-base">{{translate('select')}}</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="billing_addresses">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize">{{translate('saved_addresses')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mapouter">
                    <div class="row ">
                        @if (auth('customer')->check() && $billing_addresses->count()>0)
                         @foreach($billing_addresses as $key=>$address)
                            <div class="col-md-12">
                                <div class="address-card mb-20px ">
                                    <div class="address-card-header bg-transparent d-flex justify-content-between align-items-center">
                                        <label class="d-flex align-items-start gap-3 cursor-pointer mb-0 w-100">
                                            <input class="s-16px form-check-input mt-1" type="radio" name="billing_method_id" {{$key==0?'checked':''}} value="{{$address['id']}}" >
                                            <div class="__border-base"></div>
                                            <div class="w-0 flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center column-gap-4">
                                                    <h6 class="text-capitalize">{{$address['address_type']}}</h6>
                                                    <a href="{{route('address-edit',$address->id)}}" >
                                                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/edit.png') }}" alt="{{ translate('edit') }}">
                                                    </a>
                                                </div>
                                                <div class="address-card-body pb-0 px-0 text-start">
                                                    <ul>
                                                        <li>
                                                            <span class="form--label w-70px">{{ translate('name') }}</span>
                                                            <span class="info ps-2 billing-contact-name">{{$address['contact_person_name']}}</span>
                                                        </li>
                                                        <li>
                                                            <span class="form--label w-70px">{{ translate('phone') }}</span>
                                                            <span class="info ps-2 billing-contact-phone">{{$address['phone']}}</span>
                                                        </li>
                                                        <li>
                                                            <span class="form--label w-70px">{{ translate('address') }}</span>
                                                            <span class="info ps-2 billing-contact-address">{{$address['address']}}</span>
                                                        </li>
                                                        <span class="billing-contact-city d-none">{{ $address['city'] }}</span>
                                                        <span class="billing-contact-zip d-none">{{ $address['zip'] }}</span>
                                                        <span class="billing-contact-country d-none">{{ $address['country'] }}</span>
                                                        <span class="billing-contact-address_type d-none">{{ $address['address_type'] }}</span>
                                                    </ul>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @else
                            <div class="text-center">
                                <img loading="lazy" src="{{ theme_asset('assets/img/icons/address.svg') }}" alt="{{ translate('address') }}" class="w-25">
                                <h5 class="my-3 pt-1 text-muted">
                                        {{translate('no_address_is_saved')}}!
                                </h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer p-3">
                <button type="button" class="btn rounded btn-reset text-title"
                    data-bs-dismiss="modal">{{translate('close')}}</button>
                <button type="button" data-bs-dismiss="modal" class="btn rounded btn-base">{{translate('select')}}</button>
            </div>
        </div>
    </div>
</div>


<span id="shippingaddress-storage"
    data-latitude="{{ $default_location ? $default_location['lat'] : '' }}"
    data-longitude="{{ $default_location ? $default_location['lng'] : '' }}">
</span>

@endsection

@push('script')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ getWebConfig(name: 'map_api_key') }}&callback=mapsShopping&libraries=places&v=3.49" defer></script>
    <script src="{{ theme_asset('assets/js/shipping-page.js') }}"></script>
@endpush


