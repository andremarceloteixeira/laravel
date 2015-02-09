<?php

class InsuredType extends Eloquent {
    protected $table = 'insured_types';
    protected $fillable = array('code');
    
    public function getNameAttribute() {
        return trans($this->attributes['code']);
    }
    
    public function scopeDropdown($query) {
        $arr = [];
        foreach($query->get() as $c) {
            $arr[$c->id] = $c->name;
        }
        return $arr;
    }
    
}
