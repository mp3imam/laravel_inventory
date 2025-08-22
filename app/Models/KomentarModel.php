<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomentarModel extends Model
{
    use HasFactory;
    protected $table = "komentar";
    protected $guarded = ['id'];

    public function user(){
        return $this->hasMany(User::class,'id', 'user_id');
     }

    public function keluhan(){
       return $this->belongsTo(KeluhanModel::class,'keluhan_id');
    }
}
