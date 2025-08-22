<?php

use App\Events\HelloWorld;
use App\Http\Controllers\ActiveLayananUsersController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\BookingLayananController;
use App\Http\Controllers\ExportUserController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\ProvinsiController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SatkerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MasterModuleController;
use App\Http\Controllers\MasterThemeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\BackupManagerController;
use App\Http\Controllers\LogActivitiesController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DasboardController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\IntegrateController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RateAndSupportSystemController;
use App\Http\Controllers\RatingUserController;
use App\Http\Controllers\ReportManagementController;
use App\Http\Controllers\SettingsPrinterKioskController;
use App\Http\Controllers\SistemAnalistController;
use App\Http\Controllers\UploadBannersController;
use App\Http\Controllers\UploadVideoController;
use App\Http\Controllers\TestPrintController;
use App\Http\Controllers\InventoryController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('print', [TestPrintController::class, 'index']);


Route::get('rating_user', [RatingUserController::class, 'rating_user'])->name('rating_user');
Route::get('generate_url', [RatingUserController::class, 'generateTempUrl']);
Route::post('rating_answer', [RatingUserController::class, 'rating_answer'])->name('rating_answer');
Route::get('user_rating_success', [RatingUserController::class, 'user_rating_success'])->name('user_rating_success');
Route::get('sistem_antrians', [AntrianController::class, 'index']);
Route::get('sistem_antrian/{satker_id}', [AntrianController::class, 'sistem_antrian'])->name('sistem_antrian');

Route::get('guest.booking', [BookingLayananController::class, 'guest_booking'])->name('guest.booking');
Route::post('guest.pdf', [BookingLayananController::class, 'guest_pdf'])->name('guest.pdf');
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
// Monitor Antrian
Route::get('banner_play', [BookingLayananController::class, 'banner_play'])->name('banner_play');
Route::get('imam', [DasboardController::class, 'imam'])->name('imam');
Route::get('send-event', function(){
    broadcast(new HelloWorld());
});


