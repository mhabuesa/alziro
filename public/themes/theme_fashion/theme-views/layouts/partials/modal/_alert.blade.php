<div class="modal initial-modal fade" id="status-warning-modal">
    <div class="modal-dialog modal-dialog-centered status-warning-modal">
        <div class="modal-content">
            <button type="button" class="btn-close bg-text-2" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body pt-0">
                <div class="pb-4">
                    <div class="text-center pb-4 mb-20">
                        <img loading="lazy" src="{{theme_asset('assets/img/delete.png')}}" alt="{{ translate('delete') }}" class="mb-20 mt-5">
                        <h5 class="modal-title mt-2 text-capitalize">{{translate('are_you_sure')}}?</h5>
                        <p id="alert_message">
                            {{translate("Customers_will_not_be_able_to_select_COD_as_a_payment_method_during_checkout").translate("please_review_your_settings_and_enable_COD_if_you_wish_to_offer_this_payment_option_to_customers")}}
                        </p>
                    </div>
                    <div class="btn--container justify-content-center">
                        <button id="reset_btn" type="reset" class="btn badge-soft-secondary min-w-120" data-bs-dismiss="modal">
                            {{translate("cancel")}}
                        </button>
                        <a type="button" class="btn badge-soft-danger min-w-120" id="delete_button">
                            {{translate('delete')}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
