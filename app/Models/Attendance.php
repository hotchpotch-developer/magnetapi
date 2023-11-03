<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['className'];

    public function userData(){
        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'first_name', 'last_name', 'role_id');
    }

    public function getTitleAttribute($value)
    {
        return ucwords(str_replace('_', ' ', $value));
    }

    public function getTypeAttribute($value)
    {
        return ucwords(str_replace('_', ' ', $value));
    }

    public function getEventTimingAttribute($value)
    {
        return date('M d, Y h:i A', strtotime($value));
    }

    public function getClassNameAttribute($value)
    {
        if($this->title == 'Attendance'){
            return 'bg-success-subtle';
        }

        if($this->title == 'Short Leave'){
            return 'bg-warning-subtle';
        }

        if($this->title == 'Half Leave'){
            return 'bg-info-subtle';
        }

        if($this->title == 'Leave'){
            return 'bg-danger-subtle';
        }
    }
}
