<?php

class PendingController extends BaseController {

    protected $process;
    protected $user;
    protected $experts;
    protected $expert;

    public function __construct(Process $process, Expert $expert) {
        $this->beforeFilter('admin');
        $this->process = $process;
        $this->user = Auth::user();
        $this->experts = Expert::dropdown();
        $this->expert = $expert;
    }

    public function index() {
        $experts = $this->experts;

        return View::make('processes.pending')
                        ->with(['experts' => $experts]);
    }

    public function charge()
    {
        $input = Input::all();
        $v = Validator::make($input, ['id' => 'required|exists:processes,id', 'expert_id' => 'required|numeric']);
        if ($v->passes()) {
            $process = $this->process->find($input['id']);
            if ($process->status_id != 1) {
                return Response::json(
                    [
                        'status' => 'error',
                        'title' => trans('actions.error'),
                        'message' => trans('notifications.pending_no_more')
                    ]
                );
            }
            if (!is_null($process->expert_id)) {
                return Response::json(
                    [
                        'status' => 'error',
                        'title' => trans('actions.error'),
                        'message' => trans('notifications.pending_already_expert')
                    ]
                );
            }
            if ($input['expert_id'] > 0) {
                $expert = $this->expert->find($input['expert_id']);

                if (!is_null($expert)) {
                    $process->expert_id = $input['expert_id'];
                    $process->status_id = 2;
                    $process->save();
                    Helper::makeNotification(
                        'notifications.charge_process',
                        $process->certificate,
                        'me/processes/' . $process->id,
                        $process->expert_id
                    );
                    $certificate = !empty($process->certificate) ? $process->certificate : null;
                    pr($process);
                    echo $adidas;
                    Helper::chargeEmail($expert->getUsernameAttribute(), $certificate, $expert->getEmailAttribute(), $process->client->country_id);
                    return Response::json(
                        [
                            'status' => 'success',
                            'title' => trans('actions.success'),
                            'message' => trans(
                                'notifications.pending_processing_expert',
                                ['id' => $process->certificate, 'name' => $expert->name]
                            )
                        ]
                    );
                }
            }
            $process->status_id = 2;
            $process->save();
            Helper::makeNotificationAdmin(
                'notifications.new_process',
                $process->certificate,
                'processes/' . $process->id
            );


            return Response::json(
                [
                    'status' => 'success',
                    'title' => trans('actions.success'),
                    'message' => trans('notifications.pending_processing', ['id' => $process->certificate])
                ]
            );
        }
        return Response::json(
            ['status' => 'error', 'title' => trans('actions.error'), 'message' => trans('notifications.pending_id')]
        );
    }

    public function pending() {
        try {
            $processes = $this->process->pending()->get();
            $arr = [];
            
            $attach_begin = '<div class="btn-group"><a class="btn btn-dropdown-toggle btn-sm btn-teal" data-toggle="dropdown" href="#"><i class="clip-file"></i> '. trans('processes.attachments') .' <span class="caret"></span></a><ul role="menu" class="dropdown-menu pull-right">';
            $attach_end = '</ul></div>';

            foreach ($processes as $p) {
                $tmp = [];
                $tmp[] = $p->reference;
                $tmp[] = $p->client->name;
                $tmp[] = $p->apolice;
                $items = "";
                foreach($p->clientAttachs as $a) {
                    $items .= '<li role="presentation">
                            <a role="menuitem" tabindex="-1" href="'. route('processes.downloadClientAttach', ['id' => $a->id]) .'">
                                <img src="'. asset('assets/images/files/16/'.explode('.', $a->name)[1].'.png') .'" width="16" height="16" /> '. $a->name . ' 
                            </a>
                         </li>';
                }
                //$tmp[] = $attach_begin . $items . $attach_end;
                //$tmp[] = $p->client_insureds_info;
                //$tmp[] = $p->client_others_info;
                $tmp[] = '<a href="'.route('processes.show', $p->id).'" class="btn btn-teal btn-block tooltips" data-placement="top" data-original-title="'.trans('actions.show').'"><i class="clip-info-2"></i></a><button id="pending-row-'.$p->id.'" data-title="' . trans('actions.charging', ['name' => $p->certificate, 'type' => trans('processes.singular')]) . '" data-id="' . $p->id . '" data-size="l" data-style="expand-right" class="btn btn-block btn-success ladda-button tooltips chargeModalAction" data-placement="top" data-original-title="' . trans('actions.charge') . '"><i class="clip-checkmark-2"></i></button>';
                $arr[] = $tmp;
            }

            return json_encode(array('sEcho' => 1, 'iTotalRecords' => count($processes), 'iTotalDisplayRecords' => count($processes), 'aaData' => $arr));
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

}
