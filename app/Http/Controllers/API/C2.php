<?php

namespace App\Http\Controllers\API;

use App\Helpers\LogActivity;
use App\Http\Controllers\BookingLayananController;
use App\Http\Controllers\Controller;
use App\Models\ActiveLayananDetailModel;
use App\Models\AntrianModel;
use App\Models\AntrianModelMobile;
use App\Models\LayananModel;
use App\Models\LogActivitiesModel;
use App\Models\ProvinsiModel;
use App\Models\SatkerModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Alert;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Models\Role;
use App\Notifications\NewUserNotification;
use Illuminate\Notifications\Notification;

class C2 extends Controller
{
    public function index(Request $request){
        $provinsi = ProvinsiModel::with(['satkers'])
        ->when($request->id, function($q) use($request){
            return $q->whereIn('id',explode(',',$request->id));
        })
        ->when($request->q, function($q) use($request){
            $q->where('name', 'LIKE', '%'. $request->q. '%');
            $q->orWhereHas('satkers', function($q) use($request){
                return $q->where('name', 'LIKE', '%'. $request->q. '%');
            });
            return $q;
        })
        ->orderBy('name')
        ->get();

        $dataS = collect([]);
        foreach($provinsi as $p){
            $dataS->push([
                'id_provinsi' => $p->id,
                'provinsi'    => $p->name,
                'lokasi_mpp'  => $p->satkers->map(function ($satker){
                    return [
                        'id'             => $satker->id,
                        'mpp_diresmikan' => $satker->name
                    ];
                })
            ]);
        }

        $data = [
            'status' => Response::HTTP_OK,
            'data'   => $dataS
        ];

        return response()->json($data);
    }

    public function provinsi(Request $request){
        $provinsi = ProvinsiModel::select("id", "name")
            ->when($request->provinsi_id, function($q) use($request){
                return $q->whereIn('id',explode(',',$request->provinsi_id));
            })
            ->when($request->q, function($q) use($request){
                return $q->where('name', 'LIKE', '%'. $request->q. '%');
            })
            ->orderBy('name')
            ->get();

        $data = [
            'status' => Response::HTTP_OK,
            'data'   => $provinsi
        ];

        return response()->json($data);
    }

    public function get_antrian(Request $request){
        $antrian = AntrianModel::where('status','=',"0")
            ->where('satker_id', $request->satker_id)
            ->where('layanan_id', $request->layanan_id)
            ->where('tanggal_hadir',date('Y-m-d'))
            ->first();

        $data = [
            'status'  => Response::HTTP_OK,
            'message' => !$antrian ? "Antrian tidak ditemukan" : "Antrian Ditemukan",
            'data'    => [
                'satker'           => $antrian ? $antrian->satker : "",
                'layanan'          => $antrian ? $antrian->layanan : "",
                'antrian_saat_ini' => $antrian ? $antrian->layanans->kode.$antrian->nomor_antrian :  "Tidak Ada Antrian"
            ]
        ];

        return response()->json($data);
    }

    public function users(Request $request){
        $satker = User::query()->select("id", "name", 'email')
            ->when($request->id, function($q) use($request){
                return $q->whereIn('id',explode(',',$request->id));
            })
            ->when($request->q, function($q) use($request){
                return $q->where('name', 'LIKE', '%'. $request->q. '%');
            })
            ->whereHas('roles', function($q){
                return $q->where('name','Admin');
            })
            ->orderBy('name')
            ->get();

        $data = [
            'status' => Response::HTTP_OK,
            'data'   => $satker
        ];

        return response()->json($data);
    }

    public function role_users(Request $request){
        $roles = Role::query()->select('id', 'name')
        ->when($request->id, function($q) use($request) {
            return $q->whereIn('id',$request->id);
        })
        ->when($request->q, function($q) use($request) {
            return $q->where('name','like','%'.$request->q.'%');
        })
        ->get();

        $roles->push([
            "id"   => 0,
            "name" => "Guest"
        ]);

        $data = [
            'status' => Response::HTTP_OK,
            'data'   => $roles->all()
        ];

        return response()->json($data);
    }

