<?php

class TypesController extends BaseController {

    protected $type;

    public function __construct(Type $type) {
        $this->beforeFilter('admin');
        $this->type = $type;
    }

    public function index() {
        $types = $this->type->all();
        return View::make('types.index', compact('types'));
    }

    public function create() {
        return View::make('types.create');
    }

    public function store() {
        $input = Input::all();
        $validation = Validator::make($input, Type::$rules);
        $validation->setAttributeNames(Helper::niceNames('Type'));
        if ($validation->passes()) {
            $type = $this->type->create($input);
            if (Input::has('checkboxes')) {
                foreach (Input::get('checkboxes') as $v) {
                    if ($v != "") {
                        CheckField::create(['type_id' => $type->id, 'value' => $v]);
                    }
                }
            }
            if (Input::has('fields')) {
                foreach (Input::get('fields') as $v) {
                    if ($v != "") {
                        TypeField::create(['type_id' => $type->id, 'value' => $v]);
                    }
                }
            }
            return Redirect::route('types.index');
        }
        return Redirect::route('types.create')
                        ->withErrors($validation)
                        ->withInput(Input::all());
    }

    public function show($id) {
        $type = $this->type->find($id);
        if (is_null($type)) {
            return Redirect::route('types.index');
        }
        return View::make('reports.survey-preview', compact('type'));
    }

    public function edit($id) {
        $type = $this->type->find($id);
        if (is_null($type)) {
            return Redirect::route('types.index');
        }

        return View::make('types.edit')
                        ->with(['type' => $type]);
    }

    public function update($id) {
        $type = $this->type->find($id);
        if (is_null($type)) {
            Session::flash('notification', 'get fked bro.');
            return Redirect::route('types.index');
        }

        $input = Input::all();
        $rules = Type::$rules;
        $rules['name'] = 'required|unique:types,name,' . $id;
        $validation = Validator::make($input, $rules);
        $validation->setAttributeNames(Helper::niceNames('Type'));
        if ($validation->passes()) {
            $type->update($input);
            TypeField::where('type_id', '=', $type->id)->delete();
            CheckField::where('type_id', '=', $type->id)->delete();
            if (Input::has('checkboxes')) {
                foreach (Input::get('checkboxes') as $v) {
                    if ($v != "") {
                        CheckField::create(['type_id' => $type->id, 'value' => $v]);
                    }
                }
            }
            if (Input::has('fields')) {
                foreach (Input::get('fields') as $v) {
                    if ($v != "") {
                        TypeField::create(['type_id' => $type->id, 'value' => $v]);
                    }
                }
            }
            return Redirect::route('types.index');
        }
        return Redirect::route('types.edit', ['id' => $id])
                        ->withErrors($validation)
                        ->withInput(Input::all());
    }

}
