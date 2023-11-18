<?php

namespace App\Http\Controllers\Frontend;


use App\Enums\Activity;
use App\Enums\PaymentStatus;
use App\Enums\PaymentGateway as PaymentGatewayEnum;
use App\Http\Requests\PaymentRequest;
use App\Libraries\AppLibrary;
use App\Models\Currency;
use App\Models\Order;
use App\Models\PaymentGateway;
use App\Models\ThemeSetting;
use App\Services\PaymentManagerService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Smartisan\Settings\Facades\Settings;
use App\Http\PaymentGateways\Gateways\YoPayment;

class PaymentController extends Controller
{
    private PaymentManagerService $paymentManagerService;

    public function __construct(PaymentManagerService $paymentManagerService)
    {
        $this->paymentManagerService = $paymentManagerService;
    }

    public function index(
        Order $order
    ): \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\View\View | \Illuminate\Contracts\Foundation\Application | \Illuminate\Http\RedirectResponse {
        $credit          = false;
        $paymentGateways = PaymentGateway::with('gatewayOptions')->whereNotIn('id', [1])->where(['status' => Activity::ENABLE])->get();
        $company         = Settings::group('company')->all();
        $logo            = ThemeSetting::where(['key' => 'theme_logo'])->first();
        $faviconLogo     = ThemeSetting::where(['key' => 'theme_favicon_logo'])->first();
        $currency        = Currency::findOrFail(Settings::group('site')->get('site_default_currency'));
        if ($order?->user?->balance >= $order->total) {
            $credit = true;
        }

        if (blank($order->transaction) && $order->payment_status === PaymentStatus::UNPAID) {
            return view('payment', [
                'company'         => $company,
                'logo'            => $logo,
                'currency'        => $currency,
                'faviconLogo'     => $faviconLogo,
                'paymentGateways' => $paymentGateways,
                'order'           => $order,
                'creditAmount'    => AppLibrary::currencyAmountFormat($order?->user?->balance),
                'credit'          => $credit
            ]);
        }
        return redirect()->route('home')->with('error', trans('all.message.payment_canceled'));
    }

    public function payment(Order $order, PaymentRequest $request)
    {
        // if ($this->paymentManagerService->gateway($request->paymentMethod)->status()) {
        //     $className = 'App\\Http\\PaymentGateways\\PaymentRequests\\' . ucfirst($request->paymentMethod);
        //     $gateway   = new $className;
        //     $request->validate($gateway->rules());
            // return $this->paymentManagerService->gateway($request->paymentMethod)->payment($order, $request);
        // } else {
        //     return redirect()->route('payment.index', ['order' => $order])->with(
        //         'error',
        //         trans('all.message.payment_gateway_disable')
        //     );
        // }



        $payment_type = $request->paymentMethod;
        if($payment_type == 'mobile_money') {
            return redirect()->route('payment.momo', ['order' => $order]);
        } else if($payment_type == 'cash_on_delivery') {
            return redirect()->route('payment.cod', ['order' => $order]);
        }

    }

    public function mobileMoneyIndex (Order $order, PaymentRequest $request)
    {
        $credit          = false;
        $paymentGateways = PaymentGateway::with('gatewayOptions')->whereNotIn('id', [1])->where(['status' => Activity::ENABLE])->get();
        $company         = Settings::group('company')->all();
        $logo            = ThemeSetting::where(['key' => 'theme_logo'])->first();
        $faviconLogo     = ThemeSetting::where(['key' => 'theme_favicon_logo'])->first();
        $currency        = Currency::findOrFail(Settings::group('site')->get('site_default_currency'));
        if ($order?->user?->balance >= $order->total) {
            $credit = true;
        }
        return view('mobile-money', [
            'company'         => $company,
            'logo'            => $logo,
            'currency'        => $currency,
            'faviconLogo'     => $faviconLogo,
            'paymentGateways' => $paymentGateways,
            'order'           => $order,
            'creditAmount'    => AppLibrary::currencyAmountFormat($order?->user?->balance),
            'credit'          => $credit
        ]);
    }

    public function codIndex (Order $order, PaymentRequest $request)
    {
        $credit          = false;
        $paymentGateways = PaymentGateway::with('gatewayOptions')->whereNotIn('id', [1])->where(['status' => Activity::ENABLE])->get();
        $company         = Settings::group('company')->all();
        $logo            = ThemeSetting::where(['key' => 'theme_logo'])->first();
        $faviconLogo     = ThemeSetting::where(['key' => 'theme_favicon_logo'])->first();
        $currency        = Currency::findOrFail(Settings::group('site')->get('site_default_currency'));
        if ($order?->user?->balance >= $order->total) {
            $credit = true;
        }

        return view('cash-on-delivery',  [
            'company'         => $company,
            'logo'            => $logo,
            'currency'        => $currency,
            'faviconLogo'     => $faviconLogo,
            'paymentGateways' => $paymentGateways,
            'order'           => $order,
            'creditAmount'    => AppLibrary::currencyAmountFormat($order?->user?->balance),
            'credit'          => $credit
        ]);

    }

    public function payMomo (Order $order, Request $request)
    {
        $this->validate($request, [
            'phone_number' => 'required|min:9|max:9'
        ]);

        $amount = Order::find($order->id)->total;
        $phone = '256'.$request->phone_number;

        $pay = new YoPayment();
        return $pay->initiatePayment($phone, $amount, 'Payment for an order', $order);
    }

    public function payCod (Order $order, PaymentRequest $request)
    {
        $order->payment_status = PaymentStatus::PAID;
        $order->payment_method  =   PaymentGatewayEnum::CASH_ON_DELIVERY;
        $order->save();
        return redirect()->route('payment.successful', ['order' => $order]);
    }
    public function success(Order $order, Request $request)
    {
        return redirect()->route('payment.successful', ['order' => $order]);
    }

    public function fail(Order $order, Request $request)
    {
        return redirect()->route('payment.index', ['order' => $order])->with('error', trans('all.message.something_wrong'));
    }

    public function cancel(PaymentGateway $paymentGateway, Order $order, Request $request)
    {
        return $this->paymentManagerService->gateway($paymentGateway->slug)->cancel($order, $request);
    }

    public function successful(
        Order $order
    ): \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\View\View | \Illuminate\Contracts\Foundation\Application | \Illuminate\Http\RedirectResponse {
        $company     = Settings::group('company')->all();
        $logo        = ThemeSetting::where(['key' => 'theme_logo'])->first();
        $faviconLogo = ThemeSetting::where(['key' => 'theme_favicon_logo'])->first();

        if (!blank($order->transaction)) {
            return view('paymentSuccess', [
                'company'     => $company,
                'logo'        => $logo,
                'faviconLogo' => $faviconLogo,
                'order'       => $order,
            ]);
        }
        return redirect()->route('home')->with('error', trans('all.message.payment_canceled'));
    }
}