Route::group(['middleware' => ['auth']], function() {
    Route::resource('inventory', InventoryController::class);
    Route::get('listStock', [InventoryController::class,'list'])->name('listStock');
    Route::post('stock', [InventoryController::class,'stock'])->name('stock');

    Route::get('cek_logs', [DasboardController::class, 'logs'])->name('cek_logs');
    Route::get('cek_logs_lists', [DasboardController::class, 'cek_logs_lists'])->name('cek_logs_lists');
    Route::get('kiosk_aktif', [DasboardController::class, 'kiosk_aktif'])->name('kiosk_aktif');
    Route::get('list_status_kiosk', [DasboardController::class, 'list_status_kiosk'])->name('list_status_kiosk');
    Route::get('list_status_admin', [DasboardController::class, 'list_status_admin'])->name('list_status_admin');
    Route::get('list_detail_pengunjung', [DasboardController::class, 'list_detail_pengunjung'])->name('list_detail_pengunjung');
    // Route::get('dashboard', [AuthController::class, 'dashboard']);
    Route::get('dashboard', [DasboardController::class, 'index'])->name('dashboard');;
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('roles', RoleController::class)->except(['show']);

    Route::get('booking-layanan', [BookingLayananController::class, 'index'])->name('booking.index');
    Route::post('booking.pdf', [BookingLayananController::class, 'pdf'])->name('booking.pdf');
    Route::post('booking.cek_antrian', [BookingLayananController::class, 'cek_antrian'])->name('booking.cek_antrian');
    Route::post('booking.cek_proses', [BookingLayananController::class, 'cek_proses'])->name('booking.cek_proses');
    Route::post('booking.update_alasan', [BookingLayananController::class, 'update_alasan'])->name('booking.update_alasan');
    Route::post('booking.ubah_tanggal', [BookingLayananController::class, 'ubah_tanggal'])->name('booking.ubah_tanggal');
    Route::get('booking-layanan-add', [BookingLayananController::class, 'add'])->name('booking.add');
    Route::get('booking-layanan/create', [BookingLayananController::class, 'create'])->name('booking.create');
    Route::get('booking-layanan/listBookingLayanan', [BookingLayananController::class, 'listBookingLayanan'])->name('listBookingLayanan');
    Route::post('booking-layanan/changeStatusAntrian', [BookingLayananController::class, 'changeStatusAntrian'])->name('changeStatusAntrian');
    Route::resource('rate_support_sistem', RateAndSupportSystemController::class)->except(['show','update']);
    Route::post('rate_support_sistem/update_keluhan', [RateAndSupportSystemController::class,'update_keluhan'])->name('update_keluhan');
    Route::post('rate_support_sistem/update_komentar', [RateAndSupportSystemController::class,'update'])->name('update_komentar');
    Route::get('list_rate_support_sistem', [RateAndSupportSystemController::class,'list'])->name('list_rate_support_sistem');
    Route::get('list_komentars/{id}', [RateAndSupportSystemController::class,'list_komentars'])->name('list_komentars');
    Route::get('report_management_system', [ReportManagementController::class,'index'])->name('report_management_system');
    Route::get('list_report_management_system', [ReportManagementController::class,'list_report_management_system'])->name('list_report_management_system');
    Route::post('report_management_system.pdf', [ReportManagementController::class, 'pdf'])->name('report_management_system.pdf');
    Route::resource('provinsis', ProvinsiController::class)->except(['show']);
    Route::post('provinsis.pdf', [ProvinsiController::class,'pdf'])->name('provinsis.pdf');
    Route::get('ratings', [RatingController::class,'index'])->name('ratings');
    Route::get('list_ratings', [RatingController::class,'list'])->name('list_ratings');
    Route::get('questions', [QuestionController::class,'index'])->name('questions');
    Route::get('list_questions', [QuestionController::class,'list'])->name('list_questions');
    Route::get('questions_create', [QuestionController::class,'create'])->name('questions.create');
    Route::post('questions_store', [QuestionController::class,'store'])->name('questions.store');
    Route::post('questions_update', [QuestionController::class,'update'])->name('questions.update');
    Route::delete('questions_delete/{id}', [QuestionController::class,'destroy'])->name('questions.delete');
    Route::get('questions_edit/{id}', [QuestionController::class,'edit'])->name('questions.edit');
    Route::resource('satkers', SatkerController::class)->except(['show']);
    Route::post('satkers.pdf', [SatkerController::class, 'pdf'])->name('satkers.pdf');
    Route::resource('layanans', LayananController::class)->except(['show']);
    Route::post('layanans.pdf', [LayananController::class, 'pdf'])->name('layanans.pdf');
    Route::resource('active_layanans', ActiveLayananUsersController::class);
    Route::get('listActiveLayanan', [ActiveLayananUsersController::class,'list'])->name('listActiveLayanan');
    Route::get('listLayanan', [ActiveLayananUsersController::class,'listLayanan'])->name('listLayanan');
    Route::resource('users', UserController::class)->except(['show']);

    Route::get('theme', [MasterThemeController::class, 'index'])->name('theme.index');
    Route::resource('export_users', ExportUserController::class)->except(['show']);
    Route::post('export_users.pdf', [ExportUserController::class, 'pdf'])->name('export_users.pdf');
    Route::get('listUsers', [ExportUserController::class,'list'])->name('listUsers');

    Route::get('module', [MasterModuleController::class, 'index'])->name('master.module');
    Route::post('module-reorder', [MasterModuleController::class, 'moduleReorder'])->name('module.reorder.post');
    Route::post('module-filter', [MasterModuleController::class, 'moduleFilter'])->name('master.module.filter');
    Route::get('module-create', [MasterModuleController::class, 'create'])->name('master.module.create');
    Route::post('module-store', [MasterModuleController::class, 'store'])->name('master.module.store');
    Route::get('module-getById/{id}', [MasterModuleController::class, 'getById']);
    Route::post('module-update', [MasterModuleController::class, 'update'])->name('master.module.update');
    Route::get('module-delete/{id}', [MasterModuleController::class, 'delete']);

    Route::get('kustomisasi-mail', [MailController::class, 'index'])->name('mail.index');
    Route::get('kustomisasi-mail/send-mail', [MailController::class, 'create'])->name('mail.create');
    Route::post('kustomisasi-mail/send-mail/store', [MailController::class, 'store'])->name('mail.store');
    Route::get('kustomisasi-mail/{id}', [MailController::class, 'edit'])->name('mail.edit');
    Route::patch('kustomisasi-mail/update-mail/{id}', [MailController::class, 'update'])->name('mail.update');
    Route::get('apply-custom-mail/{token}', [MailController::class, 'showApplyForm'])->name('apply.mail.get');
    Route::post('submit-mail', [MailController::class, 'submitApplyForm'])->name('submit.mail.post');
    Route::get('test-mail/{id}', [MailController::class, 'testingMail'])->name('test.mail');
    Route::post('mail/accepted/{id}', [MailController::class, 'mailAccepted'])->name('mail.accepted');

    Route::get('backup', [BackupManagerController::class, 'index'])->name('backupmanager');
    Route::post('create', [BackupManagerController::class, 'createBackup'])->name('backupmanager_create');
    Route::post('restore_delete', [BackupManagerController::class, 'restoreOrDeleteBackups'])->name('backupmanager_restore_delete');
    Route::get('download/{file}', [BackupManagerController::class, 'download'])->name('backupmanager_download');

    // Log
    Route::get('logs', [LogActivitiesController::class,'index'])->name('logs');
    Route::get('logsList', [LogActivitiesController::class,'list'])->name('logsList');
    Route::post('logs.pdf', [LogActivitiesController::class, 'pdf'])->name('logs.pdf');
    Route::get('berita', [BeritaController::class , 'index'])->name('berita.index');
    Route::get('berita/create', [BeritaController::class , 'create'])->name('berita.create');
    Route::get('berita/edit/{id}', [BeritaController::class , 'edit'])->name('berita.edit');
    Route::delete('berita/delete/{id}', [BeritaController::class , 'destroy'])->name('berita.destroy');
    Route::post('/save', [BeritaController::class, 'save'])->name('berita.save');

    Route::post('/upload', [BeritaController::class, 'store']);
    Route::post('/update', [BeritaController::class, 'update']);
    Route::post('/updateBerita', [BeritaController::class, 'updateBerita'])->name('berita.updateBerita');

    Route::get('notify', [NotificationController::class, 'index'])->name('notif.index');
    Route::get('notify/set-admin', [NotificationController::class, 'notif_admin'])->name('notif.admin');
    Route::post('notify/set-admin/update', [NotificationController::class, 'notif_admin_update'])->name('notif.admin.update');
    Route::get('notify/set-user', [NotificationController::class, 'notif_user'])->name('notif.user');
    Route::post('notify/set-user/update', [NotificationController::class, 'notif_user_update'])->name('notif.user.update');
    Route::post('notify/mark-as-read',[NotificationController::class, 'markNotification'])->name('markNotification');
    Route::get('notify/detail/{uuid}',[NotificationController::class, 'detail'])->name('notif.detail');

    Route::post('/save-token', [NotificationController::class, 'saveToken'])->name('save-token');
    Route::post('/send-notification', [NotificationController::class, 'sendNotification'])->name('send.notification');
    Route::get('profile', [UserController::class, 'profile'])->name('users.profile');
    Route::post('profile/update', [UserController::class, 'profile_update'])->name('profile.update');
    Route::post('profile/avatar', [UserController::class, 'update_avatar'])->name('update.avatar');

    Route::post('users/active/{id}', [UserController::class, 'usersActive'])->name('users.active');
    Route::get('change-password', [UserController::class, 'changePassword'])->name('change.password');
    Route::post('profile/change-password', [UserController::class, 'password_update'])->name('password.update');
    Route::get('integrasi-data', [IntegrateController::class, 'index'])->name('integrate.index');
    Route::get('integrasi-data/xml-opt', [IntegrateController::class, 'xml_opt'])->name('integrate.xml-opt');

    Route::post('integrasi-data/data-satker.xml', [IntegrateController::class, 'xml'])->name('integrate.xml');

    // Upload Video
    Route::resource('upload_video', UploadVideoController::class)->except(['show']);
    Route::get('upload_video.list', [UploadVideoController::class, 'list'])->name('upload_video.list');
    Route::post('cek_video_aktif', [UploadVideoController::class, 'cek_video_aktif'])->name('upload_video.cek_video_aktif');
    Route::post('video_status', [UploadVideoController::class, 'video_status'])->name('upload_video.video_status');

    // Smart Analist
    Route::get('smart_analist', [SistemAnalistController::class, 'index'])->name('smart_analist');
    Route::get('smart_analist/jumlah_pengunjung_satker_perbulan', [SistemAnalistController::class, 'jumlah_pengunjung_satker_perbulan'])->name('jumlah_pengunjung_satker_perbulan');

    // Banner KiosK
    Route::resource('upload_banner', UploadBannersController::class)->except(['show']);
    Route::get('upload_banner.list', [UploadBannersController::class, 'list'])->name('upload_banner.list');

    // Text KiosK
    // Route::resource('upload_text', UploadTextController::class)->except(['show']);
    Route::get('test', function () {
        event(new App\Events\MessageSent('Hello World !!!'));
        return "Event has been sent!";
    });

    // Dokumentasi
    Route::resource('dokumentasi', DokumentasiController::class)->except(['show']);
    Route::get('dokumentasi.list', [DokumentasiController::class, 'list'])->name('dokumentasi.list');

    // Pengaturan Cetak KiosK
    Route::resource('printout_kiosk', SettingsPrinterKioskController::class)->except(['show']);
    Route::get('printout_kiosk.list', [SettingsPrinterKioskController::class, 'list'])->name('printout_kiosk.list');

    // Documentasi FAQ
    Route::resource('faq', FAQController::class)->except(['show']);
});
// Auth::routes();

//Language Translation
Route::get('index/{locale}', [HomeController::class, 'lang']);

Route::get('/', [HomeController::class, 'root'])->name('root');

//Update User Details
Route::post('/update-profile/{id}', [HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [HomeController::class, 'updatePassword'])->name('updatePassword');

Route::get('{any}', [HomeController::class, 'index'])->name('index');

// Kioska
Route::get('kiosk/{satker_id}', [BookingLayananController::class, 'guest_kiosk_booking'])->name('guest.kiosk.booking');
Route::get('data_antrian/{satker_id}', [AntrianController::class, 'data_antrian'])->name('data_antrian');
Route::get('video.play/{satker_id}', [BookingLayananController::class, 'video_play'])->name('video.play');
Route::post('cek_guest_kiosk', [AntrianController::class, 'cek_guest_kiosk'])->name('cek_guest_kiosk');

Route::post('save_appkey_kiosk', [AntrianController::class, 'save_appkey_kiosk'])->name('save_appkey_kiosk');
