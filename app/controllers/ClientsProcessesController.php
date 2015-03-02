<?php

class ClientsProcessesController extends BaseController {

    protected $process;
    protected $client;

    public function __construct(Process $process) {
        $this->beforeFilter('client');
        $this->client = Auth::user();
        $this->process = $process;
    }

    public function index()
    {
        $pending = $this->getPendingProcesses();
        $processing = $this->process->mine()->where('status_id', '=', '2')->get();
        $complete = $this->process->mine()->where('status_id', '=', '3')->get();
        $cancelled = $this->process->mine()->where('status_id', '=', '4')->get();
        return View::make('clients.processes.index')->with(['processing' => $processing,
            'complete' => $complete, 'cancelled' => $cancelled, 'pending' => $pending]);
    }


    /**
     *
     * @return mixed
     */
    private function getPendingProcesses()
    {
        return $this->process->mine()->leftjoin('process_attachments',
                    'processes.id', '=',
                    'process_attachments.process_id')
        ->select('processes.*', 'process_attachments.name', 'process_attachments.id as processId', 'process_attachments.path')
        ->where('status_id', '1')
        ->get();
    }

    public function create()
    {
        return View::make('clients.processes.create',  array('reference' => strtoupper(uniqid())));
    }

    public function store()
    {
        $input = Input::all();
        $rules = [
            'apolice' => 'required|max:25',
            'reference' => 'max:25',
            'email' => 'required|email',
            'client_insureds_info' => 'required|max:5000',
            'client_others_info' => 'max:5000'
        ];
        //attachments
        /**
         * @var $validatio  \Illuminate\Validation\Validator
         */
        $validation = Validator::make($input, $rules);
        $validation->setAttributeNames(Helper::niceNames('Process'));
        if ($validation->passes()) {
            if (Input::hasFile('attachments') && !empty($_FILES)) {
                $validator = Validator::make(
                    ['attachments' => Input::file("attachments")[0]],
                    ['attachments' => 'required|max:10240'],
                    ['attachments.max' => 'Ficheiro Invalido. Maximo de Upload 10 MB']
                );
                $extension = Input::file("attachments")[0]->getClientOriginalExtension();
                $extensionsValid = (!in_array($extension, ['png','gif','jpg','pdf','msg','JPGE','jpge','bmp']));
             if (!$validator->passes()) {
                    if ($extensionsValid) {
                        Session::flash('attachs_warning', 'Tipos Permitidos : png,gif,jpg,pdf,msg,JPGE,jpge,bmp');
                    }
                    return Redirect::route('clients.processes.create')
                        ->withInput()->withErrors($validator);
                }
                if (UPLOAD_ERR_INI_SIZE  && !empty($_FILES['attachments']['name'][0])  && !empty($_FILES['attachments']['error'][0]) ||
                        !isset($_FILES['attachments'])) {
                    Session::flash('attachs_warning', 'Limite do ficheiro 10MB');
                    return Redirect::route('clients.processes.create')
                        ->withInput()
                        ->withErrors($validator);
                }
            }
            $process = $this->saveProcess($input);
            if (!File::exists(Config::get('settings.process_folder') . $process->folder)) {
                File::makeDirectory(Config::get('settings.process_folder') . $process->folder, 0777);
            }
            $attachements = Helper::makeProcessAttachs($process, 'attachments');
            Helper::makeClientAttachs($process, 'client_attachments');
            Helper::makeNotificationAdmin('notifications.new_process_ask', '', 'processes/' . $process->id);
            Session::flash('notification', trans('notifications.process_ask_create', ['name' => '']));
            return Redirect::route('clients.processes.index');
        }
        if ($validation->fails()) {
            // send back to the page with the input data and errors
            return Redirect::route('clients.processes.create')->withInput()->withErrors($validation);
        }
    }

    /**
     * Save Process in database
     * @param $input
     * @return bool
     */
    private function saveProcess($input)
    {
        $process = new Process();
        $process->client_id = $this->client->id;
        $process->status_id = 1;
        $process->reference = $input['reference'];
        $process->email = $input['email'];
        $process->apolice = $input['apolice'];
        $process->client_insureds_info = $input['client_insureds_info'];
        $process->client_others_info = $input['client_others_info'];
        $process->save();
        return $process;
    }

}

