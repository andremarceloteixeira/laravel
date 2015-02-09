<?php

class Process extends Eloquent {

    protected $table = 'processes';
    protected $fillable = array('certificate', 'reference', 'client_id', 'insured_id', 'taker_id', 'status_id', 'expert_id', 'email', 'apolice', 'type_id', 'preliminar_date', 'situation_observations', 'situation_losts', 'deadline_preliminar', 'deadline_complete');
    public static $rules = array(
        'certificate' => array('required', 'regex:/[0-9]+\/[0-9]{2}\b/', 'unique:processes,certificate'),
        'preliminar_date' => 'date_format:"d/m/Y"',
        'type_id' => 'required|exists:types,id',
        'email' => 'required|email',
        'client_id' => 'required|exists:clients,user_id',
        'deadline_preliminar' => 'integer',
        'deadline_complete' => 'integer',
    );
    public static $names = array(
        'certificate' => 'processes.certificate',
        'reference' => 'processes.reference',
        'client_id' => 'processes.client_id',
        'expert_id' => 'processes.expert_id',
        'insured_id' => 'processes.insured_id',
        'taker_id' => 'processes.taker_id',
        'attachments' => 'processes.attachments',
        'type_id' => 'processes.type_id',
        'status_id' => 'processes.status_id',
        'apolice' => 'processes.apolice',
        'preliminar_date' => 'processes.preliminar_date',
        'situation_observations' => 'processes.situation_observations',
        'situation_losts' => 'processes.situation_losts',
        'updated_at' => 'processes.updated_at',
        'email' => 'processes.email',
        'client_insureds_info' => 'processes.client_insureds_info',
        'client_others_info' => 'processes.client_others_info'
    );

    public static function create(array $attributes = array()) {
        if (array_key_exists('preliminar_date', $attributes)) {
            if (Helper::isNull($attributes['preliminar_date'])) {
                if (array_key_exists('deadline_preliminar', $attributes)) {
                    $attributes['preliminar_date'] = Carbon::now()->addDays($attributes['deadline_preliminar'])->format('d/m/Y');
                } else {
                    $attributes['preliminar_date'] = Carbon::now()->addDays(1)->format('d/m/Y');
                }
            }
        }
        $process = parent::create($attributes);
        if (!File::exists(Config::get('settings.process_folder') . $process->folder)) {
            File::makeDirectory(Config::get('settings.process_folder') . $process->folder, 0777);
        }
        return $process;
    }

    public function update(array $attributes = array()) {
        if (!File::exists(Config::get('settings.process_folder') . $this->folder)) {
            File::makeDirectory(Config::get('settings.process_folder') . $this->folder, 0777);
        }
        if (array_key_exists('situation_observations', $attributes) || array_key_exists('situation_losts', $attributes)) {
            if ($attributes['situation_observations'] != $this->situation_observations || $attributes['situation_losts'] != $this->situation_losts) {
                $this->situation_date = Carbon::now()->format('d/m/Y');
            }
        }
        if (array_key_exists('preliminar_sent', $attributes)) {
            $this->preliminar_sent = true;
        } else {
            $this->preliminar_sent = false;
        }
        $this->save();

        parent::update($attributes);
    }

    public function client() {
        return $this->belongsTo('Client');
    }

    public function expert() {
        return $this->belongsTo('Expert');
    }

    public function status() {
        return $this->belongsTo('Status');
    }

    public function type() {
        return $this->belongsTo('Type');
    }

    public function insured() {
        return $this->belongsTo('Insured', 'insured_id');
    }

    public function taker() {
        return $this->belongsTo('Insured', 'taker_id');
    }

    public function processAttachs() {
        return $this->hasMany('ProcessAttach');
    }

    public function clientAttachs() {
        return $this->hasMany('ClientAttach');
    }

    public function fields() {
        return $this->hasMany('ProcessField');
    }

    public function scopeMine($query) {
        if (Check::isAdmin()) {
            return $query;
        } else if (Check::isExpert()) {
            return $query->where('expert_id', '=', Auth::user()->id);
        } else if (Check::isClient()) {
            return $query->where('client_id', '=', Auth::user()->id);
        }
    }

    public function scopePending($query) {
        return $query->where('status_id', '=', 1);
    }

    public function scopeProcessing($query) {
        return $query->where('status_id', '=', 2);
    }

    public function scopeCancelled($query) {
        return $query->where('status_id', '=', 4);
    }

    public function scopeCompleted($query) {
        return $query->where('status_id', '=', 3);
    }

    public function getCreatedAtDateAttribute() {
        return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->format('Y-m-d');
    }

    public function getUpdatedAtDateAttribute() {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['updated_at'])->format('Y-m-d');
    }

    public function getInsAttribute() {
        return $this->insured->name;
    }
    
    public function getTakAttribute() {
        return $this->taker->name;
    }

    public function getFolderAttribute() {
        $year = substr(Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->year, -2);
        return $this->attributes['id'] . '-' . $year . '/';
    }

}