    public function role_users_not_guest(Request $request){
        $roles = Role::query()->select('id', 'name')
        ->when($request->id, function($q) use($request) {
            return $q->whereIn('id',$request->id);
        })
        ->when($request->q, function($q) use($request) {
            return $q->where('name','like','%'.$request->q.'%');
        })
        ->get();

        $roles->push([
            "id"   => 0,
            "name" => "Pilih Semua"
        ]);

        $data = [
            'status' => Response::HTTP_OK,
            'data'   => $roles->sort()->values()->all()
        ];

        return response()->json($data);
    }

    public function role_admin_superadmin(Request $request){
        $roles = Role::query()->select('id', 'name')
        ->when($request->id, function($q) use($request) {
            return $q->whereIn('id',$request->id);
        })
        ->when($request->q, function($q) use($request) {
            return $q->where('name','like','%'.$request->q.'%');
        })
        ->whereIn('id',[2,3])
        ->get();

        $roles->push(
            [
                "id"   => null,
                "name" => "--Pilih Semua--"
            ]
        );

        $data = [
            'status' => Response::HTTP_OK,
            'data'   => $roles->all()
        ];

        return response()->json($data);
    }
    public function satker(Request $request){
        $satkers = SatkerModel::select("id", "name")
        ->with(['provinsi'])
        ->when($request->provinsi_id, function($q) use($request){
            return $q->whereIn('provinsi_id',explode(',',$request->provinsi_id));
        })
        ->when($request->satker, function($q) use($request){
            return $q->whereId($request->satker);
        })
        ->when($request->satker_id, function($q) use($request){
            return $q->whereIn('id',$request->satker_id);
        })
        ->when($request->q, function($q) use($request){
            return $q->where('name','like','%'.$request->q.'%');
        })
        ->orderBy('name')
        ->get();

        $data = [
            'status' => Response::HTTP_OK,
            'data'   => $satkers
        ];

        return response()->json($data);
    }

    public function all_users(Request $request){
        $satker = User::query()->select("id", "name", 'email')
            ->when($request->id, function($q) use($request){
                return $q->whereIn('id',$request->id);
            })
            ->when($request->q, function($q) use($request){
                return $q->where('name', 'LIKE', '%'. $request->q. '%');
            })
            ->when($request->admin, function($q){
                return $q->whereHas('roles', function($q){
                    return $q->where('name','Admin');
                });
            })
            ->get();

        $data = [
            'status' => Response::HTTP_OK,
            'data'   => $satker
        ];

        return response()->json($data);
    }

    public function layanan(Request $request){
        $layanan = LayananModel::query()
            ->when($request->layanan_id, function($q) use($request){
                return $q->whereIn('id',$request->layanan_id);
            })
            ->when($request->q, function($q) use($request){
                return $q->where('name', 'LIKE', '%'. $request->q. '%');
            })
            ->orderBy('id')
            ->get();

        $data = [
            'status' => Response::HTTP_OK,
            'data'   => $layanan
        ];

        return response()->json($data);
    }

    public function layanan_persatkers(Request $request){
        $layanan_satkers = SatkerModel::with(['active_layanans'])
        ->whereHas('active_layanans', function($q) use($request){
            return $q->where('satker_id', $request->satker_id);
        })
        ->get();

        $layanan_satker = collect();
        $layanan_satkers->each(function($satkers) use($layanan_satker){
            $satkers->active_layanans->each(function ($layanan) use($layanan_satker){
                if ($layanan->status !== 0 && $layanan->layanans !== null){
                    $layanan_satker->push([
                        'id'        => $layanan->layanans->id,
                        'name'      => $layanan->layanans->name,
                        'deskripsi' => $layanan->layanans->deskripsi ?? "-",
                        'icon'      => $layanan->layanans->icon ?? "-",
                        'createdAt' => $layanan->layanans->created_at,
                        'updatedAt' => $layanan->layanans->updated_at
                    ]);
                }
            });
        });

        // if ($layanan_satker->isEmpty()){
        //     $layanan_satker->push([
        //         'id'   => null,
        //         'name' => "-- Layanan Belum Tersedia --"
        //     ]);
        // }

        $data = [
            'status' => Response::HTTP_OK,
            'data'   => $layanan_satker->sort()->values()->all()
        ];

        return response()->json($data);
    }

