<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class CustomChannel //extends Notification
{
    public function send($notifiable, Notification $notification)
  {
    $data = $notification->toDatabase($notifiable);
    // dd($data);
    return $notifiable->routeNotificationFor('database')->create([
        'id' => $notification->id,
        'type' => get_class($notification),
        'data' => $data,
        'read_at' => null,
        'satker_id'=> $data['satker_id'],
    ]);
  }
}
