<?php

namespace App\Broadcasting;

use App\Services\HrmsServices;
use Illuminate\Notifications\Notification;

class HrmsNotifyCreatorChannel
{
    public function send($notifiable, Notification $notification)
    {
        $userId = $notifiable->created_by;
        $notif = $notification->toArray($notifiable);
        HrmsServices::setNotification($notification->getToken(), $userId, $notif);
    }
}
