<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryModel extends Model
{
    use HasFactory;
    protected $table = "items";
    protected $guarded = ['id'];

    public function antrian(){
        return $this->hasOne(AntrianModel::class,'layanan_id');
    }

    public function satkers(){
        return $this->belongsTo(SatkerModel::class, 'satker_id');
    }

    public function active_layanans(){
        return $this->hasMany(ActiveModel::class, 'id');
    }

    public function getIconAttribute($value)
    {
        if ($value) {
            return asset('storage/icons_layanans/' . $value);
        }
    }

    public function question(){
        return $this->belongsTo(QuestionModel::class, 'layanan_id');
    }

    public function rating(){
        return $this->belongsTo(RatingModel::class, 'layanan_id');
    }
}
