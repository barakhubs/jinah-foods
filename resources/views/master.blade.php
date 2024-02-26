<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- REQUIRED META TAGS -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FONTS -->
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/fontawesome/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/lab/lab.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/typography/public/public.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/typography/rubik/rubik.css') }}">

    <!-- CUSTOM STYLE -->
    <link rel="stylesheet" href="{{ asset('themes/default/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/css/custom.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <!-- PAGE TITLE -->
    <title>{{ Settings::group('company')->get('company_name') }}</title>

    <!-- FAV ICON -->
    <link rel="icon" type="image" href="{{ $favicon }}">

    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(function(OneSignal) {
            OneSignal.init({
                appId: "41a5fc47-4587-4084-9e84-7478c145e477",
            });

            if (OneSignal.User.PushSubscription.optedIn) {
                // User has subscribed, send a POST request to 'frontend/web/token/'
                fetch('https://admin.jinahonestop.com/api/frontend/device-token/web', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            // Assuming you want to send the OneSignal player ID
                            token: OneSignal.User.PushSubscription.id,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => console.log('Success:', data))
                    .catch((error) => console.error('Error:', error));
            }
        });
    </script>



    @if (!blank($analytics))
        @foreach ($analytics as $analytic)
            @if (!blank($analytic->analyticSections))
                @foreach ($analytic->analyticSections as $section)
                    @if ($section->section == \App\Enums\AnalyticSection::HEAD)
                        {!! $section->data !!}
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif
</head>

<body>
    @if (!blank($analytics))
        @foreach ($analytics as $analytic)
            @if (!blank($analytic->analyticSections))
                @foreach ($analytic->analyticSections as $section)
                    @if ($section->section == \App\Enums\AnalyticSection::BODY)
                        {!! $section->data !!}
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif

    <div id="app">
        <default-component />
    </div>

    @if (!blank($analytics))
        @foreach ($analytics as $analytic)
            @if (!blank($analytic->analyticSections))
                @foreach ($analytic->analyticSections as $section)
                    @if ($section->section == \App\Enums\AnalyticSection::FOOTER)
                        {!! $section->data !!}
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif

    <script>
        const APP_URL = "{{ env('MIX_HOST') }}";
        const APP_KEY = "{{ env('MIX_API_KEY') }}";
        const GOOGLE_TOKEN = "{{ env('MIX_GOOGLE_MAP_KEY') }}";
        const APP_DEMO = "{{ env('MIX_DEMO') }}";
    </script>

    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('themes/default/js/jquery-v3.2.1.min.js') }}"></script>
    <script src="{{ asset('themes/default/js/drawer.js') }}"></script>
    <script src="{{ asset('themes/default/js/modal.js') }}"></script>
    <script src="{{ asset('themes/default/js/jqueryScript.js') }}"></script>
    <script src="{{ asset('themes/default/js/tabs.js') }}"></script>
    <script src="{{ asset('themes/default/js/jqueryDropdown.js') }}"></script>
    <script src="{{ asset('themes/default/js/apexcharts/apexcharts.min.js') }}"></script>
</body>

</html>
