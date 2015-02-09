<?php

class Role extends Eloquent {
    protected $table = 'roles';
    protected $fillable = ['code'];

    public function users() {
        return $this->hasMany('User');
    }
    

}
