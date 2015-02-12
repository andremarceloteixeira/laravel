<?php

namespace Support\Models;

class Helper {

    protected $loggedUser;

    public function __construct() {
        $this->loggedUser = \Auth::user();
    }

    public function isNull($var) {
        if (is_null($var) || $var == "") {
            return true;
        }
        return false;
    }

    public function uploadPhoto($name, $current = false) {
        if ($current == false) {
            $photo = \Config::get('settings.photo_default');
        } else {
            $photo = $current;
        }
        if (!\Input::hasFile($name)) {
            return $photo;
        }
        $file = \Input::file($name);
        $filename = str_random(20) . '.' . $file->getClientOriginalExtension();
        $dest = '/assets/images/profile/';
        if ($file->move(public_path() . $dest, $filename) != false) {
            try {
                $i = \Image::make(public_path() . $dest . $filename);
                if ($i->height() >= $i->width()) {
                    $max = $i->height();
                } else {
                    $max = $i->width();
                }
                \Image::canvas($max, $max, '#ffffff')->insert($i, 'center')->save(public_path() . $dest . $filename);

                return $dest . $filename;
            } catch (Exception $ex) {
                return $photo;
            }
        }
        return $photo;
    }

    public function fillable($data, $rules) {
        $final = array();
        foreach ($data as $k => $v) {
            if (in_array($k, $rules) && !is_null($v) && @$v != "") {
                $final[$k] = $v;
            }
        }
        return $final;
    }

