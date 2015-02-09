<?php

class ProcessAttach extends Eloquent {
    protected $table = 'process_attachments';
    
    public function delete() {
        $path = Config::get('settings.process_folder') . $this->path;
        if(File::exists($path)) {
            File::delete($path);
        }
        parent::delete();
    }
}
