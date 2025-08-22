<?php

use App\Http\Controllers\API\C2;
use App\Http\Controllers\API\BeritaController;
use App\Http\Controllers\API\MailController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DocsAPIController;
use App\Http\Controllers\API\RatingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('mail',[MailController::class, 'getIndex']);

Route::post('image',[BeritaController::class, 'imageStore']);
Route::get('berita',[BeritaController::class, 'getBerita']);
Route::post('berita/add',[BeritaController::class, 'store']);
Route::post('berita/{id}/show',[BeritaController::class, 'show']);
Route::post('berita/{id}/update',[BeritaController::class, 'update']);
Route::delete('berita/{id}/delete',[BeritaController::class, 'destroy']);
Route::get('berita/category',[BeritaController::class, 'byCategory']);


Route::post('/register', [AuthController::class, 'register']);
//API route for login user
Route::post('/login', [AuthController::class, 'login'])->middleware('logs_activities');
Route::get('/v1/satker', [DocsAPIController::class, 'index']);
//Protecting Routes['middleware' => ['auth', 'logs_activities']]
Route::group(['middleware' => ['auth:sanctum','logs_activities']], function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });

    // API route for logout user
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Simpan Antrian Web dan Mobile
Route::post('/save_antrian', [C2::class,'store'])->name('save_antrian');
Route::post('/save_guest', [C2::class,'save_guest'])->name('save_guest');
Route::post('/save_guest_kiosk', [C2::class,'save_guest_kiosk'])->name('save_guest_kiosk');
Route::post('/save_mobile', [C2::class,'store_mobile'])->name('save_mobile');

// Get History Mobile
Route::get('/list_history/{id}', [C2::class,'list_history'])->middleware('logs_activities');

// Get Provinsi ?provinsi_id = 2
Route::get('/get_provinsi', [C2::class,'provinsi'])->name('api.provinsi');

// Get User
Route::get('/get_all_users', [C2::class,'all_users'])->name('api.all_users');
Route::get('/get_users', [C2::class,'users'])->name('api.users');
Route::get('/role_users', [C2::class,'role_users'])->name('api.role_users');
Route::get('/role_users_not_guest', [C2::class,'role_users_not_guest'])->name('api.role_users_not_guest');
Route::get('/role_admin_superadmin', [C2::class,'role_admin_superadmin'])->name('api.role_admin_superadmin');

// Satker ?provinsi_id = 2
Route::get('/get_satker', [C2::class,'index']);
Route::get('/satker', [C2::class,'satker'])->name('api.satker');

// Layanan
Route::get('/mst_layanan', [C2::class,'mst_layanan'])->name('mst_layanan');
Route::get('/layanan', [C2::class,'layanan'])->name('api.layanan');
Route::get('/layanan_sakters', [C2::class,'layanan_persatkers'])->name('api.layanan_sakters');
Route::get('/get_antrian', [C2::class,'get_antrian'])->name('api.get_antrian');

// List Menu
Route::get('/get_menu', [C2::class,'get_menu'])->name('api.get_menu');

// ubah jadwal
Route::post('/ubah_jadwal', [C2::class, 'ubah_jadwal']);
Route::post('/membatalkan', [C2::class, 'membatalkan_booking']);

// Menu Question Rating
Route::get('/get_questions', [RatingController::class,'index'])->name('api.get_questions');
Route::post('/answers_rating', [RatingController::class,'store'])->name('api.answers_rating');