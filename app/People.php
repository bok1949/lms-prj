<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    protected $primaryKey = "people_id";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_number', 'last_name','first_name','middle_name','ext_name'
    ];

    public function userAccounts(){
        return $this->hasMany('App\UserAccount');
    }

    public function employees(){
        return $this->hasMany('App\Employee');
    }

}
