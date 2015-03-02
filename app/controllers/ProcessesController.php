<?php

class ProcessesController extends BaseController
{

    protected $process;
    protected $user;

    /**
     * @param Process $process
     */
    public function __construct(Process $process)
    {

        /*$this->beforeFilter('admin', ['only' => ['index', 'create', 'store', 'edit', 'update',
            'completeProcess', 'cancelProcess', 'addInvoice', 'downloadFinal']]);
        $this->beforeFilter('expert', ['only' => ['sendPreliminar', 'deleteProcessAttach',
            'downloadProcessAttach', 'downloadProcess']]);
        $this->beforeFilter('auth', ['only' => ['preliminar', 'downloadPreliminar', 'survey', 'downloadSurvey', 'show',
            'downloadClientAttach', 'deleteClientAttach', 'begin', 'downloadBegin']]);
        $this->beforeFilter('client', ['only' => ['downloadInvoice', 'deleteClientAttach']]);
        $this->beforeFilter('ajax', ['only' => ['deleteAttach', 'sendPreliminar']]);*/
        $this->process = $process;
        $this->user = Auth::user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $processing = $this->process->where('status_id', '=', '2')->get();
        $complete = $this->process->where('status_id', '=', '3')->get();
        $cancelled = $this->process->where('status_id', '=', '4')->get();
        return View::make('processes.index')
            ->with(['processing' => $processing, 'complete' => $complete, 'cancelled' => $cancelled]);
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $data = date("Y");
        $reference = false;
        $lastProcess = $this->process->orderby('created_at', 'desc')->first();
        if (!empty($lastProcess)) {
            $reference = explode('/',$lastProcess->certificate);
            $reference = $reference[0]+ 1 .'/' . substr($data, 2);
        }
        return View::make('processes.create', ['reference' =>  $reference]);
    }

    /**
     * @return mixed
     */
    public function store()
    {
        $input = Input::all();
        $rules = Process::$rules;
        $validation = Validator::make($input, $rules);
        $validation->setAttributeNames(Helper::niceNames('Process'));
        if ($validation->passes()) {
            $input['status_id'] = 2;
            if ($input['expert_id'] == 0) {
                $input['expert_id'] = null;
            }
            if (Helper::isNull($input['insured_id'])) {
                $input['insured_id'] = null;
            } else {
                $insured = Insured::where('insured_type_id', '=', 1)->where('name', '=', $input['insured_id'])->get();
                if (count($insured) > 0) {
                    $input['insured_id'] = $insured[0]->id;
                } else {
                    $input['insured_id'] = Insured::create(['name' => $input['insured_id'], 'insured_type_id' => 1])->id;
                }
            }
            if (Helper::isNull($input['taker_id'])) {
                $input['taker_id'] = null;
            } else {
                $taker = Insured::where('insured_type_id', '=', 2)->where('name', '=', $input['taker_id'])->get();
                if (count($taker) > 0) {
                    $input['taker_id'] = $taker[0]->id;
                } else {
                    $input['taker_id'] = Insured::create(['name' => $input['taker_id'], 'insured_type_id' => 2])->id;
                }
            }
            $process = $this->process->create($input);

            Helper::makeProcessKeys($process, $input['key'], $input['value']);
            Helper::makeProcessAttachs($process, 'process_attachments');
            Helper::makeClientAttachs($process, 'client_attachments');
            Helper::makeNotificationAdmin('notifications.new_process', $process->certificate, 'processes/' . $process->id);
            Helper::chargeEmail($process->client->name, $process->certificate, $process->email, $process->client->country_id);

            Session::flash('notification', trans('notifications.process_create', ['name' => $process->certificate]));
            return Redirect::route('processes.index');
        }
        return Redirect::route('processes.create')
            ->withInput(Input::except('process_attachments', 'client_attachments'))
            ->withErrors($validation);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $process = $this->process->find($id);

        if (!Check::canEditProcess($process)) {
            return Redirect::route('processes.index');
        }

        return View::make('processes.edit', compact('process'));
    }

