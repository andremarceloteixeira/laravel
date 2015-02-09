<?php

class Type extends Eloquent {
    protected $table = 'types';
    protected $fillable = ['code', 'name', 'title', 'first_notes', 'last_notes'];
    
    public static $rules = [
        'code' => 'required',
        'name' => 'required',
        'title' => 'required',
    ];
    
    public static $names = [
        'code' => 'types.code',
        'name' => 'types.name',
        'title' => 'types.title',
        'first_notes' => 'types.first_notes',
        'last_notes' => 'types.last_notes'
    ];
    
    public function scopeDropdown($query) {
        $arr = [];
        foreach($query->get() as $c) {
            $arr[$c->id] = $c->code;
        }
        return $arr;
    }
    
    public function processes() {
        return $this->hasMany('Process');
    }
    
    public function fields() {
        return $this->hasMany('TypeField');
    }
    
    public function checkboxes() {
        return $this->hasMany('CheckField');
    }

}
