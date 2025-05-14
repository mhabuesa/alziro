@php use Illuminate\Support\Str; @endphp
@extends('layouts.back-end.app')

@section('title', translate('customer_import'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/customer.png') }}" alt="">
                Import Customer
                <span class="badge badge-soft-dark radius-50"></span>
            </h2>
        </div>
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Import Customer</h4>
                        <h5 class="text-danger">The first row must contain the headers: name, phone, address.</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.customer.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mt-3">
                                <label for="formFile" class="form-label">{{ translate('upload_csv_file') }}
                                    <span class="text-danger">*</span">
                                </label>
                                <input type="file" class="form-control" name="file" id="file_name">
                                @error('file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mt-5">
                                <button class="btn btn--primary" type="submit">Import</button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
