<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    
    protected $primaryKey = "dept_id";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dept_code', 'dept_description','dept_status'
    ];
    public function departmentEmployee(){
        return $this->hasMany('App\Employee');
    }
}
