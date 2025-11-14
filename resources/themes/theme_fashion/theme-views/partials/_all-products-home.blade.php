<section class="all-products-section scroll_to_form_top mt-5">
    <div class="container">
        <div class="section-title">
            <div class="d-flex flex-wrap justify-content-between row-gap-2 column-gap-4 align-items-center">
                <h2 class="title mb-0 me-auto text-capitalize">{{ translate('all_products') }}</h2>
                {{-- <div class="product-count-wrapper d-flex flex-wrap justify-content-between flex-grow-1 me-auto gap-2">
                    <div class="product-count-card">
                        <h6>{{ $all_products_info['total_products'] }}</h6><span>{{ translate('products') }}</span>
                    </div>
                    <div class="product-count-card">
                        <h6>{{ $all_products_info['total_orders'] }}</h6><span>{{ translate('order') }}</span>
                    </div>
                    <div class="product-count-card">
                        <h6>{{ $all_products_info['total_delivary'] }}</h6><span>{{ translate('delivery') }}</span>
                    </div>
                    <div class="product-count-card">
                        <h6>{{ $all_products_info['total_reviews'] }}</h6><span>{{ translate('review') }}</span>
                    </div>
                </div> --}}
                <div class="ms-auto ms-md-0 d-flex align-items-center column-gap-3">
                    <button type="button" class="btn btn-base filter-toggle d-lg-none">
                        <i class="bi bi-funnel"></i>
                    </button>
                    <a href="{{route('products')}}" id="data_form_id" class="see-all text-capitalize">
                        {{ translate('see_all') }}
                    </a>
                </div>
            </div>
        </div>
        <form action="{{ route('ajax-filter-products') }}" method="POST" id="fashion_products_list_form">
            @csrf
            <main class="main-wrapper">

                <aside class="sidebar">
                    @include('theme-views.partials.products._products-list-aside',['categories'=>$categories, 'colors'=>$colors_in_shop])
                </aside>

                <article class="article">
                    <div class="scroller-wrapper">
                        <div class="scrollLeft">
                            <i class="bi bi-chevron-left"></i>
                        </div>
                        <div class="scroller-inner text-capitalize">
                            <ul class="products_navs_list">
                                <li>
                                    <label class="filter_by_all active activeFilterNav" data-key="filter_by_all">
                                        {{ translate('all') }}
                                    </label>
                                    <input type="radio" name="filter_by" value="default" id="filter_by_all" checked hidden>
                                </li>
                                <li>
                                    <label class="filter_by_latest activeFilterNav" data-key="filter_by_latest">
                                        {{ translate('latest_product') }}
                                    </label>
                                    <input type="radio" name="filter_by" value="latest" id="filter_by_latest" hidden>
                                </li>
                                <li>
                                    <label class="filter_by_top_rated activeFilterNav" data-key="filter_by_top_rated">
                                        {{ translate('top_rated') }}
                                    </label>
                                    <input type="radio" name="filter_by" value="top_rated" id="filter_by_top_rated" hidden>
                                </li>
                                <li>
                                    <label class="filter_by_discount activeFilterNav" data-key="filter_by_discount">
                                        {{ translate('discount') }}%
                                    </label>
                                    <input type="radio" name="filter_by" value="discount" id="filter_by_discount" hidden>
                                </li>
                                <li>
                                    <label class="filter_by_best_selling activeFilterNav" data-key="filter_by_best_selling">
                                        {{ translate('best_selling') }}
                                    </label>
                                    <input type="radio" name="filter_by" value="best_selling" id="filter_by_best_selling" hidden>
                                </li>
                                <li>
                                    <label class="filter_by_featured activeFilterNav" data-key="filter_by_featured">
                                        {{ translate('featured') }}
                                    </label>
                                    <input type="radio" name="filter_by" value="featured" id="filter_by_featured" hidden>
                                </li>
                                <li>
                                    <label class="filter_by_most_loved activeFilterNav" data-key="filter_by_most_loved">
                                        {{ translate('most_loved') }}
                                    </label>
                                    <input type="radio" name="filter_by" value="most_loved" id="filter_by_most_loved" hidden>
                                </li>
                            </ul>
                        </div>
                        <div class="scrollRight">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </div>

                    <div id="ajax_products_section">
                        @include('theme-views.product._ajax-products',['products'=>$all_products,'page'=>1,])

                        @php($paginate_count = $all_products->total() > 20 ? ceil($all_products->total()/20) : 1)
                        @php($page = 1)
                        @if($all_products->total() > 20)
                            <ul class="search-pagination justify-content-end">
                                <li>
                                    <label for="paginate_{{($page-1)}}" class="paginate_{{($page-1)}}"
                                    ><i class="bi bi-chevron-left"></i>
                                    </label>
                                </li>

                                @for ($i = 1; $i <= $paginate_count; $i++)
                                    <li>
                                        <label class="paginate_{{$i}} {{$page == $i?'active':''}}" for="paginate_{{$i}}">{{$i}}</label>
                                        <input class="paginate_btn paginate_btn_id{{$i}} d-none" type="radio" name="page" id="paginate_{{$i}}" value="{{$i}}" {{$page == $i?'checked':''}}>
                                    </li>
                                @endfor
                                <li>
                                    <label for="paginate_{{($page+1)}}" class="paginate_{{($page+1)}}"
                                    ><i class="bi bi-chevron-right"></i>
                                    </label>
                                </li>
                            </ul>
                        @endif

                    </div>

                </article>
            </main>
        </form>
    </div>
</section>

@if($all_products->total() > 20)
<script>
    "use strict";

    @for ($i = 1; $i <= $paginate_count; $i++)
    $('.paginate_'+{{$i}}).on('click', function (){
        inputTypeNumberClick('{{$i}}');
    })
    @endfor

    @if ($page != $paginate_count)
    $('.paginate_'+{{$page}}).on('click', function (){
        inputTypeNumberClick('{{$page}}');
    })
    @endif

    @if ($page != 1)
    $('.paginate_'+{{$page-1}}).on('click', function (){
        inputTypeNumberClick('{{$page-1}}');
    })
    @endif

</script>
@endif
