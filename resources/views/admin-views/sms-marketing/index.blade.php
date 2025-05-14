@extends('layouts.back-end.app')
@section('title', 'SMS Marketing')
@section('css_or_js')


@endsection
@section('content')

    <section class="section-content pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title text-capitalize m-auto">SMS Marketing</h3>
                        </div>
                        <div class="card-body pb-5">
                            <form action="{{ route('admin.smsMarketing.send') }}" method="POST">
                                <div class="row d-flex justify-content-center">
                                    @csrf
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <label for="message_body" class="form-label">Message Body</label>
                                                    <textarea name="message" class="form-control" id="message_body" cols="30" rows="14" required
                                                        placeholder="Write your message here"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label for="customer_selection" class="form-label">Select
                                                        Customer</label>
                                                    <select name="customer_selection" id="customer_selection"
                                                        class="form-control">
                                                        <option value="all">All</option>
                                                        <option value="week">Last one Week</option>
                                                        <option value="15days">Last 15 Days</option>
                                                        <option value="month">Last one Month</option>
                                                        <option value="custom">Custom Date</option>
                                                    </select>
                                                </div>
                                                <div id="custom_date"
                                                    style="max-height: 0; overflow: hidden; transition: max-height 0.5s ease;">
                                                    <div class="row d-flex justify-content-center">
                                                        <div class="col-lg-6" id="from_div">
                                                            <label class="title-color"
                                                                for="customer">{{ translate('start_date') }}</label>
                                                            <div class="form-group">
                                                                <input type="date" name="from" id="from_date"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6" id="to_div">
                                                            <label class="title-color"
                                                                for="customer">{{ translate('end_date') }}</label>
                                                            <div class="form-group">
                                                                <input type="date" name="to" id="to_date"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-5">
                                                    <label for="filter_customer" class="form-label">Filter Customers by
                                                        Order Status</label>
                                                    <select name="filter_customer" id="filter_customer"
                                                        class="form-control">
                                                        <option value="all">All Customers</option>
                                                        <option value="ordered">Customers Who Ordered</option>
                                                        <option value="not_ordered">Customers Who Didn't Order</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="message_body" class="form-label">
                                                        Total Customers: <strong
                                                            id="total_customers">{{ $customers->count() }}</strong>
                                                    </label>
                                                    @php
                                                        $smsClass = 'text-success';

                                                        if (is_null($sms) || $sms < $customers->count()) {
                                                            $smsClass = 'text-danger';
                                                        }
                                                    @endphp

                                                    <p>
                                                        SMS Credit:
                                                        <strong id="sms_credit" class="{{ $smsClass }}">
                                                            {{ $sms ?? 'N/A' }}
                                                        </strong>
                                                        <span id="insufficient_sms"
                                                            class="text-danger {{ !is_null($sms) && $sms >= $customers->count() ? 'd-none' : '' }}">
                                                            (Insufficient credit)
                                                        </span>
                                                    </p>


                                                </div>

                                                <div class="mt-5">
                                                    <button class="btn btn--primary w-100" type="submit">Send SMS</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
@push('script_2')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function fetchCustomerCount() {
                const selection = document.getElementById('customer_selection').value;
                const filter = document.getElementById('filter_customer').value;
                const from = document.getElementById('from_date').value;
                const to = document.getElementById('to_date').value;

                fetch("{{ route('admin.customer.count') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            selection: selection,
                            filter: filter,
                            from: from,
                            to: to
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        const sms = parseFloat("{{ $sms }}"); // Fixed SMS credit
                        const total = data.count;

                        // Update total customer count
                        document.getElementById('total_customers').innerText = total;

                        // Elements
                        const smsCreditElement = document.getElementById('sms_credit');
                        const insufficientSms = document.getElementById('insufficient_sms');

                        // Remove previous color
                        smsCreditElement.classList.remove('text-danger', 'text-success');

                        if (sms < total) {
                            smsCreditElement.classList.add('text-danger');
                            insufficientSms.classList.remove('d-none');
                        } else {
                            smsCreditElement.classList.add('text-success');
                            insufficientSms.classList.add('d-none');
                        }
                    });




            }

            // Event listeners
            document.getElementById('customer_selection').addEventListener('change', function() {
                const customDiv = document.getElementById('custom_date');
                customDiv.style.maxHeight = this.value === 'custom' ? "200px" : "0";
                fetchCustomerCount();
            });

            document.getElementById('filter_customer').addEventListener('change', fetchCustomerCount);
            document.getElementById('from_date').addEventListener('change', fetchCustomerCount);
            document.getElementById('to_date').addEventListener('change', fetchCustomerCount);
        });
    </script>
@endpush
