<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notes extends Model
{
    use HasFactory, SoftDeletes;

    public function companies(){
        return $this->hasOne(Company::class, 'id', 'company_id')->select('id', 'id AS value', 'name AS label');
    }
}
