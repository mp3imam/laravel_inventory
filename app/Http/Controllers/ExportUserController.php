<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use PDF;

class ExportUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $title = 'Export List Users';
    private $li_1 = 'Index';

    public function index(Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        return view('export_users.index', $title);
    }

    public function list(Request $request){
        return Datatables::of(
            $this->models($request)
        )
            ->addColumn('role_user', function ($row){
                return $row->roles[0]->name;
            })
            ->addColumn('activity_date', function ($row){
                return $row->log ? $row->log->created_at : "-";
            })
            ->addColumn('user_activity', function ($row){
                return $row->log ? $row->log->subject : "-";
            })
            ->rawColumns(['role_user'])
            ->make(true);
    }

    public function models($request)
    {
        return User::with(['roles','log'])
        ->when($request->users_id, function($q) use($request){
            $users_id = is_array($request->users_id) ? $request->users_id : explode(',', $request->users_id);
            return $q->whereIn('id', $users_id);
        })
        ->when($request->email, function($q) use($request){
            return $q->where('email','like','%'.$request->email.'%');
        })
        ->when($request->role_id, function($q) use($request){
            $q->whereHas('roles', function($q) use($request){
                return $q->whereIn('id',$request->role_id);
            });
        })
        ->when($request->order, function($q) use($request){
            switch ($request->order[0]['column']) {
                case '1': $order = 'email'; break;
                default: $order = 'id'; break;
            }
            return $q->orderBy($order ,$request->order[0]['dir']);
        })
        ->get();
    }

    public function pdf(Request $request)
    {
        $datas = $this->models($request);
        $satker = Auth::user();
        $user['name']     = "Kejati DKI Jakarta";
        $user['address']  = "Jl. H. R. Rasuna Said No.2, RT.5/RW.4, Kuningan Tim., Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12950";
        $satker = $satker->roles[0]->name == 'super-admin' ? $user : $satker->satker;

        $pdf = PDF::loadview('export_users.pdf',[
                'name'   => 'Export List User',
                'satker' => $satker,
                'datas'  => $datas
            ]
        )->setPaper('F4');

        LogActivity::addToLog('Donwload Export Pengguna');
        return $pdf->download('Laporan-Export-Pengguna-PDF');
    }
}
