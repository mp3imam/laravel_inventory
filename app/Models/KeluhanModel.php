<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeluhanModel extends Model
{
    use HasFactory;
    protected $table = "keluhan";
    protected $guarded = ['id'];

    // Rating Keluhan
    const RATING_TIDAK_MEMUASKAN  = 'Tidak Memuaskan';
    const RATING_KURANG_MEMUASKAN = 'Kurang Memuaskan';
    const RATING_CUKUR_MEMUASKAN  = 'Cukup Memuaskan';
    const RATING_MEMUASKAN        = 'Memuaskan';
    const RATING_SANGAT_MEMUASKAN = 'Sangat Memuaskan';
    const RATING_BELUM_ADA = 'Belum ada rating';

    // Status Keluhan
    const STATUS_BELUM_TERJAWAB  = 'Belum Terjawab';
    const STATUS_SUDAH_TERJAWAB  = 'Sudah Terjawab';
    const STATUS_SUDAH_TUTUP  = 'Sudah Tutup';

    public function komentar(){
        return $this->hasMany(KomentarModel::class,'keluhan_id');
     }

     public function komentar_satker($id){
        return $this->hasMany(KomentarModel::class,'keluhan_id')->whereKeluhanId($id);
     }

     public function user(){
        return $this->hasOne(User::class,'id','user_id');
     }

     public function satker(){
        return $this->hasOne(SatkerModel::class,'id');
     }

     public function getImageAttribute($value)
     {
         if ($value) {
             return asset('storage/rate_support_sistem/' . $value);
         }
     }

     public function getCreatedAtAttribute($value)
     {
         return Carbon::parse($value)->format('d-m-Y');
     }


}