    public function layanan_satker(Request $request){
        $layanan = LayananModel::query()
            ->when($request->id, function($q) use($request){
                return $q->whereIn('id',$request->id);
            })
            ->when($request->q, function($q) use($request){
                return $q->where('name', 'LIKE', '%'. $request->q. '%');
            })
            ->orderBy('name')
            ->get();

        $Activelayanan = ActiveLayananDetailModel::query()
            ->when($request->id, function($q) use($request){
                return $q->whereId($request->id);
            })
            ->get();

        $data = [
            'status'        => Response::HTTP_OK,
            'dataLayanan'   => $layanan,
            'activeLayanan' => $Activelayanan
        ];

        return response()->json($data);
    }

    public function mst_layanan(){
        $payload = [
            "status"=> 200,
            "data"  => [
                [
                    "id"            =>1,
                    "satker_id"     =>1,
                    "layanan"       =>"Pengembalian Barang Bukti",
                    "deskripsi"     =>"-",
                    "createdAt"     =>"2023-03-16T07:08:16.913Z",
                    "updatedAt"     =>"2023-03-16T07:08:16.913Z"
                ],
                [
                    "id"            =>2,
                    "satker_id"     =>1,
                    "layanan"       =>"Pengembalian Tilang",
                    "deskripsi"     =>"-",
                    "createdAt"     =>"2023-03-16T07:08:35.072Z",
                    "updatedAt"     =>"2023-03-16T07:08:35.072Z"
                ],
                [
                    "id"            =>3,
                    "satker_id"     =>1,
                    "layanan"       =>"Besuk Tahanan",
                    "deskripsi"     =>"-",
                    "createdAt"     =>"2023-03-16T07:08:49.244Z",
                    "updatedAt"     =>"2023-03-16T07:08:49.244Z"
                ],
                [
                    "id"            =>4,
                    "satker_id"     =>1,
                    "layanan"       =>"Penuluhan Hukum",
                    "deskripsi"     =>"-",
                    "createdAt"     =>"2023-03-16T07:09:10.391Z",
                    "updatedAt"     =>"2023-03-16T07:09:10.391Z"
                ],
                [
                    "id"            =>5,
                    "satker_id"     =>1,
                    "layanan"       =>"Pendampingan Hukum",
                    "deskripsi"     =>"-",
                    "createdAt"     =>"2023-03-16T07:09:26.709Z",
                    "updatedAt"     =>"2023-03-16T07:09:26.709Z"
                ]
            ]
        ];

        return response()->json($payload);
    }

    public function store(Request $request){
        $validasi = [
            'provinsi_id'   => 'required',
            'provinsi'      => 'required',
            'satker_id'     => 'required',
            'satker'        => 'required',
            'user_id'       => 'nullable',
            'user'          => 'nullable',
            'no_hp'         => 'nullable',
            'tanggal_hadir' => 'required',
            'layanan_id'    => 'required',
            'layanan'       => 'required',
            'keterangan'    => 'nullable',
        ];

        $image = $request->file('image');
        if ($image) $validasi += ['image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg'];

        $validator = Validator::make($request->all(), $validasi);
        $agent = new Agent();

        if ($validator->fails()) {
            if(!$agent->isDesktop())
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'error'  => $validator->messages()
            ]);

