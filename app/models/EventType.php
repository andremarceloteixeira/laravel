<?php

class EventType extends Eloquent {

    protected $table = 'event_priorities';
    protected $fillable = ['code', 'label'];

    public function getNameAttribute() {
        return trans($this->attributes['code']);
    }

    public function events() {
        return $this->hasMany('CalendarEvent', 'type_id');
    }

    public function scopeJSON($query) {
        $json = '{';
        foreach ($query->get() as $e) {
            $json .= '"' . $e->label . '": "' . $e->name . '", ';
        }
        $json .= '}';
        return $json;
    }

}
