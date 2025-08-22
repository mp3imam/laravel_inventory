<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'Inventory','module_icon'=> 'ri-dashboard-fill','module_url'=>'inventory','module_parent' =>0, 'module_position' => 1,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Booking Layanan','module_icon'=> 'ri-book-read-line','module_url'=>'booking-layanan','module_parent' =>0, 'module_position' => 2,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Smart Analysis','module_icon'=> 'ri-bar-chart-grouped-fill','module_url'=>'smart_analist','module_parent' =>0, 'module_position' => 3,'module_description' => '', 'module_status'=>1]);

        // // Master
        // Permission::create(['name' => 'Master','module_icon'=> 'bx bxs-report','module_url'=>'sideMaster','module_parent' =>0, 'module_position' => 4,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Pengelolaan Pengguna','module_icon'=> 'ri-file-user-fill','module_url'=>'users','module_parent' =>4, 'module_position' => 1,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Provinsi','module_icon'=> 'ri-mail-settings-fill','module_url'=>'provinsis','module_parent' =>4, 'module_position' => 2,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Satker','module_icon'=> 'ri-mail-settings-fill','module_url'=>'satkers','module_parent' =>4, 'module_position' => 3,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Layanan','module_icon'=> 'ri-mail-settings-fill','module_url'=>'layanans','module_parent' =>4, 'module_position' => 4,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Berita','module_icon'=> 'ri-mail-settings-fill','module_url'=>'berita','module_parent' =>4, 'module_position' => 5,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Upload Video','module_icon'=> 'ri-mail-settings-fill','module_url'=>'upload_video','module_parent' =>4, 'module_position' => 6,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Upload Banner','module_icon'=> 'ri-mail-settings-fill','module_url'=>'upload_banner','module_parent' =>4, 'module_position' => 7,'module_description' => '', 'module_status'=>1]);

        // // Laporan
        // Permission::create(['name' => 'Report','module_icon'=> 'bx bxs-report','module_url'=>'sideReport','module_parent' =>0, 'module_position' => 5,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Report Management System','module_icon'=> 'bx bxs-report','module_url'=>'report_management_system','module_parent' =>12, 'module_position' => 1,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Rate And Support System','module_icon'=> 'bx bxs-report','module_url'=>'rate_support_sistem','module_parent' =>12, 'module_position' => 2,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Export Pengguna','module_icon'=> 'ri-user-received-2-fill','module_url'=>'export_users','module_parent' =>12, 'module_position' => 3,'module_description' => '', 'module_status'=>1]);
        Permission::create(['name' => 'Log Aktifitas Pengguna','module_icon'=> ' ri-contacts-fill','module_url'=>'logs','module_parent' =>0, 'module_position' => 4,'module_description' => '', 'module_status'=>1]);

        // // Pengaturan
        // Permission::create(['name' => 'Setting','module_icon'=> 'bx bxs-report','module_url'=>'sideSetting','module_parent' =>0, 'module_position' => 6,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Role Level Pengguna','module_icon'=> 'ri-admin-fill','module_url'=>'roles','module_parent' => 17, 'module_position' => 1,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Pengaturan Tema Aplikasi','module_icon'=> 'ri-layout-6-fill','module_url'=>'theme','module_parent' => 17, 'module_position' => 2,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Pengaturan Menu','module_icon'=> 'ri-menu-5-fill','module_url'=>'module','module_parent' => 17, 'module_position' => 3,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Data Profile Pengguna','module_icon'=> 'ri-profile-fill','module_url'=>'profile','module_parent' => 17, 'module_position' => 4,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Kustomisasi Email','module_icon'=> 'ri-mail-settings-fill','module_url'=>'kustomisasi-mail','module_parent' => 17, 'module_position' => 5,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Aktifasi Layanan','module_icon'=> 'ri-mail-settings-fill','module_url'=>'active_layanans','module_parent' => 17, 'module_position' => 6,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Pertanyaan Untuk Rating','module_icon'=> 'ri-mail-settings-fill','module_url'=>'questions','module_parent' => 17, 'module_position' => 7,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Pengaturan Cetak Printer','module_icon'=> 'ri-mail-settings-fill','module_url'=>'printout_kiosk','module_parent' => 17, 'module_position' => 8,'module_description' => '', 'module_status'=>1]);

        // Permission::create(['name' => 'Rating','module_icon'=> 'ri-star-half-s-line','module_url'=>'ratings','module_parent' =>0, 'module_position' => 7,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Notifikasi','module_icon'=> 'ri-notification-3-fill','module_url'=>'notify','module_parent' =>0, 'module_position' => 8,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'System Backup dan Restore Data','module_icon'=> 'ri-database-2-fill','module_url'=>'backup','module_parent' =>0, 'module_position' => 9,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Integration Data','module_icon'=> 'ri-database-fill','module_url'=>'integrasi-data','module_parent' =>0, 'module_position' => 10,'module_description' => '', 'module_status'=>1]);

        // // Dokumentasi
        // Permission::create(['name' => 'Documentation','module_icon'=> 'bx bxs-report','module_url'=>'sideDokumentasi','module_parent' =>0, 'module_position' => 11,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'Documentation User','module_icon'=> 'ri-file-word-2-fill','module_url'=>'dokumentasi','module_parent' =>30, 'module_position' => 1,'module_description' => '', 'module_status'=>1]);
        // Permission::create(['name' => 'FAQ','module_icon'=> 'ri-mail-settings-fill','module_url'=>'faq','module_parent' => 30, 'module_position' => 2,'module_description' => '', 'module_status'=>1]);

        //create roles and assign existing permissions
        $writerRole = Role::create(['name' => 'user']);
        // $writerRole->givePermissionTo('Documentation');
        // $writerRole->givePermissionTo('Documentation User');
        // $writerRole->givePermissionTo('Booking Layanan');

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('Inventory');
        $adminRole->givePermissionTo('Log Aktifitas Pengguna');
        // $adminRole->givePermissionTo('Booking Layanan');
        // $adminRole->givePermissionTo('Smart Analysis');
        // $adminRole->givePermissionTo('Master');
        // $adminRole->givePermissionTo('Report');
        // $adminRole->givePermissionTo('Setting');
        // $adminRole->givePermissionTo('Report Management System');
        // $adminRole->givePermissionTo('Rate And Support System');
        // $adminRole->givePermissionTo('Export Pengguna');
        // $adminRole->givePermissionTo('Pengaturan Menu');
        // $adminRole->givePermissionTo('System Backup dan Restore Data');
        // $adminRole->givePermissionTo('Integration Data');
        // $adminRole->givePermissionTo('Rating');
        // $adminRole->givePermissionTo('Documentation');
        // $adminRole->givePermissionTo('Documentation User');
        // $adminRole->givePermissionTo('FAQ');
        // $adminRole->givePermissionTo('Pengaturan Tema Aplikasi');
        // $adminRole->givePermissionTo('Data Profile Pengguna');
        // $adminRole->givePermissionTo('Pengelolaan Pengguna');
        // $adminRole->givePermissionTo('Notifikasi');
        // $adminRole->givePermissionTo('Upload Banner');
        // $adminRole->givePermissionTo('Upload Video');

        $superadminRole = Role::create(['name' => 'super-admin']);
        // gets all permissions via Gate::before rule
        $superadminRole->givePermissionTo(Permission::all());

        // create demo users
        $user = User::factory()->create([
            'name' => 'User Tamu',
            'email' => 'tamu@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        $user->assignRole($writerRole);

        $user = User::factory()->create([
            'name' => 'Admin Satker',
            'email' => 'admin@gmail.com',
            'provinsi_id' => 1,
            'satker_id' => 1,
            'password' => bcrypt('12345678')
        ]);
        $user->assignRole($adminRole);

        $user = User::factory()->create([
            'name' => 'Superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        $user->assignRole($superadminRole);
    }
}
