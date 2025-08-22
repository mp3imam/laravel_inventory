<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul','berita','gambar','users_id','category_id'
    ];


    public function getGambarAttribute($value)
    {
        if ($value) {
            return asset('storage/berita/image/' . $value) ?? '';
        }
    }

    public function category()
    {
        return $this->belongsTo(CategoryBerita::class, 'category_id', 'id');
    }
}
