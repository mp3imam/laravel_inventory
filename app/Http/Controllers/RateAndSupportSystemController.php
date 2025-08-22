<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\KeluhanModel;
use App\Models\KomentarModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use DataTables, Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RateAndSupportSystemController extends Controller
{
    private $title = 'Rate And Support System';
    private $li_1 = 'Index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:Rate And Support System');
    }


    public function index(Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;
        $role = Auth::user()->roles[0]->name;

        return view('keluhan.index', $title, compact(['role']));
    }

    public function list(Request $request){
        $data = KeluhanModel::with(['user','satker'])
        ->when($request->id, function($q) use($request){
            return $q->whereId($request->id);
        })
        ->when($request->nomor_keluhan, function($q) use($request){
            return $q->where('nomor_keluhan','like','%'.$request->nomor_keluhan.'%');
        })
        ->when($request->status != null, function($q) use($request){
            return $q->whereStatus($request->status);
        })
        ->when($request->tanggal, function($q) use($request){
            $tanggal = explode(" to ",$request->tanggal);
            if (!isset($tanggal[1])) return $q
            ->where('tanggal_hadir','>=', $tanggal[0])
            ->where('tanggal_hadir', '<=', $tanggal[0].' 23:59:59');

            return  $q
            ->where('tanggal_hadir','>=', $tanggal[0])
            ->where('tanggal_hadir', '<=', $tanggal[1].' 23:59:59');
        })
        ->when($request->order, function($q) use($request){
            switch ($request->order[0]['column']) {
                case '1': $order = 'satker_id'; break;
                case '2': $order = 'user_id'; break;
                case '3': $order = 'pertanyaan'; break;
                case '5': $order = 'status'; break;
                case '6': $order = 'rating'; break;
                case '7': $order = 'created_at'; break;
                default: $order = 'nomor_keluhan'; break;
            }
            return $q->orderBy($order ,$request->order[0]['dir']);
        })
        ->when($request->satker_id, function($q) use($request){
            return $q->whereSatkerId($request->satker_id);
        });

        if (Auth::user()->roles[0]->name == "admin")
        $data->whereSatkerId(Auth::user()->satker_id);

        return DataTables::of($data->get())
        ->addColumn('satker', function ($row){
            return $row->user->satker->name;
        })
        ->addColumn('user', function ($row){
            return $row->user->name;
        })
        ->addColumn('statuss', function ($row){
            switch ($row->status) {
                case '1': $status = KeluhanModel::STATUS_SUDAH_TERJAWAB; break;
                case '2': $status = KeluhanModel::STATUS_SUDAH_TUTUP;    break;
                default: $status  = KeluhanModel::STATUS_BELUM_TERJAWAB; break;
            }
            return $status;
        })
        ->addColumn('ratings', function ($row){
            switch ($row->rating) {
                case '1': $rating = KeluhanModel::RATING_TIDAK_MEMUASKAN;    break;
                case '2': $rating = KeluhanModel::RATING_KURANG_MEMUASKAN;   break;
                case '3': $rating = KeluhanModel::RATING_CUKUR_MEMUASKAN;    break;
                case '4': $rating = KeluhanModel::RATING_MEMUASKAN;          break;
                case '5': $rating = KeluhanModel::RATING_SANGAT_MEMUASKAN;   break;
                default : $rating = KeluhanModel::RATING_BELUM_ADA;          break;
            }
            return $rating;
        })
        ->addColumn('action', function ($row){
            switch ($row->status) {
                case "1":
                $action = '<a href="'.route('rate_support_sistem.edit', $row->id).'" class="btn btn-success btn-sm button">Response</a>';
                break;
                case "2":
                $action = '<a href="#" class="btn btn-light btn-sm button">Close</a>';
                break;
                default:
                $action = '<a href="'.route('rate_support_sistem.edit', $row->id).'" class="btn btn-primary btn-sm button">Jawab</a>';                break;
            }
            return $action;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title['title'] = 'Layanan';
        $title['li_1'] = 'Index';

        $user = Auth::user();

        return view('keluhan.create', $title, compact(['user']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $image          = $request->file('image');
        $validasi = ['pertanyaan' => 'required'];
        if ($image) $validasi += ['image' => 'required|image|mimes:jpg,png,jpeg,gif,svg'];

        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return response()->json([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => $validator->messages()
            ]);
        }

        $id             = KeluhanModel::where('created_at','>', date('Y-m-d'))->count();
        $nomor_antrian  = sprintf("%04s", $id+1);

        $path = public_path('storage/rate_support_sistem/');
        File::isDirectory($path) ? null : File::makeDirectory($path, 0777, true, true);

        if($image){
            $filename = str_replace(" ","-", date('Y-m-d H:i:s')."-".$image->getClientOriginalName());
            Image::make($image->getRealPath())->resize(468, 249)->save($path.$filename);
        }

        //Store your file into directory and db
        $save = new KeluhanModel();
        $save->nomor_keluhan    = $nomor_antrian;
        $save->satker_id        = $request->satker_id;
        $save->user_id          = $request->user_id;
        $save->pertanyaan       = $request->pertanyaan;
        if ($image)
        $save->image            = $filename;
        $save->save();

        return redirect()->route('rate_support_sistem.index');
    }

    public function list_komentars(Request $request){
        $data = KomentarModel::with(['user'])->whereKeluhanId($request->id)->get();
        return response()->json([
            "success" => 200,
            "data"    => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;


        // update Status Komentar
        KomentarModel::whereKeluhanId($id)->where('user_id','!=',Auth::user()->id)->update(['status' => "1"]);
        $keluhan = KeluhanModel::whereId($id)->first();

        return view('keluhan.edit', $title, compact(['keluhan']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validasi = [
            'user_id'    => 'required',
            'keluhan_id' => 'required',
            'komentar'   => 'required'
        ];

        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return response()->json([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => $validator->messages()
            ]);
        }

        // update status
        $status = Auth::user()->roles[0]->name == 'super-admin' ? "1" : "0";
        KeluhanModel::find($request->keluhan_id)->update(['status' => $status]);


        //Store your file into directory and db
        $save = new KomentarModel();
        $save->keluhan_id = $request->keluhan_id;
        $save->user_id    = $request->user_id;
        $save->komentar   = $request->komentar;
        $save->save();


        // if($image){
        //     $filename = str_replace(" ","-", date('Y-m-d-H:i:s')."-".$image->getClientOriginalName());
        //     Image::make($image->getRealPath())->resize(512, 512)->save('icons_layanans/'.$filename);
        //     $update += ['icon'  => $filename];
        // }

        if (!$save)
            return response()->json([
                'data'    => 400,
                'message' => $save
            ]);

        LogActivity::addToLog("Menambahkan Pertanyaan");
        return response()->json([
            'data'    => 200,
            'message' => $save
        ]);
    }

        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_keluhan(Request $request)
    {
        $validasi = ['keluhan_id' => 'required'];

        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return response()->json([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => $validator->messages()
            ]);
        }

        //Update Status DB Close
        $save = KeluhanModel::whereId($request->keluhan_id)->update([
            'status' => "2",
            'rating' => $request->rating
        ]);

        if (!$save)
            return response()->json([
                'data'    => 400,
                'message' => $save
            ]);

        LogActivity::addToLog("Memberikan Komentar");
        return response()->json([
            'data'    => 200,
            'message' => $save
        ]);
    }
}
