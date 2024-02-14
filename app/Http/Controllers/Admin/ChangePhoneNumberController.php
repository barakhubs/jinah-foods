<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\SmsGateways\Requests\FurahaSms;
use Illuminate\Http\Request;
use App\Models\Otp;
use Illuminate\Support\Facades\Http;

class ChangePhoneNumberController extends Controller
{
    public function changePhoneNumber($phone_number) {

        // Generate OTP (6 digits)
        $otpCode = rand(1000, 9999);

        // Store OTP in the database
        $otp = Otp::create([
            'phone' => $phone_number,
            'code' => '+256',
            'token' => $otpCode,
            'created_at' => now(), // Assuming 'created_at' is the timestamp column
        ]);

        // Send OTP via SMS
        $message = "Your `code` to change phone `number` is: " .$otpCode;
        $sms = new FurahaSms('85607206', 'LKrN8NomYKeA1kX4QVKbZk3UbCDUdEJl');
        $send = $sms->sendSMS('+256'.$phone_number, $message);

        // Handle SMS delivery status
        if ($send) {
            return response()->json(['message' => 'OTP sent successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to send OTP'], 500);
        }
    }
}
