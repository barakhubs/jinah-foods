<?php

namespace App\Listeners;

use App\Events\SendSmsCode;
use App\Http\SmsGateways\Requests\FurahaSms;
use App\Services\SmsManagerService;
use App\Services\SmsService;
use App\Sms\VerifyCode;
use Exception;
use Illuminate\Support\Facades\Log;

class SendSmsCodeNotification
{

    public SmsManagerService $smsManagerService;
    public string $gateway;

    public function __construct(SmsManagerService $smsManagerService, SmsService $smsService)
    {
        $this->smsManagerService = $smsManagerService;
        $this->gateway = $smsService->gateway();
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\SendResetPassword $event
     * @return void
     */
    public function handle(SendSmsCode $event)
    {
        try {
            $message = 'Your code is ' + $event->info['code'];
            $sms = new FurahaSms('51856485', 'KH7lfPYjb20McfanaC5qeAZ7kHTkVzr6');
            $response = $sms->sendSMS($event->info['phone'], $message);

        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
