@extends('layouts.back-end.app')
@section('title', 'Transfer to Steadfast')
@push('css_or_js')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css"
        integrity="sha512-8vq2g5nHE062j3xor4XxPeZiPjmRDh6wlufQlfC6pdQ/9urJkU07NM0tEREeymP++NczacJ/Q59ul+/K2eYvcg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <div class="content container-fluid">
        <div>
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <h2 class="h1 mb-0">
                    <img width="30"
                        src="https://www.clipartmax.com/png/middle/292-2920157_delivery-computer-icons-truck-logistics-clip-art-track-your-order-icon.png"
                        class="mb-1 mr-1 rounded" alt="">
                    Transfer to Steadfast
                </h2>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Order Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.transferToDelivery') }}" method="POST">
                        @csrf
                        <table class="table">
                            <tr>
                                <td>
                                    <label class="form-label">Invoice ID</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="invoice_id" value="{{ $order->invoice_id }}"
                                        readonly>
                                        <input type="hidden" class="form-control" name="order_id" value="{{ $order->id }}">
                                </td>
                            </tr>
                            <tr>
                                @php
                                    if ($order->order_type == 'POS') {
                                        $name = $order->customer->name;
                                        $phone = $order->customer->phone;
                                        $address = $order->customer->street_address;
                                    } else {
                                        $name = $order->customer->name;
                                        $phone = $order->customer->phone;
                                        if ($order->shipping_address_data == null) {
                                            $address = $order->customer->street_address;
                                        } else {
                                            $address = $order->shipping_address_data;
                                        }
                                    }
                                @endphp
                                <td>
                                    <label class="form-label">Recipient Name <span class="text-danger">*</span></label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="name" value="{{ $name }}"
                                        required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="form-label">Recipient Phone <span class="text-danger">*</span></label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="phone" value="{{ $phone }}"
                                        required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="form-label">Recipient Address <span class="text-danger">*</span></label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="address" value="{{ $address }}"
                                        required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="form-label">COD Amount <span class="text-danger">*</span></label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="amount" value="{{ $order->order_amount }}"
                                        required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="form-label">Important Note <small class="text-muted">
                                            (Optional)</small></label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="note" placeholder="Enter Note here"
                                        value="{{ $order->order_note }}">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><button type="submit"
                                        class="btn btn-primary w-25 mt-5">Submit</button></td>
                            </tr>
                        </table>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
