@if (isset($web_config['announcement']) && $web_config['announcement']['status']==1)
    <div class="offer-bar" data-bg-img="{{theme_asset('assets/img/media/top-offer-bg.png')}}">
        <div class="d-flex py-2 gap-2 align-items-center">
            <div class="offer-bar-close px-2">
                <i class="bi bi-x-lg"></i>
            </div>
            <div class="top-offer-text flex-grow-1 d-flex justify-content-center fw-semibold text-center">
                {{ $web_config['announcement']['announcement'] }}
            </div>
        </div>
    </div>
@endif
<header class="header-section bg-base">
    <div class="container">
        <div class="header-wrapper">
            <a href="{{route('home')}}" class="logo me-xl-5 bg-light" style="border-radius: 10px">
                <img loading="lazy" class="d-sm-none mobile-logo-cs"
                     src="{{ getValidImage(path: "storage/app/public/company/".$web_config['mob_logo']->value, type: 'logo') }}" alt="{{ translate('logo') }}">
                <img loading="lazy" class="d-none d-sm-block"
                     src="{{ getValidImage(path: "storage/app/public/company/".$web_config['web_logo']->value, type: 'logo') }}" alt="{{ translate('logo') }}">
            </a>
            <div class="menu-area text-capitalize flex-grow-1 ps-xl-4">
                <ul class="menu me-xl-4">
                    <li>
                        <a href="{{route('home')}}"
                           class="{{ Request::is('/')?'active':'' }}">{{ translate('home') }}</a>
                    </li>
                    @php($categories = \App\Utils\CategoryManager::get_categories_with_counting())
                    <li>
                        <a href="javascript:">{{ translate('all_categories')}}</a>
                        <ul class="submenu">
                            @foreach($categories as $key => $category)
                                @if ($key <= 10)
                                    <li>
                                        <a class="py-2"
                                           href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">{{$category['name']}}</a>
                                    </li>
                                @endif
                            @endforeach

                            @if ($categories->count() > 10)
                                <li>
                                    <a href="{{route('products')}}" class="btn-text">{{ translate('view_all') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    @if($web_config['brand_setting'])
                        <li>
                            <a href="{{route('brands')}}"
                               class="{{ Request::is('brands')?'active':'' }}">{{ translate('all_brand') }}</a>
                        </li>
                    @endif
                    <li>
                        <a href="{{route('products',['data_from'=>'discounted','page'=>1])}}"
                           class="{{ request('data_from')=='discounted'?'active':'' }}">
                            {{ translate('offers') }}
                            <div class="offer-count flower-bg d-flex justify-content-center align-items-center offer-count-custom ">
                                {{ ($web_config['total_discount_products'] < 100 ? $web_config['total_discount_products']:'99+') }}
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('contacts')}}"
                           class="{{ Request::is('contacts')?'active':'' }}">
                            Contact Us
                        </a>
                    </li>

                    @if($web_config['business_mode'] == 'multi')
                        <li>
                            <a href="{{route('vendors')}}"
                               class="{{ Request::is('vendors')?'active':'' }}">{{translate('vendors')}}</a>
                        </li>

                        @if ($web_config['seller_registration'])
                            <li class="d-sm-none">
                                <a href="{{route('shop.apply')}}"
                                   class="{{ Request::is('shop.apply')?'active':'' }}">{{translate('vendor_reg').'.'}}</a>
                            </li>
                        @endif
                    @endif

                </ul>

                <ul class="header-right-icons ms-auto">
                    <li class="d-none d-xl-block">
                        @if(auth('customer')->check())
                            <a href="{{ route('wishlists') }}">
                                <div class="position-relative mt-1 px-8px">
                                    <i class="bi bi-heart"></i>
                                    <span class="btn-status wishlist_count_status">{{session()->has('wish_list')?count(session('wish_list')):0}}</span>
                                </div>
                            </a>
                        @else
                            <a href="{{route('customer.login')}}">
                                <div class="position-relative mt-1 px-8px">
                                    <i class="bi bi-heart"></i>
                                    <span class="btn-status">{{translate('0')}}</span>
                                </div>
                            </a>
                        @endif
                    </li>
                    <li id="cart_items" class="d-none d-xl-block">
                        @include('theme-views.layouts.partials._cart')
                    </li>
                    <li class="d-xl-none">
                        <a href="javascript:" class="search-toggle">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>
                    @if(auth('customer')->check())
                        <li class="me-2 me-sm-0 dropdown">
                            <a href="javascript:" data-bs-toggle="dropdown">
                                <i class="bi bi-person d-none d-xl-inline-block"></i>
                                <i class="bi bi-person-circle d-xl-none"></i>
                                <span class="mx-1 d-none d-md-block">{{auth('customer')->user()->f_name}}</span>
                                <i class="ms-1 text-small bi bi-chevron-down d-none d-md-block"></i>
                            </a>
                            <div class="dropdown-menu __dropdown-menu">
                                <ul class="language">
                                    <li class="thisIsALinkElement" data-linkpath="{{route('account-oder')}}">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/user/shopping-bag.svg') }}"
                                             alt="{{ translate('user') }}">
                                        <span>{{ translate('my_order') }}</span>
                                    </li>
                                    <li class="thisIsALinkElement" data-linkpath="{{route('user-profile')}}">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/user/profile.svg') }}"
                                             alt="{{ translate('user') }}">
                                        <span>{{ translate('my_profile') }}</span>
                                    </li>
                                    <li class="thisIsALinkElement" data-linkpath="{{route('customer.auth.logout')}}">
                                        <img loading="lazy" src="{{ theme_asset('assets/img/user/logout.svg') }}"
                                             alt="{{ translate('user') }}">
                                        <span>{{translate('sign_Out')}}</span>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @else
                        <li class="me-2 me-sm-0">
                            <a href="{{route('customer.login')}}" class="">
                                <i class="bi bi-person d-none d-xl-inline-block"></i>
                                <i class="bi bi-person-circle d-xl-none"></i>
                                <span class="mx-1 d-none d-md-block">{{ translate('login') }} / {{ translate('register') }}</span>
                            </a>
                        </li>
                    @endif

                     <li class="me-2 me-sm-0">
                        <div class="darkLight-switcher d-none d-xl-block">
                        <button type="button" title="{{ translate('Dark_Mode') }}" class="dark_button">
                            <img loading="lazy" class="svg" src="{{theme_asset('assets/img/icons/dark.svg')}}"
                                    alt="{{ translate('dark_Mode') }}">
                        </button>
                        <button type="button" title="{{ translate('Light_Mode') }}" class="light_button">
                            <img loading="lazy" class="svg" src="{{theme_asset('assets/img/icons/light.svg')}}"
                                    alt="{{ translate('light_Mode') }}">
                        </button>
                    </div>
                    </li>

                    <li class="nav-toggle d-xl-none" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                        aria-controls="offcanvasRight">
                        <span></span>
                        <span></span>
                        <span></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="mobile-search-form-wrapper">
        <div class="search-form-header d-xl-none">
            <div class="d-flex w-100 align-items-center">
                <form class="search-form m-0 p-0 sidebar-search-form" action="{{route('products')}}" type="submit">
                    <div class="input-group search_input_group">
                        <svg width="12" class="category-icon" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.5 5.00001C0.367392 5.00001 0.240215 4.94733 0.146447 4.85356C0.0526785 4.75979 0 4.63262 0 4.50001V0.501007C0 0.368399 0.0526785 0.241222 0.146447 0.147454C0.240215 0.0536856 0.367392 0.00100708 0.5 0.00100708H4.5C4.63261 0.00100708 4.75979 0.0536856 4.85355 0.147454C4.94732 0.241222 5 0.368399 5 0.501007V4.50001C5 4.63262 4.94732 4.75979 4.85355 4.85356C4.75979 4.94733 4.63261 5.00001 4.5 5.00001H0.5ZM7.5 5.00001C7.36739 5.00001 7.24021 4.94733 7.14645 4.85356C7.05268 4.75979 7 4.63262 7 4.50001V0.501007C7 0.368399 7.05268 0.241222 7.14645 0.147454C7.24021 0.0536856 7.36739 0.00100708 7.5 0.00100708H11.499C11.6316 0.00100708 11.7588 0.0536856 11.8526 0.147454C11.9463 0.241222 11.999 0.368399 11.999 0.501007V4.50001C11.999 4.63262 11.9463 4.75979 11.8526 4.85356C11.7588 4.94733 11.6316 5.00001 11.499 5.00001H7.5ZM0.5 12C0.367392 12 0.240215 11.9473 0.146447 11.8536C0.0526785 11.7598 0 11.6326 0 11.5V7.50001C0 7.3674 0.0526785 7.24022 0.146447 7.14645C0.240215 7.05269 0.367392 7.00001 0.5 7.00001H4.5C4.63261 7.00001 4.75979 7.05269 4.85355 7.14645C4.94732 7.24022 5 7.3674 5 7.50001V11.5C5 11.6326 4.94732 11.7598 4.85355 11.8536C4.75979 11.9473 4.63261 12 4.5 12H0.5ZM7.5 12C7.36739 12 7.24021 11.9473 7.14645 11.8536C7.05268 11.7598 7 11.6326 7 11.5V7.50001C7 7.3674 7.05268 7.24022 7.14645 7.14645C7.24021 7.05269 7.36739 7.00001 7.5 7.00001H11.499C11.6316 7.00001 11.7588 7.05269 11.8526 7.14645C11.9463 7.24022 11.999 7.3674 11.999 7.50001V11.5C11.999 11.6326 11.9463 11.7598 11.8526 11.8536C11.7588 11.9473 11.6316 12 11.499 12H7.5Z" fill="currentColor"/>
                        </svg>
                        <select class="select2-init header-select2 text-capitalize mobile-search-select-2" id="search_category_value_mobile"
                                name="search_category_value">
                            <option value="all">{{ translate('all_categories') }}</option>
                            @foreach($web_config['main_categories'] as $category)
                                <option value="{{ $category->id }}">{{$category['name']}}</option>
                            @endforeach
                        </select>
                        <input type="search" class="form-control action-global-search-mobile" id="input-value-mobile"
                            placeholder="{{ translate('search_for_items_or_store') }}..." name="name" autocomplete="off">

                        <button class="btn btn-base" type="submit"><i class="bi bi-search"></i></button>
                        <div class="card search-card position-absolute z-99 w-100 bg-white d-none top-100 start-0 search-result-box-mobile"></div>
                    </div>
                    <input name="data_from" value="search" hidden>
                    <input name="page" value="1" hidden>
                </form>
            </div>
        </div>
    </div>
