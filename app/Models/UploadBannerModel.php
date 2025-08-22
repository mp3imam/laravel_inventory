<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadBannerModel extends Model
{
    use HasFactory;
    protected $table = 'upload_banner';
    protected $guarded = ['id'];

    const KATEGORY_SMARTTV = "Smart Tv";
    const KATEGORY_KIOSK = "Kios K";

    public function satkers(){
        return $this->hasOne(SatkerModel::class, 'id', 'satker_id');
    }

    public function getBannerAttribute($val)
    {
        if($val){
            return asset('storage/uploads_banner/' . $val) ?? '';
        }
    }
}