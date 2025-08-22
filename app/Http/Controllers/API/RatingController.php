<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AntrianModel;
use App\Models\QuestionModel;
use App\Models\RatingModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Question\Question;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $antrian = AntrianModel::whereId($request->antrian_id);
        if (!$antrian->exists())
            return response()->json([
                'status'  => Response::HTTP_CONFLICT,
                'message' => "Data Antrian Tidak Ditemukan"
            ]);

        if (!$antrian->whereNull('rating')->exists())
            return response()->json([
                'status'  => Response::HTTP_PRECONDITION_FAILED,
                'message' => "Anda sudah mengisi kuesioner"
            ]);

        if ($antrian->where('status','!=',"2")->exists())
            return response()->json([
                'status'  => Response::HTTP_LENGTH_REQUIRED,
                'message' => "Status Belum Selesai"
            ]);

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Success',
            'data'      => QuestionModel::limit(14)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $antrian = AntrianModel::whereId($request->antrian_id);
        if (!$antrian->exists())
            return response()->json([
                'status'  => Response::HTTP_CONFLICT,
                'message' => "Data Antrian Tidak Ditemukan"
            ]);

        if (!$antrian->whereNull('rating')->exists())
            return response()->json([
                'status'  => Response::HTTP_PRECONDITION_FAILED,
                'message' => "Anda sudah mengisi kuesioner"
            ]);

        if ($antrian->where('status','!=',"2")->exists())
            return response()->json([
                'status'  => Response::HTTP_LENGTH_REQUIRED,
                'message' => "Status Belum Selesai"
            ]);

        $validator = Validator::make($request->all(),
        [
            'antrian_id'  => 'required',
            'provinsi_id' => 'required',
            'satker_id'   => 'required',
            'layanan_id'  => 'required',
            'user_id'     => 'nullable',
            'answer'      => 'required',
        ]);

        if ($validator->fails())
            return response()->json([
                'status'  => Response::HTTP_BAD_REQUEST,
                'message' => "Data Tidak Boleh Kosong",
                'data'    => $validator
            ]);

        $total = 0;
        $answers = explode(',', $request->answer);
        foreach ($answers as $numStr) {
            $num = intval($numStr);
            $total += $num;
        }

        // Rating
        $rating_value = $total  / QuestionModel::limit(14)->count();

        if (round($rating_value) == 1) $rating = "Tidak Memuaskan";
        if (round($rating_value) == 2) $rating = "Kurang Memuaskan";
        if (round($rating_value) == 3) $rating = "Cukup Memuaskan";
        if (round($rating_value) == 4) $rating = "Memuaskan";
        if (round($rating_value) == 5) $rating = "Sangat Memuaskan";
        if (round($rating_value) == 6) $rating = "Luar Biasa";

        // agar tidak bisa digunakan kembali
        AntrianModel::find($request->antrian_id)->update([
            'rating' => $rating,
            'penilaian_rating' => round($rating_value)
        ]);

        // simpan ke rating
        $save = new RatingModel();
        $save->provinsi_id = $request->provinsi_id;
        $save->satker_id   = $request->satker_id;
        $save->layanan_id  = $request->layanan_id;
        $save->user_id     = $request->user_id;
        $save->antrian_id  = $request->antrian_id;
        $save->answer_1    = $answers[0];
        $save->answer_2    = isset($answers[1]) ? $answers[1] : null;
        $save->answer_3    = isset($answers[2]) ? $answers[2] : null;
        $save->answer_4    = isset($answers[3]) ? $answers[3] : null;
        $save->answer_5    = isset($answers[4]) ? $answers[4] : null;
        $save->answer_6    = isset($answers[5]) ? $answers[5] : null;
        $save->answer_7    = isset($answers[6]) ? $answers[6] : null;
        $save->answer_8    = isset($answers[7]) ? $answers[7] : null;
        $save->answer_9    = isset($answers[8]) ? $answers[8] : null;
        $save->answer_10   = isset($answers[9]) ? $answers[9] : null;
        $save->answer_11   = isset($answers[10]) ? $answers[10] : null;
        $save->answer_12   = isset($answers[11]) ? $answers[11] : null;
        $save->answer_13   = isset($answers[12]) ? $answers[12] : null;
        $save->answer_14   = isset($answers[13]) ? $answers[13] : null;
        $save->rating      = $rating;
        $save->save();

        $datas = AntrianModel::whereId($request->antrian_id)->first();
        $data = Collect([]);
        $data->push([
            'antrian_id'    => (int)$datas->id,
            'provinsi_id'   => (int)$datas->provinsi_id,
            'provinsi'      => $datas->provinsi,
            'satker_id'     => (int)$datas->satker_id,
            'satker'        => $datas->satker,
            'layanan_id'    => (int)$datas->layanan_id,
            'layanan'       => $datas->layanan,
            'user_id'       => (int)$datas->user_id,
            'user'          => $datas->user,
            'no_hp'         => $datas->no_hp,
            'tanggal_hadir' => Carbon::parse($datas->tanggal_hadir)->format('Y-m-d'),
            'layanan'       => $datas->layanan,
            'keterangan'    => $datas->keterangan,
            'nomor_antrian' => $datas->layanans->kode.$datas->nomor_antrian,
            'qrcode'        => $datas->qrcode,
            'status'        => $datas->status,
            'alasan'        => $datas->alasan,
            'image'         => $datas->image,
            'otp'           => "$datas->otp",
            'alasan'        => $datas->alasan,
            'rating'        => $datas->rating,
            'rating_value'  => $datas->penilaian_rating,
        ]);

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Success',
            'data'      => $data
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
