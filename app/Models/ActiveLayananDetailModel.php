<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveLayananDetailModel extends Model
{
    use HasFactory;
    protected $table = "active_layanan_detail";

    public function active_layanan(){
        return $this->belongsTo(ActiveModel::class);
    }

    public function layanans(){
        return $this->belongsTo(LayananModel::class);
    }
}
