<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\UploadBannerModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\returnSelf;

class UploadBannersController extends Controller
{
    private $title = 'Data Upload Banner';
    private $li_1 = 'Index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:Upload Banner');
    }

    public function index(Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        return view('upload_banners.index', $title);
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        return DataTables::of(
            UploadBannerModel::query()
            ->when($user->roles[0]->name != 'super-admin', function ($q) use($user){
                return $q->where('satker_id', $user->satker_id);
            })
            ->when($request->category_id, function($q) use($request){
                return $q->whereCategory($request->category_id);
            })
            ->when($request->order, function ($q) use ($request) {
                switch ($request->order[0]['column']) {
                    case '1':
                        $order = 'banner';
                        break;
                    default:
                        $order = 'id';
                        break;
                }
                return $q->orderBy($order, $request->order[0]['dir']);
            })
            ->get()
        )
        ->addColumn('satkers', function ($row) {
            return $row->satkers ? $row->satkers->name : "SuperAdmin";
        })
        ->addColumn('kategori', function ($row){
            return $row->category == 1 ? UploadBannerModel::KATEGORY_SMARTTV : UploadBannerModel::KATEGORY_KIOSK;
        })
        ->addColumn('action', function ($row){
            return ' <a href="#" type="button" onclick="alert_delete('.$row->id.')" class="btn btn-danger btn-sm buttonDestroy">Hapus</a>';
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
        $title['title'] = 'Upload Banner';
        $title['li_1'] = 'Index';

        return view('upload_banners.create', $title);
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
        $data_aktif = UploadBannerModel::whereRole($user->roles[0]->name)->whereCategory($request->category_id)->count();

        // Login Superadmin
        if ($user->roles[0]->name == 'super-admin' & $data_aktif > 2)
            return redirect()->route('upload_banner.index')->with(['error','Kategory sudah lebih dari 3']);
        // Login Admin
        if ($user->roles[0]->name == 'admin' & $data_aktif > 1)
            return redirect()->route('upload_banner.index')->with(['error','Kategory sudah lebih dari 2']);

        $max_upload = 1024 * 100;
        $validasi = ['banner' => "required|max:$max_upload"];

        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails())
            return back()->withErrors($validator->messages());

        $name = time().'.'.$request->banner->getClientOriginalExtension();
        $request->banner->move(public_path('storage/uploads_banner'), $name);

        //Store your file into directory and db
        $save = new UploadBannerModel();
        $save->banner      = $name;
        $save->role        = $user->roles[0]->name;
        $save->category    = $request->category_id;
        if ($user->roles[0]->name == 'admin')
        $save->satker_id   = $user->satker_id;

        $save->save();
        LogActivity::addToLog("Menambahkan Banner");

        // return redirect()->route('upload_banners.index')->with('success','Simpan video berhasil');
        return redirect('upload_banner')->with(['success'=>'Image Uploaded Successfully']);
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

        $detail = UploadBannerModel::findOrFail($id);

        return view('upload_banners.edit', $title, compact(['detail']));
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
        $validasi = [
            'banner' => 'nullable',
        ];
        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return response()->json([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => $validator->messages()
            ]);
        }

        UploadBannerModel::findOrFail($id)->update([
            'banner'   => $request->satker,
            'category' => $request->category_id,
        ]);
        LogActivity::addToLog("Mengubah Upload Banner");

        return redirect('upload_banners');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = UploadBannerModel::whereId($id)->first()->video;
        $path = str_replace(asset('/'), public_path('/'), $file);

        // hapus file video
        if (File::exists(($path)))
        File::delete($path);
        $delete = UploadBannerModel::findOrFail($id)->delete();
        LogActivity::addToLog("Menghapus Upload Banner");

        return response()->json([
            'status'  => Response::HTTP_OK,
            'message' => $delete
        ]);
    }
}