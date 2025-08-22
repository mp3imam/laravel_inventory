<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\LayananModel;
use duncan3dc\Speaker\Providers\ResponsiveVoiceProvider;
use duncan3dc\Speaker\Providers\VoiceRssProvider;
use duncan3dc\Speaker\TextToSpeech;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Image;
use PDF;

class LayananController extends Controller
{
    private $title = 'Data Layanan';
    private $li_1 = 'Index';

    public function index(Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        if ($request->ajax()) {
            return DataTables::of(
                $this->models($request)
            )
                ->addColumn('icons', function ($row){
                    return $row->icon ?? '-';
                })
                ->addColumn('warnas', function ($row){
                    return '<div class="text-white text-center" style="width:100px; height:30px; background-color:'.$row->color.'">'.$row->color.'</div>';
                })
                ->addColumn('action', function ($row){
                    $actionBtn ='
                        <audio controls>
                            <source src="'.URL::asset("storage/suara_mp3/")."/".strtolower(str_replace(' ', '_', $row->name).".mp3").'" type="audio/mp3">
                        </audio>
                        <a href="'.route('layanans.edit', $row->id).'" class="btn btn-warning btn-sm button">
                            Ubah
                        </a>
                        <a href="#" type="button" onclick="alert_delete('.$row->id.',`'.$row->name.'`)" class="btn btn-danger btn-sm buttonDestroy">
                            Hapus
                        </a>
                        ';
                    return $actionBtn;
                })
                ->rawColumns(['warnas','action'])
                ->make(true);
        }

        return view('layanan.index', $title);
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

        return view('layanan.create', $title);
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
            'layanan'   => 'required',
            'kode'      => 'required|unique:layanan,kode|min:2|max:3',
            'deskripsi' => 'nullable',
        ];

        $image = $request->file('icon');
        if ($image) $validasi += ['icon' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg'];

        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return back()->withErrors([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => $validator->messages()
            ]);
        }

        $path = public_path('storage/icons_layanans/');
        File::isDirectory($path) ? null : File::makeDirectory($path, 0777, true, true);

        if($image){
            $filename = str_replace(" ","-", date('d-m-Y-H:i:s')."-".$image->getClientOriginalName());
            Image::make($image->getRealPath())->resize(512, 512)->save($path.$filename);
        }

        $save = new LayananModel();
        $save->name      = $request->layanan;
        $save->kode      = strtoupper($request->kode);
        $save->deskripsi = $request->deskripsi;
        $save->color     = $request->colorPicker;
        if ($image) $save->icon = $filename;
        $save->save();

        // add voice
        $pathMp3 = public_path('storage/suara_mp3/');
        File::isDirectory($pathMp3) ? null : File::makeDirectory($pathMp3, 0777, true, true);

        $provider = new VoiceRssProvider("77139aba4f8346ad876d5816e2416fe5","id", 1);
        $tts = new TextToSpeech("$save->name", $provider);
        $tts->save($pathMp3.strtolower(str_replace(" ","_",$save->name)).".mp3");

        LogActivity::addToLog("Menambahkan Layanan");

        return redirect('layanans');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        $detail = LayananModel::findOrFail($id)->first();

        return view('layanan.detail', $title, compact(['detail']));
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

        $detail = LayananModel::findOrFail($id);

        return view('layanan.edit', $title, compact(['detail']));
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
            'layanan' => 'required',
            'kode'    => 'required|min:2|max:3',
        ];

        $kodeSama = LayananModel::where('kode', $request->kode)->where('id','!=', $id)->first();
        if($kodeSama){
            return back()->withErrors([
                'status'    => 'Failed',
                'message'   => 'Kode Sama'
            ]);
        }

        $image = $request->file('icon');
        if ($image) $validasi += ['icon' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg'];
        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return back()->withErrors([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => $validator->messages()
            ]);
        }

        $update = [
            'name'      => $request->layanan,
            'kode'      => strtoupper($request->kode),
            'color'     => $request->colorPicker,
            'deskripsi' => $request->deskripsi
        ];

        $path = public_path('storage/icons_layanans/');
        File::isDirectory($path) ? null : File::makeDirectory($path, 0777, true, true);

        if($image){
            $filename = str_replace(" ","-", date('d-m-Y-H:i:s')."-".$image->getClientOriginalName());
            Image::make($image->getRealPath())->resize(512, 512)->save($path.$filename);
            $update += ['icon'  => $filename];
        }

        LayananModel::findOrFail($id)->update($update);

        // add voice
        $pathMp3 = public_path('storage/suara_mp3/');
        File::isDirectory($pathMp3) ? null : File::makeDirectory($pathMp3, 0777, true, true);

        $provider = new VoiceRssProvider("77139aba4f8346ad876d5816e2416fe5","id", 2);
        $tts = new TextToSpeech("$request->layanan", $provider);
        $tts->save($pathMp3.strtolower(str_replace(" ","_",$request->layanan)).".mp3");

        LogActivity::addToLog("Mengubah Layanan");

        return redirect('layanans');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = LayananModel::findOrFail($id)->delete();
        LogActivity::addToLog("Menghapus Layanan");

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $res
        ]);
    }

    public function models($request)
    {
        return LayananModel::query()
        ->when($request->layanan_id, function($q) use($request){
            $layanan_id = is_array($request->layanan_id) ? $request->layanan_id : explode(',', $request->layanan_id);
            return $q->whereIn('id', $layanan_id);
        })
        ->when($request->order, function($q) use($request){
            switch ($request->order[0]['column']) {
                case '1': $order = 'id'; break;
                case '2': $order = 'deskripsi'; break;
                default: $order = 'id'; break;
            }
            return $q->orderBy($order ,$request->order[0]['dir']);
        })
        ->get();
    }

    public function pdf(Request $request)
    {
        $datas = $this->models($request);
        $satker['name']     = "Kejati DKI Jakarta";
        $satker['address']  = "Jl. H. R. Rasuna Said No.2, RT.5/RW.4, Kuningan Tim., Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12950";

        $pdf = PDF::loadview('layanan.pdf',[
                'name'  => 'Report Daftar Layanan',
                'satker' => $satker,
                'datas' => $datas
            ]
        )->setPaper('F4');

        LogActivity::addToLog('Donwload Daftar Layanan');
        return $pdf->download('Laporan-Daftar-Layanans-PDF');
    }
}
