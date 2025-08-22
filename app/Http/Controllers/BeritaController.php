<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\CategoryBerita;
use App\Models\TemporaryFile;

use DataTables, Auth, Alert, DB, Storage, Session, File;
use Image As Img;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $title['title'] = '';
        $title['li_1'] = '';
        $data = Berita::latest()->get();
        $query =Berita::latest();

        if ($request->ajax()) {

            if($request->get('judul')){
                $data =$data->where('judul', $request->get('judul'));
            }
            if($request->get('berita')) {
                $q = $query->where('berita', 'like', '%'.$request->get('berita').'%');
                $data = $q->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('gambar', function ($row) {
                    $url= $row->gambar;
                    return '<img src="'.$url.'"  class="avatar-md rounded-circle"/>';
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="'.route('berita.edit', $row->id).'" class="btn btn-info btn-sm">
                    <span class="icon-on"><i class="ri-edit-2-line align-bottom me-1"></i>Ubah</span>
                </a>
                <a href="#" class="button btn btn-danger btn-sm" data-id="' . $row->id .'"><span class="icon-on"><i class=" ri-delete-bin-5-line align-bottom me-1"></i>Hapus</span></a>';
                    return $actionBtn;
                })

                ->rawColumns(['gambar','action'])
                ->make(true);
        }
        // $data = $data;
        return view('berita.index', $title, compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $title['title'] = '';
        $title['li_1'] = '';
        $category = DB::table('category_berita')->get();
        return view('berita.create', $title, compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){

        if($request->hasFile('attachment')){
            $file = $request->file('attachment');
            $filename = date('YmdHis') . "." . $file->getClientOriginalExtension();
            $folder = 'public/berita/image';
            Session::put('folder', $folder); //save session  folder
            Session::put('filename', $filename);
            $file->storeAs($folder, $filename);

            TemporaryFile::create([
                'folder' => $folder,
                'filename' => $filename
            ]);
            return $folder;
        }
        return 'success';
    }

    public function save(Request $request){

        // $temporaryFile = TemporaryFile::where('folder', $request->attachment)->first();

        // dd($request->attachment);
        // $user = Berita::create([
        //     "judul" => $request->judul,
        //     "berita" => $request->berita,
        //     'gambar' => $temporaryFile->filename ?? '',
        //     "users_id" => auth()->user()->id
        // ]);
        // if($temporaryFile){
        //     $temporaryFile->delete();
        //     Storage::move(storage_path('app/public/berita/image/'. $request->attachment), public_path('storage/berita/image/'. $request->attachment));
        // }
        $temporaryFolder = Session::get('folder');
        $namefile = Session::get('filename');

        $temporary = TemporaryFile::where('folder', $temporaryFolder)->where('filename', $namefile)->first();

        if ($temporary) { //if exist

                Berita::create([
                    "judul" => $request->judul,
                    "berita" => $request->berita,
                    'gambar' => $namefile,
                    'category_id' => $request->category,
                    "users_id" => auth()->user()->id
                ]);

                //hapus file and folder temporary
                $path = storage_path() . 'app/public/berita/image/' . $temporary->filename;
                if (File::exists($path)) {

                    Storage::move(storage_path('app/public/berita/image/'. $temporary->filename), public_path('storage/berita/image/'. $temporary->filename));

                    File::delete($path);
                    rmdir(storage_path('app/public/berita/image/' . $temporary->folder));

                    //delete record in temporary table
                    $temporary->delete();
                    Alert::success('Success', 'Berita Berhasil Disimpan.');
                    return redirect()->route('berita.index');

                }
                Alert::success('Berhasil', 'Berita Berhasil Tersimpan.');

                return redirect()->route('berita.index');
            }

        Alert::success('Success', 'Berita Berhasil Disimpan.');

        return redirect()->route('berita.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $title['title'] = 'Form Ubah Berita';
        $title['li_1'] = 'form edit berita';

        $data = Berita::find($id);

        $category = CategoryBerita::get();
        return view('berita.edit', $title, compact('data','category'));
    }

    public function update(Request $request){

        if($request->hasFile('attachment')){
            $file = $request->file('attachment');
            $filename = date('YmdHis') . "." . $file->getClientOriginalExtension();
            $folder = 'public/berita/image';
            Session::put('upd-folder', $folder); //save session  folder
            Session::put('upd-filename', $filename);
            $file->storeAs($folder, $filename);

            TemporaryFile::create([
                'folder' => $folder,
                'filename' => $filename
            ]);
            return $folder;
        }
        return '';
    }

    public function updateBerita(Request $request)
    {
        $temporaryFolder = Session::get('upd-folder');
        $namefile = Session::get('upd-filename');
        $temporaryFile = TemporaryFile::where('folder',  $temporaryFolder)->where('filename', $namefile)->first();
        $user = Berita::where('id', $request->id)->update([
            "judul" => $request->judul,
            "berita" => $request->berita,
            'gambar' => $namefile ?? '',
            'category_id' => (int)$request->category,
            "users_id" => auth()->user()->id
        ]);
        if($temporaryFile){
            $temporaryFile->delete();
            Storage::move(storage_path('app/public/berita/image/'. $temporaryFile->folder), public_path('storage/berita/image/'. $temporaryFile->filename));
        }


        Alert::success('Success', 'Berita Berhasil Diubah.');

        return redirect()->route('berita.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $temporaryFolder = Session::get('folder');
        $namefile = Session::get('filename');

        $data = Berita::find($id);
        $image_path = storage_path("app/public/berita/image/". $namefile);

        if(file_exists($image_path)){
            unlink($image_path);
        }
        if (File::exists($image_path)) {
            File::delete($image_path);
            rmdir(storage_path('app/public/berita/image/' . $namefile));

            //delete record in table temporaryImage
            TemporaryFile::where([
                'folder' =>  $temporaryFolder,
                'filename' => $namefile
            ])->delete();

            return 'success';
        }

        $data->delete();

        return redirect()->back();
    }



}
