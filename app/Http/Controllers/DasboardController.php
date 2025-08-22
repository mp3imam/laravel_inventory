<?php

namespace App\Http\Controllers;

use App\Models\AntrianModel;
use App\Models\LogActivitiesModel;
use App\Models\logUserModel;
use App\Models\SatkerModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DasboardController extends Controller
{
    private $title = 'Cockpit Smart Monitoring';
    private $li_1 = 'Index';

    public function imam()
    {
        return view('imam');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        // Menghitung Jumlah Satker
        $jumlah_satker = (int)SatkerModel::get()->count();

        // Menghitung Jumlah Satker yang aktif
        $kiosk_aktif  = (int) LogActivitiesModel::where('created_at', '>', Carbon::now()->format('Y-m-d').' 00:00:00')->groupBy('url')->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->whereSubject('Membuka KiosK')->get()->count();

        // Menghitung Admin yang membuka Layanan
        $satker_aktif = (int) LogActivitiesModel::where('created_at', '>', Carbon::now()->format('Y-m-d').' 00:00:00')->groupBy('user_id')->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->where('Subject','Buka Booking Layanan')->where('user_id','!=',3)->orWhereNull('user_id')->get()->count();

        // Jumlah Tamu yang berkunjung
        //     $data_tamu = AntrianModel::rightJoin('satker', 'satker.id', 'antrian.satker_id')
        // ->where('tanggal_hadir', Carbon::now()->format('Y-m-d'))
        // ->orWhereNull('tanggal_hadir')
        // ->groupBy('satker.id')
        // ->orderBy('satker.id')
        // ->get(['satker.name', DB::raw("count(antrian.satker_id) as jumlah_tamu")]);

        // $antrian = AntrianModel::where('tanggal_hadir','2023-08-01')->groupBy('satker_id')->get();
        // $satker = SatkerModel::get();
        // dd(json_encode($antrian), json_encode($satker));

        // $data_tamu = AntrianModel::rightJoin('satker','satker.id','antrian.satker_id')->where('tanggal_hadir',Carbon::now()->format('Y-m-d'))->orWhereNull('tanggal_hadir')->groupBy('satker.id')->orderBy('satker.id')
        // ->toSql();
        // ->get(['name', DB::raw("count('satker_id') as jumlah_tamu")]);
        // dd($data_tamu);

        // DB::raw("SELECT count(ant.satker_id);
        // dd($data_tamu);

        // $data_tamu = DB::query("
        //     SELECT name,
        //         (SELECT count(ant.satker_id)
        //             FROM antrian as ant
        //             RIGHT JOIN satker as sat ON sat.id = ant.satker_id
        //             WHERE ant.tanggal_hadir='2023-08-03' AND ant.satker_id = satker.id
        //         ) as kehadiran
        //     FROM satker GROUP BY satker.id");
        // dd($data_tamu);

        $data_tamu = SatkerModel::query()
        ->select('satker.name', DB::raw("
            (SELECT count(ant.satker_id)
                FROM antrian as ant
                RIGHT JOIN satker as sat ON sat.id = ant.satker_id
                WHERE ant.tanggal_hadir = '".Carbon::now()->format('Y-m-d')."' AND ant.satker_id = satker.id
            ) as jumlah_tamu
        "))
        ->groupBy('satker.id')
        ->get();
        // dd($data_tamu);

        $satker_tamu = collect();
        $jumlah_tamu = collect();
        foreach ($data_tamu as $jtamu) {
            $satker_tamu->push($jtamu->name);
            $jumlah_tamu->push($jtamu->jumlah_tamu);
        }

        return view('dasboard.index', $title, compact(['jumlah_satker','kiosk_aktif','satker_aktif','satker_tamu','jumlah_tamu']));
    }

    public function jumlah_tamu()
    {
        // Jumlah Tamu yang berkunjung
        $data_tamu = AntrianModel::rightJoin('satker','satker.id','antrian.satker_id')->where('tanggal_hadir','2023-07-04')->orWhereNull('tanggal_hadir')->groupBy('satker.id')->orderBy('satker.id')->get(['name', DB::raw("count(satker_id) as jumlah_tamu")]);
        $satker_tamu = collect();
        $jumlah_tamu = collect();
        foreach ($data_tamu as $jtamu) {
            $satker_tamu->push($jtamu['name']);
            $jumlah_tamu->push($jtamu['jumlah_tamu']);
        }

        return response()->json([
            '' => $satker_tamu
        ]);
    }

    public function kiosk_aktif()
    {
        return LogActivitiesModel::whereSubject('Membuka KiosK')
        ->where('created_at', '>', Carbon::now()->format('Y-m-d').' 00:00:00')
        ->groupBy('url')
        ->get('url');
    }

    public function admin_aktif()
    {
        return LogActivitiesModel::with('users.satker')->whereSubject('Buka Booking Layanan')
        ->where('created_at', '>', Carbon::now()->format('Y-m-d').' 00:00:00')
        ->groupBy('user_id')
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
        ->get();
    }

    public function list_status_kiosk(Request $request)
    {
        $kiosk_aktif = $this->kiosk_aktif();
        $kode_satker_aktif = collect();
        foreach ($kiosk_aktif as $kiosk) {
            $kode_satker = Str::substr($kiosk['url'], -6);
            $kode_satker_aktif->push($kode_satker);
        }

        return Datatables::of(
            SatkerModel::query()
            ->when($request->status == 0, function($q) use($kode_satker_aktif){
                return $q->whereIn('kode_satker',$kode_satker_aktif);
            })
            ->when($request->status == 1, function($q) use($kode_satker_aktif){
                return $q->whereNotIn('kode_satker',$kode_satker_aktif);
            })->get()
        )
        ->addColumn('provinsis', function ($row){
            return $row->provinsis->name;
        })
        ->rawColumns(['provinisis'])
        ->make(true);
    }

    public function list_status_admin(Request $request)
    {
        $admin_aktif = $this->admin_aktif();
        $kode_admin_aktif = collect();
        foreach ($admin_aktif as $admin) {
            if ($admin['user_id'] != 3)
            $kode_admin_aktif->push($admin['users']['satker']['id']);
        }

        return Datatables::of(
            SatkerModel::query()
            ->when($request->status == 0, function($q) use($kode_admin_aktif){
                return $q->whereIn('id',$kode_admin_aktif);
            })
            ->when($request->status == 1, function($q) use($kode_admin_aktif){
                return $q->whereNotIn('id',$kode_admin_aktif);
            })->get()
        )
        ->addColumn('provinsis', function ($row){
            return $row->provinsis->name;
        })
        ->rawColumns(['provinisis'])
        ->make(true);
    }

    public function list_detail_pengunjung(Request $request)
    {
        return Datatables::of(
            AntrianModel::query()
            ->where('satker_id', $request->satker_id)
            ->where('tanggal_hadir', date('Y-m-d'))
            ->groupBy(['satker','layanan'])
            ->get(['*', DB::raw("count(layanan_id) jumlah_layanan")])
        )
        ->make(true);
    }

    function logs(Request $request) {
        $title['title'] = "Logs All User";
        $title['li_1'] = $this->li_1;

        return view('logs_users.index', $title);
    }

    public function cek_logs_lists(Request $request)
    {
        $data = logUserModel::query()
        ->when($request->tanggal, function($q) use($request){
            $q->whereCreatedAt($request->tanggal);
        })
        ->where('type', 'request')
        ->get(['content','created_at']);

        $collect = collect();
        foreach ($data as $dt => $d) {
            $collect->push(json_decode($d['content']));
        }

        return Datatables::of(
            $collect
        )
        ->make(true);
    }

}