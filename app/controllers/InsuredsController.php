<?php

class InsuredsController extends BaseController {

    protected $insured;

    public function __construct(Insured $insured) {
        $this->beforeFilter('admin', ['except' => 'show']);
        $this->beforeFilter('expert', ['only' => 'show']);
        $this->insured = $insured;
    }

    public function index() {
        $insureds = $this->insured->all();
        return View::make('insureds.index', compact('insureds'));
    }

    public function create() {
        return View::make('insureds.create');
    }

    public function store() {
        $validator = Validator::make(Input::all(), Insured::$rules);
        $validator->setAttributeNames(Helper::niceNames('Insured'));
        if ($validator->passes()) {
            $insured = $this->insured->create(Input::all());
            Session::flash('notification', trans('notifications.insured_create', ['type' => $insured->type->name, 'name' => $insured->name]));
            return Redirect::route('insureds.index');
        }
        return Redirect::route('insureds.create')
                        ->withInput(Input::all())
                        ->withErrors($validator);
    }

    public function edit($id) {
        $insured = $this->insured->find($id);
        if(is_null($insured)) {
            return Redirect::route('insured.index');
        }
        
        return View::make('insureds.edit', compact('insured'));
    }

    public function update($id) {
        $insured = $this->insured->find($id);
        if(is_null($insured)) {
            return Redirect::route('insureds.index');
        }
        
        $rules = Insured::$rules;
        $rules['reference'] = 'unique:insureds,reference,'.$id;
        $validator = Validator::make(Input::all(), $rules);
        $validator->setAttributeNames(Helper::niceNames('Insured'));
        if ($validator->passes()) {
            $insured->update(Input::all());
            Session::flash('notification', trans('notifications.insured_update', ['type' => $insured->type->name, 'name' => $insured->name]));
            return Redirect::route('insureds.index');
        }
        return Redirect::route('insureds.create')
                        ->withInput(Input::all())
                        ->withErrors($validator);
    }

    public function destroy($id) {
        $insured = $this->insured->find($id);
        if (!is_null($insured)) {
            $insured->delete();
            Session::flash('notification', trans('notifications.insured_delete', ['type' => $insured->type->name, 'name' => $insured->name]));
        }
        return route('insureds.index');
    }
    
    public function show($id) {
        $insured = $this->insured->find($id);
        if (is_null($insured)) {
            return Redirect::route('insureds.index');
        }
        
        return View::make('insureds.show', compact('insured'));
    }

}