</header>

<div class="search-toggle search-togle-overlay"></div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header justify-content-end">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body text-capitalize d-flex flex-column">
        <div>
            <ul class="menu scrollY-60 ">
                <li>
                    <a href="{{route('home')}}">{{ translate('home') }}</a>
                </li>
                <li>
                    <a href="javascript:">{{ translate('all_categories') }}</a>
                    <ul class="submenu">
                        @foreach($categories as $key => $category)
                            @if ($key <= 10)
                                <li>
                                    <a class="py-2"
                                       href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">{{$category['name']}}</a>
                                </li>
                            @endif
                        @endforeach

                        @if ($categories->count() > 10)
                            <li>
                                <a href="{{route('products')}}" class="btn-text">{{ translate('view_all') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
                @if($web_config['brand_setting'])
                    <li>
                        <a href="{{route('brands')}}">{{ translate('all_brand') }}</a>
                    </li>
                @endif
                <li>
                    <a class="d-flex align-items-center gap-2"
                       href="{{route('products',['data_from'=>'discounted','page'=>1])}}">
                        {{ translate('offers') }}
                        <div class="offer-count flower-bg d-flex justify-content-center align-items-center offer-count-custom">
                            {{ ($web_config['total_discount_products'] < 100 ? $web_config['total_discount_products']:'99+') }}
                        </div>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center gap-2"
                       href="{{route('contacts')}}">
                       Contact Us
                    </a>
                </li>

                @if($web_config['business_mode'] == 'multi')
                    <li>
                        <a href="{{route('vendors')}}">{{translate('vendors')}}</a>
                    </li>

                    @if ($web_config['seller_registration'])
                        <li class="d-sm-none">
                            <a href="{{route('shop.apply')}}">{{translate('vendor_reg').'.'}}</a>
                        </li>
                    @endif
                @endif

            </ul>
        </div>

        <div class="d-flex align-items-center gap-2 justify-content-between py-4 mt-3">
            <span class="text-dark">{{ translate('theme_mode') }}</span>
            <div class="theme-bar">
                <button class="light_button active">
                    <img loading="lazy" class="svg" src="{{theme_asset('assets/img/icons/light.svg')}}"
                         alt="{{ translate('light_Mode') }}">
                </button>
                <button class="dark_button">
                    <img loading="lazy" class="svg" src="{{theme_asset('assets/img/icons/dark.svg')}}" alt="{{ translate('dark_Mode') }}">
                </button>
            </div>
        </div>

        @if(auth('customer')->check())
            <div class="d-flex justify-content-center mb-2 pb-3 mt-auto px-4">
                <a href="{{route('customer.auth.logout')}}" class="btn btn-base w-100">{{ translate('logout') }}</a>
            </div>
        @else
            <div class="d-flex justify-content-center mb-2 pb-3 mt-auto px-4">
                <a href="javascript:" class="btn btn-base w-100 customer_login_register_modal">
                    {{ translate('login') }} / {{ translate('register') }}
                </a>
            </div>
        @endif
    </div>
</div>
