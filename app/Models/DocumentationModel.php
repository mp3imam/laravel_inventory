<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentationModel extends Model
{
    use HasFactory;
    protected $table = 'dokumentasi';
    protected $guarded = ['id'];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getFileAttribute($val)
    {
        if($val){
            return asset('storage/dokumentasi/' . $val) ?? '';
        }
    }

    public function getCreatedAtAttribute($val)
    {
        return $val ? date('d-m-Y H:i:s', strtotime($val)) : $val;
    }
}