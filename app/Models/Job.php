<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        static::creating(function($model){
            $model->position_no = date('dmY').'-'. str_pad(static::max('id') + 1, 1, '0', STR_PAD_LEFT);
        });
    }

    public function stateName(){
        return $this->hasOne(State::class, 'id', 'state_id')->select('name', 'id');
    }

    public function location(){
        return $this->hasOne(Location::class, 'id', 'location_id')->select('name', 'id');
    }

    public function company(){
        return $this->hasOne(Company::class, 'id', 'company_id')->select('name', 'id');
    }

    public function designation(){
        return $this->hasOne(Designation::class, 'id', 'designation_id')->select('name', 'id');
    }

    public function department(){
        return $this->hasOne(Department::class, 'id', 'department_id')->select('name', 'id');
    }
}
