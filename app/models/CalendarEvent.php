<?php

class CalendarEvent extends Eloquent {
    protected $table = 'events';
    protected $fillable = ['name', 'description', 'starts', 'ends', 'type_id', 'user_id'];
    
    public function type() {
        return $this->belongsTo('EventType', 'type_id');
    }

    public function getStartIsoAttribute() {
        return str_replace(' ', 'T', $this->attributes['starts']);
    }
    
    public function getEndIsoAttribute() {
        return str_replace(' ', 'T', $this->attributes['ends']);
    }
    
    public function scopeJSON($query) {
        $json = '[';
        foreach($query->where('user_id','=',Auth::user()->id)->get() as $e) {
            $json .= '{"id": "'.$e->id.'", "description": "'.$e->description.'", "title": "'.$e->name.'", "className": "'.$e->type->label.'", "start": "'.$e->startIso.'", "end": "'.$e->endIso.'", "allDay":false},';
        }
        $json .= ']';
        return $json;
    }
}
