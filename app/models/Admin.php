<?php

class Admin extends Eloquent {

    protected $table = 'admins';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $fillable = array('birthday', 'function');

    public static $rules = array(
        'username' => 'required|between:4,15|unique:users,username',
        'email' => 'email',
        'name' => 'required',
        'photo' => 'mimes:jpeg,gif,png|max:512',
        'birthday' => 'date_format:"d/m/Y"',
    );

    public static $names = array(
        'username' => 'users.username',
        'name' => 'users.name',
        'photo' => 'users.photo',
        'birthday' => 'admins.birthday',
        'function' => 'admins.function',
    );


    public function user() {
        return $this->belongsTo('User');
    }

    public function delete() {
        User::find($this->user_id)->delete();
        parent::delete();
    }
    
    public function getNameAttribute() {
        return $this->user->name;
    }
    
    public function getUsernameAttribute() {
        return $this->user->username;
    }
    
    public function getPhotoAttribute() {
        return $this->user->photo;
    }
    
    public function getEmailAttribute() {
        return $this->user->email;
    }
    
    public static function create(array $input) {
        return DB::transaction(function() use ($input) {
                $user = new User;
                $user->fill($input);
                $user->role_id = 3;
                $user->password = $input['password'];
                $user->save();
                
                $admin = new Admin;
                $admin->user_id = $user->id;
                $admin->fill($input);
                $admin->save();
                return $admin;
        });
    }
    
    public function update(array $attributes = array()) {
        $this->user()->update($attributes);
        parent::update($attributes);
    }

}
