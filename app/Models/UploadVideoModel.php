<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadVideoModel extends Model
{
    use HasFactory;
    protected $table = 'upload_video';
    protected $guarded = ['id'];

    const KATEGORY_SMARTTV = "Smart Tv";
    const KATEGORY_KIOSK = "Kios K";

    const STATUS_TIDAK_AKTIF = "Tidak Aktif";
    const STATUS_AKTIF = "Aktif";

    public function provinsis(){
        return $this->hasOne(ProvinsiModel::class, 'id', 'provinsi_id');
    }

    public function satkers(){
        return $this->hasOne(SatkerModel::class, 'id', 'satker_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getVideoAttribute($val)
    {
        if($val){
            return asset('storage/uploads_video/' . $val) ?? '';
        }
    }

    public function getStatusAttribute($val)
    {
        return $val == 0 ? UploadVideoModel::STATUS_TIDAK_AKTIF : UploadVideoModel::STATUS_AKTIF;
    }
}