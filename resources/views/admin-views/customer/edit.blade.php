@php use Illuminate\Support\Str; @endphp
@extends('layouts.back-end.app')

@section('title', translate('customer_edit'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/customer.png') }}" alt="">
                {{ translate('customer_edit') }}
            </h2>
        </div>
        <div class="row gy-2 align-items-center">
            <div class="col-sm-8 col-md-6 col-lg-4 m-auto">
                <div class="card">
                    <div class="px-3 py-4">
                        <form action="{{ route('admin.customer.update.info', $customer->id) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="input-label" for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $customer->name }}" required placeholder="Enter Customer Name">
                            </div>
                            <div class="mb-3">
                                <label class="input-label" for="phone">Phone <span class="text-danger">*</span></label>
                                <input type="phone" name="phone" id="phone" class="form-control" value="{{ $customer->phone }}" required readonly placeholder="Enter Customer Phone">
                            </div>
                            <div class="mb-3">
                                <label class="input-label" for="email">Email <small class="text-muted">(optional)</small></label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ $customer->email }}" placeholder="Enter Customer Email" >
                                <small class="text-muted">Leave it blank if you don't want to change it.</small>
                            </div>
                            <div class="mb-3">
                                <label class="input-label" for="address">Address <span class="text-danger">*</span></label>
                                <input type="address" name="address" id="address" class="form-control" value="{{ $customer->street_address }}" required placeholder="Enter Customer Address">
                            </div>
                            <div class="mb-3">
                                <label class="input-label" for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                                <small class="text-muted">Leave it blank if you don't want to change it.</small>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="input-label" for="con_password">Confirm Password</label>
                                <input type="password" name="con_password" id="con_password" class="form-control" placeholder="Enter Password Confirmation">
                                <small class="text-muted">Leave it blank if you don't want to change it.</small>
                                @error('con_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mt-4 d-flex justify-content-center">
                                <button class="btn btn--primary w-25" type="submit">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
