<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\AntrianModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use PDF;

class ReportManagementController extends Controller
{
    //
    private $title = 'Report Management System';
    private $li_1 = 'Index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:Report Management System');
    }

    public function index(Request $request){
        $title['title'] = $this->title;
        $title['li_1'] =  $this->li_1;
        $satker = Auth::user();

        return view('report_management_system.index', $title, compact(['satker']));
    }

    public function list_report_management_system(Request $request){
        return Datatables::of(
            $this->models($request)
        )
        ->addColumn('roles', function ($row){
            if ($row->user_id == null) return "Guest";
            return $row->userRole->roles[0]->name;
        })
        ->make(true);
    }

    public function models($request)
    {
        $authUser = Auth::user();
        return AntrianModel::query()
        ->when($request->provinsi_id, function($q) use($request){
            $provinsi_id = is_array($request->provinsi_id) ? $request->provinsi_id : explode(',', $request->provinsi_id);
            return $q->whereIn('provinsi_id', $provinsi_id);
        })
        ->when($request->satker_id, function($q) use($request){
            $satker_id = is_array($request->satker_id) ? $request->satker_id : explode(',', $request->satker_id);
            return $q->whereIn('satker_id', $satker_id);
        })
        ->when($request->layanan_id, function($q) use($request){
            $layanan_id = is_array($request->layanan_id) ? $request->layanan_id : explode(',', $request->layanan_id);
            return $q->whereIn('layanan_id', $layanan_id);
        })
        ->when($authUser->roles[0]->name == 'admin', function($q) use($authUser){
            return $q->whereSatkerId($authUser->satker_id);
        })
        ->when($request->users, function($q) use($request){
            return $q->where('user','like','%'.$request->users.'%');
        })
        ->when($request->role_id || $request->role_id != null, function($q) use($request){
            if ($request->role_id == "null") return;
            if ($request->role_id == 0)
            return $q->whereNull('user_id');

            return $q->whereUserId($request->role_id);
        })
        ->when($request->tanggal_awal, function($q) use($request){
            return $q->where('tanggal_hadir','>=', Carbon::parse($request->tanggal_awal)->format('Y-m-d'));
        })
        ->when($request->tanggal_akhir, function($q) use($request){
            return $q->where('tanggal_hadir','<=', Carbon::parse($request->tanggal_akhir)->format('Y-m-d 23:59:59'));
        })
        ->when($request->status !== null, function($q) use($request){
            return $q->whereStatus($request->status);
        })
        ->when($request->order, function($q) use($request){
            switch ($request->order[0]['column']) {
                case '1': $order = 'satker'; break;
                case '2': $order = 'nomor_antrian'; break;
                case '3': $order = 'user'; break;
                case '4': $order = 'layanan'; break;
                case '5': $order = 'tanggal_hadir'; break;
                case '6': $order = 'keterangan'; break;
                case '7': $order = 'status'; break;
                default: $order = 'provinsi'; break;
            }
            return $q->orderBy($order ,$request->order[0]['dir']);
        })
        ->whereYear('created_at', date('Y'))
        ->get();
    }

    public function pdf(Request $request)
    {
        $datas = $this->models($request);
        $satker = Auth::user();
        $user['name']     = "Kejati DKI Jakarta";
        $user['address']  = "Jl. H. R. Rasuna Said No.2, RT.5/RW.4, Kuningan Tim., Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12950";
        $satker = $satker->roles[0]->name == 'super-admin' ? $user : $satker->satker;

        $pdf = PDF::loadview('report_management_system.pdf',[
                'name'   => 'Management',
                'satker' => $satker,
                'datas'  => $datas,
                'filter' => $request->all()
            ]
        )->setPaper('F4');

        LogActivity::addToLog('Donwload Report Management');
        return $pdf->download('Laporan-Report-Management-PDF');
    }
}
