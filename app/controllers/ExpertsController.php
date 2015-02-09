<?php

class ExpertsController extends BaseController {

    /**
     * Expert Repository
     *
     * @var Expert
     */
    protected $expert;

    public function __construct(Expert $expert) {
        $this->beforeFilter('admin', ['except' => 'show']);
        $this->beforeFilter('expert', ['only' => 'show']);
        $this->expert = $expert;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $experts = $this->expert->all();

        return View::make('experts.index', compact('experts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('experts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $input = Input::all();
        $validation = Validator::make($input, Expert::$rules);
        $validation->setAttributeNames(Helper::niceNames('Expert'));

        $password = str_random(12);
        $input['password'] = $password;

        if ($validation->passes()) {
            $input['photo'] = Helper::uploadPhoto('photo');
            $expert = $this->expert->create($input);

           Helper::makeNotificationAdmin('notifications.new_expert', $expert->name, 'experts/'.$expert->user_id);
           Session::flash('notification', trans('notifications.expert_create', ['name' => $expert->name]));
           Session::flash('credentials', trans('notifications.credentials', ['username' => $expert->username, 'password' => $password]));
            
            return Redirect::route('experts.index');
        }

        return Redirect::route('experts.create')
                        ->withInput(Input::except('photo'))
                        ->withErrors($validation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $expert = $this->expert->find($id);

        if (is_null($expert)) {
            return Redirect::route('experts.index');
        }

        return View::make('experts.edit', compact('expert'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $input = Input::all();
        $expert = $this->expert->find($id);

        $rules = Expert::$rules;
        $rules['email'] = 'email|unique:users,email,' . $expert->user->id;
        $rules['username'] = 'required|unique:users,username,' . $expert->user->id;
        $validation = Validator::make($input, $rules);
        $validation->setAttributeNames(Helper::niceNames('Expert'));

        if ($validation->passes()) {
            $input['photo'] = Helper::uploadPhoto('photo', $expert->photo);
            $expert->update($input);

            Helper::makeNotificationAdmin('notifications.change_expert', $expert->name, 'experts/'.$expert->user_id);
            Session::flash('notification', trans('notifications.expert_update', ['name' => $expert->name]));

            return Redirect::route('experts.index');
        }
        return Redirect::route('experts.edit', $id)
                        ->withInput(Input::except('photo'))
                        ->withErrors($validation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $expert = $this->expert->find($id);
        $name = $expert->name;
        $expert->delete();
        
        Helper::makeNotificationAdmin('notifications.delete_expert', $name, '');
        
        Session::flash('notification', trans('notifications.expert_delete', ['name' => $name]));
        return route('experts.index');
    }

    /**
     * Resets the clients password
     *
     * @return String
     */
    public function reset($id) {
        $password = str_random(12);
        $expert = $this->expert->find($id);
        $expert->user->password = $password;
        $expert->user->save();

        Session::flash('notification', trans('notifications.expert_reset', ['name' => $expert->name]));
        Session::flash('credentials', trans('notifications.credentials', ['username' => $expert->username, 'password' => $password]));

        return route('experts.index');
    }

    /**
     * Resets the clients password
     *
     * @return Route
     */
    public function show($id) {
        $expert = $this->expert->find($id);
        if (is_null($expert)) {
            return Redirect::route('experts.index');
        }

        return View::make('experts.show', compact('expert'));
    }

}
