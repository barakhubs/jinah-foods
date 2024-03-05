<?php

namespace App\Services;


use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\SwitchBox;
use App\Models\Branch;
use App\Models\FrontendOrder;
use App\Models\NotificationAlert;
use App\Models\User;
use App\Push\PushNotification;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Enums\Role as EnumRole;

class OrderPushNotificationBuilder
{
    public int $orderId;
    public mixed $status;
    public object $order;

    public function __construct($orderId, $status)
    {
        $this->orderId = $orderId;
        $this->status = $status;
        $this->order = FrontendOrder::find($orderId);
    }

    // public function send(): void
    // {
    //     if (!blank($this->order)) {
    //         $user = User::find($this->order->user_id);
    //         if (!blank($user)) {
    //             if (!blank($user->web_token) || !blank($user->device_token)) {
    //                 // $fcmTokenArray = [];
    //                 // if (!blank($user->web_token)) {
    //                 //     $fcmTokenArray[] = $user->web_token;
    //                 // }
    //                 // if (!blank($user->device_token)) {
    //                 //     // $fcmTokenArray[] = $user->device_token;
    //                 // }
    //                 // $this->message($fcmTokenArray, $this->status, $this->orderId);
    //                 if ($this->status == OrderStatus::PENDING)
    //                     $message = 'Your order is pending! Please wait for confirmation';
    //                 elseif ($this->status == OrderStatus::ACCEPT)
    //                     $message = 'Your order is accepted! Please wait for processing';
    //                 elseif ($this->status == OrderStatus::PROCESSING)
    //                     $message = 'Your order is processing! Please wait for out for delivery';
    //                 elseif ($this->status == OrderStatus::OUT_FOR_DELIVERY)
    //                     $message = 'Your order is out for delivery!';
    //                 elseif ($this->status == OrderStatus::DELIVERED)
    //                     $message = 'Your order is delivered! Thank you for your order';
    //                 elseif ($this->status == OrderStatus::CANCELED)
    //                     $message = 'Your order is canceled! Please contact with us for more details';
    //                 elseif ($this->status == OrderStatus::REJECTED)
    //                     $message = 'Your order is rejected! Please contact with us for more details';
    //                 elseif ($this->status == OrderStatus::RETURNED)
    //                     $message = 'Your order is returned! Please contact with us for more details';
    //                 $pushNotification = new PushNotification();
    //                 $pushNotification->sendMessage('Jinah Foods Notification', $message, $user->device_token);
    //             }
    //         }

    //         $admins = User::whereIn('role', [
    //             EnumRole::ADMIN,
    //             EnumRole::POS_OPERATOR,
    //             EnumRole::BRANCH_MANAGER
    //         ])->get();

    //         foreach ($admins as $admin) {
    //             if ($this->status == OrderStatus::PENDING)
    //                 $message = 'An order has been placed! Please check your dashboard!';
    //             elseif ($this->status == PaymentStatus::PAID)
    //                 $message = 'A payment has been made for an order';
    //             elseif ($this->status == OrderStatus::CANCELED)
    //                 $message = 'Your order is canceled! Please contact with us for more details';
    //             elseif ($this->status == OrderStatus::RETURNED)
    //                 $message = 'Your order is returned! Please contact with us for more details';

    //             $pushNotification = new PushNotification();
    //             $pushNotification->sendWebNotification('Jinah Foods', $message, 'https://admin.jinahonestop.com/', $admin->web_token);
    //         }

    //     }
    // }

    public function send(): void
    {
        if (!blank($this->order)) {
            // Send notification to the customer
            $user = User::find($this->order->user_id);
            if (!blank($user)) {
                if (!blank($user->web_token) || !blank($user->device_token)) {
                    $message = $this->getMessageForCustomer($this->status);
                    $this->sendNotification('Jinah Foods Notification', $message, $user->device_token);

                    Log::info('Message send');
                    // if ($sendPush) {
                    //     // Send notification to POS managers
                    //     $roleNames = [
                    //         EnumRole::POS_OPERATOR,
                    //     ];

                    //     $branch = Branch::find($this->order->branch_id);
                    //     $posManager = User::role($roleNames)->where('branch_id', $branch->id)->first();

                    //     $message = $this->getMessageForAdmin($this->status);

                    //     $smsManagerService = new SmsManagerService();
                    //     $sendMessage = $smsManagerService->send($posManager->country_code, $posManager->phone, $message);

                    //     if ($sendMessage) {
                    //         Log::info('Message send');
                    //     }
                    // } else
                    // {
                    //     Log::info('Message not sent!');
                    // }
                }
            }


        }
    }

