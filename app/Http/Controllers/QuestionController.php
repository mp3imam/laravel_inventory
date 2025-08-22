<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\QuestionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use DataTables;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Redirect;

class QuestionController extends Controller
{
    private $title = 'Data Pertanyaan';
    private $li_1 = 'Index';

    public function index(Request $request){
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        return view('question.index', $title);
    }

    public function list(Request $request){
        return DataTables::of(
            QuestionModel::query()
            ->when($request->question, function($q) use($request){
                return $q->where('pertanyaan','like','%'.$request->question.'%');
            })
            ->get())
            ->addColumn('action', function ($row){
                $actionBtn ='
                    <a href="'.route('questions.edit', $row->id).'" class="btn btn-warning btn-sm button">
                        Ubah
                    </a>
                    <a href="#" type="button" onclick="alert_delete('.$row->id.',`'.$row->pertanyaan.'`)" class="btn btn-danger btn-sm buttonDestroy">
                        Hapus
                    </a>
                    ';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (QuestionModel::count() > 13)
            return back()->withErrors('Pertanyaan tidak boleh lebih dari 14, silahkan hapus atau ubah');

        $title['title'] = 'Question';
        $title['li_1'] = 'Index';

        return view('question.create', $title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        ['question'    => 'required']);

        if ($validator->fails())
            return back()->withErrors($validator->messages());

        $save = new QuestionModel();
        $save->pertanyaan  = $request->question;
        $save->save();

        return redirect('questions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        $detail = QuestionModel::findOrFail($id);

        return view('question.edit', $title, compact(['detail']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),['question'    => 'required']);

        if ($validator->fails()) {
            return back()->withErrors($validator->messages());
        }

        $update = ['pertanyaan'  => $request->question];

        QuestionModel::findOrFail($request->id)->update($update);
        LogActivity::addToLog($request->path());

        return redirect('questions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $res = QuestionModel::findOrFail($id)->delete();
        LogActivity::addToLog($request->path());

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $res
        ]);
    }
}