<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\SatkerModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class SatkerController extends Controller
{
    private $title = 'Data Satker';
    private $li_1 = 'Index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:Satker');
    }

    public function index(Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        if ($request->ajax()) {
            return DataTables::of(
                $this->models($request)
            )
                ->addColumn('provinsis', function ($row){
                    return $row->provinsis->name;
                })
                ->addColumn('action', function ($row){
                    $actionBtn ='
                        <a href="'.route('satkers.edit', $row->id).'" class="btn btn-warning btn-sm button">
                            Ubah
                        </a>
                        <a href="#" type="button" onclick="alert_delete('.$row->id.',`'.$row->name.'`)" class="btn btn-danger btn-sm buttonDestroy">
                            Hapus
                        </a>
                        ';
                    return $actionBtn;
                })
                ->rawColumns([
                    'provinsis',
                    'action'])
                ->make(true);
        }

        return view('satker.index', $title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title['title'] = 'Satker';
        $title['li_1'] = 'Index';

        return view('satker.create', $title);
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
            'provinsi_id' => 'required',
            'satker'      => 'required',
            'kode_satker' => 'nullable',
            'address'     => 'nullable',
        ];

        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return response()->json([
                'status'  => Response::HTTP_BAD_REQUEST,
                'message' => $validator->messages()
            ]);
        }

        //Store your file into directory and db
        $save = new SatkerModel();
        $save->provinsi_id  = $request->provinsi_id;
        $save->name         = $request->satker;
        $save->kode_satker  = $request->kode_satker;
        $save->address      = $request->address;
        $save->save();
        LogActivity::addToLog("Menambahkan Satker");

        return redirect('satkers');
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

        $detail = SatkerModel::findOrFail($id)->first();

        return view('satker.detail', $title, compact(['detail']));
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

        $detail = SatkerModel::findOrFail($id);

        return view('satker.edit', $title, compact(['detail']));
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
            'provinsi_id' => 'required',
            'satker'      => 'required',
            'kode_satker' => 'nullable',
            'address'     => 'nullable',
        ];
        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return response()->json([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => $validator->messages()
            ]);
        }

        SatkerModel::findOrFail($id)->update([
            'provinsi_id' => $request->provinsi_id,
            'name'        => $request->satker,
            'kode_satker' => $request->kode_satker,
            'address'     => $request->address
        ]);
        LogActivity::addToLog("Mengubah Satker");

        return redirect('satkers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = SatkerModel::findOrFail($id)->delete();
        LogActivity::addToLog("Menghapus Satker");

        return response()->json([
            'status'  => Response::HTTP_OK,
            'message' => $res
        ]);
    }

    public function models($request)
    {
        return SatkerModel::with(['provinsis'])
        ->when($request->provinsi_id, function($q) use($request){
            $provinsi_id = is_array($request->provinsi_id) ? $request->provinsi_id : explode(',', $request->provinsi_id);
            return $q->whereIn('provinsi_id', $provinsi_id);
        })
        ->when($request->satker_id, function($q) use($request){
            $satker_id = is_array($request->satker_id) ? $request->satker_id : explode(',', $request->satker_id);
            return $q->whereIn('id', $satker_id);
        })
        ->when($request->order, function($q) use($request){
            switch ($request->order[0]['column']) {
                case '2': $order = 'id'; break;
                case '3': $order = 'name'; break;
                case '4': $order = 'kode_satker'; break;
                case '5': $order = 'address'; break;
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

        $pdf = PDF::loadview('satker.pdf',[
                'name'  => 'Data Satker',
                'satker' => $satker,
                'datas' => $datas
            ]
        )->setPaper('F4');

        LogActivity::addToLog('Donwload Satkers');
        return $pdf->download('Laporan-Satkers-PDF');
    }
}