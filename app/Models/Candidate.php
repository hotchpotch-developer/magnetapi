<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use HasFactory, SoftDeletes;



    protected static function boot()
    {
        parent::boot();
        static::creating(function($model){
            $model->candidate_id = 'CNDT'.'-'. str_pad(static::max('id') + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    public function stateName(){
        return $this->hasOne(State::class, 'id', 'state_id')->select('name AS label', 'id AS value', 'id');
    }

    public function industry(){
        return $this->hasOne(Industry::class, 'id', 'industry_id')->select('name AS label', 'id AS value', 'id');
    }

    public function salesNon(){
        return $this->hasOne(SalesNonSales::class, 'id', 'sales_non_sales_id')->select('name AS label', 'id AS value', 'id');
    }

    public function company(){
        return $this->hasOne(Company::class, 'id', 'company_id')->select('name AS label', 'id AS value', 'id');
    }

    public function department(){
        return $this->hasOne(Department::class, 'id', 'department_id')->select('name AS label', 'id AS value', 'id');
    }

    public function channel(){
        return $this->hasOne(Channel::class, 'id', 'channel_id')->select('name AS label', 'id AS value', 'id');
    }

    public function level(){
        return $this->hasOne(Level::class, 'id', 'level_id')->select('name AS label', 'id AS value', 'id');
    }

    public function qualification(){
        return $this->hasOne(HighQualification::class, 'id', 'level_id')->select('name AS label', 'id AS value', 'id');
    }

    public function location(){
        return $this->hasOne(Location::class, 'id', 'level_id')->select('name AS label', 'id AS value', 'id');
    }

}
