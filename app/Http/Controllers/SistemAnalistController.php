<?php

namespace App\Http\Controllers;

use App\Models\AntrianModel;
use App\Models\LayananModel;
use App\Models\LogActivitiesModel;
use App\Models\SatkerModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Facades\DB;

class SistemAnalistController extends Controller
{
    private $title = 'Cockpit Smart Monitoring';
    private $li_1 = 'Index';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        $layanans = LayananModel::get();

        $jumlah_tamu = collect();
        for($i=1;$i < 13; $i++){
            foreach ($layanans as $l) {
                $chartuser     = LayananModel::select('layanan', DB::raw("count(satker_id) as jumlah_tamu"))
                                ->rightJoin('antrian','antrian.layanan_id','layanan.id')
                                ->whereMonth('tanggal_hadir',$i)
                                ->whereYear('tanggal_hadir', date('Y'))
                                ->whereLayananId($l['id'])
                                ->orderBy(DB::raw('MONTH(tanggal_hadir)'))
                                ->orderBy('layanan_id')
                                ->first();

                $jumlah_tamu = $jumlah_tamu->push([
                    'name'   => $l['name'],
                    'bulan'  => $i,
                    'jumlah' => $chartuser->jumlah_tamu ?? 0
                ]);
            }
        }

        // Group data by name
        $groupedData = [];
        foreach ($jumlah_tamu as $item) {
            $name = $item['name'];
            $bulan = $item['bulan'];
            $jumlah = $item['jumlah'];

            if (!isset($groupedData[$name])) {
                $groupedData[$name] = [
                    'name' => $name,
                    'data' => array_fill(0, 12, 0)
                ];
            }

            $groupedData[$name]['data'][$bulan - 1] = $jumlah;
        }
        // dd($groupedData);

        // Convert grouped data to the desired format
        $finalData = array_values($groupedData);
        $finalData = json_decode(json_encode($finalData));

        return view('sistem_analist.index', $title, compact('finalData'));
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
        return LogActivitiesModel::whereSubject('Buka Booking Layanan')
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
            $kode_admin_aktif->push($admin['user_id']);
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

    public function jumlah_pengunjung_satker_perbulan(Request $request)
    {
        $satkers  = SatkerModel::get();
        $layanans = LayananModel::get();


        $satker_tamu = collect();
        foreach ($satkers as $s) {
            $satker_tamu->push($s['name']);
        }

        $jumlah_tamu = collect();
        foreach ($satkers as $satker) {
            foreach ($layanans as $l) {
                $chartuser     = LayananModel::rightJoin('antrian','antrian.layanan_id','layanan.id')
                                ->whereSatkerId($satker['id'])
                                ->whereLayananId($l['id'])
                                ->whereMonth('tanggal_hadir',(int) $request->bulan + 1)
                                ->whereYear('tanggal_hadir',date('Y'))
                                ->groupBy('layanan_id')
                                ->orderBy(DB::raw('MONTH(tanggal_hadir)'))
                                ->orderBy('satker_id')
                                ->orderBy('layanan_id')
                                // ->toSql();
                                ->first(['satker', 'layanan', DB::raw('MONTH(tanggal_hadir) bulan'), DB::raw('count(layanan_id) jumlah_layanan')]);
                // dd($chartuser, $request->bulan);
                $jumlah_tamu = $jumlah_tamu->push([
                    'name'   => $l['name'],
                    'jumlah' => $chartuser->jumlah_layanan ?? 0
                ]);
            }
        }
        // dd($jumlah_tamu);

        $transformedData = $jumlah_tamu->groupBy('name')->map(function ($items) {
            // dd($items);
            $jumlah = $items->pluck('jumlah')->toArray();
            return [
                'name' => $items->first()['name'],
                'data' => $jumlah
            ];
        })->values()->toArray();
        // dd($transformedData);

        return response()->json([
            'satkers' => $satker_tamu,
            'data'    => $transformedData
        ]);
    }
}
