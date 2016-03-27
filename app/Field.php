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
        'form_id','name','field_id','type','options','order'
    ];

    public function formdefinition(){
        return $this->belongsTo('CALWebtool\FormDefinition');
    }

}
