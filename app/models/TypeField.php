<?php

class TypeField extends Eloquent {
    protected $table = 'type_fields';
    protected $fillable = ['value', 'type_id'];
    
    public function scopeDropdown($query) {
        $arr = [];
        foreach($query->get() as $c) {
            $arr[$c->id] = $c->value;
        }
        return $arr;
    }
    
    public function type() {
        return $this->belongTo('Type');
    }

}
