<?php
use YoAPI;
class YoPayment
{
    public function pay()
    {
        $mode = "production"; //For production, set this to production
        $yoAPI = new YoApi($username, $password);
        $yoAPI->set_nonblocking("TRUE");
        $response = $yoAPI->ac_deposit_funds('256773034311', 10000, 'Reason for transfer of funds');
        if ($response['Status'] == 'OK') {
            // Transaction was successful and funds were deposited onto your account
            echo "Transaction Reference = " . $response['TransactionReference'];
        }
    }
}
