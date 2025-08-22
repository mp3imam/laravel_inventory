<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntrianModelMobile extends Model
{
    use HasFactory;
    protected $table = "antrian";
    protected $guarded = ['id'];

    const STATUS_PANGGIL = "Panggil";
    const STATUS_MENUNGGU = "Menunggu";
    const STATUS_BATAL = "Dibatalkan";
    const STATUS_TIDAK_HADIR = "Tidak Hadir";
    const STATUS_EDIT = "Edit";
    const STATUS_PROSESS = "Proses";
    const STATUS_SELESAI = "Selesai";
    const STATUS_SEND_EMAIL = "Kirim Ulang";

    public function provinsis(){
        return $this->belongsTo(ProvinsiModel::class, 'provinsi_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function satker(){
        return $this->belongsTo(SatkerModel::class, 'satker_id');
    }

    public function satkers(){
        return $this->belongsTo(SatkerModel::class, 'satker_id');
    }

    public function layanan(){
        return $this->belongsTo(LayananModel::class, 'layanan_id');
    }

    public function layanans(){
        return $this->belongsTo(LayananModel::class, 'layanan_id');
    }

    public function rating(){
        return $this->belongsTo(RatingModel::class, 'rating_id');
    }

    public function getStatusAttribute($value){
        switch ($value) {
            case '1': $status = AntrianModel::STATUS_PROSESS; break;
            case '2': $status = AntrianModel::STATUS_SELESAI; break;
            case '3': $status = AntrianModel::STATUS_BATAL; break;
            case '4': $status = AntrianModel::STATUS_TIDAK_HADIR; break;
            default : $status = AntrianModel::STATUS_MENUNGGU; break;
        }
        return $status;
    }
}