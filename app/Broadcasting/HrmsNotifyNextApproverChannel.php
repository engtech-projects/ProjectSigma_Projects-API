<?php

namespace App\Broadcasting;

use App\Services\HrmsServices;
use Notification;

class HrmsNotifyNextApproverChannel
{
    public function send($notifiable, Notification $notification): void
    {
        $userId = $notifiable->getNextPendingApproval()['user_id'];
        $notif = $notification->toArray($notifiable);
        HrmsServices::setNotification($notification->getToken(), $userId, $notif);
    }
}
