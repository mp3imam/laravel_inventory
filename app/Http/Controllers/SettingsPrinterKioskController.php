<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\PrinterSettingModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\returnSelf;

class SettingsPrinterKioskController extends Controller
{
    private $title = 'Data Pengaturan Cetak Printer';
    private $li_1 = 'Index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:Pengaturan Cetak Printer');
    }

    public function index(Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        return view('printout_kiosk.index', $title);
    }

    public function list(Request $request)
    {
        return DataTables::of(
            PrinterSettingModel::query()
            ->when($request->bawah, function ($q) use ($request) {
                return $q->whereBawah($request->bawah);
            })
            ->when($request->order, function ($q) use ($request) {
                switch ($request->order[0]['column']) {
                    case '1':
                        $order = 'bawah';
                        break;
                    default:
                        $order = 'bawah';
                        break;
                }
                return $q->orderBy($order, $request->order[0]['dir']);
            })
            ->get()
        )
        ->addColumn('action', function ($row) {
            return '<a href="'.route('printout_kiosk.edit', $row->id).'" class="btn btn-warning btn-sm button">Ubah</a> <a href="#" type="button" onclick="alert_delete('.$row->id.')" class="btn btn-danger btn-sm buttonDestroy">Hapus</a>';
        })
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title['title'] = 'Pengaturan Cetak Printer';
        $title['li_1'] = 'Index';

        return view('printout_kiosk.create', $title);
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
            'bawah' => 'required'
        ];

        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails())
            return back()->withErrors($validator->messages());

        //Store your file into directory and db
        $save = new PrinterSettingModel();
        $save->bawah = $request->bawah;
        $save->save();
        LogActivity::addToLog("Menambahkan Pengaturan Cetak Kios K");

        return redirect('printout_kiosk');
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

        $detail = PrinterSettingModel::findOrFail($id);

        return view('printout_kiosk.edit', $title, compact(['detail']));
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
        $validasi = ['bawah' => 'required'];
        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return response()->json([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => $validator->messages()
            ]);
        }

        PrinterSettingModel::findOrFail($id)->update([ 'bawah' => $request->bawah ]);
        LogActivity::addToLog("Mengubah Pengaturan Cetak KiosK");

        return redirect('printout_kiosk');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = PrinterSettingModel::findOrFail($id)->delete();
        LogActivity::addToLog("Menghapus Upload Video");

        return response()->json([
            'status'  => Response::HTTP_OK,
            'message' => $delete
        ]);
    }
}