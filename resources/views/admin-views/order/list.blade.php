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
                <span class="badge badge-soft-dark radius-50 fz-14">{{ $orders->count() }}</span>
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
                                        <input type="text" id="searchInput" placeholder="Search orders..."
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

                                <tbody id="tableBody">
                                    @foreach ($orders as $key => $order)
                                        <tr class="status-{{ $order['order_status'] }} class-all">
                                            @if ($status == 'out_for_delivery')
                                                <td>
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" name="id[]"
                                                            value="{{ $order->id }}" id="id_{{ $order->id }}">
                                                        <label for="id_{{ $order->id }}"></label>
                                                    </div>
                                                </td>
                                            @endif
                                            <td>{{ $key + 1 }}</td>
                                            </td>
                                            <td>
                                                <a class="title-color"
                                                    href="{{ route('admin.orders.details', ['id' => $order['id']]) }}">{{ $order['id'] }}
                                                    {!! $order->order_type == 'POS' ? '<span class="text--primary">(POS)</span>' : '' !!}</a>
                                            </td>
                                            <td>{{ $order['invoice_id'] }}</td>
                                            <td>
                                                <div>{{ date('d M Y', strtotime($order['created_at'])) }},</div>
                                                <div>{{ date('h:i A', strtotime($order['created_at'])) }}</div>
                                            </td>
                                            <td>
                                                @if ($order->is_guest)
                                                    <strong class="title-name">{{ translate('guest_customer') }}</strong>
                                                @elseif($order->customer_id == 0)
                                                    <strong
                                                        class="title-name">{{ translate('walking_customer') }}</strong>
                                                @else
                                                    @if ($order->customer)
                                                        <a class="text-body text-capitalize"
                                                            href="{{ route('admin.orders.details', ['id' => $order['id']]) }}">
                                                            <strong
                                                                class="title-name">{{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}</strong>
                                                        </a>
                                                        @if ($order->customer['phone'])
                                                            <a class="d-block title-color"
                                                                href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                                        @else
                                                            <a class="d-block title-color"
                                                                href="mailto:{{ $order->customer['email'] }}">{{ $order->customer['email'] }}</a>
                                                        @endif
                                                    @else
                                                        <label
                                                            class="badge badge-danger fz-12">{{ translate('invalid_customer_data') }}</label>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <span class="store-name font-weight-medium">
                                                    @if ($order->seller_is == 'admin')
                                                        {{ translate('in_House') }}
                                                    @else
                                                        Web
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    @php($discount = 0)
                                                    @if (
                                                        $order->order_type == 'default_type' &&
                                                            $order->coupon_discount_bearer == 'inhouse' &&
                                                            !in_array($order['coupon_code'], [0, null]))
                                                        @php($discount = $order->discount_amount)
                                                    @endif

                                                    @php($free_shipping = 0)
                                                    @if ($order->is_shipping_free)
                                                        @php($free_shipping = $order->shipping_cost)
                                                    @endif
                                                    @php($totalAmount = $order->order_amount + $discount + $free_shipping)

                                                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->advanced == 1 ? '0' : $totalAmount), currencyCode: getCurrencyCode()) }}
                                                </div>

                                                @if ($order->payment_status == 'paid')
                                                    <span class="badge badge-soft-success">{{ translate('paid') }}</span>
                                                @else
                                                    <span class="badge badge-soft-danger">{{ translate('unpaid') }}</span>
                                                @endif
                                            </td>
                                            @if ($status == 'all')
                                                <td class="text-center text-capitalize">
                                                    @if ($order['order_status'] == 'pending')
                                                        <span class="badge badge-soft-info fz-12">
                                                            {{ translate($order['order_status']) }}
                                                        </span>
                                                    @elseif($order['order_status'] == 'processing' || $order['order_status'] == 'out_for_delivery')
                                                        <span class="badge badge-soft-warning fz-12">
                                                            {{ str_replace('_', ' ', $order['order_status'] == 'processing' ? translate('packaging') : translate($order['order_status'])) }}
                                                        </span>
                                                    @elseif($order['order_status'] == 'confirmed')
                                                        <span class="badge badge-soft-success fz-12">
                                                            {{ translate($order['order_status']) }}
                                                        </span>
                                                    @elseif($order['order_status'] == 'failed')
                                                        <span class="badge badge-danger fz-12">
                                                            {{ translate('failed_to_deliver') }}
                                                        </span>
                                                    @elseif($order['order_status'] == 'delivered')
                                                        <span class="badge badge-soft-success fz-12">
                                                            {{ translate($order['order_status']) }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-danger fz-12">
                                                            {{ translate($order['order_status']) }}
                                                        </span>
                                                    @endif
                                                </td>
                                            @else
                                                <td class="text-capitalize">
                                                    {{ str_replace('_', ' ', $order['payment_method']) }}
                                                </td>
                                            @endif
                                            @if ($status == 'scheduled_delivery')
                                                <?php
                                                $isDueOrToday = \Carbon\Carbon::parse($order->scheduled_date)->isSameDay(today()) || \Carbon\Carbon::parse($order->scheduled_date)->isPast();

                                                ?>

                                                <td class="text-center text-capitalize">
                                                    <span
                                                        class="badge badge-{{ $isDueOrToday ? 'danger' : 'soft-success' }} fz-12">
                                                        {{ $order->scheduled_date }}
                                                    </span>
                                                </td>
                                            @endif
                                            <td class="text-center text-capitalize">
                                                @if ($order->seller_id != 0)
                                                    <span class="badge badge-soft-success fz-12">
                                                        {{ App\Models\Admin::where('id', $order->seller_id)->value('name') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-success fz-12">
                                                        Customer
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-outline--primary square-btn btn-sm mr-1"
                                                        title="{{ translate('view') }}"
                                                        href="{{ route('admin.orders.details', ['id' => $order['id']]) }}">
                                                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/eye.svg') }}"
                                                            class="svg" alt="">
                                                    </a>
                                                    <a class="btn btn-outline-success square-btn btn-sm mr-1"
                                                        target="_blank" title="{{ translate('invoice') }}"
                                                        href="{{ route('admin.invoice', [$order['id']]) }}">
                                                        <i class="tio-receipt"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-outline-danger square-btn btn-sm mr-1 delete-confirm"
                                                        title="Transfer to delivery" data-id="{{ $order['id'] }}"
                                                        data-url="{{ route('admin.orderDelete') }}">
                                                        <i class="tio-delete"></i>
                                                    </button>
                                                    @if ($status == 'confirmed')
                                                        <a href="{{ route('admin.steadfast.page', $order['id']) }}"
                                                            class="btn btn-outline-primary square-btn mx-4"
                                                            title="Transfer to delivery"><i class="tio-truck"></i></a>
                                                    @endif



                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delivery-confirm').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const url = this.getAttribute('data-url');
                    const orderId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This order will be transferred to delivery!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#00c9a7',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, Transfer it!'
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
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-confirm').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const url = this.getAttribute('data-url');
                    const orderId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This order will be Deleted Parmanently!",
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
            });
        });
    </script>

    <script>
        const rowsPerPage = 20;
        let currentPage = 1;
        const table = document.getElementById('ordersTable');
        const tableBody = document.getElementById('tableBody');
        const rows = Array.from(tableBody.getElementsByTagName('tr'));
        const pagination = document.getElementById('pagination');
        const searchInput = document.getElementById('searchInput');

        function displayPage(page, filteredRows = rows) {
            currentPage = page;
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            // Hide all rows
            rows.forEach(row => row.style.display = 'none');

            // Show only selected rows
            filteredRows.slice(start, end).forEach(row => row.style.display = '');

            // Update pagination buttons
            updatePaginationButtons(filteredRows.length);
        }

        function updatePaginationButtons(totalRows) {
            const totalPages = Math.ceil(totalRows / rowsPerPage);
            pagination.innerHTML = '';

            const maxVisibleButtons = 5; // how many page numbers to show
            const half = Math.floor(maxVisibleButtons / 2);

            // Previous Button
            const prevBtn = document.createElement('button');
            prevBtn.innerHTML = '&laquo;';
            prevBtn.className = 'btn btn-sm btn-outline-primary';
            prevBtn.disabled = currentPage === 1;
            prevBtn.addEventListener('click', () => displayPage(currentPage - 1, filterRows()));
            pagination.appendChild(prevBtn);

            let startPage = Math.max(1, currentPage - half);
            let endPage = Math.min(totalPages, currentPage + half);

            if (currentPage <= half) {
                endPage = Math.min(totalPages, maxVisibleButtons);
            }

            if (currentPage + half > totalPages) {
                startPage = Math.max(1, totalPages - maxVisibleButtons + 1);
            }

            if (startPage > 1) {
                addPageButton(1);
                if (startPage > 2) {
                    addEllipsis();
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                addPageButton(i);
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    addEllipsis();
                }
                addPageButton(totalPages);
            }

            // Next Button
            const nextBtn = document.createElement('button');
            nextBtn.innerHTML = '&raquo;';
            nextBtn.className = 'btn btn-sm btn-outline-primary';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.addEventListener('click', () => displayPage(currentPage + 1, filterRows()));
            pagination.appendChild(nextBtn);

            function addPageButton(i) {
                const btn = document.createElement('button');
                btn.innerText = i;
                btn.className = 'btn btn-sm btn-outline-primary';
                if (i === currentPage) btn.classList.add('active');
                btn.addEventListener('click', () => displayPage(i, filterRows()));
                pagination.appendChild(btn);
            }

            function addEllipsis() {
                const span = document.createElement('span');
                span.innerText = '...';
                span.className = 'btn btn-sm btn-outline-secondary disabled';
                pagination.appendChild(span);
            }
        }

        function filterRows() {
            const query = searchInput.value.toLowerCase();
            return rows.filter(row => row.textContent.toLowerCase().includes(query));
        }

        // Handle search
        searchInput.addEventListener('input', () => displayPage(1, filterRows()));

        // Initial display
        displayPage(1);
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select_all');
            const checkboxes = document.querySelectorAll('input[name="id[]"]');

            // Select all or unselect all
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = selectAll.checked;
                });
            });

            // Uncheck "select all" if any single checkbox is unchecked
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    if (!this.checked) {
                        selectAll.checked = false;
                    } else {
                        // If all checkboxes are checked, then check the selectAll
                        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                        selectAll.checked = allChecked;
                    }
                });
            });
        });
    </script>
@endpush
