<?php

/*
  |--------------------------------------------------------------------------
  | Application & Route Filters
  |--------------------------------------------------------------------------
  |
  | Below you will find the "before" and "after" events for the application
  | which may be used to do any work before or after a request into your
  | application. Here you may also register your custom route filters.
  |
 */

App::before(function($request) {
    //
});


App::after(function($request, $response) {
    //
});

/*
  |--------------------------------------------------------------------------
  | Authentication Filters
  |--------------------------------------------------------------------------
  |
  | The following filters are used to verify that the user of the current
  | session is logged into this application. The "basic" filter easily
  | integrates HTTP Basic authentication for quick, simple checking.
  |
 */

Route::filter('auth', function() {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return View::make('auth.create');
        }
    }
});

Route::filter('admin', function() {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return View::make('auth.create');
        }
    } else {
        $user = Auth::user();
        if (!Check::isAdmin($user)) {
            if (Request::ajax()) {
                return Response::make('Unauthorized', 401);
            } else {
                return Response::view('errors.401', [], 401);
            }
        }
    }
});

Route::filter('expert', function() {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return View::make('auth.create');
        }
    } else {
        $user = Auth::user();
        if (!Check::isExpert($user) && !Check::isAdmin($user)) {
            if (Request::ajax()) {
                return Response::make('Unauthorized', 401);
            } else {
                return Response::view('errors.401', [], 401);
            }
        }
    }
});

Route::filter('expert_only', function() {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return View::make('auth.create');
        }
    } else {
        $user = Auth::user();
        if (!Check::isExpert($user)) {
            if (Request::ajax()) {
                return Response::make('Unauthorized', 401);
            } else {
                return Response::view('errors.401', [], 401);
            }
        }
    }
});

Route::filter('client', function() {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return View::make('auth.create');
        }
    } else {
        $user = Auth::user();
        if (!Check::isClient($user) && !Check::isAdmin($user)) {
            if (Request::ajax()) {
                return Response::make('Unauthorized', 401);
            } else {
                return Response::view('errors.401', [], 401);
            }
        }
    }
});

Route::filter('client_only', function() {
    if (Auth::guest()) {
        if (Request::ajax()) {
            return Response::make('Unauthorized', 401);
        } else {
            return View::make('auth.create');
        }
    } else {
        $user = Auth::user();
        if (!Check::isClient($user)) {
            if (Request::ajax()) {
                return Response::make('Unauthorized', 401);
            } else {
                return Response::view('errors.401', [], 401);
            }
        }
    }
});

Route::filter('guest', function() {
    if (Auth::check()) {
        Auth::logout();
        return Redirect::to('/');
    }
});


Route::filter('auth.basic', function() {
    return Auth::basic();
});

/*
  |--------------------------------------------------------------------------
  | CSRF Protection Filter
  |--------------------------------------------------------------------------
  |
  | The CSRF filter is responsible for protecting your application against
  | cross-site request forgery attacks. If this special token in a user
  | session does not match the one given in this request, we'll bail.
  |
 */

Route::filter('csrf', function() {
    if (Session::token() != Input::get('_token')) {
        return Response::make('You can only loggin from the website. The Administrator will be reported of this issued.<br>IP Address:' . Helper::getCurrentIp(), 401);
    }
});


Route::filter('ajax', function() {
    if (!Request::ajax()) {
        return Response::make('Unauthorized', 401);
    }
});

Route::filter('feature', function($route, $request, $first) {
    if(!Check::isEnableFeature($first)) {
        App::abort(404);
    }

});

