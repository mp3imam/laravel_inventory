<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionModel extends Model
{
    use HasFactory;
    protected $table = "question";
    protected $guarded = ['id'];

    public function provinsis(){
        return $this->hasOne(ProvinsiModel::class, 'id', 'provinsi_id');
    }

    public function satkers(){
        return $this->hasOne(SatkerModel::class, 'id', 'satker_id');
    }

    public function layanans(){
        return $this->hasOne(LayananModel::class, 'id', 'layanan_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id');
    }
}
