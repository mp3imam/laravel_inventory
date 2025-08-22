<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivitiesModel extends Model
{
    use HasFactory;
    protected $table = "log_activities";
    protected $guarded = ['id'];

    public function users(){
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'id','user_id');
    }

    public function getCreatedAtAttribute($val)
    {
        return $val ? date('d-m-Y H:i:s', strtotime($val)) : $val;
    }
}
