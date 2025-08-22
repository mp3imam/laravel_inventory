<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\DocumentationModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DokumentasiController extends Controller
{
    private $title = 'Data Dokumentasi';
    private $li_1 = 'Index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {

    }

    public function index(Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        return view('documentasi.index', $title);
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        return DataTables::of(
            DocumentationModel::with(['user'])
            ->when($user->roles[0]->id != 3, function($q) use($user){
                return $q->where('user_id', $user->roles[0]->id);
            })
            ->when($request->role_id != 0, function($q) use($request){
                return $q->where('user_id', $request->role_id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
        )
        ->addColumn('user', function($q){
            return $q->user->name;
        })
        ->addColumn('action', function ($row) use($user) {
            $btnDownload = "<a target='_blank' href='$row->file' class='btn btn-success btn-sm'>Lihat</a> <a href='dokumentasi/$row->id/edit' type='button' class='btn btn-warning btn-sm'>Edit</a>";
            $btnHapus = ' <a target="_blank" type="button" onclick="alert_delete('.$row->id.')" class="btn btn-danger btn-sm">Hapus</a>';

            if ($user->roles[0]->id == 3)
            return $btnDownload.$btnHapus;
            return $btnDownload;
        })
        ->rawColumns(['action','user'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (Auth::user()->roles[0]->name != 'super-admin')
            return redirect()->route('dokumentasi.index')->with('error','Anda bukan Admin');


        $title['title'] = 'Tambah Dokumentasi';
        $title['li_1'] = 'Index';

        return view('documentasi.create', $title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validasi = [
            'role_id' => 'required',
            'judul'   => 'required',
            'file'    => 'required',
        ];

        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails())
            return back()->withErrors($validator->messages());

        $path = public_path('storage/dokumentasi/');
        File::isDirectory($path) ? null : File::makeDirectory($path, 0777, true, true);

        $name = time().'.'.$request->file->getClientOriginalExtension();
        $request->file->move(public_path('storage/dokumentasi'), $name);

        //Store your file into directory and db
        $save = new DocumentationModel();
        $save->user_id = $request->role_id;
        $save->judul   = $request->judul;
        $save->file    = $name;
        $save->save();
        LogActivity::addToLog("Admin Menambahkan Dokumentasi");

        return redirect('dokumentasi');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (Auth::user()->roles[0]->name != 'super-admin')
            return redirect()->route('dokumentasi.index')->with('error','Anda bukan Admin');

        $title['title'] = 'Edit Dokumentasi';
        $title['li_1'] = 'Index';

        $detail = DocumentationModel::findOrFail($id);

        return view('documentasi.edit', $title, compact(['detail']));
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
        $validasi = ['judul'   => 'required'];
        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return back()->withErrors([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => $validator->messages()
            ]);
        }

        $update = ['judul' => $request->judul];
        $user_id = DocumentationModel::where('id', $id)->first()->user_id;
        if ($request->role_id !== $user_id)
        $update += ['user_id'   => $request->role_id];

        if($request->file('file')){
            $name = time().'.'.$request->file->getClientOriginalExtension();
            $request->file->move(public_path('storage/dokumentasi'), $name);
            $update += ['file'  => $name];
        }

        DocumentationModel::findOrFail($id)->update($update);
        LogActivity::addToLog("Mengubah Layanan");

        return redirect('dokumentasi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = DocumentationModel::whereId($id)->first()->file;
        $path = str_replace(asset('/'), public_path('/'), $file);

        // hapus file video
        if (File::exists(($path)))
        File::delete($path);
        $delete = DocumentationModel::findOrFail($id)->delete();
        LogActivity::addToLog("Menghapus Documentasi");

        return response()->json([
            'status'  => Response::HTTP_OK,
            'message' => $delete
        ]);
    }
}