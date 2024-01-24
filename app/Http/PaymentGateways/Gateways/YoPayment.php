<?php

namespace App\Http\PaymentGateways\Gateways;

use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use YoAPI;

class YoPayment
{
    private $yoAPI;

    public function __construct()
    {
        // For production, set this to production
        $username = '100445521257';
        $password  = '8xTx-qyht-pL0e-DzN0-9bNs-sL3w-QrxS-zOaD';
        // $username = '90003148545';
        // $password = '5095818426';
        $mode = "production";

        $this->yoAPI = new YoAPI($username, $password, $mode);

    }

    public function initiatePayment($phone, $amount, $reason, $order)
    {
        // Create a unique transaction reference that you will reference this payment with
        $transaction_reference = date("YmdHis") . rand(1, 100);
        $this->yoAPI->set_external_reference($transaction_reference);

        // Set nonblocking to TRUE so that you get an instant response
        $this->yoAPI->set_nonblocking("TRUE");

        // Set an instant notification URL where a successful payment notification POST will be sent
        // See documentation on how to handle IPN
        $this->yoAPI->set_instant_notification_url('https://webhook.site/7e87335b-d909-44b5-84f7-7ae82b4fceb7');

        // Set a failure notification URL where a failed payment notification POST will be sent
        // See documentation on how to handle IPNs
        $this->yoAPI->set_failure_notification_url('https://webhook.site/7e87335b-d909-44b5-84f7-7ae82b4fceb7');

        // Log information about the initiated payment
        Log::info("Initiating Yo Payment for order {$order->id} with transaction reference {$transaction_reference}");

        $response = $this->yoAPI->ac_deposit_funds($phone, $amount, $reason);

        // Log the Yo Payments API response
        Log::info("Yo Payments API Response: " . json_encode($response));

        if ($response['Status'] == 'OK') {
            // check transaction
            Log::info("Payment initiated successfully. Checking transaction status.");
            return $this->checkTransaction($response['TransactionReference'], $order);   // Save this transaction for future reference
        } else {
            $errorMessage = "Yo Payments Error: " . $response['StatusMessage'];

            // Log the error
            Log::error($errorMessage);

            Log::info("Redirecting to payment fail route for order {$order->id}");

            return redirect()->route('payment.fail', ['order' => $order])->with('error', $errorMessage);
        }
    }

    public function checkTransaction($transaction_reference, $order)
    {
        // Log information about checking transaction status
        Log::info("Checking Yo Payment transaction status for order {$order->id} with transaction reference {$transaction_reference}");

        $maxAttempts = 10; // You can adjust the maximum number of attempts
        $attempts = 0;

        do {
            $transaction = $this->yoAPI->ac_transaction_check_status($transaction_reference);
            $attempts++;

            // Log the Yo Payments API response for transaction status check
            Log::info("Yo Payments Transaction Status Check Response: " . json_encode($transaction));

            if (strcmp($transaction['TransactionStatus'], 'SUCCEEDED') == 0) {
                Log::info("Transaction for order {$order->id} succeeded. Redirecting to payment successful route.");


                //update order status
                Order::where('id', $order->id)->update([
                    'payment_status' => PaymentStatus::PAID,
                    'payment_method' => PaymentGateway::MOBILE_MONEY
                ]);

                return redirect()->route('payment.successful', ['order' => $order])->with('success', trans('all.message.payment_successful'));
            } elseif (strcmp($transaction['TransactionStatus'], 'DECLINED') == 0) {
                Log::info("Transaction for order {$order->id} declined. Redirecting to payment fail route.");
                return redirect()->route('payment.fail', ['order' => $order])->with('error', 'Transaction declined');
            }

            // Add a delay before the next attempt (adjust as needed)
            sleep(5);

        } while ($attempts < $maxAttempts);

        // If the loop completes without a final status, handle it accordingly
        Log::info("Transaction for order {$order->id} is still pending after {$maxAttempts} attempts. Redirecting to payment fail route.");
        return redirect()->route('payment.fail', ['order' => $order])->with('error', 'Transaction is still pending');
    }

}
