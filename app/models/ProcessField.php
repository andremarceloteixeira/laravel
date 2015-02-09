<?php

class ProcessField extends Eloquent {
    protected $table = 'process_fields';
    protected $fillable = ['process_id', 'key', 'value'];
    
}