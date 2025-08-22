<?php

namespace Database\Seeders;

use App\Models\ProvinsiModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinsis = [
            "Aceh",
            "Sumatera Utara",
            "Sumatera Barat",
            "Riau",
            "Jambi",
            "Sumatera Selatan",
            "Lampung",
            "Jakarta",
            "Jawa Barat",
            "Jawa Tengah",
            "Daerah Istimewa Yogyakarta",
            "Jawa Timur",
            "Kalimantan Barat",
            "Kalimantan Selatan",
            "Kalimantan Timur",
            "Sulawesi Utara",
            "Sulawesi Tengah",
            "Sulawesi Tenggara",
            "Sulawesi Selatan",
            "Bali",
            "Banten",
            "Gorongtalo",
            "Kepulauan Riau",
        ];
        foreach($provinsis  as $provinsis)
          {
             $inputs[]=['name'=>$provinsis];
          }
         ProvinsiModel::insert($inputs);
    }
}
