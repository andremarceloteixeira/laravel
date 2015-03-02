<?php

class MyProcessesController extends BaseController {

    protected $process;
    protected $expert;

    public function __construct(Process $process) {
        $this->beforeFilter('expert');
        $this->expert = Auth::user();
        $this->process = $process;
    }

    public function index() {

        $processing = $this->process->mine()->where('status_id', '=', '2')->get();
        $complete = $this->process->mine()->where('status_id', '=', '3')->get();
        $cancelled = $this->process->mine()->where('status_id', '=', '4')->get();
        return View::make('me.processes.index')->with(['processing' => $processing, 'complete' => $complete, 'cancelled' => $cancelled]);
    }

    public function show($id) {
        $process = $this->process->find($id);
        if (is_null($process)) {
            return Redirect::route('me.processes.index');
        }
        if ($process->status_id == 1) {
            return Redirect::route('me.processes.index');
        }
        if ($process->expert_id != $this->expert->id) {
            return Redirect::route('me.processes.index');
        }
        
        return View::make('me.processes.show')
                        ->with(['process' => $process]);
    }

    public function edit($id) {
        $process = $this->process->find($id);

        if (!Check::canEditProcess($process)) {
            return Redirect::route('me.processes.index');
        }

        return View::make('me.processes.edit', compact('process'));
    }

    public function update($id) {
        $process = $this->process->find($id);
        if (!Check::canEditProcess($process)) {
            return Redirect::route('me.processes.index');
        }
        $input = Input::all();
        $validation = Validator::make($input, Process::$rules);
        $validation->setAttributeNames(Helper::niceNames('Process'));
        if ($validation->passes()) {
            $input['status_id'] = $process->status_id;
            if (array_key_exists('expert_id', $input)) {
                $input['expert_id'] = $this->expert->id;
            }
            if ($input['insured_id'] == 0) {
                $input['insured_id'] = null;
            }
            if ($input['taker_id'] == 0) {
                $input['taker_id'] = null;
            }
            $process->update($input);

            Helper::makeProcessKeys($process, $input['key'], $input['value']);
            Helper::makeProcessAttachs($process, 'attachments');
            Helper::makeNotificationAdmin('notifications.change_process', $process->certificate, 'processes/'.$process->id);
            Session::flash('notification', trans('notifications.process_update', ['name' => $process->certificate]));
            return Redirect::route('me.processes.index');
        }

        return Redirect::route('me.processes.edit', ['id' => $id])
                        ->withInput(Input::except('attachments'))
                        ->withErrors($validation);
    }

}
