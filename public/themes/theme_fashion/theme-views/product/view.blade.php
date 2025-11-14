@extends('theme-views.layouts.app')

@section('title',translate('products').' | '.$web_config['name']->value.' '.translate('ecommerce'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']}}"/>
    <meta property="og:title" content="Products of {{$web_config['name']}} "/>
    <meta property="og:url" content="{{config('app.url')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']}}"/>
    <meta property="twitter:title" content="Products of {{$web_config['name']}}"/>
    <meta property="twitter:url" content="{{config('app.url')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
@endpush

@section('content')

    <section class="promo-page-header">
        @if ($banner)
            <img loading="lazy" src="{{ getValidImage(path: 'storage/app/public/banner/'.($banner ? json_decode($banner['value'])->image:''), type:'banner') }}"
                 class="w-100" alt="{{ translate('banner') }}">
        @else
            <div class="product_blank_banner"></div>
        @endif
    </section>

    <div class="container">
        @include('theme-views.layouts.partials._search-form-partials')
    </div>

    <section class="all-products-section pt-20px scroll_to_form_top">
        <form action="{{ route('ajax-filter-products') }}" method="POST" id="fashion_products_list_form">
            @csrf
            <div class="container">
                <div class="section-title mb-4">
                    <div
                        class="d-flex flex-column flex-sm-row justify-content-between row-gap-3 column-gap-2 align-items-sm-center search-page-title">
                        <ul class="breadcrumb">
                            <li>
                                <a href="{{ route('home') }}">{{ translate('home') }}</a>
                            </li>
                            <li>
                                <a href="javascript:" class="text-capitalize text-base product_view_title"
                                   data-allproduct="{{translate('all_products')}}">
                                    {{translate(str_replace(['-', '_', '/'],' ',request('data_from')))}} {{translate('products')}} {{ request('brand_name') ? ' / '.str_replace('_',' ',request('brand_name')) : ''}} {{ request('name') ? '('.request('name').')' : ''}}
                                </a>
                            </li>
                        </ul>
                        <div
                            class="d-flex flex-wrap-reverse justify-content-between justify-content-sm-end align-items-center column-gap-3 row-gap-2 text-capitalize min-w-lg-190">
                            <div class="flex-grow-1">
                                <div class="position-relative select2-prev-icon d-none d-lg-block">
                                    <i class="bi bi-sort-up"></i>
                                    <select
                                        class="select2-init form-control size-40px filter_select_input filter_by_product_list_web ps-32px"
                                        name="sort_by"
                                        data-primary_select="{{translate('sort_by')}} : {{translate('default')}}">
                                        <option value="default">{{translate('sort_by')}}
                                            : {{translate('default')}}</option>
                                        <option
                                            value="latest" {{ request('data_from') == 'latest' ? 'selected':'' }}>{{translate('sort_by')}}
                                            : {{translate('latest')}}</option>
                                        <option value="a-z">{{translate('sort_by')}}
                                            : {{translate('a_to_z_order')}}</option>
                                        <option value="z-a">{{translate('sort_by')}}
                                            : {{translate('z_to_a_order')}}</option>
                                        <option value="low-high">{{translate('sort_by')}}
                                            : {{translate('low_to_high_price')}}
                                        </option>
                                        <option value="high-low">{{translate('sort_by')}}
                                            : {{translate('high_to_low_price')}}
                                        </option>
                                    </select>
                                </div>
                                <div class="d-lg-none">
                                    <button type="button" class="btn btn-base filter-toggle d-lg-none">
                                        <i class="bi bi-funnel"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <main class="main-wrapper">

                    <aside class="sidebar">
                        @include('theme-views.partials.products._products-list-aside',['categories'=>$categories, 'colors'=>$colors_in_shop])
                    </aside>

                    <article class="article">
                        <div id="selected_filter_area">
                            @include('theme-views.product._selected_filter_tags',['tags_category'=>$tag_category,'tags_brands'=>$tag_brand,'rating'=>null])
                        </div>
                        @php($paginate_count = $products->total() > 20 ? ceil($products->total()/20) : 1)
                        <div id="ajax_products_section">
                            @include('theme-views.product._ajax-products',['products'=>$products,'page'=>1,'paginate_count'=>$paginate_count])
                        </div>
                    </article>
                </main>
            </div>
        </form>
    </section>

    @include('theme-views.partials._how-to-section')

@endsection
