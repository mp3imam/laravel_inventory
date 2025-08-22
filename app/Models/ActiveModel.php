<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveModel extends Model
{
    use HasFactory;
    protected $table = "active_layanan";
    protected $fillable = ['satker_id', 'layanan_id', 'status'];

    public function provinsis(){
        return $this->belongsTo(ProvinsiModel::class, 'id');
    }

    public function satkers(){
        return $this->belongsTo(SatkerModel::class, 'id');
    }

    public function layanans(){
        return $this->belongsTo(LayananModel::class, 'layanan_id');
    }
}
