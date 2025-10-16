@extends('layouts.back-end.app')
@section('title', translate('order_List'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/plugins/icheck-bootstrap.min.css') }}">
    <style>
        #pagination button.active {
            background-color: #0d6efd;
            color: white;
        }
    </style>
@endpush
@section('content')
    <div class="content container-fluid">
        <div>
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <h2 class="h1 mb-0">
                    <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/all-orders.png') }}" class="mb-1 mr-1"
                        alt="">
                    {{ translate('orders') }}
                </h2>
            </div>

            {{-- Filter --}}
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.orders.list', ['status' => request('status')]) }}" id="form-data"
                        method="GET">
                        <div class="row gx-2">
                            <div class="col-12">
                                <h4 class="mb-3 text-capitalize">{{ translate('filter_order') }}</h4>
                            </div>
                            @if (request('delivery_man_id'))
                                <input type="hidden" name="delivery_man_id" value="{{ request('delivery_man_id') }}">
                            @endif

                            <div class="col-12">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-sm-6 col-lg-2 col-xl-2">
                                        <div class="form-group">
                                            <label class="title-color text-capitalize"
                                                for="orderType">{{ translate('order_type') }}</label>
                                            <select name="order_type" id="orderType" class="form-control">
                                                <option value="all" {{ $orderType == 'all' ? 'selected' : '' }}>
                                                    {{ translate('all') }}</option>
                                                <option value="web" {{ $orderType == 'web' ? 'selected' : '' }}>
                                                    Frontend</option>
                                                <option value="POS" {{ $orderType == 'POS' ? 'selected' : '' }}>In House
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-2 col-xl-2" id="payment_status">
                                        <div class="form-group">
                                            <label class="title-color" for="store">Payment Status</label>
                                            <select name="payment_status" id="payment_status" class="form-control">
                                                <option value="all">{{ translate('all') }}</option>
                                                <option {{ $payment_status == 'paid' ? 'selected' : '' }} value="paid"
                                                    id="paid">Paid </option>
                                                <option {{ $payment_status == 'unpaid' ? 'selected' : '' }} value="unpaid"
                                                    id="unpaid">Unpaid</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6 col-lg-2 col-xl-2">
                                        <label>Users</label>
                                        <select name="user_id" class="form-control select2" style="width: 100%;">
                                            <option value="all">All Users</option>
                                            @foreach ($users as $user)
                                                <option {{ $userId == $user->id ? 'selected' : '' }}
                                                    value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-lg-2 col-xl-2">
                                        <label class="title-color" for="date_type">{{ translate('date_type') }}</label>
                                        <div class="form-group">
                                            <select class="form-control __form-control" name="date_type" id="date_type">
                                                <option value="all" selected disabled>
                                                    {{ translate('select_Date_Type') }}
                                                </option>
                                                <option value="today" {{ $dateType == 'today' ? 'selected' : '' }}>
                                                    Today
                                                </option>
                                                <option value="this_week" {{ $dateType == 'this_week' ? 'selected' : '' }}>
                                                    {{ translate('this_Week') }}</option>
                                                <option value="this_month"
                                                    {{ $dateType == 'this_month' ? 'selected' : '' }}>
                                                    {{ translate('this_Month') }}</option>
                                                <option value="this_year" {{ $dateType == 'this_year' ? 'selected' : '' }}>
                                                    {{ translate('this_Year') }}</option>
                                                <option value="custom_date"
                                                    {{ $dateType == 'custom_date' ? 'selected' : '' }}>
                                                    {{ translate('custom_Date') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3" id="from_div">
                                        <label class="title-color" for="customer">{{ translate('start_date') }}</label>
                                        <div class="form-group">
                                            <input type="date" name="from" value="{{ $from }}" id="from_date"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3" id="to_div">
                                        <label class="title-color" for="customer">{{ translate('end_date') }}</label>
                                        <div class="form-group">
                                            <input type="date" value="{{ $to }}" name="to" id="to_date"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-lg-2 col-xl-2">
                                        <div class="form-group">
                                            <label class="title-color text-capitalize" for="deliveryType">Delivery
                                                Type</label>
                                            <select name="deliveryType" id="deliveryType" class="form-control">
                                                <option value="all" {{ $deliveryType == 'all' ? 'selected' : '' }}>
                                                    {{ translate('all') }}
                                                </option>
                                                <option value="steadfast"
                                                    {{ $deliveryType == 'steadfast' ? 'selected' : '' }}>
                                                    Steadfast
                                                </option>
                                                <option value="pathao" {{ $deliveryType == 'pathao' ? 'selected' : '' }}>
                                                    Pathao
                                                </option>
                                                <option value="pathao_time_luxe" {{ $deliveryType == 'pathao_time_luxe' ? 'selected' : '' }}>
                                                    Pathao Time Luxe
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{ route('admin.orders.list', ['status' => request('status')]) }}"
                                        class="btn btn-secondary px-5">
                                        {{ translate('reset') }}
                                    </a>
                                    <button type="submit" class="btn btn--primary px-5" id="formUrlChange"
                                        data-action="{{ url()->current() }}">
                                        {{ translate('show_data') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Filter End --}}


            <div class="card mt-3">
                <div class="card-body">
                    <div class="px-3 py-4 light-bg">
                        <div class="row g-2 align-items-center flex-grow-1">
                            <div class="col-md-4">
                                <h5 class="text-capitalize d-flex gap-1">
                                    {{ translate('order_list') }}
                                    <span class="badge badge-soft-dark radius-50 fz-12">{{ $orders->count() }}</span>
                                </h5>
                            </div>
                            <div class="col-md-8 d-flex gap-3 flex-wrap flex-sm-nowrap justify-content-md-end">
                                <form action="" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input type="text" id="orderSearch" placeholder="Search orders..."
                                            class="form-control">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive datatable-custom">
                        <form action="{{ route('admin.multipleInvoice') }}" method="POST">
                            @csrf
                            <table id="ordersTable"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                                <thead class="thead-light thead-50 text-capitalize">
                                    <thead class="thead-light thead-50 text-capitalize">
                                        <tr>
                                            @if ($status == 'out_for_delivery')
                                                <th>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="select_all" value=""
                                                            id="select_all">
                                                        <label for="select_all"></label>
                                                    </div>
                                                </th>
                                            @endif
                                            <th>{{ translate('SL') }}</th>
                                            <th>{{ translate('order_ID') }}</th>
                                            <th class="text-capitalize">Invoice ID</th>
                                            <th class="text-capitalize">{{ translate('order_date') }}</th>
                                            <th class="text-capitalize">{{ translate('customer_info') }}</th>
                                            <th>{{ translate('store') }}</th>
                                            <th class="text-capitalize">{{ translate('total_amount') }}</th>
                                            @if ($status == 'all')
                                                <th class="text-center">{{ translate('order_status') }} </th>
                                            @else
                                                <th class="text-capitalize">{{ translate('payment_method') }} </th>
                                            @endif
                                            @if ($status == 'scheduled_delivery')
                                                <th class="text-center">Scheduled Date</th>
                                            @endif
                                            <th class="text-center">Order Placed By</th>
                                            <th class="text-center">{{ translate('action') }}</th>
                                        </tr>
                                    </thead>

                                </thead>

                                <tbody id="tableBody"></tbody>
                                @if ($status == 'out_for_delivery')
                                    <tfoot>
                                        <tr>
                                            <td colspan="10" class="pb-0 pl-0">
                                                <div class="">
                                                    <button class="btn btn--primary" type="submit">Print</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>


                        </form>
                        <div class="text-center mt-3">
                            <button id="loadMore" class="btn btn-primary">
                                <span class="btn-text">Load More</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                            </button>
                        </div>

                    </div>
                    {{-- <div class="table-responsive">
                        <div class="d-flex justify-content-lg-end">
                            {!! $orders->links() !!}
                        </div>
                    </div> --}}

                    <div class="table-responsive">
                        <div class="d-flex justify-content-lg-end">
                            <div id="pagination" class="mt-3 d-flex gap-2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal d-none">
                <span class="hs-nav-scroller-arrow-prev d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:">
                        <i class="tio-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:">
                        <i class="tio-chevron-right"></i>
                    </a>
                </span>
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">{{ translate('order_list') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <span id="message-date-range-text" data-text="{{ translate('invalid_date_range') }}"></span>
    <span id="js-data-example-ajax-url" data-url="{{ route('admin.orders.customers') }}"></span>
@endsection

@push('script_2')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/order.js') }}"></script>



    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true, // চাইলে true করো
                "info": true,
                "responsive": true
            });
        });
    </script>

    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on('click', '.delete-confirm', function(e) {
            e.preventDefault();

            const url = $(this).data('url');
            const orderId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This order will be deleted permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff0000',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ✅ Create a temporary form for GET submission
                    const form = document.createElement('form');
                    form.method = 'GET';
                    form.action = url;

                    // Hidden input to send the ID
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'id';
                    input.value = orderId;
                    form.appendChild(input);

                    // Append and submit
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>

    <input type="text" id="orderSearch" class="form-control mb-3" placeholder="Search Order...">

    <script>
        let currentPage = 1;
        let currentStatus = "{{ $status }}";
        let currentSearch = "";

        function loadOrders(reset = false) {
            let button = $("#loadMore");

            button.find('.btn-text').addClass('d-none');
            button.find('.spinner-border').removeClass('d-none');

            $.ajax({
                url: "{{ route('admin.orders.getOrders') }}",
                data: {
                    page: currentPage,
                    status: currentStatus,
                    search: currentSearch,
                    order_type: "{{ $orderType }}",
                    payment_status: "{{ $payment_status }}",
                    user_id: "{{ $userId }}",
                    date_type: "{{ $dateType }}",
                    from: "{{ $from }}",
                    to: "{{ $to }}",
                    deliveryType: "{{ $deliveryType }}",
                },
                cache: false, // ✅ cache বন্ধ
                success: function(res) {
                    if (reset) {
                        $("#tableBody").html("");
                    }
                    $("#tableBody").append(res.data);

                    if (!res.hasMore) {
                        button.hide();
                    } else {
                        button.show();
                    }
                },
                complete: function() {
                    button.find('.btn-text').removeClass('d-none');
                    button.find('.spinner-border').addClass('d-none');
                }
            });
        }

        // প্রথমবার লোড
        loadOrders(true);

        // Load More
        $("#loadMore").on("click", function() {
            currentPage++;
            loadOrders();
        });

        // ✅ Search input
        $("#orderSearch").on("keyup", function() {
            currentSearch = $(this).val();
            currentPage = 1;
            loadOrders(true);
        });
    </script>


    <script>
        $(document).on('change', '#select_all', function() {
            let checked = $(this).prop('checked');
            $('input[name="id[]"]').prop('checked', checked);
        });

        // যদি আলাদা row uncheck করে, তবে "select_all" ও uncheck হবে
        $(document).on('change', 'input[name="id[]"]', function() {
            if ($('input[name="id[]"]').length === $('input[name="id[]"]:checked').length) {
                $('#select_all').prop('checked', true);
            } else {
                $('#select_all').prop('checked', false);
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#orderSearch").on("keyup", function() {
                let value = $(this).val().toLowerCase();

                $("#ordersTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
@endpush