    private function getMessageForCustomer($status)
    {
        switch ($status) {
            case OrderStatus::PENDING:
                return 'Your order is pending! Please wait for confirmation';
            case OrderStatus::ACCEPT:
                return 'Your order is accepted! Please wait for processing';
            case OrderStatus::PROCESSING:
                return 'Your order is processing! Please wait for out for delivery';
            case OrderStatus::OUT_FOR_DELIVERY:
                return 'Your order is out for delivery!';
            case OrderStatus::DELIVERED:
                return 'Your order is delivered! Thank you for your order';
            case OrderStatus::CANCELED:
                return 'Your order is canceled! Please contact us for more details';
            case OrderStatus::REJECTED:
                return 'Your order is rejected! Please contact us for more details';
            case OrderStatus::RETURNED:
                return 'Your order is returned! Please contact us for more details';
            default:
                return '';
        }
    }

    private function getMessageForAdmin($status)
    {
        switch ($status) {
            case OrderStatus::PENDING:
                return 'An order has been placed! Please check your dashboard!';
            case PaymentStatus::PAID:
                return 'A payment has been made for an order';
            case OrderStatus::CANCELED:
                return 'An order has been canceled! Please check your dashboard for more details';
            case OrderStatus::RETURNED:
                return 'An order has been returned! Please check your dashboard for more details';
            default:
                return '';
        }
    }

    private function sendNotification($title, $message, $token)
    {
        $pushNotification = new PushNotification();
        $pushNotification->sendMessage($title, $message, $token);
    }

    private function sendWebNotification($title, $message, $url, $token)
    {
        $pushNotification = new PushNotification();
        $pushNotification->sendWebNotification($title, $message, $url, $token);
    }

    private function message($fcmTokenArray, $status, $orderId): void
    {
        if ($status == OrderStatus::PENDING) {
            $this->pending($fcmTokenArray, $orderId);
        } elseif ($status == OrderStatus::ACCEPT) {
            $this->confirmation($fcmTokenArray, $orderId);
        } elseif ($status == OrderStatus::PROCESSING) {
            $this->processing($fcmTokenArray, $orderId);
        } elseif ($status == OrderStatus::OUT_FOR_DELIVERY) {
            $this->outForDelivery($fcmTokenArray, $orderId);
        } elseif ($status == OrderStatus::DELIVERED) {
            $this->delivered($fcmTokenArray, $orderId);
        } elseif ($status == OrderStatus::CANCELED) {
            $this->canceled($fcmTokenArray, $orderId);
        } elseif ($status == OrderStatus::REJECTED) {
            $this->rejected($fcmTokenArray, $orderId);
        } elseif ($status == OrderStatus::RETURNED) {
            $this->returned($fcmTokenArray, $orderId);
        }
    }

    private function notification($fcmTokenArray, $orderId, $message): void
    {
        try {
            $pushNotification = (object) [
                'title' => 'Order Notification',
                'description' => $message,
                'order_id' => $orderId
            ];
            $firebase = new FirebaseService();
            $firebase->sendNotification($pushNotification, $fcmTokenArray, "Order Notification");
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function pending($fcmTokenArray, $orderId): void
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_pending_message'])->first();
        if ($notificationAlert && $notificationAlert->push_notification == SwitchBox::ON) {
            $this->notification($fcmTokenArray, $orderId, $notificationAlert->push_notification_message);
        }
    }

    private function confirmation($fcmTokenArray, $orderId): void
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_confirmation_message'])->first();
        if ($notificationAlert && $notificationAlert->push_notification == SwitchBox::ON) {
            $this->notification($fcmTokenArray, $orderId, $notificationAlert->push_notification_message);
        }
    }

    private function processing($fcmTokenArray, $orderId): void
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_processing_message'])->first();
        if ($notificationAlert && $notificationAlert->push_notification == SwitchBox::ON) {
            $this->notification($fcmTokenArray, $orderId, $notificationAlert->push_notification_message);
        }
    }

    private function outForDelivery($fcmTokenArray, $orderId): void
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_out_for_delivery_message'])->first();
        if ($notificationAlert && $notificationAlert->push_notification == SwitchBox::ON) {
            $this->notification($fcmTokenArray, $orderId, $notificationAlert->push_notification_message);
        }
    }

    private function delivered($fcmTokenArray, $orderId): void
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_delivered_message'])->first();
        if ($notificationAlert && $notificationAlert->push_notification == SwitchBox::ON) {
            $this->notification($fcmTokenArray, $orderId, $notificationAlert->push_notification_message);
        }
    }

    private function canceled($fcmTokenArray, $orderId): void
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_canceled_message'])->first();
        if ($notificationAlert && $notificationAlert->push_notification == SwitchBox::ON) {
            $this->notification($fcmTokenArray, $orderId, $notificationAlert->push_notification_message);
        }
    }

    private function rejected($fcmTokenArray, $orderId): void
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_rejected_message'])->first();
        if ($notificationAlert && $notificationAlert->push_notification == SwitchBox::ON) {
            $this->notification($fcmTokenArray, $orderId, $notificationAlert->push_notification_message);
        }
    }

    private function returned($fcmTokenArray, $orderId): void
    {
        $notificationAlert = NotificationAlert::where(['language' => 'order_returned_message'])->first();
        if ($notificationAlert && $notificationAlert->push_notification == SwitchBox::ON) {
            $this->notification($fcmTokenArray, $orderId, $notificationAlert->push_notification_message);
        }
    }
}
