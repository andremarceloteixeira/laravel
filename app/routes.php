<?php

Route::post('ajax/dashboard/chart1', ['uses' => 'HomeController@expertsProcesses', 'as' => 'dashboard.expertsProcesses']);
Route::get('/', ['uses' => 'HomeController@index', 'as' => 'home.index']);
Route::get('lang/{token}', ['uses' => 'HomeController@changeLanguage', 'as' => 'home.lang']);

Route::get('teste', function() {
    $process = Process::find(1);
    return View::make('reports.begin', compact('process'));
});

Route::post('ajax/notifications/setjqueryviewer', array('uses' => 'NotificationsController@jqueryViewed', 'as' => 'notifications.jqueryViewed'));
Route::post('ajax/notifications/all', array('uses' => 'NotificationsController@all', 'as' => 'notifications.all'));
Route::resource('notifications', 'NotificationsController', ['only' => ['show']]);

Route::get('profile', array('uses' => 'AccountController@create', 'as' => 'account.create'));
Route::post('profile', array('uses' => 'AccountController@store', 'as' => 'account.store'));

Route::post('auth', array('before' => 'guest|csrf', 'uses' => 'AuthController@store', 'as' => 'auth.store'));
Route::get('logout', array('before' => 'auth', 'uses' => 'AuthController@destroy', 'as' => 'auth.destroy'));

Route::resource('types', 'TypesController', ['except' => ['destroy']]);

Route::get('clients/{id}/reset', ['uses' => 'ClientsController@reset', 'as' => 'clients.reset']);
Route::resource('clients', 'ClientsController');

Route::get('experts/{id}/reset', ['uses' => 'ExpertsController@reset', 'as' => 'experts.reset']);
Route::resource('experts', 'ExpertsController');

Route::resource('insureds', 'InsuredsController');

Route::get('processes/{id}/preliminar', ['uses' => 'ProcessesController@preliminar', 'as' => 'processes.preliminar']);
Route::get('processes/{id}/preliminar/download', ['uses' => 'ProcessesController@downloadPreliminar', 'as' => 'processes.downloadPreliminar']);
Route::get('processes/{id}/survey', ['uses' => 'ProcessesController@survey', 'as' => 'processes.survey']);
Route::get('processes/{id}/survey/download', ['uses' => 'ProcessesController@downloadSurvey', 'as' => 'processes.downloadSurvey']);
Route::get('processes/{id}/begin', ['uses' => 'ProcessesController@begin', 'as' => 'processes.begin']);
Route::get('processes/{id}/begin/download', ['uses' => 'ProcessesController@downloadBegin', 'as' => 'processes.downloadBegin']);
Route::get('processes/processesattachs/{id}/download', ['uses' => 'ProcessesController@downloadProcessAttach', 'as' => 'processes.downloadProcessAttach']);
Route::get('processes/clientsattachs/{id}/download', ['uses' => 'ProcessesController@downloadClientAttach', 'as' => 'processes.downloadClientAttach']);
Route::get('processes/{id}/finalreport/download', ['uses' => 'ProcessesController@downloadFinal', 'as' => 'processes.downloadFinal']);
Route::get('processes/{id}/invoice/download', ['uses' => 'ProcessesController@downloadInvoice', 'as' => 'processes.downloadInvoice']);
Route::get('processes/{id}/download', ['uses' => 'ProcessesController@downloadProcess', 'as' => 'processes.downloadProcess']);
Route::get('ajax/processes/deleteprocessattach', ['uses' => 'ProcessesController@deleteProcessAttach', 'as' => 'processes.deleteProcessAttach']);
Route::get('ajax/processes/deleteclientattach', ['uses' => 'ProcessesController@deleteClientAttach', 'as' => 'processes.deleteClientAttach']);
Route::get('ajax/processes/sendpreliminar', ['uses' => 'ProcessesController@sendPreliminar', 'as' => 'processes.sendPreliminar']);
Route::post('ajax/processes/invoice', ['uses' => 'ProcessesController@addInvoice', 'as' => 'processes.invoice']);
Route::post('ajax/processes/complete', ['uses' => 'ProcessesController@completeProcess', 'as' => 'processes.complete']);
Route::post('ajax/processes/cancel', ['uses' => 'ProcessesController@cancelProcess', 'as' => 'processes.cancel']);
Route::resource('processes', 'ProcessesController');

Route::get('ajax/processes/pending', ['uses' => 'PendingController@pending', 'as' => 'pending.pending']);
Route::get('ajax/processes/charge', ['uses' => 'PendingController@charge', 'as' => 'pending.charge']);
Route::resource('pending', 'PendingController', ['only' => ['index']]);

Route::resource('me/processes', 'ExpertsProcessesController', ['names' => ['index' => 'experts.processes.index', 'edit' => 'experts.processes.edit', 'update' => 'experts.processes.update'], 'only' => ['index', 'edit', 'update']]);

Route::resource('my/processes', 'ClientsProcessesController',
    ['names' => [
                            'index' => 'clients.processes.index',
                            'create' => 'clients.processes.create',
                            'store' => 'clients.processes.store',
                            'only' => ['index', 'create', 'store']]]);

Route::post('/my/processes/account', ['uses' => 'ClientsProcessesController@account', 'as' => 'clients.processes.account']);

Route::get('calendar', ['uses' => 'EventsController@index', 'as' => 'calendar.index']);
Route::post('ajax/calendar/store', ['uses' => 'EventsController@store', 'as' => 'calendar.store']);
Route::post('ajax/calendar/update', ['uses' => 'EventsController@update', 'as' => 'calendar.update']);
Route::post('ajax/calendar/destroy', ['uses' => 'EventsController@destroy', 'as' => 'calendar.destroy']);
Route::post('ajax/calendar/move', ['uses' => 'EventsController@move', 'as' => 'calendar.move']);
Route::resource('contact', 'ContactController');