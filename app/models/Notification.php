<?php

class Notification extends Eloquent {
    protected $table = 'notifications';
    protected $fillable = ['label', 'icon', 'route', 'code', 'value', 'user_id'];
    
    public function getNameAttribute() {
        return trans($this->attributes['code'], ['value' => $this->attributes['value']]);
    }
    
    public function scopeUnreaded($query) {
        return $query->where('viewed', '=', false)->where('user_id', '=', Auth::user()->id)->orderBy('jquery_viewed', 'ASC')->get();
    }
    
    public function scopeUnreadedJquery($query) {
        return $query->where('viewed', '=', false)->where('jquery_viewed', '=', false)->where('user_id', '=', Auth::user()->id)->get();
    }
}
