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
}
