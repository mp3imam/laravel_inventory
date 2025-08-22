<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\LogActivitiesModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use PDF;

class LogActivitiesController extends Controller
{
    //
    private $title = 'Log Activities Users';
    private $li_1 = 'Index';

    public function index(Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        return view('logs.index', $title);
    }

    public function list(Request $request){
        return Datatables::of(
            $this->models($request)
        )
            ->addColumn('user', function ($row){
                return $row->users->name;
            })
            ->make(true);
    }

    public function models($request)
    {
        return LogActivitiesModel::with(['users'])
        ->when($request->user_id, function($q) use($request){
            $user_id = is_array($request->user_id) ? $request->user_id : explode(',', $request->user_id);
            return $q->whereIn('user_id', $user_id);
        })
        ->when($request->menu, function($q) use($request){
            return $q->whereSubject($request->menu);
        })
        ->when($request->tanggal_awal, function($q) use($request){
            return $q->where('created_at','>=', Carbon::parse($request->tanggal_awal)->format('Y-m-d'));
        })
        ->when($request->tanggal_akhir, function($q) use($request){
            return $q->where('created_at','<=', Carbon::parse($request->tanggal_akhir)->format('Y-m-d 23:59:59'));
        })
        ->when($request->order, function($q) use($request){
            switch ($request->order[0]['column']) {
                case '1': $order = 'url'; break;
                case '2': $order = 'method'; break;
                case '3': $order = 'agent'; break;
                case '4': $order = 'user_id'; break;
                case '5': $order = 'created_at'; break;
                default: $order = 'subject'; break;
            }
            return $q->orderBy($order ,$request->order[0]['dir']);
        })
        ->latest()
        ->get();
    }

    public function pdf(Request $request)
    {
        $datas = $this->models($request);
        $satker = Auth::user();
        $user['name']     = "Kejati DKI Jakarta";
        $user['address']  = "Jl. H. R. Rasuna Said No.2, RT.5/RW.4, Kuningan Tim., Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12950";
        $satker = $satker->roles[0]->name == 'super-admin' ? $user : $satker->satker;

        $pdf = PDF::loadview('logs.pdf',[
                'name'   => 'Logs Aktifitas Pengguna',
                'datas'  => $datas,
                'filter' => $request->all(),
                'satker' => $satker
            ]
        )->setPaper('F4');

        return $pdf->download('Laporan-Logs-PDF');
    }
}
