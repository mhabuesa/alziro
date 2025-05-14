@extends('layouts.back-end.app-seller')
@section('title', translate('dashboard'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header pb-0 border-0 mb-3">
            <div class="flex-between row align-items-center mx-1">
                <div>
                    <h1 class="page-header-title text-capitalize">{{translate('welcome').' '.auth('seller')->user()->f_name.' '.auth('seller')->user()->l_name}}</h1>
                    <p>{{ translate('monitor_your_business_analytics_and_statistics').'.'}}</p>
                </div>

                <div>
                    <a class="btn btn--primary" href="{{route('vendor.products.list',['type'=>'all'])}}">
                        <i class="tio-premium-outlined mr-1"></i> {{translate('products')}}
                    </a>
                </div>
            </div>
        </div>
        <div class="card mb-3 remove-card-shadow">
            <div class="card-body">
                <div class="row justify-content-between align-items-center g-2 mb-3">
                    <div class="col-sm-6">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/business_analytics.png')}}" alt="">
                            {{translate('order_analytics')}}
                        </h4>
                    </div>
                    <div class="col-sm-6 d-flex justify-content-sm-end">
                        <select class="custom-select w-auto" id="statistics_type" name="statistics_type">
                            <option value="overall">
                                {{translate('overall_Statistics')}}
                            </option>
                            <option value="today">
                                {{translate('todays_Statistics')}}
                            </option>
                            <option value="thisMonth">
                                {{translate('this_Months_Statistics')}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row g-2" id="order_stats">
                    @include('vendor-views.partials._dashboard-order-status',['orderStatus'=>$dashboardData['orderStatus']])
                </div>
            </div>
        </div>
        <div class="card mb-3 remove-card-shadow">
            <div class="card-body">
                <div class="row justify-content-between align-items-center g-2 mb-3">
                    <div class="col-sm-6">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            <img width="20" class="mb-1" src="{{dynamicAsset(path: 'public/assets/back-end/img/admin-wallet.png')}}" alt="">
                            {{translate('vendor_Wallet')}}
                        </h4>
                    </div>
                </div>
                <div class="row g-2" id="order_stats">
                    @include('vendor-views.partials._dashboard-wallet-status',['dashboardData'=>$dashboardData])
                </div>
            </div>
        </div>

        <div class="modal fade" id="balance-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{translate('withdraw_Request')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('vendor.dashboard.withdraw-request')}}" method="post">
                        <div class="modal-body">
                            @csrf
                            <div class="">
                                <select class="form-control" id="withdraw_method" name="withdraw_method" required>
                                    @foreach($withdrawalMethods as $method)
                                        <option value="{{$method['id']}}" {{ $method['is_default'] ? 'selected':'' }}>{{$method['method_name']}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="" id="method-filed__div">

                            </div>

                            <div class="mt-1">
                                <label for="recipient-name" class="col-form-label fz-16">{{translate('amount')}}
                                    :</label>
                                <input type="number" name="amount" step=".01"
                                       value="{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $dashboardData['totalEarning']), currencyCode: getCurrencyCode(type: 'default'))}}"
                                       class="form-control" id="">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{translate('close')}}</button>
                                <button type="submit"
                                        class="btn btn--primary">{{translate('request')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-lg-12">
                <div class="card h-100 remove-card-shadow">
                    <div class="card-body">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-6">
                                <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                    <img src="{{dynamicAsset(path: 'public/assets/back-end/img/earning_statictics.png')}}" alt="">
                                    {{translate('earning_statistics')}}
                                </h4>
                            </div>
                            <div class="col-md-6 d-flex justify-content-md-end">
                                <ul class="option-select-btn">
                                    <li>
                                        <label class="basic-box-shadow">
                                            <input type="radio" name="statistics2" hidden="" checked="">
                                            <span data-earn-type="yearEarn" class="earning-statistics">{{translate('this_Year')}}</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="basic-box-shadow">
                                            <input type="radio" name="statistics2" hidden="">
                                            <span data-earn-type="MonthEarn" class="earning-statistics">{{translate('this_Month')}}</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="basic-box-shadow">
                                            <input type="radio" name="statistics2" hidden="">
                                            <span data-earn-type="WeekEarn" class="earning-statistics">{{translate('this_Week')}}</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="chartjs-custom mt-2" id="set-new-graph">
                            <canvas id="updatingData" class="earningShow"
                                    data-hs-chartjs-options='{
                            "type": "bar",
                            "data": {
                              "labels": ["Jan","Feb","Mar","April","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                              "datasets": [{
                                "label": "{{ translate('income')}}",
                                "data": [
                                            @php($index = 0)
                                            @php($array_count = count($vendorEarningArray))
                                            @foreach($vendorEarningArray as $value)
                                                {{ $value }}{{ (++$index < $array_count) ? ',':'' }}
                                            @endforeach
                                        ],
                                        "backgroundColor": "#0177CD",
                                        "borderColor": "#0177CD"
                                      },
                                      {
                                        "label": "{{ translate('commission_Given')}}",
                                        "data": [
                                                @php($index = 0)
                                                @php($array_count = count($commissionGivenToAdminArray))
                                                @foreach($commissionGivenToAdminArray as $value)
                                                    {{ $value }}{{ (++$index < $array_count) ? ',':'' }}
                                                @endforeach
                                        ],
                                        "backgroundColor": "#FFB36D",
                                        "borderColor": "#FFB36D"
                                      }]
                                    },
                                    "options": {
                                    "legend": {
                                        "display": true,
                                        "position": "top",
                                        "align": "center",
                                        "labels": {
                                            "usePointStyle": true,
                                            "boxWidth": 6,
                                            "fontColor": "#758590",
                                            "fontSize": 14
                                        }
                                    },
                                      "scales": {
                                        "yAxes": [{
                                          "gridLines": {
                                                "color": "rgba(180, 208, 224, 0.5)",
                                                "borderDash": [8, 4],
                                                "drawBorder": false,
                                                "zeroLineColor": "rgba(180, 208, 224, 0.5)"
                                          },
                                          "ticks": {
                                            "beginAtZero": true,
                                            "fontSize": 12,
                                            "fontColor": "#97a4af",
                                            "fontFamily": "Open Sans, sans-serif",
                                            "padding": 10,
                                            "postfix": "{{ getCurrencySymbol(currencyCode: getCurrencyCode(type: 'default')) }}"
                                  }
                                }],
                                "xAxes": [{
                                  "gridLines": {
                                        "color": "rgba(180, 208, 224, 0.5)",
                                        "display": true,
                                        "drawBorder": true,
                                        "zeroLineColor": "rgba(180, 208, 224, 0.5)"
                                  },
                                  "ticks": {
                                    "fontSize": 12,
                                    "fontColor": "#97a4af",
                                    "fontFamily": "Open Sans, sans-serif",
                                    "padding": 5
                                  },
                                  "categoryPercentage": 0.5,
                                  "maxBarThickness": "7"
                                }]
                              },
                              "cornerRadius": 3,
                              "tooltips": {
                                "prefix": " ",
                                "hasIndicator": true,
                                "mode": "index",
                                "intersect": false
                              },
                              "hover": {
                                "mode": "nearest",
                                "intersect": true
                              }
                            }
                          }'></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 remove-card-shadow">
                    @include('vendor-views.partials._top-rated-products',['topRatedProducts'=>$dashboardData['topRatedProducts']])
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 remove-card-shadow">
                    @include('vendor-views.partials._top-selling-products',['topSell'=>$dashboardData['topSell']])
                </div>
            </div>
            @php( $shippingMethod = getWebConfig('shipping_method'))
            @if($shippingMethod=='sellerwise_shipping')
                <div class="col-lg-4">
                    <div class="card h-100 remove-card-shadow">
                        @include('vendor-views.partials._top-rated-delivery-man',['topRatedDeliveryMan'=>$dashboardData['topRatedDeliveryMan']])
                    </div>
                </div>
           @endif
        </div>
    </div>
    <span id="earning-statistics-url" data-url="{{ route('vendor.dashboard.earning-statistics') }}"></span>
    <span id="withdraw-method-url" data-url="{{ route('vendor.dashboard.method-list') }}"></span>
    <span id="order-status-url" data-url="{{ route('vendor.dashboard.order-status', ['type' => ':type']) }}"></span>
    <span id="seller-text" data-text="{{ translate('vendor')}}"></span>
    <span id="in-house-text" data-text="{{ translate('In-house')}}"></span>
    <span id="customer-text" data-text="{{ translate('customer')}}"></span>
    <span id="store-text" data-text="{{ translate('store')}}"></span>
    <span id="product-text" data-text="{{ translate('product')}}"></span>
    <span id="order-text" data-text="{{ translate('order')}}"></span>
    <span id="brand-text" data-text="{{ translate('brand')}}"></span>
    <span id="business-text" data-text="{{ translate('business')}}"></span>
    <span id="customers-text" data-text="{{ $dashboardData['customers'] }}"></span>
    <span id="products-text" data-text="{{ $dashboardData['products'] }}"></span>
    <span id="orders-text" data-text="{{ $dashboardData['orders'] }}"></span>
    <span id="brands-text" data-text="{{ $dashboardData['brands'] }}"></span>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/vendor/chart.js/dist/Chart.min.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/vendor/chart.js.extensions/chartjs-extensions.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js')}}"></script>
@endpush

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/dashboard.js')}}"></script>
@endpush
