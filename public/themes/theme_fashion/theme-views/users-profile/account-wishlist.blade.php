@extends('theme-views.layouts.app')

@section('title', translate('my_wishlists').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@section('content')
    <section class="user-profile-section section-gap pt-0">
        <div class="container">
            @include('theme-views.partials._profile-aside')
            <div class="mb-4">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="search-form-2 search-form-mobile">
                        <span class="icon d-flex">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" placeholder="{{ translate('search_by_name_or_category') }}" name="search" class="form-control" value="{{ request('search') }}">
                        <button type="submit" class="clear border-0">
                            @if (request('search') != null)
                                <a href="{{route('wishlists')}}" class="text-danger title" >{{translate('clear')}}</a>
                            @else
                                <span class="text-title">{{translate('search')}}</span>
                            @endif
                        </button>
                    </div>
                </form>
            </div>
            @if($wishlists->count()>0)
                @if (request('search') == null)
                    <div class="cart-title-area"><h6 class="title text-capitalize">{{ translate('all_wishing_product_list') }} (<span class="wishlist_count_status">{{ $wishlists->total()  }}</span>)</h6>
                        <a href="javascript:" class="text-text-2 route_alert_function"
                        data-routename="{{ route('delete-wishlist-all') }}"
                        data-message="{{ translate('want_to_clear_all_wishlist?') }}"
                        data-typename="">{{translate('remove_all')}}</a>
                    </div>
                @endif

            @include('theme-views.partials._wish-list-data',['wishlists'=>$wishlists])

            <div class="d-flex justify-content-end w-100 overflow-auto mt-3" id="paginator-ajax">
                {{ $wishlists->links() }}
            </div>
            @endif
            @if($wishlists->count()==0)
                <div class="text-center pt-5 w-100">
                    <div class="text-center mb-5">
                        <img loading="lazy" src="{{ theme_asset('assets/img/icons/wishlist.svg') }}" alt="{{ translate('wishlist') }}">
                        <h5 class="my-3 text-muted">{{translate('no_Saved_Products_Found')}}!</h5>
                        <p class="text-center text-muted">{{ translate('you_have_not_add_any_products_in_Wishlist ') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
