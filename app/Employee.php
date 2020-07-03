<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $primaryKey = "emp_id";
    //protected $foreignKey = "people_id";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'people_id', 'dept_id'
    ];

    public function peopleEmployee(){
        return $this->belongsTo('App\People', 'people_id', 'people_id');
    }
    public function employeeDepartment(){
        return $this->belongsTo('App\Department', 'dept_id', 'dept_id');
    }
}
