<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;

class User extends Eloquent implements UserInterface {

    use UserTrait;

    protected $table = 'users';
    protected $fillable = ['username', 'name', 'email', 'photo'];
    protected $hidden = ['password', 'remember_token'];
    protected $guarded = array();
    
    public function setPasswordAttribute($value) {
         $this->attributes['password'] = Hash::make($value);
    }
    
    public function getPhotoAttribute() {
        return $this->attributes['photo'];
    }
    
    public function role() {
        return $this->belongsTo('Role');
    }

    public function client() {
        return $this->hasOne('Client');
    }
    
    public function expert() {
        return $this->hasOne('Expert');
    }
    
    public function admin() {
        return $this->hasOne('Admin');
    }
    
    public function delete() {
        if($this->photo!=Config::get('settings.photo_default')) {
            if(File::exists(public_path() . '/' . $this->photo)) {
                File::delete(public_path() . '/' . $this->photo);
            }
        }
        parent::delete();
    }
    
    public function update(array $attributes = array()) {
        if($this->photo!=Config::get('settings.photo_default')) {
            if(File::exists(public_path() . '/' . $this->photo)) {
                File::delete(public_path() . '/' . $this->photo);
            }
        }
        parent::update($attributes);
    }
    
}
