<?php

namespace App\Http\Controllers;

use App\Models\AntrianModel;
use App\Models\QuestionModel;
use App\Models\RatingModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use AshAllenDesign\ShortURL\Classes\Builder;
use DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    private $title = 'Data Rating';
    private $li_1 = 'Index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:Rating');
    }


    public function index(Request $request){
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;
        $satker = Auth::user();

        return view('rating.index', $title, compact(['satker']));
    }

    public function list(Request $request){
        $admin = Auth::user();
        return DataTables::of(
            RatingModel::with(['provinsis','satkers','layanans','user'])
            ->when($request->provinsi_id, function($q) use($request){
                return $q->whereIn('provinsi_id',$request->provinsi_id);
            })
            ->when($request->satker_id, function($q) use($request){
                return $q->whereIn('satker_id',$request->satker_id);
            })
            ->when($admin->roles[0]->name == 'admin', function($q) use($admin){
                return $q->whereSatkerId($admin->satker->id);
            })
            ->when($request->layanan_id, function($q) use($request){
                return $q->whereIn('layanan_id',$request->layanan_id);
            })
            ->when($request->user_id, function($q) use($request){
                return $q->whereIn('user_id',$request->user_id);
            })
            ->when($request->nomor_antrian, function($q) use($request){
                return $q->whereHas('antrians', function($q) use($request){
                    $q->where('nomor_antrian','like','%'.$request->nomor_antrian.'%');
                });
            })
            ->when($request->tanggal_awal, function($q) use($request){
                return $q->where('created_at','>=', Carbon::parse($request->tanggal_awal)->format('Y-m-d'));
            })
            ->when($request->tanggal_akhir, function($q) use($request){
                return $q->where('created_at','<=', Carbon::parse($request->tanggal_akhir)->format('Y-m-d 23:59:59'));
            })
            ->when($request->rating, function($q) use($request){
                return $q->whereRating($request->rating);
            })
            ->when($request->order, function($q) use($request){
                switch ($request->order[0]['column']) {
                    case '1': $order = 'satker_id';   break;
                    case '2': $order = 'layanan_id';  break;
                    case '3': $order = 'user_id';     break;
                    case '3': $order = 'rating';      break;
                    default : $order = 'provinsi_id'; break;
                }
                return $q->orderBy($order ,$request->order[0]['dir']);
            })
            ->get())
            ->addColumn('provinsi', function ($row){
                return $row->provinsis->name;
            })
            ->addColumn('satker', function ($row){
                return $row->satkers->name;
            })
            ->addColumn('layanan', function ($row){
                return $row->layanans->name;
            })
            ->addColumn('user', function ($row){
                return $row->user ? $row->user->name : "Guest";
            })
            ->addColumn('antrian', function ($row){
                return $row->antrians->nomor_antrian;
            })
            ->rawColumns(['provinsis','satker','layanans','user','antrian'])
            ->make(true);
    }

}
