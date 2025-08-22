<?php

namespace Database\Seeders;

use App\Models\LayananModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $layanans =[
            [
                'name'  => 'Pengembalian Barang Bukti',
                'kode'  => 'BB',
                'color' => '#0F9D58',
            ],
            [
                'name' => 'Pengembalian Tilang',
                'kode' => 'TL',
                'color' => '#4267B2',
            ],
            [
                'name' => 'Besuk Tahanan',
                'kode' => 'TH',
                'color' => '#F4B400',
            ],
            [
                'name' => 'Penyuluhan Hukum',
                'kode' => 'PH',
                'color' => '#4285F4',
            ],
            [
                'name' => 'Pendampingan Hukum',
                'kode' => 'PD',
                'color' => '#DB4437',
            ]
        ];

        foreach($layanans  as $layanan)
        {
            $inputs[]=[
                'name'  => $layanan['name'],
                'kode'  => $layanan['kode'],
                'color' => $layanan['color']
            ];
        }
        LayananModel::insert($inputs);

    }
}