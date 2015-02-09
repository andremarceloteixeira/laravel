<?php

class Insured extends Eloquent {
    protected $table = 'insureds';
    protected $fillable = array('name', 'reference', 'nif', 'insured_type_id');
    public static $rules = [
        'name' => 'required',
        'reference' => 'unique:insureds,reference',
        'insured_type_id' => 'required|exists:insured_types,id'
    ];
    public static $names = [
        'name' => 'insureds.name',
        'reference' => 'insureds.refernece',
        'nif' => 'insureds.nif',
        'insured_type_id' => 'insureds.insured_type_id'
    ];

    public function scopeDropdownInsured($query) {
        $text = "[";
        foreach ($query->where('insured_type_id', '=', 1)->orderBy('name')->get() as $c) {
            $nif = Helper::isNull($c->nif) ? '' : '|'.$c->nif;
            $ref = Helper::isNull($c->reference) ? '' : '|'.$c->reference;
            $text .= '{"label": "' . $c->name . '", "value": "'.$c->name.'", "searchable": "' . $c->name . $nif . $ref . '"},';
        }
        $text .= "]";
        return $text;
    }
    
    public function scopeDropdownTaker($query) {
        $text = "[";
        foreach ($query->where('insured_type_id', '=', 2)->orderBy('name')->get() as $c) {
            $nif = Helper::isNull($c->nif) ? '' : '|'.$c->nif;
            $ref = Helper::isNull($c->reference) ? '' : '|'.$c->reference;
            $text .= '{"label": "' . $c->name . '", "value": "'.$c->name.'", "searchable": "' . $c->name . $nif . $ref . '"},';
        }
        $text .= "]";
        return $text;
    }

    public function type() {
        return $this->belongsTo('InsuredType', 'insured_type_id');
    }

}
