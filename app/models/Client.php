<?php

class Client extends Eloquent {

    protected $table = 'clients';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $fillable = array('address', 'city', 'nif', 'zipcode', 'reference', 'country_id', 'birthday');

    public static $rules = array(
        'username' => 'required|between:4,25|unique:users,username',
        'email' => 'email',
        'name' => 'required',
        'photo' => 'mimes:jpeg,gif,png|max:512',
        'country_id' => 'required|exists:countries,id',
    );

    public static $names = array(
        'username' => 'users.username',
        'reference' => 'clients.reference',
        'name' => 'users.name',
        'photo' => 'users.photo',
        'city' => 'clients.city',
        'address' => 'clients.address',
        'nif' => 'clients.nif',
        'zipcode' => 'clients.zipcode',
        'cname' => 'clients.country_id',
    );


    public function user() {
        return $this->belongsTo('User');
    }

    public function country() {
        return $this->belongsTo('Country');
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
    
    public function getEmailAttribute() {
        return $this->user->email;
    }
    
    public function getPhotoAttribute() {
        return $this->user->photo;
    }
    
    public function getCnameAttribute() {
        return $this->country->name;
    }
    
    public function scopeDropdown($query) {
        $arr = [];
        foreach($query->get() as $c) {
            $arr[$c->user_id] = $c->name;
        }
        asort($arr);
        return $arr;
    }
    
    public static function create(array $input) {
        return DB::transaction(function() use ($input) {
                $user = new User;
                $user->fill($input);
                $user->role_id = 1;
                $user->password = $input['password'];
                $user->save();
                
                $client = new Client;
                $client->user_id = $user->id;
                $client->fill($input);
                $client->save();
                return $client;
        });
    }
    
    public function update(array $attributes = array()) {
        $this->user()->update($attributes);
        parent::update($attributes);
    }

}
