<?php

class AuthController extends BaseController {
    /**
     * Stores the information given from the auth form.
     *
     * @return Redirect
     */
    public function store() {
        $v = Validator::make(Input::all(), ['username' => 'required', 'password' => 'required']);
        if ($v->passes()) {
            if (Auth::attempt(['username' => Input::get('username'), 'password' => Input::get('password')], Input::get('remember'))) {
                return Redirect::to('/');
            }
            return Redirect::back()
                            ->withInput(Input::except('password'))
                            ->withErrors([trans('validation.auth')]);
        }
        return Redirect::back()
                        ->withInput(Input::except('password'))
                        ->withErrors($v);
    }

    /**
     * Logouts the current autenticated user
     *
     * @return Redirect
     */
    public function destroy() {
        Auth::logout();
        return Redirect::to('/');
    }

}
