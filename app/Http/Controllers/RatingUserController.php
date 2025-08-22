<?php

namespace App\Http\Controllers;

use App\Models\AntrianModel;
use App\Models\QuestionModel;
use App\Models\RatingModel;
use AshAllenDesign\ShortURL\Classes\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class RatingUserController extends Controller
{
    public function rating_user(Request $request){
        // Pengecekan jika url sudah kadaluarsa
        if (!$request->hasValidSignature()) {
            $data = "Kuesioner telah melewati batas waktu yang telah ditentukan";
            return view('rating.success', compact('data'));
        }

        // Pengecekan jika data sudah diisi
        $antrian = AntrianModel::whereId($request->id)
        ->whereNull('rating')
        ->first();
        if (!$antrian) {
            $data = "Terima kasih. Anda sudah mengisi Kuesioner";
            return view('rating.success', compact('data'));
        }

        $questions = QuestionModel::limit(14)->get();

        return view('rating.user', compact('antrian', 'questions'));
    }

    public function generateTempUrl(Request $request){
        // make generate temp url
        $generateUrl = URL::temporarySignedRoute('rating_user', now()->addWeek(1));

        // cek if http
        if (!request()->secure()) return $generateUrl;

        // make short url
        $builder = new Builder();
        return $builder->destinationUrl($generateUrl)->singleUse()->make()->default_short_url;
    }

    public function rating_answer(Request $request){
        // cek jika sudah ada, maka tidak bisa input lagi
        if (AntrianModel::whereId($request->id)
            ->whereNotNull('rating')
            ->exists()) {
            $data = "Terima kasih. Anda sudah Pernah mengisi Kuesioner";
            return view('rating.success', compact('data'));
        }

        $validator = Validator::make($request->all(),
        [
            'id'          => 'required',
            'provinsi_id' => 'required',
            'satker_id'   => 'required',
            'layanan_id'  => 'required',
            'user_id'     => 'nullable',
            'answer_1'    => 'nullable',
            'answer_2'    => 'nullable',
            'answer_3'    => 'nullable',
            'answer_4'    => 'nullable',
            'answer_5'    => 'nullable',
            'answer_6'    => 'nullable',
            'answer_7'    => 'nullable',
            'answer_8'    => 'nullable',
            'answer_9'    => 'nullable',
            'answer_10'    => 'nullable',
            'answer_11'    => 'nullable',
            'answer_12'    => 'nullable',
            'answer_13'    => 'nullable',
            'answer_14'    => 'nullable',
        ]);

        if ($validator->fails())
            return back()->withErrors($validator->messages());

        // Rating
        $rating_value = (int)$request->answer_1
            + (int)$request->answer_2
            + (int)$request->answer_3
            + (int)$request->answer_4
            + (int)$request->answer_5
            + (int)$request->answer_6
            + (int)$request->answer_7
            + (int)$request->answer_8
            + (int)$request->answer_9
            + (int)$request->answer_10
            + (int)$request->answer_11
            + (int)$request->answer_12
            + (int)$request->answer_13
            + (int)$request->answer_14 /
            QuestionModel::limit(14)->count();

        if ($rating_value == 1) $rating = "Tidak Memuaskan";
        if ($rating_value == 2) $rating = "Kurang Memuaskan";
        if ($rating_value == 3) $rating = "Cukup Memuaskan";
        if ($rating_value == 4) $rating = "Memuaskan";
        if ($rating_value == 5) $rating = "Sangat Memuaskan";
        if ($rating_value == 6) $rating = "Luar Biasa";

        // agar tidak bisa digunakan kembali
        AntrianModel::find($request->antrian_id)->update([
            'rating' => $rating,
            'penilaian_rating' => $rating_value
        ]);

        // simpan ke rating
        $save = new RatingModel();
        $save->provinsi_id = $request->provinsi_id;
        $save->satker_id   = $request->satker_id;
        $save->layanan_id  = $request->layanan_id;
        $save->user_id     = $request->user_id;
        $save->antrian_id  = $request->id;
        $save->answer_1    = $request->answer_1;
        $save->answer_2    = $request->answer_2;
        $save->answer_3    = $request->answer_3;
        $save->answer_4    = $request->answer_4;
        $save->answer_5    = $request->answer_5;
        $save->answer_6    = $request->answer_6;
        $save->answer_7    = $request->answer_7;
        $save->answer_8    = $request->answer_8;
        $save->answer_9    = $request->answer_9;
        $save->answer_10   = $request->answer_10;
        $save->answer_11   = $request->answer_11;
        $save->answer_12   = $request->answer_12;
        $save->answer_13   = $request->answer_13;
        $save->answer_14   = $request->answer_14;
        $save->rating      = $rating;
        $save->save();

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $save
        ]);
    }

    public function user_rating_success(Request $request)
    {
        $data = "Terima kasih. Anda sudah Pernah mengisi Kuesioner";
        return view('rating.success', compact('data'));

    }

}