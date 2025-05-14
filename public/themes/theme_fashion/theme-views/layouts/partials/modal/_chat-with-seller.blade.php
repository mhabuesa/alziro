<div class="modal fade contact-seller-modal" id="contact_sellerModal" tabindex="-1" aria-labelledby="contact_sellerModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header px-sm-5 pb-1">
                <h5 class="text-capitalize" id="contact_sellerModalLabel">{{translate('contact_with_vendor')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5">
                <form action="{{route('messages_store')}}" method="post" id="contact_with_seller_form"
                      data-success-message="{{translate('send_successfully')}}">
                    @csrf

                    @if($shopId != 0)
                        <input value="{{$shopId}}" name="shop_id" hidden>
                        <input value="{{$sellerId}}" name="seller_id" hidden>
                    @endif

                    <textarea name="message" class="form-control" rows="8" required
                              placeholder="{{translate('type_your_message')}}"></textarea>
                    <div class="d-flex justify-content-between mt-3">
                        <div class="d-flex">
                            <button class="btn btn-base text-white me-2"
                                {{($shopId == 0?'disabled':'')}}>{{translate('send')}}</button>
                            <button type="button" class="btn btn-base secondary-color me-2"
                                    data-bs-dismiss="modal">{{translate('close')}}</button>
                        </div>
                        <div>
                            @if ($shopId != 0)
                                <a href="{{route('chat', ['type' => 'seller','shop_id'=>$shopId])}}"
                                   class="btn btn-base me-2 text-capitalize">{{translate('go_to_chatbox')}}</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