    public function niceNames($model) {
        try {
            $final = array();
            foreach ($model::$names as $k => $v) {
                $final[$k] = trans($v);
            }
            return $final;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function makePreliminar($process, $sendEmail = false, $download = false) {
        if (is_null($process)) {
            return false;
        }

        if (!\File::exists(\Config::get('settings.process_folder') . $process->folder)) {
            \File::makeDirectory(\Config::get('settings.process_folder') . $process->folder, 0777);
        }

        $name = 'Relatorio_Preliminar';
        $path = \Config::get('settings.process_folder') . $process->folder . $name . '.pdf';
        \PDF::loadHTML(\View::make('reports.preliminar', ['process' => $process])->render())->save($path);

        if ($sendEmail) {
            $this->makeNotificationAdmin('notifications.send_process', $process->certificate, 'processes/' . $process->id);
            if (!is_null($process->expert_id)) {
                $this->makeNotification('notifications.send_process', $process->certificate, 'processes/' . $process->id, $process->expert_id);
            }

            $process->preliminar_sent = true;
            $process->save();
            $client = $process->client;

            $lang = $this->convertLocale($client->country_id);
            $data['attachs'] = [$path];
            $data['to'] = $process->email;
            $data['view'] = 'emails.reports.preliminar';
            $data['subject'] = 'emails.preliminar-subject';
            $data['name'] = $client->name;
            $data['certificate'] = $process->certificate;
            $data['lang'] = $lang;
            \Event::fire('email.template', [$data]);
        }

        if ($download) {
            $content = file_get_contents($path);
            return \Response::make($content, 200, array('content-type' => 'application/pdf'));
            //return \Response::download($path);
        }

        return true;
    }

    private function convertLocale($id) {
        $default = 'en';
        $langs = array(
            'pt' => [181, 31],
            'es' => [67, 61, 63, 56, 54, 28, 14, 5, 86, 95, 151, 175, 180, 244, 13, 47],
                //'de' => [4, 17, 219],
                //'fr' => [74, 137],
        );

        foreach ($langs as $k => $v) {
            if (in_array($id, $v)) {
                return $k;
            }
        }
        return $default;
    }

    public function makeSurvey($process, $download = false) {
        if (is_null($process)) {
            return false;
        }

        $name = 'Relatorio_Vistoria';
        $path = \Config::get('settings.process_folder') . $process->folder . $name . '.pdf';
        \PDF::loadHTML(\View::make('reports.survey', ['process' => $process])->render())->save($path);

        if ($download) {
            $content = file_get_contents($path);
            return \Response::make($content, 200, array('content-type' => 'application/pdf'));
            //return \Response::download($path);
        }

        return true;
    }

    public function makeBegin($process, $download = false)
    {
        if (is_null($process)) {
            return false;
        }

        $name = 'Relatorio_Inicial';
        $path = \Config::get('settings.process_folder') . $process->folder . $name . '.pdf';
        \PDF::loadHTML(\View::make('reports.begin', ['process' => $process])->render())->save($path);

        if ($download) {
            $content = file_get_contents($path);
            return \Response::make($content, 200, array('content-type' => 'application/pdf'));
            //return \Response::download($path);
        }

        return true;
    }

    public function updateProcess($process, $makeZip = false) {
        if (is_null($process)) {
            return false;
        }
        $this->makePreliminar($process);
        $this->makeSurvey($process);
        $this->makeBegin($process);

        if ($makeZip) {
            $date = \Carbon::now()->format('Y_m_d_H_i_s');
            $name = 'process_' . $date . '.zip';
            $folder = substr($process->folder, 0, -1);

            if ($this->zip([\Config::get('settings.process_folder') . $folder], \Config::get('settings.tmp_folder') . $name)) {
                return \Config::get('settings.tmp_folder') . $name;
            }
            return false;
        }
        return true;
    }

    private function zip(array $files, $destination) {
        $zip = \Zipper::make($destination);
        foreach ($files as $f) {
            if (file_exists($f)) {
                $zip->add($f);
            }
        }
        $zip->close();
        return file_exists($destination);
    }

    public function searchAutoComplete() {
        $text = '[';
        if (!\Check::isClient()) {
            foreach (\Client::all() as $c) {
                $nif = $this->isNull($c->nif) ? '' : '|' . $c->nif;
                $text .= '{"label": "' . $c->name . '", "category": "' . trans('clients.plural') . '", "searchable": "' . $c->name . $nif . '","url": "' . route('clients.show', ['id' => $c->user_id]) . '"},';
            }

            foreach (\Expert::all() as $e) {
                $text .= '{"label": "' . $e->name . '", "category": "' . trans('experts.plural') . '", "searchable": "' . $e->name . '" ,"url": "' . route('experts.show', ['id' => $e->user_id]) . '"},';
            }

            foreach (\Insured::all() as $i) {
                $nif = $this->isNull($i->nif) ? '' : '|' . $i->nif;
                $ref = $this->isNull($i->reference) ? '' : '|' . $i->reference;
                $text .= '{"label": "' . $i->name . '", "category": "' . trans('insureds.plural') . '", "searchable": "' . $i->name . $ref . $nif . '" , "url": "' . route('insureds.show', ['id' => $i->id]) . '"},';
            }

            foreach (\Process::all() as $i) {
                if (\Check::isAdmin($this->loggedUser) || $i->expert_id == $this->loggedUser->id) {
                    $ref = $this->isNull($i->reference) ? '' : '|' . $i->reference;
                    $apo = $this->isNull($i->apolice) ? '' : '|' . $i->apolice;
                    $nameSeg = $this->isNull($i->insured) ? '' : '|' . $i->insured->name;
                    $refSeg = $this->isNull(@$i->insured->reference) ? '' : '|' . $i->insured->reference;
                    $nifSeg = $this->isNull(@$i->insured->nif) ? '' : '|' . $i->insured->nif;
                    $nameTom = $this->isNull($i->taker) ? '' : '|' . $i->taker->name;
                    $refTom = $this->isNull(@$i->taker->reference) ? '' : '|' . $i->taker->reference;
                    $nifTom = $this->isNull(@$i->taker->nif) ? '' : '|' . $i->taker->nif;
                    $expert = $this->isNull($i->expert) ? '' : '|' . $i->expert->name;
                    if (\Check::isAdmin()) {
                        $text .= '{"label": "' . $i->certificate . '", "category": "' . trans('processes.plural') . '", "searchable": "' . $i->certificate . $ref . $apo . $nameSeg . $refSeg . $nifSeg . $nameTom . $refTom . $nifTom . $expert . '" , "url": "' . route('processes.show', ['id' => $i->id]) . '"},';
                    } else if (\Check::isExpert()) {
                        $text .= '{"label": "' . $i->certificate . '", "category": "' . trans('processes.plural') . '", "searchable": "' . $i->certificate . $ref . $apo . $nameSeg . $refSeg . $nifSeg . $nameTom . $refTom . $nifTom . $expert . '" , "url": "' . route('processes.show', ['id' => $i->id]) . '"},';
                    }
                }
            }
        }
        $text .= "]";
        return $text;
    }

    public function registerEmail($name, $email, $password, $id) {
        $lang = $this->convertLocale($id);
        $data['to'] = $email;
        $data['view'] = 'emails.auth.register';
        $data['subject'] = 'emails.register-subject';
        $data['name'] = $name;
        $data['email'] = $email;
        $data['password'] = $password;
        $data['lang'] = $lang;
        \Event::fire('email.template', [$data]);
    }

    public function resetEmail($name, $email, $password, $id) {
        $lang = $this->convertLocale($id);
        $data['to'] = $email;
        $data['view'] = 'emails.auth.reset';
        $data['subject'] = 'emails.reset-subject';
        $data['name'] = $name;
        $data['email'] = $email;
        $data['password'] = $password;
        $data['lang'] = $lang;
        \Event::fire('email.template', [$data]);
    }

    public function chargeEmail($name, $certificate, $email, $id) {
        $lang = $this->convertLocale($id);
        $data['to'] = $email;
        $data['view'] = 'emails.process.charge';
        $data['subject'] = 'emails.charge-subject';
        $data['name'] = $name;
        $data['certificate'] = $certificate;
        $data['lang'] = $lang;
        \Event::fire('email.template', [$data]);
    }

    private function processWarnings($days, $type) {
        if ($days < 0) {
            if ($days == -1) {
                $alert = 'danger_yesterday';
            } else {
                $alert = 'danger';
            }
            $alert2 = 'danger';
            $icon = 'fa fa-times-circle';
        } else if ($days >= 0 && $days < 3) {
            if ($days == 0) {
                $alert = 'warning_today';
            } else if ($days == 1) {
                $alert = 'warning_tomorrow';
            } else {
                $alert = 'warning';
            }
            $alert2 = 'warning';
            $icon = 'fa fa-exclamation-triangle';
        } else {
            $alert2 = 'info';
            $alert = 'info';
            $icon = 'fa fa-info-circle';
        }
        if ($days < 0) {
            $days = ($days * -1);
        }
        return '<div class="alert alert-' . $alert2 . '"><button data-dismiss="alert" class="close">×</button><i class="' . $icon . '"></i> ' . trans('processes.' . $alert . '_' . $type, ['day' => $days]) . '</div>';
    }

    private function processWarningsTooltips($days, $type) {
        if ($days < 0) {
            if ($days == -1) {
                $alert = 'danger_yesterday';
            } else {
                $alert = 'danger';
            }
            $alert2 = 'danger';
            $icon = 'fa fa-times-circle';
        } else if ($days >= 0 && $days < 3) {
            if ($days == 0) {
                $alert = 'warning_today';
            } else if ($days == 1) {
                $alert = 'warning_tomorrow';
            } else {
                $alert = 'warning';
            }
            $alert2 = 'warning';
            $icon = 'fa fa-exclamation-triangle';
        } else {
            $alert = 'info';
            $alert2 = 'info';
            $icon = 'fa fa-info-circle';
        }
        if ($days < 0) {
            $days = ($days * -1);
        }

        return '<button style="margin:4px;" type="button" data-html="true" class="btn btn-xs btn-' . $alert2 . ' tooltips" data-original-title="' . trans('processes.' . $alert . '_' . $type, ['day' => $days]) . '"><i class="' . $icon . '"></i></button>';
    }

    public function processDeadlines($process) {
        if ($process->status_id != 2) {
            return "";
        }
        $text = "";
        if ($process->preliminar_sent) {
            $text .= '<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button><i class="fa fa-check-circle"></i> ' . trans('processes.success_preliminar') . '</div>';
        } else {
            $text .= $this->processWarnings(\Check::getProcessDiff($process->created_at_date, $process->deadline_preliminar), 'preliminar');
        }

        if ($process->status_id == 3) {
            $text .= '<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button><i class="fa fa-check-circle"></i> ' . trans('processes.success_complete') . '</div>';
        } else {
            $text .= $this->processWarnings(\Check::getProcessDiff($process->created_at_date, $process->deadline_complete), 'complete');
        }

        return $text;
    }

    public function processDeadlinesTooltips($process) {
        if ($process->status_id != 2) {
            return "";
        }
        $text = "";
        if ($process->preliminar_sent) {
            $text .= '<button style="margin:4px;" type="button" data-html="true" class="btn btn-xs btn-success tooltips" data-original-title="' . trans('processes.success_preliminar') . '"><i class="fa fa-check-circle"></i></button>';
        } else {
            $text .= $this->processWarningsTooltips(\Check::getProcessDiff($process->created_at_date, $process->deadline_preliminar), 'preliminar');
        }

        if ($process->status_id == 3) {
            $text .='<button style="margin:4px;" type="button" data-html="true" class="btn btn-xs btn-success tooltips" data-original-title="' . trans('processes.success_complete') . '"><i class="fa fa-check-circle"></i></button>';
        } else {
            $text .= $this->processWarningsTooltips(\Check::getProcessDiff($process->created_at_date, $process->deadline_complete), 'complete');
        }

        return $text;
    }

    public function makeProcessAttachs($process, $attachments) {
        if (\Input::hasFile($attachments)) {
            $attachfolder = \Config::get('settings.process_folder') . $process->folder . 'anexos_processo/';
            if (!\File::exists($attachfolder)) {
                \File::makeDirectory($attachfolder, 0777);
            }
            $db_path = $process->folder . 'anexos_processo/';
            
            foreach (\Input::file($attachments) as $f) {
                $validator = \Validator::make(['attachments' => $f], ['attachments' => 'max:5120']);
                if ($validator->passes()) {
                    $original = $f->getClientOriginalName();
                    $new = str_random(30) . '.' . $f->getClientOriginalExtension();
                    $check = \ProcessAttach::where('name', '=', $original)->where('process_id', '=', $process->id)->get();
                    if (count($check) > 0) {
                        $check[0]->delete();
                    }
                    if ($f->move($attachfolder, $new)) {
                        $pa = new \ProcessAttach;
                        $pa->process_id = $process->id;
                        $pa->name = $original;
                        $pa->path = $db_path . $new;
                        $pa->save();
                    }
                } else {
		    \Log::warning("The attach '".$f->getClientOriginalName()."' wasn't uploaded because ext: '".$f->getClientOriginalExtension()."' and size: '".$f->getSize()."'");
                    \Session::flash('attachs_warning', 'Alguns anexos não foram inseridos porque não têm uma extensão válida (.jpg, .png, .gif, .pdf, .msg, .doc, .docx, .xls, .xlsx) ou são maiores do que 5mb');
                }
            }
        }
    }

    public function makeClientAttachs($process, $attachments) {
        if (\Input::hasFile($attachments)) {
            $attachfolder = \Config::get('settings.process_folder') . $process->folder . 'anexos_cliente/';
            if (!\File::exists($attachfolder)) {
                \File::makeDirectory($attachfolder, 0777);
            }
            $db_path = $process->folder . 'anexos_cliente/';
            
            foreach (\Input::file($attachments) as $f) {
                $validator = \Validator::make(['attachments' => $f], ['attachments' => 'max:10240']);
                if ($validator->passes()) {
                    $original = $f->getClientOriginalName();
                    $new = str_random(30) . '.' . $f->getClientOriginalExtension();
                    $check = \ClientAttach::where('name', '=', $original)->where('process_id', '=', $process->id)->get();
                    if (count($check) > 0) {
                        $check[0]->delete();
                    }
                    if ($f->move($attachfolder, $new)) {
                        $pa = new \ClientAttach;
                        $pa->process_id = $process->id;
                        $pa->name = $original;
                        $pa->path = $db_path . $new;
                        $pa->save();
                    }
                } else {
		    \Log::warning("The attach '".$f->getClientOriginalName()."' wasn't uploaded because ext: '".$f->getClientOriginalExtension()."' and size: '".$f->getSize()."'");
                    \Session::flash('attachs_warning', 'Alguns anexos não foram inseridos porque não têm uma extensão válida (.jpg, .png, .gif, .pdf, .msg, .doc, .docx, .xls, .xlsx) ou são maiores do que 5mb');
                }
            }
        }
    }

    public function makeProcessKeys($process, $keys, $values) {
        if (is_null($process) || is_null($keys) || is_null($values)) {
            return;
        }
        \ProcessField::where('process_id', '=', $process->id)->delete();
        for ($i = 0; $i < count($keys); $i++) {
            if ($keys[$i] != '' && $values[$i] != '') {
                \ProcessField::create(['process_id' => $process->id, 'key' => $keys[$i], 'value' => $values[$i]]);
            }
        }
    }

    public function makeNotificationAdmin($name, $value, $route) {
        $not = \Config::get('settings.notifications')[$name];
        foreach (\Admin::all() as $admin) {
            \Notification::create(['user_id' => $admin->user_id, 'code' => $name, 'icon' => $not['icon'], 'label' => $not['label'], 'value' => $value, 'route' => $route]);
        }
    }

    public function makeNotification($name, $value, $route, $userID) {
        $not = \Config::get('settings.notifications')[$name];
        \Notification::create(['user_id' => $userID, 'code' => $name, 'icon' => $not['icon'], 'label' => $not['label'], 'value' => $value, 'route' => $route]);
    }

    public function notificationProcessesLate() {
        $processes = \Process::where('status_id', '=', 2)->get();
        foreach ($processes as $p) {
            if (\Check::isProcessLate($p)) {
                $this->makeNotificationAdmin('notifications.preliminar_late', $p->certificate, 'processes/' . $p->id);
            }
        }
    }

    public function getFileTypeImg($ext)
    {
        if (\File::exists(public_path() . '/assets/images/files/16/' . $ext . '.png')) {
            return 'assets/images/files/16/' . $ext . '.png';
        }
        return 'assets/images/files/16/_blank.png';
    }

    public function getCurrentIp() {
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            return $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            return $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            return $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            return $_SERVER['REMOTE_ADDR'];
        else
            return 'UNKNOWN';
    }

}
