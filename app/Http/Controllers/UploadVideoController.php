<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\UploadVideoModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\returnSelf;

class UploadVideoController extends Controller
{
    private $title = 'Data Upload Video';
    private $li_1 = 'Index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:Upload Video');
    }

    public function index(Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        return view('upload_video.index', $title);
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        return DataTables::of(
            UploadVideoModel::with(['provinsis', 'satkers'])
            ->when($user->roles[0]->name != 'super-admin', function ($q) use($user){
                return $q->where('satker_id', $user->satker_id);
            })
            ->when($request->provinsi_id, function ($q) use ($request) {
                return $q->whereIn('provinsi_id', $request->provinsi_id);
            })
            ->when($request->category_id, function ($q) use ($request) {
                return $q->whereCategory((int)$request->category_id);
            })
            ->when($request->satker_id, function ($q) use ($request) {
                return $q->whereIn('satker_id', $request->satker_id);
            })
            ->when($request->status != null, function ($q) use ($request) {
                return $q->whereStatus($request->status);
            })
            ->when($request->role_id && $request->role_id != '--Pilih Semua--', function ($q) use ($request) {
                return $q->whereRole($request->role_id);
            })
            ->when($request->order, function ($q) use ($request) {
                switch ($request->order[0]['column']) {
                    case '2':
                        $order = 'provinsi_id';
                        break;
                    case '3':
                        $order = 'satker_id';
                        break;
                    case '4':
                        $order = 'category';
                        break;
                    case '5':
                        $order = 'video';
                        break;
                    case '6':
                        $order = 'status';
                        break;
                    default:
                        $order = 'id';
                        break;
                }
                return $q->orderBy($order, $request->order[0]['dir']);
            })
            ->get()
        )
        ->addColumn('provinsis', function ($row) {
            return $row->provinsis ? $row->provinsis->name : "SuperAdmin";
        })
        ->addColumn('satkers', function ($row) {
            return $row->satkers ? $row->satkers->name : "SuperAdmin";
        })
        ->addColumn('kategori', function ($row) {
            return $row->category == 1 ? UploadVideoModel::KATEGORY_SMARTTV : UploadVideoModel::KATEGORY_KIOSK;
        })
        ->addColumn('action', function ($row) use($user) {
            $btnAktif = "<button class='btn btn-light btn-sm button' onclick='video_status($row->id,1,$row->satker_id)'>Tidak Aktif</button>";
            if ($row->status == UploadVideoModel::STATUS_AKTIF)
            $btnAktif = "<button class='btn btn-success btn-sm button' onclick='video_status($row->id,0)'>Aktif</button>";
            $btnHapus = ' <a href="#" type="button" onclick="alert_delete('.$row->id.')" class="btn btn-danger btn-sm buttonDestroy">Hapus</a>';

            if ($user->roles[0]->name == 'super-admin' && $row->provinsi_id !== null)
            return $btnHapus;
            return $btnAktif.$btnHapus;
        })
        ->rawColumns([
            'provinsis',
            'satkers',
            'action'
        ])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title['title'] = 'Upload Video';
        $title['li_1'] = 'Index';

        return view('upload_video.create', $title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $data_aktif = UploadVideoModel::whereRole($user->roles[0]->name)->whereCategory($request->category_id)->count();

        if ($data_aktif > 4)
            return redirect()->route('upload_video.index')->with(['error','Kategory sudah lebih dari 5']);

        $max_upload = 1024 * 100;
        $validasi = [
            'category_id' => 'required',
            'video'       => "required|max:$max_upload",
        ];

        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails())
            return back()->withErrors($validator->messages());

        $name = time().'.'.$request->video->getClientOriginalExtension();
        $request->video->move(public_path('storage/uploads_video'), $name);

        $provinsi_id = null;
        $satker_id   = null;
        if($user->provinsi_id !== null){
            $provinsi_id = $user->provinsi_id;
            $satker_id   = $user->satker_id;
        }

        //Store your file into directory and db
        $save = new UploadVideoModel();
        $save->provinsi_id = $provinsi_id;
        $save->satker_id   = $satker_id;
        $save->category    = $request->category_id;
        $save->role        = $user->roles[0]->name;
        $save->video       = $name;
        $save->save();
        LogActivity::addToLog("Menambahkan Video");

        // return redirect()->route('upload_video.index')->with('success','Simpan video berhasil');
        return response()->json(['success'=>'Image Uploaded Successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        $detail = UploadVideoModel::findOrFail($id);

        return view('upload_video.edit', $title, compact(['detail']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $data_aktif = UploadVideoModel::whereRole($user->roles[0]->name)->whereCategory($request->category_id)->count();

        if ($user->roles[0]->name == 'super-admin' && $data_aktif > 4)
            return redirect()->route('upload_video.index')->with(['error','Kategory sudah lebih dari 5']);


        $validasi = [
            'category_id' => 'required',
            'video'       => 'nullable',
        ];
        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return response()->json([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => $validator->messages()
            ]);
        }

        UploadVideoModel::findOrFail($id)->update([
            'category'  => $request->category_id,
            'video'     => $request->satker,
        ]);
        LogActivity::addToLog("Mengubah Upload Video");

        return redirect('upload_video');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = UploadVideoModel::whereId($id)->first()->video;
        $path = str_replace(asset('/'), public_path('/'), $file);

        // hapus file video
        if (File::exists(($path)))
        File::delete($path);
        $delete = UploadVideoModel::findOrFail($id)->delete();
        LogActivity::addToLog("Menghapus Upload Video");

        return response()->json([
            'status'  => Response::HTTP_OK,
            'message' => $delete
        ]);
    }

    public function video_status(Request $request)
    {
        if ($request->status == 0){
            $response = UploadVideoModel::find($request->id)->update([
                'status' => $request->status
            ]);
            return response()->json([
                'data'   => $response,
                'status' => "OK",
            ]);
        }

        if ($request->satker_id == 'undefined'){
            $aktif = UploadVideoModel::where('status','1')->whereNull('satker_id');
            $max = 3;
        }else{
            $aktif = UploadVideoModel::where('status','1')->where('satker_id',(int)$request->satker_id);
            $max   = 2;
        }

        $response = "";
        if ($aktif->count() < $max)
            $response = UploadVideoModel::find($request->id)->update([
                'status' => $request->status
            ]);

        $status = Response::HTTP_OK;
        if (!$status)
        $status = Response::HTTP_CONFLICT;

        return response()->json([
            'data'   => $response,
            'status' => $status,
            'max'    => $max
        ]);
    }
}