<?php

namespace Database\Seeders;

use App\Models\MenuNotifModel;
use Illuminate\Database\Seeder;

class MenuNotifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu_notif =[
            [
                "menu" => "Tambah Pengelolaan Pengguna",
                "role" => "admin",
                "status" => 1,
                "is_email" => 0,
            ],[
                "menu" => "Edit Pengelolaan Pengguna",
                "role" => "admin",
                "status" => 0,
                "is_email" => 0,
            ],[
                "menu" => "Tambah Booking Layanan",
                "role" => "admin",
                "status" => 1,
                "is_email" => 0,
            ],[
                "menu" => "Tambah Booking Layanan",
                "role" => "user",
                "status" => 1,
                "is_email" => 0,
            ],[
                "menu" => "Profile Pengguna",
                "role" => "admin",
                "status" => 0,
                "is_email" => 0,
            ],[
                "menu" => "Hapus Pengelolaan Pengguna",
                "role" => "admin",
                "status" => 0,
                "is_email" => 0,
            ]
        ];

        foreach($menu_notif  as $notif)
          {
             $inputs[] = [
                'menu'   => $notif['menu'],
                'role'   => $notif['role'],
                'status' => $notif['status'],
                'is_email' => $notif['is_email'],
            ];
          }
         MenuNotifModel::insert($inputs);
    }
}