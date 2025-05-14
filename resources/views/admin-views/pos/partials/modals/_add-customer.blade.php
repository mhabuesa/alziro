<div class="modal fade" id="add-customer" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('add_new_customer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.AddNewCustomer') }}" method="post" id="product_form">
                    @csrf
                    <div class="row pl-2">
                        <div class="col-12 col-lg-12">
                            <div class="form-group">
                                <label class="input-label">Name<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                       placeholder="Name" required>
                                       @error('name')
                                       <span class="text-danger">{{$message}}</span>
                                       @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row pl-2">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('phone') }}<span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                                       placeholder="01700000000" required>
                                       @error('phone')
                                       <span class="text-danger">{{$message}}</span>
                                       @enderror
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ translate('email') }}</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                       placeholder="{{ translate('ex') }}: ex@example.com">
                                       @error('email')
                                       <span class="text-danger">{{$message}}</span>
                                       @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row pl-2">
                        <div class="col-12 col-lg-12">
                            <div class="form-group">
                                <label class="input-label">{{ translate('address') }} <span
                                        class="input-label-secondary text-danger">*</span></label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}"
                                       placeholder="{{ translate('address') }}">
                                       @error('address')
                                       <span class="text-danger">{{$message}}</span>
                                       @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-end">
                        <button type="submit" id="submit_new_customer"
                                class="btn btn--primary">{{ translate('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