            return back()->withErrors($validator->messages());
        }

        $tanggal_hadir  = Carbon::parse($request->tanggal_hadir)->format('Y-m-d');
        $date           = date('d-m-Y');
        $antrian_model  = AntrianModel::query()
            ->whereTanggalHadir($tanggal_hadir)
            ->whereSatkerId($request->satker_id)
            ->whereLayananId($request->layanan_id);
        $id = $antrian_model->latest()->first();

        if ($antrian_model->whereUserId($request->user_id)->exists()){
            if(!$agent->isDesktop())
            return response()->json([
                'status'    => Response::HTTP_CONFLICT,
                'message'   => 'Error Duplicate'
            ]);

            return back()->withErrors(['Duplicate' => 'Anda sudah melakukan booking di Satker '.$request->satker.' pada Layanan '.$request->layanan]);
        }

        $nomor_antrian  = sprintf("%03s", ($id ? $id->nomor_antrian : 0) + 1);
        $qrcode         = $date."|".$nomor_antrian."|".$request->satker."|".$request->layanan;

        $filename = null;
        if($image){
            $filename = str_replace(" ","-", date('d-m-Y-H:i:s')."-".$image->getClientOriginalName());
            Image::make($image->getRealPath())->resize(468, 249)->save('images/'.$filename);
        }

        if($agent->isDesktop() && $request->image){
            $folderPath     = "images/";
            $image_parts    = explode(";base64,", $request->image);
            $image_base64   = base64_decode($image_parts[1]);
            $filename       = uniqid() . '.png';
            $file           = $folderPath . $filename;
            file_put_contents($file, $image_base64);
        }

        //Store your file into directory and db
        $save = new AntrianModel();
        $save->provinsi_id      = $request->provinsi_id;
        $save->provinsi         = Str::of($request->provinsi)->trim;
        $save->satker_id        = $request->satker_id;
        $save->satker           = Str::of($request->satker)->trim;
        $save->user_id          = $request->user_id;
        $save->user             = $request->user;
        $save->tanggal_hadir    = Carbon::parse($tanggal_hadir)->format('Y-m-d');
        $save->no_hp            = $request->no_hp ?? "";
        $save->layanan_id       = $request->layanan_id;
        $save->layanan          = Str::of($request->layanan)->trim;
        $save->keterangan       = $request->keterangan;
        $save->nomor_antrian    = $nomor_antrian;
        $save->qrcode           = $qrcode;
        $save->otp              = mt_rand(100000,999999);
        $save->image            = ($image || $request->image) ? $filename : null;
        $save->save();

        $response = response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Success',
            'data'      => [
                'provinsi_id'   => $request->provinsi_id,
                'provinsi'      => $request->provinsi,
                'satker_id'     => $request->satker_id,
                'satker'        => $request->satker,
                'layanan_id'    => $request->layanan_id,
                'layanan'       => $request->layanan,
                'user'          => $request->user,
                'no_hp'         => $request->no_hp,
                'tanggal_hadir' => $request->tanggal_hadir,
                'layanan'       => $request->layanan,
                'keterangan'    => $request->keterangan,
                'nomor_antrian' => $nomor_antrian,
                'qrcode'        => $qrcode,
                'image'         => $filename,
                'rating'        => $save->rating,
                'rating_value'  => $save->penilaian_rating,
            ]
        ]);

        LogActivity::addToLog('Menambahkan Booking Layanan');
        $menu = \DB::table('menu_notif')->where('menu','Tambah Booking Layanan')
                                        ->first();
        if($menu->status == 1 ){
            $notif = auth()->user();
            $noted = 'Menambahkan Booking Layanan';
            $satker = $request->satker_id;
            $notif->notify(new NewUserNotification($notif, $noted, $satker));
        }
        if(!$agent->isDesktop()) return $response;

        return redirect()->route('booking.index');
    }

    public function save_guest(Request $request){
        $validasi = [
            'nama'          => 'required',
            'email'         => 'required',
            'provinsi_id'   => 'required',
            'provinsi'      => 'required',
            'satker_id'     => 'required',
            'satker'        => 'required',
            'user_id'       => 'nullable',
            'user'          => 'nullable',
            'no_hp'         => 'nullable',
            'tanggal_hadir' => 'required',
            'layanan_id'    => 'required',
            'layanan'       => 'required',
            'keterangan'    => 'nullable',
        ];

        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails())
            return back()
            ->withErrors($validator->messages())
            ->with('dataError',$validator->messages());

        $tanggal_hadir  = Carbon::parse($request->tanggal_hadir)->format('Y-m-d');
        $date           = date('d-m-Y');
        $antrian_model  = AntrianModel::query()
            ->whereTanggalHadir($tanggal_hadir)
            ->whereSatkerId($request->satker_id)
            ->whereLayananId($request->layanan_id);
        $id = $antrian_model->latest()->first();

        $nomor_antrian  = sprintf("%03s", ($id ? $id->nomor_antrian : 0) + 1);
        $qrcode         = $date."|".$nomor_antrian."|".$request->satker."|".$request->layanan;

        //Store your file into directory and db
        $save = new AntrianModel();
        $save->provinsi_id      = $request->provinsi_id;
        $save->provinsi         = Str::of($request->provinsi)->trim;
        $save->satker_id        = $request->satker_id;
        $save->satker           = Str::of($request->satker)->trim;
        $save->user_id          = $request->user_id;
        $save->user             = $request->user ?? "GUEST|".$request->nama.'|'.$request->email;
        $save->tanggal_hadir    = Carbon::parse($tanggal_hadir)->format('Y-m-d');
        $save->no_hp            = $request->no_hp ?? "";
        $save->layanan_id       = $request->layanan_id;
        $save->layanan          = Str::of($request->layanan)->trim;
        $save->keterangan       = $request->keterangan;
        $save->nomor_antrian    = $nomor_antrian;
        $save->qrcode           = $qrcode;
        $save->otp              = mt_rand(100000,999999);
        $save->save();

        LogActivity::addToLog('Guest Menambahkan Booking Layanan');

        return redirect()->route('sistem_antrian', SatkerModel::whereId($save->satker_id)->first()->kode_satker)
        ->with('nomor_antrian', $save->nomor_antrian)
        ->with('id', $save->id);
    }

    public function save_guest_kiosk(Request $request){
        $tanggal_hadir  = Carbon::parse($request->tanggal_hadir)->format('Y-m-d');
        $date           = date('d-m-Y');
        $antrian_model  = AntrianModel::query()
            ->whereTanggalHadir($tanggal_hadir)
            ->whereSatkerId($request->satker_id)
            ->whereLayananId($request->layanan_id);
        $id = $antrian_model->latest()->first();

        $nomor_antrian  = sprintf("%03s", ($id ? $id->nomor_antrian : 0) + 1);
        $qrcode         = $date."|".$nomor_antrian."|".$request->satker."|".$request->layanan;

        //Store your file into directory and db
        $save = new AntrianModel();
        $save->provinsi_id      = $request->provinsi_id;
        $save->provinsi         = Str::of($request->provinsi)->trim;
        $save->satker_id        = $request->satker_id;
        $save->satker           = Str::of($request->satker)->trim;
        $save->user_id          = $request->user_id;
        $save->user             = $request->user ?? "KIOSK";
        $save->tanggal_hadir    = Carbon::parse($tanggal_hadir)->format('Y-m-d');
        $save->no_hp            = $request->no_hp ?? "";
        $save->layanan_id       = $request->layanan_id;
        $save->layanan          = Str::of($request->layanan)->trim;
        $save->keterangan       = $request->keterangan;
        $save->nomor_antrian    = $nomor_antrian;
        $save->qrcode           = $qrcode;
        $save->otp              = mt_rand(100000,999999);
        $save->save();

        LogActivity::addToLog('Guest Menambahkan Booking Layanan');
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Success',
            'data'      => [
                'antrian_id'    => (int)$save->id,
                'provinsi_id'   => (int)$request->provinsi_id,
                'provinsi'      => $request->provinsi,
                'satker_id'     => (int)$request->satker_id,
                'satker'        => $request->satker,
                'layanan_id'    => (int)$request->layanan_id,
                'layanan'       => $request->layanan,
                'user_id'       => (int)$request->user_id,
                'user'          => $request->user,
                'no_hp'         => $request->no_hp,
                'tanggal_hadir' => Carbon::parse($tanggal_hadir)->format('Y-m-d'),
                'layanan'       => $request->layanan,
                'keterangan'    => $request->keterangan,
                'nomor_antrian' => $save->layanans->kode.$nomor_antrian,
                'qrcode'        => $qrcode,
                'alasan'        => $save->alasan,
                'rating'        => $save->rating,
                'rating_value'  => $save->penilaian_rating,
            ]
        ]);
    }

    public function store_mobile(Request $request, Notification $notification){
        $validasi = [
            'provinsi_id'   => 'required',
            'provinsi'      => 'required',
            'satker_id'     => 'required',
            'satker'        => 'required',
            'user_id'       => 'nullable',
            'user'          => 'nullable',
            'no_hp'         => 'nullable',
            'tanggal_hadir' => 'required',
            'layanan_id'    => 'required',
            'layanan'       => 'required',
            'keterangan'    => 'nullable',
        ];

        $image = $request->file('image');
        if ($image) $validasi += ['image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg'];

        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return response()->json([
                'status'  => Response::HTTP_BAD_REQUEST,
                'message' => $validator->messages(),
                'data'    => $request->all()
            ]);
        }

        $tanggal_hadir  = Carbon::parse($request->tanggal_hadir)->format('Y-m-d');
        $date           = date('d-m-Y');
        $antrian_model  = AntrianModel::query()
        ->whereTanggalHadir($tanggal_hadir)
        ->whereSatkerId($request->satker_id)
        ->whereLayananId($request->layanan_id);

        $id             = $antrian_model->latest()->count();

        if ($antrian_model->whereUserId($request->user_id)->exists()){
            return response()->json([
                'status'    => Response::HTTP_CONFLICT,
                'message'   => 'Error Duplicate',
                'data'      => $request->all()
            ]);
        }

        $nomor_antrian  = sprintf("%03s", $id+1);
        $qrcode         = $date."|".$nomor_antrian."|".$request->satker."|".$request->layanan;

        $filename = null;
        if($image){
            $filename = str_replace(" ","-", date('d-m-Y-H:i:s')."-".$image->getClientOriginalName());
            Image::make($image->getRealPath())->resize(468, 249)->save('images/'.$filename);
        }

        //Store your file into directory and db
        $save = new AntrianModel();
        $save->provinsi_id      = $request->provinsi_id;
        $save->provinsi         = Str::of($request->provinsi)->trim;
        $save->satker_id        = $request->satker_id;
        $save->satker           = Str::of($request->satker)->trim;
        $save->user_id          = $request->user_id;
        $save->user             = $request->user;
        $save->tanggal_hadir    = Carbon::parse($tanggal_hadir)->format('Y-m-d');
        $save->layanan_id       = $request->layanan_id;
        $save->layanan          = Str::of($request->layanan)->trim;
        $save->keterangan       = $request->keterangan;
        $save->nomor_antrian    = $nomor_antrian;
        $save->qrcode           = $qrcode;
        $save->otp              = mt_rand(100000,999999);
        // $save->image            = ($image || $request->image) ? $filename : null;
        $save->image            = $filename;
        $save->save();

        LogActivity::addToLog('Menambahkan Booking Layanan');
        $menu = \DB::table('menu_notif')->where('menu','Tambah Booking Layanan')
                                        ->first();
        $usr = User::find($request->user_id);
        if($menu->status == 1 ){
            // $notif = auth()->user();
            $noted = 'Menambahkan Booking Layanan';
            $satker = $request->satker_id;
            $uuid = \Str::uuid()->toString();
            $arr = array('name'=> $request->user, 'email' => $usr->email, 'noted'=> $noted);
            \DB::table('notifications')->insert([
                'id' => $uuid,
                'type' => 'App\Notifications\NewUserNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $request->user_id,
                'data' => json_encode($arr),
                'read_at' => null,
                'satker_id'=> $request->satker_id,
            ]);
        }

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Success',
            'data'      => [
                'antrian_id'    => (int)$save->id,
                'provinsi_id'   => (int)$request->provinsi_id,
                'provinsi'      => $request->provinsi,
                'satker_id'     => (int)$request->satker_id,
                'satker'        => $request->satker,
                'layanan_id'    => (int)$request->layanan_id,
                'layanan'       => $request->layanan,
                'user_id'       => (int)$request->user_id,
                'user'          => $request->user,
                'no_hp'         => $request->no_hp,
                'tanggal_hadir' => Carbon::parse($tanggal_hadir)->format('Y-m-d'),
                'layanan'       => $request->layanan,
                'keterangan'    => $request->keterangan,
                'nomor_antrian' => $save->layanans->kode.$nomor_antrian,
                'qrcode'        => $qrcode,
                'image'         => $filename,
                'alasan'        => $save->alasan,
                'otp'           => "$save->otp",
                'rating'        => $save->rating,
                'rating_value'  => $save->penilaian_rating,
            ]
        ]);
    }

    public function list_history($id){
        $datas = AntrianModelMobile::with('layanans')->whereUserId($id)->latest()->get();

        $data = Collect([]);
        foreach ($datas as $dt) {
            $data->push([
                'antrian_id'    => (int)$dt->id,
                'provinsi_id'   => (int)$dt->provinsi_id,
                'provinsi'      => $dt->provinsi,
                'satker_id'     => (int)$dt->satker_id,
                'satker'        => $dt->satker,
                'layanan_id'    => (int)$dt->layanan_id,
                'layanan'       => $dt->layanan,
                'user_id'       => (int)$dt->user_id,
                'user'          => $dt->user,
                'no_hp'         => $dt->no_hp,
                'tanggal_hadir' => Carbon::parse($dt->tanggal_hadir)->format('Y-m-d'),
                'layanan'       => $dt->layanan,
                'keterangan'    => $dt->keterangan,
                'nomor_antrian' => $dt->layanans->kode.$dt->nomor_antrian,
                'qrcode'        => $dt->qrcode,
                'status'        => $dt->status,
                'alasan'        => $dt->alasan,
                'image'         => $dt->image,
                'otp'           => "$dt->otp",
                'alasan'        => $dt->alasan,
                'rating'        => $dt->rating,
                'rating_value'  => $dt->penilaian_rating,
            ]);
        }

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Success',
            'data'      => $data
        ]);
    }

    public function get_menu(Request $request){
        $data = LogActivitiesModel::query()
        ->when($request->id, function($q) use($request){
            return $q->whereIn('id',$request->id);
        })
        ->when($request->q, function($q) use($request){
            return $q->where('name', 'like', '%'.$request->q.'%');
        })
        ->get();

        $collect = collect();
        $data->each(function ($array) use($collect) {
            if (!$array || !$collect->contains('id', $array->subject))
            return $collect->push([
                'id' => $array->subject,
                'name' => $array->subject
            ]);
        });

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Success',
            'data'      => $collect->sort()->values()->all()
        ]);
    }

    public function ubah_jadwal(Request $request){
        $antrian_awal = AntrianModel::whereId($request->antrian_id)->first();

        // jika data id tidak ditemukan
        if (!$antrian_awal) return response()->json([
            'status'    => Response::HTTP_BAD_REQUEST,
            'message'   => "$request->antrian_id tidak ditemukan",
            'data'      => $antrian_awal,
        ]);

        $tanggal_hadir = Carbon::parse($request->tanggal_hadir)->format('Y-m-d');
        $antrian = AntrianModel::query()
            ->where('id','!=',$request->antrian_id)
            ->whereSatkerId($request->satker_id)
            ->whereLayananId($request->layanan_id)
            ->whereTanggalHadir($tanggal_hadir);
        $id = $antrian->latest()->first();
        $nomor_antrian  = sprintf("%03s", ($id ? $id->nomor_antrian : 0) + 1);
        $qrcode         = date('d-m-Y')."|".$nomor_antrian."|".$antrian_awal->satker."|".$antrian_awal->layanan;

        AntrianModel::findOrFail($request->antrian_id)->update([
            'alasan' => $request->alasan."",
            'status' => 6,
        ]);

        $antrian = AntrianModel::whereId($request->antrian_id)->first();

        //Store your file into directory and db
        $save = new AntrianModel();
        $save->provinsi_id      = $antrian->provinsi_id;
        $save->provinsi         = Str::of($antrian->provinsi)->trim;
        $save->satker_id        = $antrian->satker_id;
        $save->satker           = Str::of($antrian->satker)->trim;
        $save->user_id          = $antrian->user_id;
        $save->user             = $antrian->user;
        $save->tanggal_hadir    = Carbon::parse($tanggal_hadir)->format('Y-m-d');
        $save->layanan_id       = $antrian->layanan_id;
        $save->layanan          = Str::of($antrian->layanan)->trim;
        $save->keterangan       = $antrian->keterangan;
        $save->nomor_antrian    = $nomor_antrian;
        $save->qrcode           = $qrcode;
        $save->otp              = mt_rand(100000,999999);
        $save->image            = $antrian->image;
        $save->alasan           = $request->alasan;
        $save->save();

        $antrian = AntrianModel::whereId($request->antrian_id)->first();
        LogActivity::addToLog("Mengubah Jadwal Booking");

        $data = Collect([]);
        $data->push([
            'antrian_id'    => (int)$antrian->id,
            'provinsi_id'   => (int)$antrian->provinsi_id,
            'provinsi'      => $antrian->provinsi,
            'satker_id'     => (int)$antrian->satker_id,
            'satker'        => $antrian->satker,
            'layanan_id'    => (int)$antrian->layanan_id,
            'layanan'       => $antrian->layanan,
            'user_id'       => (int)$antrian->user_id,
            'user'          => $antrian->user,
            'no_hp'         => $antrian->no_hp,
            'tanggal_hadir' => Carbon::parse($save->tanggal_hadir)->format('Y-m-d'),
            'layanan'       => $antrian->layanan,
            'keterangan'    => $antrian->keterangan,
            'nomor_antrian' => $antrian->nomor_antrian,
            'qrcode'        => $antrian->qrcode,
            'status'        => $antrian->status,
            'alasan'        => $antrian->alasan,
            'image'         => $antrian->image,
            'alasan'        => $antrian->alasan,
            'alasan_pindah' => "Diubah ke tanggal $tanggal_hadir"
        ]);

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => "Tanggal diubah ke tanggal $tanggal_hadir",
            'data'      => $data,
        ]);
    }

    public function membatalkan_booking(Request $request){
        AntrianModel::findOrFail($request->id)->update([
            'status' => "3",
            'otp' => null
        ]);
        LogActivity::addToLog("Membatalkan Antrian");

        $antrian = AntrianModel::whereId($request->id)->first();
        $data = Collect([]);
        $data->push([
            'antrian_id'    => (int)$antrian->id,
            'provinsi_id'   => (int)$antrian->provinsi_id,
            'provinsi'      => $antrian->provinsi,
            'satker_id'     => (int)$antrian->satker_id,
            'satker'        => $antrian->satker,
            'layanan_id'    => (int)$antrian->layanan_id,
            'layanan'       => $antrian->layanan,
            'user_id'       => (int)$antrian->user_id,
            'user'          => $antrian->user,
            'no_hp'         => $antrian->no_hp,
            'tanggal_hadir' => Carbon::parse($antrian->tanggal_hadir)->format('Y-m-d'),
            'layanan'       => $antrian->layanan,
            'keterangan'    => $antrian->keterangan,
            'nomor_antrian' => $antrian->nomor_antrian,
            'qrcode'        => $antrian->qrcode,
            'status'        => $antrian->status,
            'alasan'        => $antrian->alasan,
            'image'         => $antrian->image,
            'alasan'        => $antrian->alasan,
            'rating'        => $antrian->rating,
            'rating_value'  => $antrian->penilaian_rating,
            'otp'           => "$antrian->otp",
        ]);

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'id '.$request->id.' sudah dibatalkan',
            'data'      => $data
        ]);
    }
}