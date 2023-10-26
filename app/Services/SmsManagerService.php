<?php

namespace App\Services;
use App\Http\SmsGateways\Requests\FurahaSms;


class SmsManagerService
{

    public object $gateway;

    public function gateway(...$args) : static
    {
        $smsMethod = '';
        if (count($args) > 0) {
            $smsMethod = ucfirst(array_shift($args));
        }

        if (count($args) == 0) {
            $args = null;
        }

        $className     = 'App\\Http\\SmsGateways\\Gateways\\' . $smsMethod;
        $this->gateway = new $className($args);
        return $this;
    }

    public function send($code, $phone, $message)
    {
        $sms = new FurahaSms('51856485', 'KH7lfPYjb20McfanaC5qeAZ7kHTkVzr6');
        $response = $sms->sendSMS($phone, $message);
        return $response;
    }

    public function status()
    {
        return $this->gateway->status();
    }

}
