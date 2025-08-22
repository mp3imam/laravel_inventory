<?php

namespace App\Http\Controllers;

use App\Models\Integrate;
use Illuminate\Http\Request;
use App\Models\SatkerModel;
use Session, DB;

class IntegrateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $title['title'] = 'Integrasi Data';
        $title['li_1'] = '';
        return view('integrate.index', $title);
    }

    public function xml(Request $request)
    {
        $id = $request->id ?? null;
        $kode_satker = $request->kode_satker ?? null;
        $name = $request->name ?? null;
        $provinsi_id = $request->provinsi_id ?? null;
        $address = $request->address ?? null;

        $arr1 = array($id, $name,  $kode_satker , $provinsi_id, $address);
        $arr2 = array_filter($arr1);

        if(count($request->except('_token','_method')) === 0){
            Session::flash('message', 'Data tidak boleh kosong!');
            return redirect()->back();
        }
        elseif(count($request->except('_token','_method')) == 1){
            $data = implode('', $arr1);

        }
        else{
            $data = implode(",", array_filter($arr1));
        }
        $satker = SatkerModel::select(DB::raw($data))->get();

        return response()->view('integrate.data-satker', compact('satker','arr2'))
                ->header('Content-Type', 'text/xml');
    }

    public function xml_opt(){
        $title['title'] = 'XML';
        $title['li_1'] = '';
        return view('integrate.xml-opt', $title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Integrate  $integrate
     * @return \Illuminate\Http\Response
     */
    public function show(Integrate $integrate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Integrate  $integrate
     * @return \Illuminate\Http\Response
     */
    public function edit(Integrate $integrate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Integrate  $integrate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Integrate $integrate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Integrate  $integrate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Integrate $integrate)
    {
        //
    }
}
