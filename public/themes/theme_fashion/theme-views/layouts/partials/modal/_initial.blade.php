@if($web_config['popup_banner'])
<div class="modal fade initial-modal" id="initialModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="btn-close bg-text-2" data-bs-dismiss="modal" aria-label="Close"></button>
            <a href="{{$web_config['popup_banner']['url']}}" target="_blank">
                <img loading="lazy" src="{{ getValidImage(path: 'storage/app/public/banner/'.($web_config['popup_banner']['photo']), type:'banner') }}"
                    class="w-100 rounded intial-promo-banner" alt="{{ translate('promo') }}">
            </a>
        </div>
    </div>
</div>
@endif
