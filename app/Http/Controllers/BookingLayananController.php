<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Mail\SendMail;
use App\Mail\SendRating;
use App\Models\AntrianModel;
use App\Models\Emails;
use App\Models\PrinterSettingModel;
use App\Models\SatkerModel;
use App\Models\UploadBannerModel;
use App\Models\UploadVideoModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PDF, Config;;
use Session, Alert, DataTables;
use Illuminate\Support\Facades\URL;
use AshAllenDesign\ShortURL\Classes\Builder;
use Carbon\Carbon;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Illuminate\Support\Str;

class BookingLayananController extends Controller
{
    //
    private $title = 'Booking Layanan';
    private $li_1 = 'Index';

    public function index(Request $request){
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;
        $satker = Auth::user();
        LogActivity::addToLog("Buka Booking Layanan");

        return view('booking.index', $title, compact(['satker']));
    }

    public function listBookingLayanan(Request $request){
        return Datatables::of(
            $this->models($request, 'list')
        )
        ->addColumn('action', function ($row){
            $btnEdit = null;
            if ($row->alasan == null)
                $btnEdit = ' <btn type="button" class="btn btn-warning button" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="EditUlang('.$row->id.',`'.$row->satker_id.'`,`'.$row->satker.'`,`'.$row->layanan_id.'`,`'.$row->layanan.'`,`'.$row->user.'`,`'.$row->tanggal_hadir.'`)">'.AntrianModel::STATUS_EDIT.'</btn> ';

            $actionBtn = null;
            if (Carbon::parse($row->tanggal_hadir)->format('Y-m-d') > Carbon::now()->format('Y-m-d') && $row->status == AntrianModel::STATUS_MENUNGGU)
                return $btnEdit.' <btn type="button" onclick="batalUser('.$row->id.',`'.$row->nomor_antrian.'`,`'.$row->layanan.'`, 3, `'.$row->satker_id.'`, `'.$row->layanan_id.'`, `'.$row->user_id.'`)" class="btn btn-danger button">'.AntrianModel::STATUS_BATAL.'</btn>';

            // Kadaluwarsa
            if (Carbon::parse($row->tanggal_hadir)->format('Y-m-d') < Carbon::now()->format('Y-m-d') && $row->status == AntrianModel::STATUS_MENUNGGU)
                return ' <btn type="button" class="btn btn-outline-dark waves-light button">'.AntrianModel::STATUS_KADALUWARSA.'</btn>';

            if (Auth::user()->roles[0]->name == 'user' && $row->status == AntrianModel::STATUS_MENUNGGU)
                return $actionBtn .= $btnEdit.' <btn type="button" onclick="batalUser('.$row->id.',`'.$row->nomor_antrian.'`,`'.$row->layanan.'`, 3, `'.$row->satker_id.'`, `'.$row->layanan_id.'`, `'.$row->user_id.'`)" class="btn btn-danger button">'.AntrianModel::STATUS_BATAL.'</btn>';

            if ($row->status == AntrianModel::STATUS_PINDAH_TANGGAL && $row->alasan !== '-')
                return ' <btn type="button" class="btn btn-outline-dark waves-light button">'.AntrianModel::STATUS_PINDAH_TANGGAL.'</btn>';

            switch ($row->status) {
                case AntrianModel::STATUS_PROSESS: $actionBtn = ' <div class="prosess"><btn type="button" onclick="panggilProses('.$row->id.',`'.$row->nomor_antrian.'`,`'.$row->layanan.'`, 2, `'.$row->satker_id.'`, `'.$row->layanan_id.'`, `'.$row->user_id.'`, `'.$row->user.'`)" class="btn btn-primary btn-'.$row->nomor_antrian.' button">'.AntrianModel::STATUS_PROSESS.'</btn></div>'; break;
                case AntrianModel::STATUS_SELESAI: $actionBtn = ' <btn type="button" class="btn btn-outline-dark waves-light button">'.AntrianModel::STATUS_SELESAI.'</btn>'; break;
                case AntrianModel::STATUS_BATAL: $actionBtn = ' <btn type="button" class="btn btn-outline-dark waves-light button">'.AntrianModel::STATUS_BATAL.'</btn>'; break;
                case AntrianModel::STATUS_TIDAK_HADIR: $actionBtn = ' <btn type="button" class="btn btn-outline-dark waves-light button">'.AntrianModel::STATUS_TIDAK_HADIR.'</btn>'; break;
                default : $actionBtn = ' <btn type="button" onclick="cekAntrian('.$row->id.',`'.$row->nomor_antrian.'`,`'.$row->layanan.'`, 1, `'.$row->provinsi_id.'`, `'.$row->satker_id.'`, `'.$row->layanan_id.'`, `'.$row->user_id.'`)" class="btn btn-success btn-'.$row->nomor_antrian.' button">'.AntrianModel::STATUS_PANGGIL.'</btn>'. $btnEdit; break;
            }

            return $actionBtn;
        })
        ->rawColumns(['statuses','action'])
        ->make(true);
    }

