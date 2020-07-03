<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserAccount extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'user_type', 'ua_status', 'people_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function account_is_active(){
        //dd($this->ua_status);
        if($this->ua_status){
            return true;
        }
            return false;
    }

    public function people(){
        return $this->belongsTo('App\People', 'people_id', 'people_id');
    }
}
