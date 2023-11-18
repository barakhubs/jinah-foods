<?php
namespace App\Enums;

interface PaymentGateway
{
    const CASH_ON_DELIVERY = 1;
    const MOBILE_MONEY         = 2;
    const PAYPAL           = 3;
}
