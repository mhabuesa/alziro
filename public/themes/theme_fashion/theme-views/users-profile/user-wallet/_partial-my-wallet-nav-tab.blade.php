<ul class="nav nav-tabs nav--tabs-2" role="tablist">
    @if ($web_config['wallet_status'] == 1)
        <li class="nav-item" role="presentation">
            <a href="{{ route('wallet') }}" class="nav-link {{ Request::is('wallet') ? 'active' :'' }}">
                {{ translate('wallet') }}
            </a>
        </li>
    @endif
    @if ($web_config['loyalty_point_status'] == 1)
        <li class="nav-item" role="presentation">
            <a href="{{ route('loyalty') }}" class="nav-link {{  Request::is('loyalty') ? 'active' :'' }}">
                {{ translate('loyalty_point') }}
            </a>
        </li>
    @endif
</ul>
