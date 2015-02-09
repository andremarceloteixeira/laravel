<?php

class Status extends Eloquent {

    protected $table = 'status';
        
    public function getNameAttribute() {
        return trans($this->attributes['code']);
    }
    
    public function processes() {
        return $this->hasMany('Process');
    }

}
