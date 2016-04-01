<?php

namespace CALwebtool;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_definition_id','type','field_id','order','name','options'
    ];

    public function formdefinition(){
        return $this->belongsTo('CALWebtool\FormDefinition');
    }

}
