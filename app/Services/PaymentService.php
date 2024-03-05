<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function payment($order, $gatewaySlug, $transactionNo)
    {
        $transaction = Transaction::where(['order_id' => $order->id])->first();
        if (!$transaction) {
            $transaction = Transaction::create([
                'order_id'       => $order->id,
                'transaction_no' => $transactionNo,
                'amount'         => $order->total,
                'payment_method' => $gatewaySlug,
                'sign'           => '+',
                'type'           => 'payment'
            ]);
        }
        $order->payment_status = PaymentStatus::PAID;
        $order->save();

        // send sms to pos managers
        $roleNames = [
            Role::POS_OPERATOR,
        ];

        $branch = Branch::find($order->branch_id);
        $posManagers = User::role($roleNames)->where('branch_id', $branch->id)->get();

        Log::info($posManagers);
        
        $message = 'A payment has been made for an order. Please check your dashboard to process it.';

        foreach ($posManagers as $manager) {
            $smsManagerService = new SmsManagerService();
            $sendMessage = $smsManagerService->send($manager->country_code, $manager->phone, $message);

            if ($sendMessage) {
                Log::info('Message "'.$message.'" sent to ' . $manager->name);
            }
        }

        return $transaction;
    }

    public function cashBack($order, $gatewaySlug, $transactionNo)
    {
        $transaction = Transaction::where(['order_id' => $order->id])->first();
        if ($transaction) {
            $transaction = Transaction::create([
                'order_id'       => $order->id,
                'transaction_no' => $transactionNo,
                'amount'         => $order->total,
                'payment_method' => $gatewaySlug,
                'sign'           => '-',
                'type'           => 'cash_back'
            ]);

            $user = User::find($order->user_id);
            if ($user) {
                $user->balance = ($user->balance + $order->total);
                $user->save();
            }
        }

        return $transaction;
    }
}