    public function update($id)
    {
        $process = $this->process->find($id);
        if (!Check::canEditProcess($process)) {
            return Redirect::route('processes.index');
        }
        $input = Input::all();
        $rules = Process::$rules;
        $rules['certificate'][2] = 'unique:processes,certificate,' . $id;
        $validation = Validator::make($input, $rules);
        $validation->setAttributeNames(Helper::niceNames('Process'));
        if ($validation->passes()) {
            $input['status_id'] = $process->status_id;
            if ($input['expert_id'] == 0) {
                $input['expert_id'] = null;
            }
            if (Helper::isNull($input['insured_id'])) {
                $input['insured_id'] = null;
            } else {
                $insured = Insured::where('insured_type_id', '=', 1)->where('name', '=', $input['insured_id'])->get();
                if (count($insured) > 0) {
                    $input['insured_id'] = $insured[0]->id;
                } else {
                    $input['insured_id'] = Insured::create(['name' => $input['insured_id'], 'insured_type_id' => 1])->id;
                }
            }
            if (Helper::isNull($input['taker_id'])) {
                $input['taker_id'] = null;
            } else {
                $taker = Insured::where('insured_type_id', '=', 2)->where('name', '=', $input['taker_id'])->get();
                if (count($taker) > 0) {
                    $input['taker_id'] = $taker[0]->id;
                } else {
                    $input['taker_id'] = Insured::create(['name' => $input['taker_id'], 'insured_type_id' => 2])->id;
                }
            }
            $process->update($input);

            Helper::makeProcessKeys($process, $input['key'], $input['value']);
            Helper::makeProcessAttachs($process, 'process_attachments');
            Helper::makeClientAttachs($process, 'client_attachments');
            Helper::makeNotificationAdmin('notifications.change_process', $process->certificate, 'processes/' . $process->id);

            Session::flash('notification', trans('notifications.process_update', ['name' => $process->certificate]));
            return Redirect::route('processes.index');
        }

        return Redirect::route('processes.edit', ['id' => $id])
            ->withInput(Input::except('process_attachments', 'client_attachments'))
            ->withErrors($validation);
    }

    public function preliminar($id)
    {
        $process = $this->process->find($id);
        if (!Check::canUpgradeProcess($process)) {
            return Redirect::back();
        }

        if (Check::isAdmin()) {
            $back = route('processes.index');
            $edit = route('processes.edit', $process->id);
        } else if (Check::isExpert()) {
            $back = route('experts.processes.index');
            $edit = route('experts.processes.edit', $process->id);
        } else if (Check::isClient()) {
            $back = route('clients.processes.index');
            $edit = '';
        }

        return View::make('reports.preliminar', compact('process'))
            ->with(['topFixed' => route('processes.downloadPreliminar', $process->id), 'back' => $back, 'edit' => $edit]);
    }

    public function downloadPreliminar($id)
    {
        $process = $this->process->find($id);

        if (is_null($process->type)) {
            return Redirect::route('processes.index');
        }
        return Helper::makePreliminar($process, false, true);
    }

    public function survey($id)
    {
        $process = $this->process->find($id);
        if (!Check::canUpgradeProcess($process)) {
            return Redirect::route('processes.index');
        }

        if (Check::isAdmin()) {
            $back = route('processes.index');
            $edit = route('processes.edit', $process->id);
        } else if (Check::isExpert()) {
            $back = route('experts.processes.index');
            $edit = route('experts.processes.edit', $process->id);
        }

        return View::make('reports.survey', compact('process'))
            ->with(['topFixed' => route('processes.downloadSurvey', $process->id), 'back' => $back, 'edit' => $edit]);
    }

    public function downloadSurvey($id)
    {
        $process = $this->process->find($id);

        if (is_null($process->type)) {
            return Redirect::route('processes.index');
        }

        return Helper::makeSurvey($process, true);
    }

    public function begin($id)
    {
        $process = $this->process->find($id);
        if (!Check::canUpgradeProcess($process)) {
            return Redirect::route('processes.index');
        }

        if (Check::isAdmin()) {
            $back = route('processes.index');
            $edit = route('processes.edit', $process->id);
        } else if (Check::isExpert()) {
            $back = route('experts.processes.index');
            $edit = route('experts.processes.edit', $process->id);
        }

        return View::make('reports.begin', compact('process'))
            ->with(['topFixed' => route('processes.downloadBegin', $process->id), 'back' => $back, 'edit' => $edit]);
    }

    public function downloadBegin($id)
    {

        $process = $this->process->find($id);

        if (is_null($process->type)) {
            return Redirect::route('processes.index');
        }

        return Helper::makeBegin($process, true);
    }

    public function show($id)
    {
        $process = $this->process->find($id);
        if (is_null($process)) {
            return Redirect::route('processes.index');
        }
        if (Check::isExpert()) {
            if (is_null($process->expert)) {
                return Redirect::route('experts.processes.index');
            }
            if ($process->expert->user_id != $this->user->id) {
                return Redirect::route('experts.processes.index');
            }
            $routeBack = 'experts.processes.index';
            $back = trans('navigation.experts.processes');
        } else if (Check::isClient()) {
            if ($process->client->user_id != $this->user->id) {
                return Redirect::route('clients.processes.index');
            }
            $routeBack = 'clients.processes.index';
            $back = trans('navigation.clients.processes');
        } else {
            $routeBack = 'processes.index';
            $back = trans('navigation.processes');
        }
        return View::make('processes.show')
            ->with(['process' => $process, 'routeBack' => $routeBack, 'back' => $back]);
    }

