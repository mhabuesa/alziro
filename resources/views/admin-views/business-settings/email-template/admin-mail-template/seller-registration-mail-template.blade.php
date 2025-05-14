@extends('layouts.back-end.app')

@section('title', translate('analytics_script'))

@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/back-end/vendor/swiper/swiper-bundle.min.css')}}"/>
    <style>
        #cke_1_bottom {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/system-setting.png')}}" alt="">
                {{translate('mail_template')}}
            </h2>

            <div>
                <select class="form-control min-w-200">
                    <option value="0">Admin Mail Templates</option>
                    <option value="0">Admin Mail Templates</option>
                    <option value="0">Admin Mail Templates</option>
                    <option value="0">Admin Mail Templates</option>
                    <option value="0">Admin Mail Templates</option>
                </select>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.email-template.admin-mail-template.admin-mail-inline-menu')
        <!-- End Inlile Menu -->

        {{-- Email Template Setup --}}
        <div class="">
            <form action="# " method="POST" enctype="multipart/form-data">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="border rounded border-color-c1 px-4 py-3 d-flex justify-content-between mb-1">
                            <h5 class="mb-0 d-flex">Get Email on New Seller Registration ? </h5>

                            <div class="position-relative">
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row gy-4 gx-xl-4">
                            <div class="col-lg-6 col-xl-5">
                                <h5 class="mb-3">Template UI</h5>
                                <div class="card">
                                    <div class="p-3 px-xl-4 py-sm-5">
                                        <img width="76" class="mb-4" src="{{dynamicAsset(path: 'public/assets/back-end/img/logo.png')}}" alt="">
                                        <h3 class="mb-3">New Store Registration Request</h3>
                                        <p>Hi Admin,</p>
                                        <p><b>Morning Mart</b> has requested to open their store in 6amMart. </p>
                                        <p>Review their request from admin panel.</p>
                                        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/new-store.png')}}" alt="" class="mt-4 mb-3 w-100">
                                        <p>Click on the button below to review the request</p>
                                        <button class="btn btn--primary rounded-0 px-5">See Registration Request</button>
                                        <hr>
                                        <p>Please contact us for any queries, we’re always happy to help. </p>
                                        <p>Thanks & Regards, <br> 6amMart</p>
                                        <div class="d-flex justify-content-center gap-2">
                                            <ul class="list-inline gap-3">
                                                <li><a href="#" class="text-dark">Privacy Policy</a></li>
                                                <li><a href="#" class="text-dark">Contact Us</a></li>
                                            </ul>
                                        </div>
                                        <div class="d-flex gap-4 justify-content-center align-items-center mb-3 fz-16">
                                            <a href="https://pinterest.com/" target="_blank"><i class="tio-pinterest-circle"></i></a>
                                            <a href="https://instagram.com/" target="_blank"><i class="tio-instagram"></i></a>
                                            <a href="https://facebook.com/" target="_blank"><i class="tio-facebook-square"></i></a>
                                            <a href="https://linkedin.com/" target="_blank"><i class="tio-linkedin-square"></i></a>
                                            <a href="https://twitter.com/" target="_blank"><i class="tio-twitter"></i></a>
                                        </div>
                                        <p class="text-center">Copyright 2023 6Valley. All right reserved</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-7">
                                {{-- Lenguage Menu --}}
                                <div class="d-flex justify-content-between gap-3 flex-wrap mb-5">
                                    <div class="table-responsive w-auto ovy-hidden">
                                        <ul class="nav nav-tabs w-fit-content flex-nowrap  border-0">
                                            <li class="nav-item text-capitalize">
                                                <a class="nav-link lang_link active" href="#" id="en-link">English(EN)</a>
                                            </li>
                                            <li class="nav-item text-capitalize">
                                                <a class="nav-link lang_link " href="#" id="sa-link">Arabic(SA)</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="text-primary d-flex align-items-center gap-3 font-weight-bolder mb-2">
                                        {{translate('read_instructions')}}
                                        <div class="ripple-animation" data-toggle="modal" data-target="#readInstructionModal">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" class="svg replaced-svg">
                                                <path d="M9.00033 9.83268C9.23644 9.83268 9.43449 9.75268 9.59449 9.59268C9.75449 9.43268 9.83421 9.2349 9.83366 8.99935V5.64518C9.83366 5.40907 9.75366 5.21463 9.59366 5.06185C9.43366 4.90907 9.23588 4.83268 9.00033 4.83268C8.76421 4.83268 8.56616 4.91268 8.40616 5.07268C8.24616 5.23268 8.16644 5.43046 8.16699 5.66602V9.02018C8.16699 9.25629 8.24699 9.45074 8.40699 9.60352C8.56699 9.75629 8.76477 9.83268 9.00033 9.83268ZM9.00033 13.166C9.23644 13.166 9.43449 13.086 9.59449 12.926C9.75449 12.766 9.83421 12.5682 9.83366 12.3327C9.83366 12.0966 9.75366 11.8985 9.59366 11.7385C9.43366 11.5785 9.23588 11.4988 9.00033 11.4993C8.76421 11.4993 8.56616 11.5793 8.40616 11.7393C8.24616 11.8993 8.16644 12.0971 8.16699 12.3327C8.16699 12.5688 8.24699 12.7668 8.40699 12.9268C8.56699 13.0868 8.76477 13.1666 9.00033 13.166ZM9.00033 17.3327C7.84755 17.3327 6.76421 17.1138 5.75033 16.676C4.73644 16.2382 3.85449 15.6446 3.10449 14.8952C2.35449 14.1452 1.76088 13.2632 1.32366 12.2493C0.886437 11.2355 0.667548 10.1521 0.666992 8.99935C0.666992 7.84657 0.885881 6.76324 1.32366 5.74935C1.76144 4.73546 2.35505 3.85352 3.10449 3.10352C3.85449 2.35352 4.73644 1.7599 5.75033 1.32268C6.76421 0.88546 7.84755 0.666571 9.00033 0.666016C10.1531 0.666016 11.2364 0.884905 12.2503 1.32268C13.2642 1.76046 14.1462 2.35407 14.8962 3.10352C15.6462 3.85352 16.24 4.73546 16.6778 5.74935C17.1156 6.76324 17.3342 7.84657 17.3337 8.99935C17.3337 10.1521 17.1148 11.2355 16.677 12.2493C16.2392 13.2632 15.6456 14.1452 14.8962 14.8952C14.1462 15.6452 13.2642 16.2391 12.2503 16.6768C11.2364 17.1146 10.1531 17.3332 9.00033 17.3327ZM9.00033 15.666C10.8475 15.666 12.4206 15.0168 13.7195 13.7185C15.0184 12.4202 15.6675 10.8471 15.667 8.99935C15.667 7.15213 15.0178 5.57907 13.7195 4.28018C12.4212 2.98129 10.8481 2.33213 9.00033 2.33268C7.1531 2.33268 5.58005 2.98185 4.28116 4.28018C2.98227 5.57852 2.3331 7.15157 2.33366 8.99935C2.33366 10.8466 2.98283 12.4196 4.28116 13.7185C5.57949 15.0174 7.15255 15.6666 9.00033 15.666Z" fill="currentColor"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Logo Upload --}}
                                <div class="form-group">
                                    <label class="title-color">Logo</label>

                                    <div class="input-group">
                                        <div class="custom-file">
                                          <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                          <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Header Content --}}
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/header-content.png')}}" alt="">
                                    <h5 class="mb-0">Header Content</h5>
                                </div>
                                <div class="bg-light p-3 rounded mb-3">
                                    <div class="form-group">
                                        <label for="main_title" class="title-color">Main Title</label>
                                        <input type="text" id="main_title" name="main_title" placeholder="Ex: Main Title" class="form-control">
                                    </div>

                                    <div class="">
                                        <label for="editor" class="title-color">Mail Body</label>
                                        <textarea class="form-control" id="editor" name="value">
                                            Hi Admin, <br>
                                            Morning Mart has requested to open their store in 6amMart. <br>
                                            Review their request from admin panel.
                                        </textarea>
                                    </div>
                                </div>

                                {{-- Product Information --}}
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/header-content.png')}}" alt="">
                                        <h5 class="mb-0">Product Information</h5>
                                    </div>

                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" name="status" checked="">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                                <div class="bg-light p-3 rounded mb-3">
                                    <p>Product Information will be automatically bind from database. If you don’t want to see the information in the mail. just turn the switch button off.</p>
                                </div>

                                {{-- Banner Image --}}
                                <div class="form-group">
                                    <label class="title-color">Banner Image</label>

                                    <div class="input-group">
                                        <div class="custom-file">
                                          <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                          <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Button Content --}}
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/header-content.png')}}" alt="">
                                        <h5 class="mb-0">Button Content</h5>
                                    </div>

                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" name="status" checked="">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                                <div class="bg-light p-3 rounded mb-3">
                                    <div class="row g-2">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <label for="button_name" class="title-color mb-0">Button Name</label>

                                                    <span class="cursor-pointer" title="Button Name">
                                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                                    </span>
                                                </div>
                                                <input type="text" id="button_name" name="button_name" placeholder="Ex: Submit" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <label for="redirect_link" class="title-color mb-0">Redirect Link</label>

                                                    <span class="cursor-pointer" title="Button Name">
                                                        <img width="16" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}" alt="">
                                                    </span>
                                                </div>
                                                <input type="text" id="redirect_link" name="redirect_link" placeholder="Ex: www.google.com" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Order Information --}}
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/header-content.png')}}" alt="">
                                        <h5 class="mb-0">Order Information</h5>
                                    </div>

                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input" name="status" checked="">
                                        <span class="switcher_control"></span>
                                    </label>
                                </div>
                                <div class="bg-light p-3 rounded mb-3">
                                    <p>Order Information will be automatically bind from database. If you don’t want to see the information in the mail. just turn the switch button off.</p>
                                </div>

                                {{-- Footer Content --}}
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/header-content.png')}}" alt="">
                                    <h5 class="mb-0">Footer Content</h5>
                                </div>

                                <div class="bg-light p-3 rounded mb-3">
                                    <div class="form-group">
                                        <label for="section_text" class="title-color font-weight-bold">Section Text</label>
                                        <input type="text" id="section_text" name="section_text" placeholder="Ex: Submit" class="form-control">
                                    </div>
                                    <div class="mb-5">
                                        <label class="title-color font-weight-bold">Page Links</label>
                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <div class="form-check text-left">
                                                <input type="checkbox" class="form-check-input" id="privacy_policy">
                                                <label class="form-check-label text-dark" for="privacy_policy">Privacy Policy</label>
                                            </div>
                                            <div class="form-check text-left">
                                                <input type="checkbox" class="form-check-input" id="refund_policy">
                                                <label class="form-check-label text-dark" for="refund_policy">Refund Policy</label>
                                            </div>
                                            <div class="form-check text-left">
                                                <input type="checkbox" class="form-check-input" id="cancelation_policy">
                                                <label class="form-check-label text-dark" for="cancelation_policy">Cancelation Policy</label>
                                            </div>
                                            <div class="form-check text-left">
                                                <input type="checkbox" class="form-check-input" id="contact_us">
                                                <label class="form-check-label text-dark" for="contact_us">Contact Us</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label class="title-color font-weight-bold">Social Media Links</label>
                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <div class="form-check text-left">
                                                <input type="checkbox" class="form-check-input" id="facebook">
                                                <label class="form-check-label text-dark" for="facebook">Facebook</label>
                                            </div>
                                            <div class="form-check text-left">
                                                <input type="checkbox" class="form-check-input" id="instagram">
                                                <label class="form-check-label text-dark" for="instagram">Instagram</label>
                                            </div>
                                            <div class="form-check text-left">
                                                <input type="checkbox" class="form-check-input" id="twitter">
                                                <label class="form-check-label text-dark" for="twitter">Twitter</label>
                                            </div>
                                            <div class="form-check text-left">
                                                <input type="checkbox" class="form-check-input" id="linkedin">
                                                <label class="form-check-label text-dark" for="linkedin">Linkedin</label>
                                            </div>
                                            <div class="form-check text-left">
                                                <input type="checkbox" class="form-check-input" id="pinterest">
                                                <label class="form-check-label text-dark" for="pinterest">Pinterest</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="copyright_content" class="title-color font-weight-bold">Copyright Content</label>
                                        <input type="text" id="copyright_content" name="copyright_content" placeholder="Ex: Copyright 2023" class="form-control">
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="d-flex justify-content-end gap-3">
                                    <button type="reset" class="btn btn-secondary px-5">Reset</button>
                                    <button type="reset" class="btn btn--primary px-5">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Admin Email Demo Templates --}}
        <div class="my-5">
            <h2 class="mb-4">Admin Email Demo Templates</h2>

            <div class="row gy-4">
                <div class="col-xl-4 col-md-6">
                    <p>forgot password</p>
                    <div class="card">
                        <div class="p-3 px-xl-4 py-sm-5">
                            <div class="text-center">
                                <img width="160" class="mb-4" src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/change-pass.png')}}" alt="">
                                <h3 class="mb-3">Change password request </h3>
                            </div>
                            <p><b>Hi Sabrina,</b></p>
                            <p>Please click <a href="#">Here</a>  or click the link below to change your password</p>
                            <p>Click here <br> <a href="https://6ammart/changepasswordi357092349-38505320">https://6ammart/changepasswordi357092349-38505320</a></p>
                            <hr>
                            <p>Please contact us for any queries, we’re always happy to help. </p>
                            <p>Thanks & Regards, <br> 6amMart</p>

                            <div class="d-flex justify-content-center mb-3">
                                <img width="76" src="{{dynamicAsset(path: 'public/assets/back-end/img/logo.png')}}" alt="">
                            </div>
                            <div class="d-flex justify-content-center gap-2">
                                <ul class="list-inline gap-3">
                                    <li><a href="#" class="text-dark">Privacy Policy</a></li>
                                    <li><a href="#" class="text-dark">Contact Us</a></li>
                                </ul>
                            </div>
                            <div class="d-flex gap-4 justify-content-center align-items-center mb-3 fz-16">
                                <a href="https://pinterest.com/" target="_blank"><i class="tio-pinterest-circle"></i></a>
                                <a href="https://instagram.com/" target="_blank"><i class="tio-instagram"></i></a>
                                <a href="https://facebook.com/" target="_blank"><i class="tio-facebook-square"></i></a>
                                <a href="https://linkedin.com/" target="_blank"><i class="tio-linkedin-square"></i></a>
                                <a href="https://twitter.com/" target="_blank"><i class="tio-twitter"></i></a>
                            </div>
                            <p class="text-center">Copyright 2023 6Valley. All right reserved</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <p>New Store Registration Request</p>
                    <div class="card">
                        <div class="p-3 px-xl-4 py-sm-5">
                            <img width="76" class="mb-4" src="{{dynamicAsset(path: 'public/assets/back-end/img/logo.png')}}" alt="">
                            <h3 class="mb-3">New Store Registration Request</h3>
                            <p>Hi Admin,</p>
                            <p><b>Morning Mart</b> has requested to open their store in 6amMart. </p>
                            <p>Review their request from admin panel.</p>
                            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/new-store.png')}}" alt="" class="mt-4 mb-3 w-100">
                            <p>Click on the button below to review the request</p>
                            <button class="btn btn--primary rounded-0 px-5">See Registration Request</button>
                            <hr>
                            <p>Please contact us for any queries, we’re always happy to help. </p>
                            <p>Thanks & Regards, <br> 6amMart</p>
                            <div class="d-flex justify-content-center gap-2">
                                <ul class="list-inline gap-3">
                                    <li><a href="#" class="text-dark">Privacy Policy</a></li>
                                    <li><a href="#" class="text-dark">Contact Us</a></li>
                                </ul>
                            </div>
                            <div class="d-flex gap-4 justify-content-center align-items-center mb-3 fz-16">
                                <a href="https://pinterest.com/" target="_blank"><i class="tio-pinterest-circle"></i></a>
                                <a href="https://instagram.com/" target="_blank"><i class="tio-instagram"></i></a>
                                <a href="https://facebook.com/" target="_blank"><i class="tio-facebook-square"></i></a>
                                <a href="https://linkedin.com/" target="_blank"><i class="tio-linkedin-square"></i></a>
                                <a href="https://twitter.com/" target="_blank"><i class="tio-twitter"></i></a>
                            </div>
                            <p class="text-center">Copyright 2023 6Valley. All right reserved</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <p>New Delivery Man Registration Request</p>
                    <div class="card">
                        <div class="p-3 px-xl-4 py-sm-5">
                            <img width="76" class="mb-4" src="{{dynamicAsset(path: 'public/assets/back-end/img/logo.png')}}" alt="">
                            <h3 class="mb-3">New Delivery Man Registration Request</h3>
                            <p>Hi Admin,</p>
                            <p><b>Jhon Doe</b> has requested to open their store in 6amMart. </p>
                            <p>Review their request from admin panel.</p>
                            <img src="{{dynamicAsset(path: 'public/assets/back-end/img/new-store.png')}}" alt="" class="mt-4 mb-3 w-100">
                            <p>Click on the button below to review the request</p>
                            <button class="btn btn--primary rounded-0 px-5">See Registration Request</button>
                            <hr>
                            <p>Please contact us for any queries, we’re always happy to help. </p>
                            <p>Thanks & Regards, <br> 6amMart</p>
                            <div class="d-flex justify-content-center gap-2">
                                <ul class="list-inline gap-3">
                                    <li><a href="#" class="text-dark">Privacy Policy</a></li>
                                    <li><a href="#" class="text-dark">Contact Us</a></li>
                                </ul>
                            </div>
                            <div class="d-flex gap-4 justify-content-center align-items-center mb-3 fz-16">
                                <a href="https://pinterest.com/" target="_blank"><i class="tio-pinterest-circle"></i></a>
                                <a href="https://instagram.com/" target="_blank"><i class="tio-instagram"></i></a>
                                <a href="https://facebook.com/" target="_blank"><i class="tio-facebook-square"></i></a>
                                <a href="https://linkedin.com/" target="_blank"><i class="tio-linkedin-square"></i></a>
                                <a href="https://twitter.com/" target="_blank"><i class="tio-twitter"></i></a>
                            </div>
                            <p class="text-center">Copyright 2023 6Valley. All right reserved</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <p>Store Withdraw Request</p>
                    <div class="card">
                        <div class="p-3 px-xl-4 py-sm-5">
                            <div class="text-center">
                                <img width="100" class="mb-4" src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/store-withdraw.png')}}" alt="">
                                <h3 class="mb-3">New Withdraw Request From Morning Mart</h3>
                                <p>Transaction # 453876934ce76538 </p>
                                <p><span class="text-primary">Morning Mart</span> has requested to withdraw money from their balance</p>
                                <p><span class="text-primary">Note:</span> Please review this withdraw request</p>
                            </div>
                            <p><b>Hi Sabrina,</b></p>
                            <p>Please click <a href="#">Here</a>  or click the link below to change your password</p>
                            <p>Click here <br> <a href="https://6ammart/changepasswordi357092349-38505320">https://6ammart/changepasswordi357092349-38505320</a></p>

                            <hr>
                            <p>Please contact us for any queries, we’re always happy to help. </p>
                            <p>Thanks & Regards, <br> 6amMart</p>

                            <div class="d-flex justify-content-center mb-3">
                                <img width="76" src="{{dynamicAsset(path: 'public/assets/back-end/img/logo.png')}}" alt="">
                            </div>
                            <div class="d-flex justify-content-center gap-2">
                                <ul class="list-inline gap-3">
                                    <li><a href="#" class="text-dark">Privacy Policy</a></li>
                                    <li><a href="#" class="text-dark">Contact Us</a></li>
                                </ul>
                            </div>
                            <div class="d-flex gap-4 justify-content-center align-items-center mb-3 fz-16">
                                <a href="https://pinterest.com/" target="_blank"><i class="tio-pinterest-circle"></i></a>
                                <a href="https://instagram.com/" target="_blank"><i class="tio-instagram"></i></a>
                                <a href="https://facebook.com/" target="_blank"><i class="tio-facebook-square"></i></a>
                                <a href="https://linkedin.com/" target="_blank"><i class="tio-linkedin-square"></i></a>
                                <a href="https://twitter.com/" target="_blank"><i class="tio-twitter"></i></a>
                            </div>
                            <p class="text-center">Copyright 2023 6Valley. All right reserved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Where Get Information Modal -->
    <div class="modal fade" id="readInstructionModal" tabindex="-1" aria-labelledby="readInstructionModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                    <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
                </div>
                <div class="modal-body px-4 px-sm-5 pt-0">
                    <div class="swiper mySwiper pb-3">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/1.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3">Select Theme</h4>
                                    <p class="text-center"> Choose a related email template theme for the purpose for which you are creating the email.</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/5.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3">Choose Logo</h4>
                                    <p class="text-center">Upload your company logo in 1:1 format. This will show above the Main Title of the email.</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/2.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3">Write a Title</h4>
                                    <p class="text-center">Give your email a ‘Catchy Title’ to help the reader understand easily.</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                    <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/3.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3">Write a message in the Email Body</h4>
                                    <p class="text-center">You can add your message using placeholders to include dynamic content. Here are some examples of placeholders you can use:</p>
                                    <ul class="d-flex flex-column px-4 gap-2 mb-4">
                                        <li>{userName}: The name of the user.</li>
                                        <li>{deliveryManName}: The name of the delivery person.</li>
                                        <li>{storeName}: The name of the store.</li>
                                        <li>{orderId}: The order id.</li>
                                        <li>{transactionId}: The transaction id.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                    <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/4.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3">Add Button & Link</h4>
                                    <p class="text-center">Specify the text and URL for the button that you want to include in your email.</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                    <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/5.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3">Change Banner Image if needed</h4>
                                    <p class="text-center">Choose the relevant banner image for the email theme you use for this mail.</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                    <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/6.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3">Add Content to Email Footer</h4>
                                    <p class="text-center">Write text on the footer section of the email and choose important page links and social media links.</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                    <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/7.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3">Create a copyright notice</h4>
                                    <p class="text-center">Include a copyright notice at the bottom of your email to protect your content.</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="d-flex flex-column align-items-center gap-2 mb-4">
                                    <img width="80" class="mb-3" src="{{dynamicAsset(path: 'public/assets/back-end/img/email-template/8.png')}}" loading="lazy" alt="">
                                    <h4 class="lh-md mb-3">Save and publish</h4>
                                    <p class="text-center">Once you ve set up all the elements of your email template save and publish it for use.</p>
                                    <button class="btn btn-primary px-10 mt-3" data-dismiss="modal">{{ translate('Got_It') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination mb-2"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection

@push('script_2')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/vendor/swiper/swiper-bundle.min.js')}}"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            autoHeight: true,
            pagination: {
                el: ".swiper-pagination",
                type: "fraction",
                dynamicBullets: true,
            },
        });
    </script>
    {{--ck editor--}}
    <script src="{{dynamicAsset(path: '/')}}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{dynamicAsset(path: '/')}}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('#editor').ckeditor({
            contentsLangDirection : '{{Session::get('direction')}}',
        });
    </script>
    {{--ck editor--}}
@endpush
