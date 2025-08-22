<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\AntrianModel;
use App\Models\SatkerModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AntrianController extends Controller
{
    private $title = 'Sistem Antrian';
    private $li_1 = 'Index';

    public function index(){
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        return view('antrian.filter');
    }

    public function save_appkey_kiosk(Request $request){
        $update_kiosk = SatkerModel::findOrFail($request->satker_id)->update(['kiosk' => $request->kiosk]);

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $update_kiosk
        ]);
    }

    public function sistem_antrian(Request $request){
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        $antrians = collect();
        $satker = SatkerModel::whereKodeSatker($request->satker_id)->first();

        $satker->only_active_layanans->each(function($q) use($antrians){
            $antrian_model = AntrianModel::query()
            ->whereTanggalHadir(date('Y-m-d'))
            ->whereSatkerId($q->satker_id)
            ->whereLayananId($q->layanans->id);

            $total_antrian    = $antrian_model->count();
            $antrian_saat_ini = $antrian_model->where('status','!=',"0")
            ->count();

            if ($antrian_saat_ini > $total_antrian)
                $antrian_saat_ini = $total_antrian;
                $antrian_saat_ini = sprintf("%03s", $antrian_saat_ini);

                if ($total_antrian == 0)
                $antrian_saat_ini = '000';
            $antrians->push([
                'id'                => $q->layanans->id,
                'nama_layanan'      => $q->layanans->name,
                'icon'              => $q->layanans->icon,
                'total_antrian'     => $total_antrian,
                'antrian_saat_ini'  => $q->layanans->kode.$antrian_saat_ini
            ]);
        });

        return view('antrian.index', compact(['title','antrians','satker']));
    }

    public function data_antrian(Request $request){
        $antrians = collect();
        $satker = SatkerModel::whereId($request->satker_id)->first();

        $satker->only_active_layanans->each(function($q) use($antrians){
            $antrian_model = AntrianModel::query()
            ->whereTanggalHadir(date('Y-m-d'))
            ->whereSatkerId($q->satker_id)
            ->whereLayananId($q->layanans->id);

            $total_antrian    = $antrian_model->count();
            $antrian_saat_ini = $antrian_model->where('status','=',"1")->first();
            $antrian_saat_ini = (!$antrian_saat_ini) ? 0 : (int)$antrian_saat_ini->nomor_antrian;

            if ($total_antrian == 0)
                $antrian_saat_ini = '000';
            if ($antrian_saat_ini > $total_antrian)
                $antrian_saat_ini = $total_antrian;
                $antrian_saat_ini = sprintf("%03s", $antrian_saat_ini);
            $antrians->push([
                'id'                => $q->layanans->id,
                'nama_layanan'      => $q->layanans->name,
                'kode_layanan'      => $q->layanans->kode,
                'color'             => $q->layanans->color,
                'icon'              => $q->layanans->icon,
                'total_antrian'     => $total_antrian,
                'antrian_saat_ini'  => $q->layanans->kode.$antrian_saat_ini
            ]);
        });

        return response()->json([
            'status' => Response::HTTP_OK,
            'data'   => $antrians
        ]);
    }

    public function cek_guest_kiosk(Request $request){
        $otp = AntrianModel::whereSatkerId($request->satker_id)
        ->whereOtp($request->digit1.$request->digit2.$request->digit3.$request->digit4.$request->digit5.$request->digit6)
        ->whereTanggalHadir(Carbon::now()->format('Y-m-d'));

        if (!$otp->first())
            return response()->json([
                'status' => Response::HTTP_CONFLICT,
                'data'   => $otp->first()
            ]);

        $opt_used = $otp;
        if ($opt_used->where('otp_used','1')->exists())
            return response()->json([
                'status' => Response::HTTP_IM_USED,
                'data'   => $opt_used->first()
            ]);

        $otp = AntrianModel::whereSatkerId($request->satker_id)
        ->whereOtp($request->digit1.$request->digit2.$request->digit3.$request->digit4.$request->digit5.$request->digit6)
        ->whereTanggalHadir(Carbon::now()->format('Y-m-d'))->first();

        $antrian = AntrianModel::whereSatkerId($request->satker_id)
        ->whereLayananId($otp->layanan_id)
        ->whereTanggalHadir(Carbon::now()->format('Y-m-d'));

        // cek nomor antrian
        $nomor_antrian = $antrian->latest()->first()->nomor_antrian;
        if ($antrian->where('status',"0")->exists())
            $nomor_antrian = $antrian->first()->nomor_antrian;

        // update otp sudah digunakan
        $otp->otp_used = 1;
        $otp->save();

        // saat nomor antrian sudah terlewat
        if ($otp->nomor_antrian < $nomor_antrian){
            $date           = date('d-m-Y');
            $antrian_model  = AntrianModel::query()
            ->whereTanggalHadir(Carbon::parse($otp->tanggal_hadir)->format('Y-m-d'))
            ->whereSatkerId($otp->satker_id)
            ->whereLayananId($otp->layanan_id);
            $id = $antrian_model->latest()->first();

            $nomor_antrian  = sprintf("%03s", ($id ? $id->nomor_antrian : 0) + 1);
            $qrcode         = $date."|".$nomor_antrian."|".$otp->satker."|".$otp->layanan;

            $save = new AntrianModel();
            $save->provinsi_id   = $otp->provinsi_id;
            $save->provinsi      = $otp->provinsi;
            $save->satker_id     = $otp->satker_id;
            $save->satker        = $otp->satker;
            $save->layanan_id    = $otp->layanan_id;
            $save->layanan       = $otp->layanan;
            $save->user_id       = $otp->user_id;
            $save->user          = $otp->user;
            $save->tanggal_hadir = Carbon::parse($otp->tanggal_hadir)->format('Y-m-d');
            $save->nomor_antrian = $nomor_antrian;
            $save->qrcode        = $qrcode;
            $save->otp           = mt_rand(100000,999999);
            $save->save();

            return response()->json([
                'status'  => Response::HTTP_CONTINUE,
                'data'    => $save,
                'nomor_antrian' => $save->layanans->kode.$save->nomor_antrian
            ]);
        }

        return response()->json([
            'status'  => Response::HTTP_OK,
            'data'    => $otp,
            'antrian' => $antrian->first(),
            'nomor_antrian' => $antrian->first()->layanans->kode.$nomor_antrian
        ]);
    }
}