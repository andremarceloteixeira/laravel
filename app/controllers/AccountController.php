<?php

class AccountController extends BaseController {

    protected $user;

    public function __construct() {
        $user = Auth::user();
        // Sets the current autenticated role type for user
        if (Check::isAdmin($user)) {
            $this->user = Auth::user()->admin;
        } elseif( Check::isClient($user)) {
            $this->user = Auth::user()->client;
        } else {
            $this->user = Auth::user()->expert;
        }
    }

    /**
     * Returns the view updating the profile.
     *
     * @return View
     */
    public function create()
    {
        return View::make('account.create')
                ->with(['user' => $this->user]);
    }
    
    /**
     * Stores the information given from the profile form.
     *
     * @return Redirect
     */
    public function store()
    {
        $data = Input::all();
        $rules = [
            'name' => 'required',
            'birthday' => 'date_format:"d/m/Y',
            'photo' => 'mimes:jpeg,gif,png|max:512',
            'current_password' => 'required_with:password',
            'password' => 'alpha_dash|between:5,12|confirmed|required_with:current_password',
        ];
        if($this->user->email != $data['email']) {
            $rules['email'] = 'required|email|unique:users,email,' . $this->user->user_id;
        }
        $v = Validator::make($data, $rules);
        $v->setAttributeNames(Helper::niceNames('Expert'));
        if ($v->passes()) {
            $data['photo'] = Helper::uploadPhoto('photo', $this->user->photo);
            $this->user->update($data);
            if (Input::has('current_password')) {
                if (Hash::check(Input::get('current_password'), $this->user->user->password)) {
                    $this->user->user->password = Input::get('password');
                    $this->user->user->birthday=  Input::get('birthday');
                    $this->user->user->save();
                } else {
                    return Redirect::back()
                                    ->withErrors([trans('validation.profile_wrong_password')]);
                }
            }
            Session::flash('notification', trans('notifications.profile_success'));
            return Redirect::route('account.create');
        }
        return Redirect::back()->withInput(Input::except('password'))
                        ->withErrors($v);
    }

}
