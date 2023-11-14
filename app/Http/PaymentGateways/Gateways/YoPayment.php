<?php

namespace App\Http\PaymentGateways\Gateways;
class YoPayment
{
    private $yoAPI;

    public function __construct()
    {
        // For production, set this to production
        // $username = '100445521257';
        // $password  = '8xTx-qyht-pL0e-DzN0-9bNs-sL3w-QrxS-zOaD';
        $username = '90003148545';
        $password  = '5095818426';
        $mode = "sandbox";

        $this->yoAPI = new YoAPI($username, $password, $mode);

    }

    public function initiatePayment()
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

        $response = $this->yoAPI->ac_deposit_funds('256773034311', 5000, 'Reason for transfer of SMS purchasing');

        if ($response['Status'] == 'OK') {
            return true;
            // Save this transaction for future reference
        } else {
            return false;
        }
    }

    public function checkTransaction($transaction_reference)
    {
        $transaction = $this->yoAPI->ac_transaction_check_status($transaction_reference);

        if (strcmp($transaction['TransactionStatus'], 'SUCCEEDED') == 0) {
            // Transaction was completed, and funds were deposited onto the account
            // Save data into the database
        } else {
            echo "Transaction was declined.";
        }
    }
}

?>
