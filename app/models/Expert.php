<?php

class Expert extends Eloquent {

    protected $table = 'experts';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $fillable = array('birthday', 'function', 'expert');

    public static $rules = array(
        'username' => 'required|between:4,25|unique:users,username',
        'email' => 'email',
        'name' => 'required',
        'photo' => 'mimes:jpeg,gif,png|max:512',
        'birthday' => 'date_format:"d/m/Y"'
    );
    
    public static $names = array(
        'username' => 'users.username',
        'email' => 'users.email',
        'name' => 'users.name',
        'photo' => 'users.photo',
        'birthage' => 'experts.birthday',
        'function' => 'experts.function'
    );

    public function user() {
        return $this->belongsTo('User');
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
    
    public function getBirthageAttribute() {
        if(!Helper::isNull($this->attributes['birthday'])) {
            $date = \Carbon\Carbon::createFromFormat('d/m/Y', $this->attributes['birthday']);
            return $this->attributes['birthday'] . ' (' . $date->age . ' ' . trans('experts.age') . ')';
        }
        return '';
    }
    
    public function getAgeAttribute() {
        if(!is_null($this->attributes['birthday'])) {
            $date = \Carbon\Carbon::createFromFormat('d/m/Y', $this->attributes['birthday']);
            return  $date->age . ' ' . trans('experts.age');
        }
        return '';
    }
    
    public function scopeDropdown($query) {
        $tmp[] = trans('actions.none');
        $arr = [];
        foreach($query->get() as $c) {
            $arr[$c->user_id] = $c->name;
        }
        asort($arr);
        $arr = $tmp + $arr;
        return $arr;
    }

    public function delete() {
        User::find($this->user_id)->delete();
        parent::delete();
    }
        
    public function processes() {
        return $this->hasMany('Process');
    }

    public static function create(array $input) {
        return DB::transaction(function() use ($input) {
                $user = new User;
                $user->fill($input);
                $user->role_id = 2;
                $user->password = $input['password'];
                $user->save();

                $expert = new Expert;
                $expert->user_id = $user->id;
                $expert->fill($input);
                $expert->save();
                return $expert;
        });
    }
    
    public function update(array $attributes = array()) {
        $this->user()->update($attributes);
        parent::update($attributes);
    }

}
