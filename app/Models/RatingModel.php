<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingModel extends Model
{
    use HasFactory;
    protected $table = "rating_user";
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
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function antrians(){
        return $this->hasOne(AntrianModel::class, 'id', 'antrian_id');
    }

    // public function getRatingAttribute($value){
    //     (string)((int)$value->rating / 25 * 100);
    // }
}