    public function sendPreliminar()
    {
        try {
            if (!Input::has('id')) {
                return Response::json(['status' => 'error', 'title' => trans('actions.error'), 'message' => trans('notifications.preliminar_send_param')]);
            }
            $process = $this->process->find(Input::get('id'));

            if (is_null($process)) {
                return Response::json(['status' => 'error', 'title' => trans('actions.error'), 'message' => trans('notifications.preliminar_send_id')]);
            }

            if (Helper::makePreliminar($process, true)) {
                return Response::json(['status' => 'success', 'title' => trans('actions.success'), 'message' => trans('notifications.preliminar_sent', ['id' => $process->certificate, 'email' => $process->email])]);
            } else {
                return Response::json(['status' => 'error', 'title' => trans('actions.error'), 'message' => trans('notifiactions.preliminar_send_error')]);
            }
        } catch (Exception $ex) {
            return $ex->getMessage() . '|' . $ex->getTraceAsString();
        }
    }

    public function deleteProcessAttach()
    {
        try {
            if (!Input::has('id')) {
                return Response::json(['status' => 'error', 'title' => trans('actions.error'), 'message' => trans('notifications.preliminar_send_param')]);
            }

            $attach = ProcessAttach::find(Input::get('id'));

            if (is_null($attach)) {
                return Response::json(['status' => 'error', 'title' => trans('actions.error'), 'message' => trans('notifications.preliminar_send_id')]);
            }

            $name = $attach->name;
            $attach->delete();

            return Response::json(['status' => 'success', 'title' => trans('actions.success'), 'message' => trans('notifications.deleted_attach', ['name' => $name])]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function deleteClientAttach()
    {
        try {
            if (!Input::has('id')) {
                return Response::json(['status' => 'error', 'title' => trans('actions.error'), 'message' => trans('notifications.preliminar_send_param')]);
            }

            $attach = ClientAttach::find(Input::get('id'));

            if (is_null($attach)) {
                return Response::json(['status' => 'error', 'title' => trans('actions.error'), 'message' => trans('notifications.preliminar_send_id')]);
            }

            $name = $attach->name;
            $attach->delete();

            return Response::json(['status' => 'success', 'title' => trans('actions.success'), 'message' => trans('notifications.deleted_attach', ['name' => $name])]);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function addInvoice()
    {
        $rules = [
            'id' => 'required|exists:processes,id',
            'invoice' => 'required|mimes:pdf|max:10240'
        ];

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->passes($validator)) {
            $process = $this->process->find(Input::get('id'));

            if ($process->status_id != 3) {
                return Redirect::back()
                    ->withErrors([trans('notifications.error_invoice')]);
            }

            $file = Input::file('invoice');
            $year = substr(Carbon::createFromFormat('Y-m-d H:i:s', $process->created_at)->year, -2);
            $path = Config::get('settings.process_folder') . $process->id . '-' . $year . '/';
            $path2 = $process->id . '-' . $year . '/';
            $filename = 'Fatura.' . $file->getClientOriginalExtension();
            if ($file->move($path, $filename) != false) {
                $process->invoice = $path2 . $filename;
                $process->save();
            }

            Helper::makeNotificationAdmin('notifications.add_invoice', $process->certificate, 'processes/' . $process->id);
            Session::flash('notification', trans('notifications.add_invoice', ['value' => $process->certificate]));
            return Redirect::back();
        }
        return Redirect::back()
            ->withErrors($validator);
    }

    public function completeProcess()
    {
        $rules = [
            'id' => 'required|exists:processes,id',
            'file' => 'required|mimes:pdf|max:5120',
            'invoice' => 'mimes:pdf|max:5120'
        ];

        $validator = Validator::make(Input::all(), $rules);
        $validator->setAttributeNames(['file' => trans('processes.complete_report'), 'invoice' => trans('processes.invoice')]);
        if ($validator->passes($validator)) {
            $process = $this->process->find(Input::get('id'));

            if (!Check::canUpgradeProcess($process)) {
                return Redirect::back()
                    ->withErrors([trans('notifications.error_complete_process')]);
            }

            $file = Input::file('file');
            $year = substr(Carbon::createFromFormat('Y-m-d H:i:s', $process->created_at)->year, -2);
            $path = Config::get('settings.process_folder') . $process->id . '-' . $year . '/';
            $path2 = $process->id . '-' . $year . '/';
            $filename = 'Relatorio_Final.' . $file->getClientOriginalExtension();
            if ($file->move($path, $filename) != false) {
                $process->status_id = 3;
                $process->complete_report = $path2 . $filename;
                $process->finish = Carbon::now()->format('Y-m-d H:i:s');

                Helper::makeNotificationAdmin('notifications.complete_process', $process->certificate, 'processes/' . $process->id);
                Session::flash('notification', trans('notifications.process_complete', ['name' => $process->certificate, 'type' => trans('processes.singular')]));
                if (Input::hasFile('invoice')) {
                    $invoice = Input::file('invoice');
                    $filename2 = 'Fatura.' . $invoice->getClientOriginalExtension();
                    if ($invoice->move($path, $filename2)) {
                        $process->invoice = $path2 . $filename2;
                    }
                }
                $process->save();
                return Redirect::back();
            }
        }
        return Redirect::back()
            ->withErrors($validator);
    }

    public function cancelProcess()
    {
        $rules = [
            'id' => 'required|exists:processes,id',
            'cancel_reason' => 'required|max:500'
        ];

        $validator = Validator::make(Input::all(), $rules);
        $validator->setAttributeNames(['cancel_reason' => trans('processes.cancel_reason')]);
        if ($validator->passes($validator)) {
            $process = $this->process->find(Input::get('id'));

            if (!Check::canUpgradeProcess($process)) {
                return Redirect::back()->withErrors([trans('notifications.error_cancel_process')]);
            }
            $process->status_id = 4;
            $process->finish = Carbon::now()->format('Y-m-d H:i:s');
            $process->cancel_reason = Input::get('cancel_reason');
            $process->save();

            Helper::makeNotificationAdmin('notifications.cancel_process', $process->certificate, 'processes/' . $process->id);
            Session::flash('notification', trans('notifications.process_cancel', ['name' => $process->certificate, 'type' => trans('processes.singular')]));
        }
        return Redirect::back()
            ->withErrors($validator);
    }

    public function downloadProcess($id)
    {
        $process = $this->process->find($id);

        if (!Check::canUpgradeProcess($process)) {
            return route('processes.index');
        }

        $path = Helper::updateProcess($process, true);
        return Response::download($path);
    }

    public function downloadProcessAttach($id)
    {
        $imgs = ['jpg', 'png', 'gif'];
        $attach = ProcessAttach::find($id);

        if (is_null($attach)) {
            return Redirect::route('processes.index');
        }
        $path = Config::get('settings.process_folder') . $attach->path;
        $ext = File::extension($path);

        if (in_array($ext, $imgs)) {
            $image = Image::make($path);
            return $image->response();
        } else if ($ext == 'pdf') {
            $content = file_get_contents($path);
            return Response::make($content, 200, array('content-type' => 'application/pdf'));
        }
        return Response::download($path);
    }

    public function downloadClientAttach($id)
    {
        $imgs = ['jpg', 'png', 'gif'];
        $attach = ClientAttach::find($id);

        if (is_null($attach)) {
            return Redirect::route('processes.index');
        }
        $path = Config::get('settings.process_folder') . $attach->path;
        $ext = File::extension($path);

        if (in_array($ext, $imgs)) {
            $image = Image::make($path);
            return $image->response();
        } else if ($ext == 'pdf') {
            $content = file_get_contents($path);
            return Response::make($content, 200, array('content-type' => 'application/pdf'));
        }
        return Response::download($path);
    }

    public function downloadFinal($id)
    {
        $process = $this->process->find($id);

        if (is_null($process)) {
            return Redirect::route('processes.index');
        }

        if ($process->status_id != 3) {
            return Redirect::route('processes.index');
        }
        $report = Config::get('settings.process_folder') . $process->complete_report;
        $content = file_get_contents($report);
        return Response::make($content, 200, array('content-type' => 'application/pdf'));
        //return Response::download($report);
    }

    public function downloadInvoice($id)
    {
        $process = $this->process->find($id);

        if (is_null($process)) {
            return Redirect::route('processes.index');
        }

        if ($process->status_id != 3) {
            return Redirect::route('processes.index');
        }
        if (!Helper::isNull($process->invoice)) {
            $report = Config::get('settings.process_folder') . $process->invoice;
            $content = file_get_contents($report);
            return Response::make($content, 200, array('content-type' => 'application/pdf'));
            //return Response::download($report);
        }
        return Redirect::route('processes.index');
    }

}
