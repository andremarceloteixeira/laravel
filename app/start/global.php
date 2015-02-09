<?php

/*
  |--------------------------------------------------------------------------
  | Register The Laravel Class Loader
  |--------------------------------------------------------------------------
  |
  | In addition to using Composer, you may use the Laravel class loader to
  | load your controllers and models. This is useful for keeping all of
  | your classes in the "global" namespace without Composer updating.
  |
 */

ClassLoader::addDirectories(array(
    app_path() . '/commands',
    app_path() . '/controllers',
    app_path() . '/models',
    app_path() . '/database/seeds',
));

/*
  |--------------------------------------------------------------------------
  | Application Error Logger
  |--------------------------------------------------------------------------
  |
  | Here we will configure the error logger setup for the application which
  | is built on top of the wonderful Monolog library. By default we will
  | build a basic log file setup which creates a single file for logs.
  |
 */

Log::useFiles(storage_path() . '/logs/laravel.log');

/*
  |--------------------------------------------------------------------------
  | Application Error Handler
  |--------------------------------------------------------------------------
  |
  | Here you may handle any errors that occur in your application, including
  | logging them or displaying custom views for specific errors. You may
  | even register several error handlers to handle different types of
  | exceptions. If nothing is returned, the default error view is
  | shown, which includes a detailed stack trace during debug.
  |
 */

App::error(function(Exception $exception) {
    Log::error($exception);

    if (!Config::get('app.debug')) {
        return Response::view('errors.500', array('message' => $exception->getMessage(), 'code' => '', 'footer' => 'File: ' . $exception->getFile() . ':' . $exception->getLine()), 500);
    }
});

/*
  |--------------------------------------------------------------------------
  | Maintenance Mode Handler
  |--------------------------------------------------------------------------
  |
  | The "down" Artisan command gives you the ability to put an application
  | into maintenance mode. Here, you will define what is displayed back
  | to the user if maintenance mode is in effect for the application.
  |
 */

App::down(function() {
    return Response::make("Be right back!", 503);
});


App::missing(function($exception) {
    if (Auth::check()) {
        return View::make('errors.404');
    } else {
        return View::make('auth.create');
    }
});

Form::macro('date', function($name, $old) {
    $div = '<div class="input-group">';
    $div .= '<input name="' . $name . '" value="' . $old . '" type="text" data-date-format="dd/mm/yyyy" data-date-viewmode="years" class="form-control date-picker">';
    $div .= '<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>';
    $div .= '</div>';
    return $div;
});

Log::useDailyFiles(storage_path() . '/logs/' . 'errors.log');

Event::listen('email.template', function($data) {
    if (!in_array('from', $data)) {
        $data['from'] = Config::get('settings.from');
    }
    if (!array_key_exists('from_name', $data)) {
        $data['from_name'] = Config::get('settings.from_name');
    }
    if (!array_key_exists('attachs', $data)) {
        $data['attachs'] = [];
    }
    if (!array_key_exists('signature', $data)) {
        $data['signature'] = Config::get('settings.from_name');
    }
    /* Tests Only */
    //$data['to'] = 'developmentest@hotmail.com';
    $currentLang = App::getLocale();
    App::setLocale($data['lang']);
    Mail::send($data['view'], $data, function($message) use($data) {
        $message->from(Config::get('settings.from'), Config::get('settings.from_name'));
        $message->replyTo(Config::get('settings.reply'), Config::get('settings.reply_name'));
        $message->subject(trans($data['subject']));
        $message->to($data['to']);

        foreach ($data['attachs'] as $a) {
            $message->attach($a);
        }
    });
    App::setLocale($currentLang);
});

/* Sets the language of the website, by default its english */
if (Cookie::has('lang')) {
    App::setLocale(Cookie::get('lang'));
} else {
    App::setLocale('pt');
}

/*
  |--------------------------------------------------------------------------
  | Require The Filters File
  |--------------------------------------------------------------------------
  |
  | Next we will load the filters file for the application. This gives us
  | a nice separate location to store our route and application filter
  | definitions instead of putting them all in the main routes file.
  |
 */

require app_path() . '/filters.php';

Validator::extend('attach', function($attribute, $value, $parameters)
{
    $options = ['jpg','gif','png','pdf','msg','xlsx','xls','doc','docx'];
    return in_array($value->getClientOriginalExtension(), $options);
});

