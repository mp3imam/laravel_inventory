<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\ActiveModel;
use App\Models\LayananModel;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Http\Response;

class ActiveLayananUsersController extends Controller
{
    //
    private $title = 'Active Layanan';
    private $li_1 = 'Index';

    public function index(Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        return view('active_layanan.index', $title);
    }

    public function list(Request $request){
        return Datatables::of(User::with(['roles','provinsi','satker'])
        ->whereHas('roles', function($q){
            return $q->where('name','Admin');
        })
        ->when($request->users_id, function($q) use($request){
            return $q->whereIn('id',$request->users_id);
        })
        ->when($request->provinsi_id, function($q) use($request){
            return $q->whereProvinsiId($request->provinsi_id);
        })
        ->when($request->satker_id, function($q) use($request){
            return $q->whereSatkerId($request->satker_id);
        })
        ->when($request->order, function($q) use($request){
            switch ($request->order[0]['column']) {
                case '2': $order = 'satker_id'; break;
                case '3': $order = 'provinsi_id'; break;
                default: $order = 'id'; break;
            }
            return $q->orderBy($order ,$request->order[0]['dir']);
        })
        ->whereNotNull('satker_id')
        ->get())
            ->addColumn('user', function ($row){
                return $row->name;
            })
            ->addColumn('role_user', function ($row){
                return $row->roles[0]->name;
            })
            ->addColumn('provinsis', function ($row){
                return $row->provinsi->name ?? '';
            })
            ->addColumn('satkers', function ($row){
                return $row->satker->name ?? '';
            })
            ->addColumn('action', function ($row){
                return '<button type="button" onclick="data_layanan('.$row->satker_id.',`'.$row->name.'`)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Ubah Layanan User</button>';
            })
            ->rawColumns(['action','user', 'provinsis', 'satkers', 'role_user'])
            ->make(true);
    }

    public function listLayanan(Request $request){
        return Datatables::of(
            LayananModel::leftJoin('active_layanan', function($join) use($request) {
                $join->on('layanan.id', 'active_layanan.layanan_id');
                $join->where('active_layanan.satker_id', $request->id);
            })
            ->whereNull('active_layanan.satker_id')
            ->orWhere('active_layanan.satker_id',$request->id)
            ->orderBy('layanan.id')
            ->select('*','layanan.id as layanan_id')
            ->get()
        )
            ->addColumn('action', function ($row) use($request){
                $checked = ($row->status == 0 || $row->status == null) ? '' : 'checked=""';
                return '<div class="form-check form-switch">
                            <input class="form-check-input checkbox_check'.$request->id.$row->layanan_id.'" type="checkbox" role="switch" onclick="save_layanan('.$request->id.','.$row->layanan_id.')" '.$checked.'>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $save = ActiveModel::firstOrNew([
            'satker_id'  => $request->get('satker_id'),
            'layanan_id' => $request->get('layanan_id'),
        ]);
        $save->status = $request->get('status');
        $save->save();
        $status = $request->get('status') == 0 ? "Menambahkan Layanan" : "Mengurangi Layanan";
        LogActivity::addToLog($status);

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $save
        ]);
    }
}
