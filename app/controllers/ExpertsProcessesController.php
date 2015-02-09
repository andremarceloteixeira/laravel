<?php

class ExpertsProcessesController extends BaseController {

    protected $process;
    protected $expert;

    public function __construct(Process $process) {
        $this->beforeFilter('expert_only');
        $this->expert = Auth::user();
        $this->process = $process;
    }

    public function index() {
        $processing = $this->process->mine()->where('status_id', '=', '2')->get();
        $complete = $this->process->mine()->where('status_id', '=', '3')->get();
        $cancelled = $this->process->mine()->where('status_id', '=', '4')->get();
        return View::make('experts.processes.index')->with(['processing' => $processing, 'complete' => $complete, 'cancelled' => $cancelled]);
    }

    public function edit($id) {
        $process = $this->process->find($id);

        if (!Check::canEditProcess($process)) {
            return Redirect::route('processes.index');
        }

        return View::make('experts.processes.edit', compact('process'));
    }

    public function update($id) {
        $process = $this->process->find($id);
        if (!Check::canEditProcess($process)) {
            return Redirect::route('experts.processes.index');
        }
        $input = Input::all();
        $validation = Validator::make($input, array_except(Process::$rules, 'certificate'));
        $validation->setAttributeNames(Helper::niceNames('Process'));
        if ($validation->passes()) {
            $input['status_id'] = $process->status_id;
            if (array_key_exists('expert_id', $input)) {
                $input['expert_id'] = $this->expert->id;
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
            $input['reference'] = $process->reference;
            $input['certificate'] = $process->certificate;
            $process->update($input);

            Helper::makeProcessKeys($process, $input['key'], $input['value']);
            Helper::makeProcessAttachs($process, 'process_attachments');
            Helper::makeClientAttachs($process, 'client_attachments');
            Helper::makeNotificationAdmin('notifications.change_process', $process->certificate, 'processes/' . $process->id);
            Session::flash('notification', trans('notifications.process_update', ['name' => $process->certificate]));
            return Redirect::route('experts.processes.index');
        }

        return Redirect::route('experts.processes.edit', ['id' => $id])
                        ->withInput(Input::except('process_attachments', 'client_attachments'))
                        ->withErrors($validation);
    }

}
