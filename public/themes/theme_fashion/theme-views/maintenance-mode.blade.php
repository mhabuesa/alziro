<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session('direction') }}">

<head>
    <meta charset="utf-8">
    <title>{{translate('maintenance_Mode_On')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/app/public/company/'.$web_config['fav_icon']->value) }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/app/public/company/'.$web_config['fav_icon']->value) }}">
    <link rel="stylesheet" media="screen" href="{{ asset('public/assets/front-end/css/theme.css') }}">
</head>

<body>

    <div class="container rtl">
        <div class="row vh-100 align-content-center">
            <div class="col-12">
                <div class="text-center">
                    <img loading="lazy" class="min-width-350px" src="{{ asset('public/assets/front-end/img/maintenance-mode.jpg') }}" alt="{{ translate('maintenance_mode') }}">
                    <h1 class="mt-3">{{ translate('website_is_under_maintenance') }}</h1><br>
                    <h5>{{ translate('please_come_back_later') }}</h5>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ theme_asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