    public function sendEmail($id, $user_id, $satker_id, $layanan_id, $user){
        $email = Emails::query()
        ->whereSatkerId($satker_id)
        ->whereLayananId($layanan_id)
        ->whereStatus(1)
        ->latest()
        ->first();

        if ($email == null) return null;
        if ($user_id){
            $userEmail = User::whereId($user_id)->first()->email;
        }else{
            if ($user == 'KIOSK') return;
            $userEmail = explode('|',$user)[2];
        }

        $mailData = [
            'title'   => $email->title,
            'body'    => $email->body,
            'satker'  => $email->satker_name,
            'layanan' => $email->layan->name,
            'generate_rating' => $this->generateTempUrl($id)
        ];

        if($email->hostname == null){
            //gmail
            Config::set('mail.mailers.smtp.username', $email->email);
            Config::set('mail.mailers.smtp.password', $email->password);
            $sendEmail = Mail::to($userEmail)->send(new SendRating($mailData));
        }else{
            $config = [
                'transport'  => 'smtp',
                'host'       => $email->hostname,
                'port'       => $email->port,
                'encryption' => env('MAIL_ENCRYPTION', 'ssl'),
                'username'   => $email->email,
                'password'   =>$email->password,
            ];
            Config::set('mail.mailers.smtp', $config);
            $sendEmail = Mail::to($userEmail)->send(new SendRating($mailData));
        }

        $sendEmail = !$sendEmail ? "0" : "1";
        AntrianModel::findOrFail($id)->update(['status_email' => $sendEmail]);

        // Mail::to($userEmail)->send(new SendRating($mailData));
        LogActivity::addToLog("Mengirimkan Email ke User");
        Alert::success('Success', 'Email Berhasil Dikirim.');

    }

    public function generateTempUrl($id){
        // make generate temp url
        $generateUrl = URL::temporarySignedRoute('rating_user', now()->addWeek(1),['id'=>$id]);

        // cek if http
        if (!request()->secure()) return $generateUrl;

        // make short url
        $builder = new Builder();
        return $builder->destinationUrl($generateUrl)->singleUse()->make()->default_short_url;
    }

    public function add(Request $request){
        $title['title'] = 'Booking Layanan';
        $title['li_1'] = 'Index';
        $satker = Auth::user();

        return view('booking.add', $title, compact('request', 'satker'));
    }

