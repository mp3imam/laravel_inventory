<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\FAQModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title['title'] = 'Frequently Asked Questions';
        $title['li_1'] = '';

        $data = FAQModel::when($request->pertanyaan, function($q) use($request){
            return $q->where('question','like','%'.$request->pertanyaan.'%')
            ->orWhere('answer','like','%'.$request->pertanyaan.'%');
        })->paginate(10);

        return view('faq.index',$title, compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title['title'] = 'Frequently Asked Questions';
        $title['li_1'] = '';

        return view('faq.create', $title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'pertanyaan' => 'required',
            'jawaban'    => 'required'
        ]);

        $faq = new FAQModel();
        $faq->question = $request->pertanyaan;
        $faq->answer   = $request->jawaban;
        $faq->save();

        LogActivity::addToLog("Email Tersimpan");

        return redirect()->route('faq.index')->with('success', 'Berhasil disimpan');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $title['title'] = 'Frequently Asked Questions';
        $title['li_1'] = '';

        $data = FAQModel::whereId($id)->first();

        return view('faq.edit', $title, compact('data'));
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
        $this->validate($request, [
            'pertanyaan' => 'required',
            'jawaban'    => 'required'
        ]);

        FAQModel::findOrFail($id)->update([
            'question' => $request->pertanyaan,
            'answer'   => $request->jawaban
        ]);
        LogActivity::addToLog("FAQ Terupdate");

        return redirect()->route('faq.index')->with('success', 'Berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = FAQModel::findOrFail($id)->delete();
        LogActivity::addToLog("Menghapus FAQ");

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $res
        ]);
    }
}