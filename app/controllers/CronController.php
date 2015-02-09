<?php

class CronController extends BaseController {

    protected $notifications;

    public function __construct(Notification $notification) {
        $this->notifications = $notification;
    }
    
    public function deleteNotifications() {
        $date = Carbon::now()->subDays(3);
        $rows = $this->notifications->where('jquery_viewed', '=', true)->where('created_at', '<', $date)->delete();
        echo $rows.' Old Notifications deleted.';
    }
    
    public function deleteEvents() {
        $date = Carbon::now()->subDays(3);
        $rows = $this->notifications->where('jquery_viewed', '=', true)->where('ends', '<', $date)->delete();
        echo $rows.' Old Events deleted.';
    }
    
}
