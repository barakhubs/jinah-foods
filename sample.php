<?php
$mode = "production";//For production, set this to production
$yoAPI = new YoAPI($username, $password);
$yoAPI->set_nonblocking("TRUE");
$response = $yoAPI->ac_deposit_funds('25677303431', 10000, 'Reason for transfer of funds');
if($response['Status']=='OK'){
	// Transaction was successful and funds were deposited onto your account
	echo "Transaction Reference = ".$response['TransactionReference'];
}
