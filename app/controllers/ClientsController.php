<?php

class ClientsController extends BaseController {

    protected $client;

    public function __construct(Client $client) {

        $this->client = $client;
    }

    /**
     * Display a listing of the clients.
     *
     * @return View
     */
    public function index()
    {
        $clients = $this->client->all();
        return View::make('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     *
     * @return View
     */
    public function create() {
        $countries = Country::dropdown();
        return View::make('clients.create', compact('countries'));
    }

    /**
     * Stores the information given from the client form.
     *
     * @return Redirect
     */
    public function store() {
        $input = Input::all();
        $validation = Validator::make($input, Client::$rules);
        $validation->setAttributeNames(Helper::niceNames('Client'));

        $password = str_random(12);
        $input['password'] = $password;

        if ($validation->passes()) {
            $input['photo'] = Helper::uploadPhoto('photo');
            $client = $this->client->create($input);

            if (!Helper::isNull($client->email)) {
                Helper::registerEmail($client->name, $client->email, $password, $client->country_id, $client->username);
            }
            
            Helper::makeNotificationAdmin('notifications.new_client', $client->name, 'clients/'.$client->user_id);

            Session::flash('notification', trans('notifications.client_create', ['name' => $client->name]));
            Session::flash('credentials', trans('notifications.credentials', ['username' => $client->username, 'password' => $password]));
            return Redirect::route('clients.index');
        }
        return Redirect::route('clients.create')
                        ->withInput(Input::except('photo'))
                        ->withErrors($validation);
    }

    /**
     * Display the specified client.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $isvalid = false;
        if(Auth::user()->id == $id || Check::isAdmin()) {
          $isvalid = true;
        }
        if (Auth::user()->id != $id || !$isvalid) {
            return Redirect::route('clients.processes.index');
        }
        $client = $this->client->find($id);
        if (is_null($client)) {
            return Redirect::route('clients.index');
        }
        return View::make('clients.show', compact('client', 'isvalid'));
    }

    /**
     * Show the form for editing the specified client.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        $isvalid = $this->isCurrentUserOrAdmin($id);
        if (Auth::user()->id != $id || !$isvalid) {
            return Redirect::route('clients.processes.index');
        }
        $client = $this->client->find($id);
        $countries = Country::dropdown();
        if (is_null($client)) {
            return Redirect::route('clients.index');
        }
        return View::make('clients.edit')->with(['client' => $client, 'countries' => $countries]);
    }


    private function isCurrentUserOrAdmin($id)
    {
        return Auth::user()->id == $id || Check::isAdmin();
    }

    private function isCurrentUser($id)
    {
        return Auth::user()->id == $id;
    }

    /**
     * Updates the information given from the client form.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $isClient = $this->isCurrentUser($id);
        $input = Input::all();
        $client = $this->client->find($id);
        $rules = Client::$rules;
        $rules['email'] = 'email|unique:users,email,' . $client->user->id;
        $rules['username'] = 'required|unique:users,username,' . $client->user->id;
        $validation = Validator::make($input, $rules);
        $validation->setAttributeNames(Helper::niceNames('Client'));
        if ($validation->passes()) {
            $input['photo'] = Helper::uploadPhoto('photo', $client->photo);
            $client->update($input);

            Helper::makeNotificationAdmin('notifications.change_client', $client->name, 'clients/'.$client->user_id);
            
            Session::flash('notification', trans('notifications.client_update', ['name' => $client->name]));


            return Redirect::route($isClient ? 'clients.processes.index': 'clients.index');
        }
        return Redirect::route('clients.edit', $id)
                        ->withInput(Input::except('photo'))
                        ->withErrors($validation);
    }

    /**
     * Removes the specified client from storage.
     *
     * @param  int  $id
     * @return Route
     */
    public function destroy($id) {
        $client = $this->client->find($id);
        $name = $client->user->name;
        $client->delete();

        Helper::makeNotificationAdmin('notifications.delete_client', $name, '');
        Session::flash('notification', trans('notifications.client_delete', ['name' => $name]));

        return route('clients.index');
    }

    /**
     * Resets the specified client password
     *
     * @return String
     */
    public function reset($id)
    {
        $isClient = $this->isCurrentUser($id);
        $password = str_random(12);
        $client = $this->client->find($id);
        $client->user->password = $password;
        $client->user->save();
        if (!Helper::isNull($client->email)) {
            Helper::resetEmail($client->name, $client->email, $password, $client->country_id);
        }
        Session::flash('notification', trans('notifications.client_reset', ['name' => $client->name]));
        Session::flash('credentials', trans('notifications.credentials', ['username' => $client->username, 'password' => $password]));

        if($isClient) {
            return route('clients.processes.index');
        }
        return route('clients.index');
    }

}
