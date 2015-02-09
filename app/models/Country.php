<?php

class Country extends Eloquent {

    protected $table = 'countries';
    protected $fillable  = ['code'];
    
    public function scopeDropdown($query) {
        $arr = [];
        foreach($query->get() as $c) {
            $arr[$c->id] = $c->name;
        }
        return $arr;
    }
    
    public function clients() {
        return $this->hasMany('Client');
    }
    
    public function getNameAttribute() {
        return trans('countries.'.$this->attributes['code']);
    }
    

}
