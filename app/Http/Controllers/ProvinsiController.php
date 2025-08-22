<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\ProvinsiModel;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProvinsiController extends Controller
{
    private $title = 'Data Provinsi';
    private $li_1 = 'Index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:Provinsi');
    }

    public function index(Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        $res = $this->models($request);

        if ($request->ajax()) {
            return DataTables::of($res)
                ->addColumn('action', function ($row){
                    $actionBtn ='
                        <a href="'.route('provinsis.edit', $row->id).'" class="btn btn-warning btn-sm button">
                            Ubah
                        </a>
                        <a href="#" type="button" onclick="alert_delete('.$row->id.',`'.$row->name.'`)" class="btn btn-danger btn-sm buttonDestroy">
                            Hapus
                        </a>
                        ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('provinsi.index', $title, compact('res'));
    }

    public function models($request)
    {
        return ProvinsiModel::query()
        ->when($request->provinsi_id, function($q) use($request){
            $provinsi_id = is_array($request->provinsi_id) ? $request->provinsi_id : explode(',', $request->provinsi_id);
            return $q->whereIn('id', $provinsi_id);
        })
        ->when($request->order, function($q) use($request){
            return $q->orderBy('id' ,$request->order[0]['dir']);
        })
        ->get();
    }

    public function pdf(Request $request)
    {
        $datas = $this->models($request);
        $satker['name']     = "Kejati DKI Jakarta";
        $satker['address']  = "Jl. H. R. Rasuna Said No.2, RT.5/RW.4, Kuningan Tim., Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12950";

        $pdf = PDF::loadview('provinsi.pdf',[
                'name'  => 'Data Provinsi',
                'satker' => $satker,
                'datas' => $datas
            ]
        )->setPaper('F4');

        LogActivity::addToLog('Donwload Provinsi');
        return $pdf->download('Laporan-Provinsi-PDF');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title['title'] = 'Provinsi';
        $title['li_1'] = 'Index';

        return view('provinsi.create', $title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $save = new ProvinsiModel();
        $save->name = $request->provinsi;
        $save->save();
        LogActivity::addToLog("Menambahkan Provinsi");

        return redirect('provinsis');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        $detail = ProvinsiModel::findOrFail($id)->first();

        return view('provinsi.detail', $title, compact(['detail']));
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

        $detail = ProvinsiModel::findOrFail($id);

        return view('provinsi.edit', $title, compact(['detail']));
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
        $validator = Validator::make($request->all(), ['provinsi' => 'required']);

        if ($validator->fails()) {
            return response()->json([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => $validator->messages()
            ]);
        }

        ProvinsiModel::findOrFail($id)->update(['name' => $request->provinsi]);
        LogActivity::addToLog("Mengubah Provinsi");

        return redirect('provinsis');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = ProvinsiModel::findOrFail($id)->delete();
        LogActivity::addToLog("Menghapus Provinsi");

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $res
        ]);
    }
}
