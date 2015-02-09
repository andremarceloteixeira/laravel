<?php

class NotificationsController extends BaseController {

    protected $notification;
    protected $user;

    public function __construct(Notification $notification) {
        $this->beforeFilter('auth');
        $this->notification = $notification;
        $this->user = Auth::user();
    }

    public function show($id) {
        $notification = $this->notification->find($id);
        if (is_null($notification)) {
            return Redirect::route('home.index');
        }
        if ($notification->user_id!=$this->user->id) {
            return Redirect::route('home.index');
        }
        $notification->delete();
        return Redirect::to($notification->route);
    }
    
    public function all() {
        $notifications = $this->notification->Unreaded();
        $total = $this->notification->UnreadedJquery();
        $data['status'] = 'success';
        $data['data'] = [];
        $data['total'] = count($total);
        $arr = [];
        foreach($notifications as $n) {
            $arr['label'] = $n->label;
            $arr['icon'] = $n->icon;
            $arr['name'] = $n->name;
            $arr['route'] = route('notifications.show', $n->id);
            $arr['title'] = trans('actions.notification');
            $arr['jquery'] = $n->jquery_viewed;
            $data['data'][] = $arr;
        }
        return Response::json($data);
    }
    
    public function jqueryViewed() {
        $this->notification->where('jquery_viewed', '=', false)->where('user_id', '=', $this->user->id)->update(['jquery_viewed' => true]);
        return Response::json(['status' => 'success']);
    }
}
