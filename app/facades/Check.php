<?php

namespace Support\Models;

class Check {

    protected $loggedUser;

    public function __construct() {
        $this->loggedUser = \Auth::user();
    }

    public function canEnterDev($user) {
        if (is_null($user)) {
            return false;
        }
        if (in_array($user->role->id, [2, 3, 4])) {
            return true;
        }
        return false;
    }

    public function canEnterClient($user) {
        if (is_null($user)) {
            return false;
        }
        if ($user->role->id == 1) {
            return true;
        }
        return false;
    }

    public function isAdmin($user = null) {
        if (is_null($user)) {
            $user = $this->loggedUser;
        }
        if (is_null($user)) {
            return false;
        }
        if ($user->role->id == 3) {
            return true;
        }
        return false;
    }

    public function isExpert($user = null) {
        if (is_null($user)) {
            $user = $this->loggedUser;
        }
        if (is_null($user)) {
            return false;
        }

        if ($user->role->id == 2) {
            return true;
        }
        return false;
    }

    public function isClient($user = null) {
        if (is_null($user)) {
            $user = $this->loggedUser;
        }
        if (is_null($user)) {
            return false;
        }
        if ($user->role->id == 1) {
            return true;
        }
        return false;
    }

    public function canEditProcess($process) {
        /* If null, false */
        if (is_null($process)) {
            return false;
        }

        /* If the autenticated user isnt Admin and if the process expert isnt the same, false */
        if (!$this->isAdmin($this->loggedUser) && $process->expert_id != $this->loggedUser->id) {
            return false;
        }

        /* If isnt in processing status, false */
        if ($process->status_id != 2) {
            return false;
        }

        /* otherwise, true */
        return true;
    }

    public function canUpgradeProcess($process) {
        /* If null, false */
        if (is_null($process)) {
            return false;
        }

        /* If the autenticated user isnt Admin and if the process expert isnt the same, false */
        if (!$this->isAdmin($this->loggedUser) && $process->expert_id != $this->loggedUser->id) {
            return false;
        }

        /* If process type_id is null, false */
        if (is_null($process->type)) {
            return false;
        }

        return true;
    }

    public function canManageProcess($process) {
        /* If null, false */
        if (is_null($process)) {
            return false;
        }
        
        /* If its in pending status, false */
        if($process->status_id == 1) {
            return false;
        }

        /* If the autenticated user isnt Admin and if the process expert isnt the same, false */
        if (!$this->isAdmin($this->loggedUser) && $process->expert_id != $this->loggedUser->id) {
            return false;
        }

        return true;
    }

    public function isProcessLate($process) {
        if (is_null($process)) {
            return false;
        }
        if ($process->status_id != 2) {
            return false;
        }

        $date1 = \Carbon::createFromFormat('Y-m-d', $process->created_at_date)->addDays($process->deadline_preliminar);
        $date2 = \Carbon::createFromFormat('Y-m-d', $process->created_at_date)->addDays($process->deadline_complete);

        $now = \Carbon::now();

        if (!$process->preliminar_sent && $now->diffInDays($date1, false) <= 0) {
            return true;
        }

        if ($now->diffInDays($date2, false) <= 0) {
            return true;
        }

        return false;
    }

    public function isEnableFeature($feature) {
        If($this->isClient()) {
            return false;
        }
        $features = \Config::get('settings.special_features');
        if (!@$features[$feature]) {
            return false;
        }
        return true;
    }

    public function getProcessDiff($created, $days) {
        $date = \Carbon::createFromFormat('Y-m-d', $created)->addDays($days);
        $now = \Carbon::now();

        return $now->diffInDays($date, false);
    }

}