    public function cek_proses(Request $request){
        $messages = 'Sukses';
        $cekProsses = AntrianModel::query()
        ->whereSatkerId($request->satker_id)
        ->whereLayananId($request->layanan_id)
        ->whereTanggalHadir(Carbon::now()->format('Y-m-d'))
        ->where('status',"1")
        ->first();

        if ($cekProsses)
            $messages = 'Antrian Belum Selesai';

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $messages
        ]);
    }

    public function cek_antrian(Request $request){
        $messages = 'Sukses';
        $cekAntrian = AntrianModel::query()
        ->whereProvinsiId($request->provinsi_id)
        ->whereSatkerId($request->satker_id)
        ->whereLayananId($request->layanan_id)
        ->whereTanggalHadir(Carbon::now()->format('Y-m-d'))
        ->where('status',"0")
        ->first();

        if ($cekAntrian && $cekAntrian->nomor_antrian != $request->nomor_antrian)
            $messages = 'Silahkan Masukan Alasan';

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $messages
        ]);
    }

    public function update_alasan(Request $request){
        $update_alasan = AntrianModel::findOrFail($request->id)->update(['keterangan' => $request->keterangan]);

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $update_alasan
        ]);
    }

    public function ubah_tanggal(Request $request){
        AntrianModel::findOrFail($request->id)->update([
            'alasan' => "Diubah ke tanggal ".date('d-m-Y'),
            'status' => "6",
        ]);

        $tanggal_hadir = Carbon::parse($request->tanggal_hadir)->format('Y-m-d');
        $antrian = AntrianModel::query()
            ->whereSatkerId($request->satker_id)
            ->whereLayananId($request->layanan_id)
            ->whereTanggalHadir($tanggal_hadir);
        $id = $antrian->latest()->first();
        $nomor_antrian  = sprintf("%03s", ($id ? $id->nomor_antrian : 0) + 1);
        $qrcode         = date('d-m-Y')."|".$nomor_antrian."|".$request->satker."|".$request->layanan;
        LogActivity::addToLog("Mengubah Jadwal Booking");

        $update = AntrianModel::whereId($request->id)->first();

        //Store your file into directory and db
        $save = new AntrianModel();
        $save->provinsi_id      = $update->provinsi_id;
        $save->provinsi         = Str::of($update->provinsi)->trim;
        $save->satker_id        = $update->satker_id;
        $save->satker           = Str::of($update->satker)->trim;
        $save->user_id          = $update->user_id;
        $save->user             = $update->user;
        $save->tanggal_hadir    = Carbon::parse($tanggal_hadir)->format('Y-m-d');
        $save->layanan_id       = $update->layanan_id;
        $save->layanan          = Str::of($update->layanan)->trim;
        $save->keterangan       = $update->keterangan;
        $save->nomor_antrian    = $nomor_antrian;
        $save->qrcode           = $qrcode;
        $save->otp              = mt_rand(100000,999999);
        $save->image            = $update->image;
        $save->alasan           = "-";
        $save->save();

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $save
        ]);
    }

    public function guest_booking(Request $request){
        $title['title'] = 'Booking Layanan';
        $title['li_1'] = 'Index';

        return view('booking.guest', $title, compact('request'));
    }

    public function guest_kiosk_booking(Request $request){
        $title['title'] = 'Booking Layanan';
        $title['li_1'] = 'Index';
        $satker = SatkerModel::whereKodeSatker($request->satker_id)->first();
        $banners = UploadBannerModel::whereCategory(2)->whereSatkerId($satker->id)->orWhereNull('satker_id')->orderBy('id')->get();
        $printout_bottom_kiosk = PrinterSettingModel::latest()->first()->bawah ?? 'Terima Kasih';

        if (!$satker) return abort('404');
        $antrians = $satker->only_active_layanans()->get();
        LogActivity::addToLog("Membuka Kiosk");

        return view('booking.kioska', $title, compact('request','satker', 'antrians','banners','printout_bottom_kiosk'));
    }

    public function video_play(Request $request){
        return response()->json([
            'status' => Response::HTTP_OK,
            'video'  => UploadVideoModel::whereSatkerId($request->satker_id)->whereStatus("1")->orWhereNull('provinsi_id')->whereStatus("1")->orderBy('id')->get(['id','video'])
        ]);
    }

    public function banner_play(Request $request){
        return response()->json([
            'status' => Response::HTTP_OK,
            'video'  => UploadBannerModel::whereCategory($request->category_id)->whereSatkerId($request->satker_id)->orWhereNull('satker_id')->orderBy('id')->get(['id','banner'])
        ]);
    }

    public function changeStatusAntrian(Request $request){
        $messages = AntrianModel::findOrFail($request->id)->update(['status' => $request->status]);
        if ($request->status == 2) $this->sendEmail($request->id, $request->user_id, $request->satker_id, $request->layanan_id, $request->user);
        switch ($request->status) {
            case '2': $status = AntrianModel::STATUS_PROSESS.' => '.AntrianModel::STATUS_SELESAI; break;
            case '4': $status = AntrianModel::STATUS_PANGGIL.' => '.AntrianModel::STATUS_TIDAK_HADIR; break;
            default : $status = AntrianModel::STATUS_PANGGIL.' => '.AntrianModel::STATUS_PROSESS; break;
        }
        LogActivity::addToLog($status);

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $messages
        ]);
    }

    public function models($request, $list)
    {
        return AntrianModel::query()
        ->when($list === 'list', function($q){
            return $q->where('status', '!=', "2");
        })
        ->when($request->satker, function($q) use($request){
            return $q->whereSatkerId($request->satker);
        })
        ->when($request->satker_id, function($q) use($request){
            $satker_id = is_array($request->satker_id) ? $request->satker_id : explode(',', $request->satker_id);
            return $q->whereIn('satker_id', $satker_id);
        })
        ->when($request->layanan_id, function($q) use($request){
            $layanan_id = is_array($request->layanan_id) ? $request->layanan_id : explode(',', $request->layanan_id);
            return $q->whereIn('layanan_id', $layanan_id);
        })
        ->when($request->user_role, function($q) use($request){
            $user = Auth::user()->roles[0]->name == 'admin' ? 'satker_id' : 'user_id';
            return $q->where($user, $request->user_role);
        })
        ->when($request->status !== null, function($q) use($request){
            return $q->whereStatus($request->status);
        })
        ->when($request->tanggal_awal, function($q) use($request){
            return $q->where('tanggal_hadir','>=', Carbon::parse($request->tanggal_awal)->format('Y-m-d'));
        })
        ->when($request->tanggal_akhir, function($q) use($request){
            return $q->where('tanggal_hadir','<=', Carbon::parse($request->tanggal_akhir)->format('Y-m-d'));
        })
        ->when($request->order, function($q) use($request){
            // Default Order BY
            if ($request->order[0]['column'] == "0")
                return  $q->orderBy('layanan')->orderBy('nomor_antrian')->orderBy('status');

            switch ($request->order[0]['column']) {
                case '1': $order = 'provinsi_id'; break;
                case '2': $order = 'satker_id'; break;
                case '3': $order = 'user_id'; break;
                case '4': $order = 'layanan_id'; break;
                case '5': $order = 'tanggal_hadir'; break;
                case '6': $order = 'keterangan'; break;
                case '7': $order = 'nomor_antrian'; break;
                case '8': $order = 'status'; break;
                default: $order = 'nomor_antrian'; break;
            }
            return $q->orderBy($order ,$request->order[0]['dir']);
        })
        ->get();
    }

    public function pdf(Request $request)
    {
        $datas = $this->models($request, 'pdf');
        $satker = Auth::user();
        $user['name']     = "Kejati DKI Jakarta";
        $user['address']  = "Jl. H. R. Rasuna Said No.2, RT.5/RW.4, Kuningan Tim., Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12950";
        $satker = $satker->roles[0]->name == 'super-admin' ? $user : $satker->satker;

        $pdf = PDF::loadview('booking.pdf',[
                'name'   => 'Booking Layanan',
                'satker' => $satker,
                'datas'  => $datas,
                'filter' => $request->all()
            ]
        )->setPaper('F4');

        LogActivity::addToLog('Donwload Antrian');
        return $pdf->download('Laporan-Antrian-PDF');
    }

    public function guest_pdf(Request $request)
    {
        // download antrian pdf
        $datas = AntrianModel::whereId($request->id)->first();
        $satker = $datas->satkers;

        $pdf = PDF::loadview('booking.guest_pdf',[
                'name'   => "Antrian $datas->nomor_antrian",
                'satker' => $satker,
                'datas'  => $datas
            ]
        );
        LogActivity::addToLog('Booking Layanan Guest');

        return $pdf->download("Laporan-Booking-Layanan-Guest-$datas->nomor_antrian-PDF");
    }
}