<?php

namespace CALwebtool;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];



    public function users(){
        return $this->belongsToMany('CALwebtool\User');
    }
}
