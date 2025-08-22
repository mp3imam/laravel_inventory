<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use App\Models\User;


class Emails extends Model
{
    use HasFactory;

    protected $table = "custom_emails";

    protected $fillable = [
        'hostname',
        'port',
        'email',
        'password',
        'title',
        'body',
        'status',
        'token',
        'provinsi_id',
        'satker_id',
        'layanan_id',
        'user_id'
    ];
    protected $appends = ['user_name','layanan_name','satker_name'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function getUserNameAttribute() {
        return $this->users->name ?? '';
    }

    public function getLayananNameAttribute() {
        return $this->layan->name ?? '';
    }

    public function getSatkerNameAttribute() {
        return $this->satker->name ?? '';
    }


    public function satker()
    {
        return $this->belongsTo(SatkerModel::class, 'satker_id', 'id');
    }

    public function provinsi()
    {
        return $this->belongsTo(ProvinsiModel::class, 'provinsi_id', 'id');
    }

    public function layanans(){
        return $this->hasMany(LayananModel::class);
    }
    public function layan(){
        return $this->belongsTo(LayananModel::class, 'layanan_id', 'id');
    }

    public function antrians(){
        return $this->hasOne(AntrianModel::class, 'satker_id');
    }

    public function active_layanans(){
        return $this->hasMany(ActiveModel::class, 'satker_id');
    }
}
