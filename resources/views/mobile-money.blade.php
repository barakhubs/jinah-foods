<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $company['company_name'] }}</title>
    <link rel="icon" href="{{ $faviconLogo->faviconLogo }}">
    <link rel="stylesheet" href="{{ asset('themes/default/css/style.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/lab/lab.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/css/custom.css') }}">
</head>

<body>
    <div class="py-14 px-4 w-full max-w-3xl mx-auto">
        <a href="{{ route('home') }}" class="block mx-auto w-36 mb-8">
            <img class="w-full" src="{{ $logo->logo }}" alt="logo">
        </a>
        <h3 class="text-[22px] text-center font-medium leading-[34px] mb-6">
            Add mobile money number</h3>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-5 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ $error }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer close-button">
                        <i class="lab lab-close-circle-line margin-top-5-px"></i>
                    </span>
                </div>
            @endforeach
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-5 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer close-button">
                    <i class="lab lab-close-circle-line margin-top-5-px"></i>
                </span>
            </div>
        @endif

        <form id="paymentForm" method="POST" action="{{ route('payment.pay-momo', ['order' => $order]) }}">
            @csrf
            <div class="flex rounded-lg shadow-sm mb-4">
                <span
                    class="px-4 inline-flex rounded-l-lg items-center min-w-fit rounded-l-md border border-e-0 border-gray-200 bg-gray-50 text-sm text-gray-500 dark:bg-gray-700 dark:border-gray-700 dark:text-gray-400">
                    (+256)
                </span>
                <input type="text" name="phone_number"
                    class="py-2 px-3 pe-11 block w-full border border-gray-200 shadow-sm rounded-r-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                    placeholder="77000000" value="{{ old('phone_number') }}" />
            </div>

            <button type="submit"
                class="py-3 w-full rounded-3xl text-center text-base font-medium bg-primary text-white" id="confirmBtn"
                onclick="handlePayment()">
                <span id="btnText">Pay now</span>
                <span id="processingText" style="display:none;">Please wait...</span>
            </button>

            <div class="py-5 px-4 w-full max-w-3xl mx-auto flex flex-col items-center justify-center">
                <a class="text-primary" href="javascript:history.go(-1);">Cancel</a>
            </div>
        </form>

    </div>

    <script src="{{ asset('lib/jquery-v3.2.1.min.js') }}"></script>
    <script>
        function handlePayment() {
            // Disable the button
            document.getElementById('confirmBtn').disabled = true;
            // Show processing message
            document.getElementById('btnText').style.display = 'none';
            document.getElementById('processingText').style.display = 'inline-block';

            // Allow form submission after a short delay (you can adjust the delay as needed)
            setTimeout(function () {
                document.getElementById('paymentForm').submit();
            }, 1000); // 1000 milliseconds (1 second) delay
        }
    </script>

</body>

</html>
