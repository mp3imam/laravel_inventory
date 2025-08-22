<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvinsiModel extends Model
{
    use HasFactory;
    protected $table = "provinsi";
    protected $fillable = ['name'];

    public function satkers(){
        return $this->hasMany(SatkerModel::class, 'provinsi_id');
    }

    public function satker(){
        return $this->belongsTo(SatkerModel::class, 'provinsi_id');
    }

    public function antrian(){
        return $this->hasOne(AntrianModel::class);
    }

    public function active_layanans(){
        return $this->belongsTo(ActiveModel::class);
    }

    public function question(){
        return $this->belongsTo(QuestionModel::class, 'provinsi_id');
    }

    public function rating(){
        return $this->belongsTo(RatingModel::class, 'provinsi_id');
    }

    public function uploadVideo(){
        return $this->belongsTo(UploadVideoModel::class, 'provinsi_id');
    }
}
