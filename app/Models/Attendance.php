<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    public function userData(){
        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'first_name', 'last_name', 'role_id');
    }
}
