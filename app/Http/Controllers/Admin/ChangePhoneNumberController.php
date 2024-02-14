<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\SmsGateways\Requests\FurahaSms;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Models\Otp;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        $message = "Your OTP is: " .$otpCode;
        $sms = new FurahaSms('85607206', 'LKrN8NomYKeA1kX4QVKbZk3UbCDUdEJl');
        $send = $sms->sendSMS('+256'.$phone_number, $message);

        // Handle SMS delivery status
        if ($send) {
            return response()->json(['message' => 'OTP sent successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to send OTP'], 500);
        }
    }

    public function verifyOTP($otp) {
        try {
            // Validate the input OTP
            if (empty($otp)) {
                throw new InvalidArgumentException("OTP is empty");
            }

            // Retrieve the OTP record from the database based on the provided OTP
            $otpRecord = Otp::where('token', $otp)->first();

            if ($otpRecord) {
                // OTP is valid
                $phoneNumber = $otpRecord->phone;

                // Update user's phone number
                $user = auth()->user();
                if ($user) {
                    $user->phone = $phoneNumber;
                    $user->save();

                    // Phone number updated successfully
                    return response()->json(['success' => true, 'message' => 'Phone number updated successfully'], 200);
                } else {
                    // User authentication failed
                    return response()->json(['success' => false, 'message' => 'User authentication failed'], 401);
                }
            } else {
                // Invalid OTP
                return response()->json(['success' => false, 'message' => 'Invalid OTP'], 400);
            }
        } catch (Exception $exception) {
            // Log the exception
            Log::error($exception->getMessage());

            // Return appropriate error response
            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }

}
