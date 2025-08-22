<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

    /**
     * @OA\Schema(
     *   @OA\Xml(name="SatkerModel"),
     *   @OA\Property(format="int64", title="ID", default=1, description="ID", property="id"),
     *   @OA\Property(format="int64", title="Kode Satker", default="005020", description="KodeSatker", property="kode_satker"),
     *   @OA\Property(format="string", title="name", default="Kejati DKI Jakarta", description="Name", property="name"),
     *   @OA\Property(format="string", title="Alamat", default="Jl. H. R. Rasuna Said No.2, RT.5/RW.4, Kuningan Tim., Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12950", description="Alamat", property="address"),
     * )
     */

class SatkerModel extends Model
{

    use HasFactory;
    protected $table = "satker";
    protected $guarded = ['id'];

    public function provinsis(){
        return $this->belongsTo(ProvinsiModel::class, 'provinsi_id');
    }

    public function provinsi(){
        return $this->hasOne(ProvinsiModel::class, 'id');
    }

    public function keluhan(){
        return $this->belongsTo(KeluhanModel::class, 'satker_id');
    }

    public function layanans(){
        return $this->hasMany(LayananModel::class);
    }

    public function antrians(){
        return $this->hasOne(AntrianModel::class, 'satker_id');
    }

    public function active_layanans(){
        return $this->hasMany(ActiveModel::class, 'satker_id');
    }

    public function only_active_layanans(){
        return $this->hasMany(ActiveModel::class, 'satker_id')->where('status',"1")->orderBy('layanan_id');
    }

    public function question(){
        return $this->belongsTo(QuestionModel::class, 'satker_id');
    }

    public function rating(){
        return $this->belongsTo(RatingModel::class, 'satker_id');
    }

    public function uploadVideo(){
        return $this->belongsTo(UploadVideoModel::class, 'satker_id');
    }

    // public function users(){
    //     return $this->belongsTo(User::class, 'id','satker_id');
    // }


}
