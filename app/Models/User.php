<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'nip',
        'provinsi_id',
        'satker_id',
        'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
        'email_verified_at' => 'datetime',
    ];

    public function active_layanans(){
        return $this->belongsTo(ActiveModel::class);
    }

    public function satker()
    {
        return $this->belongsTo(SatkerModel::class, 'satker_id', 'id');
    }
    public function satkers()
    {
        return $this->hasOne(SatkerModel::class, 'id');
    }

    public function provinsi()
    {
        return $this->belongsTo(ProvinsiModel::class, 'provinsi_id', 'id');
    }

    public function logs()
    {
        return $this->belongsTo(LogActivitiesModel::class, 'user_id');
    }

    public function keluhan()
    {
        return $this->belongsTo(KeluhanModel::class, 'keluhan_id');
    }

    public function komentar()
    {
        return $this->belongsTo(KomentarModel::class, 'user_id');
    }

    public function antrian()
    {
        return $this->belongsTo(AntrianModel::class, 'user_id');
    }

    public function log()
    {
        return $this->hasOne(LogActivitiesModel::class, 'user_id')->latest();
    }

    public function question(){
        return $this->belongsTo(QuestionModel::class, 'user_id');
    }

    public function rating(){
        return $this->belongsTo(RatingModel::class, 'user_id');
    }

    public function uploadVideo(){
        return $this->belongsTo(UploadVideoModel::class, 'user_id');
    }
}
