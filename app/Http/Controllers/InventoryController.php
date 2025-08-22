<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\ActiveModel;
use App\Models\LayananModel;
use App\Models\InventoryModel;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Http\Response;
use Carbon\Carbon;

class InventoryController extends Controller
{
    //
    private $title = 'Inventory Stock';
    private $li_1 = 'Index';

    public function index(Request $request)
    {
        $title['title'] = $this->title;
        $title['li_1'] = $this->li_1;

        return view('inventory.index', $title);
    }

    public function list(Request $request){
        return Datatables::of(InventoryModel::
        when($request->categories_id, function($q) use($request){
            dd($request->categories_id);
            return $q->whereCategory($request->categories_id);
        })
        ->when($request->item_name, function($q) use($request){
            return $q->whereItemName($request->item_name);
        })
        ->when($request->order, function($q) use($request){
            switch ($request->order[0]['column']) {
                case '2': $order = 'categories_id'; break;
                case '3': $order = 'item_name'; break;
                case '4': $order = 'stock'; break;
                default: $order = 'id'; break;
            }
            return $q->orderBy($order ,$request->order[0]['dir']);
        })
        ->get())
            ->addColumn('action', function ($row){
                return '<button type="button" onclick="stock(' . $row->id . ', \'' . addslashes($row->category) . '\', \'' . addslashes($row->item_name) . '\', ' . $row->stock . ')" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Stock</button> ' .
                       '<button type="button" onclick="edit(' . $row->id . ', \'' . addslashes($row->category) . '\', \'' . addslashes($row->item_name) . '\', ' . $row->stock . ')" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">Edit</button> ' .
                       '<button type="button" onclick="alert_delete(' . $row->id . ')" class="btn btn-danger">Hapus</button>';
            })
            ->addColumn('tanggal_pembaruan', function ($row) {
                return Carbon::parse($row->updated_at)->format('d-m-Y H:i:s');
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
        $title['title'] = 'Layanan';
        $title['li_1'] = 'Index';

        // $category = LayananModel::all();
        // $category = ;

        return view('layanan.create', $title);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $kode = InventoryModel::query()->whereYear('created_at',date('Y'))->latest()->first();
        $save = InventoryModel::firstOrNew([
            'category'  => $request->get('kategori'),
            'item_name' => $request->get('nama_barang'),
            'kode' => 'DEVICE/' . date('dmY') . '/' . sprintf("%04s", ($kode ? $kode->nomor_antrian : 0) + 1),
            'stock' => $request->get('stock'),
        ]);
        $save->save();
        $status = $request->get('status') == 0 ? "Menambahkan Inventory" : "Mengurangi Inventory";
        LogActivity::addToLog($status);

        return redirect('inventory');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stock(Request $request)
    {
        $stock = InventoryModel::whereId($request->id)->value('stock');
        if ($request->aksi == 2 && $stock < $request->stock) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Stock tidak boleh minus'
            ], Response::HTTP_BAD_REQUEST);
        }
        $newStock = $request->aksi == 1
            ? $stock + $request->stock
            : $stock - $request->stock;

        $status = $request->aksi == 1 ? "Menjumlahkan Stock" : "Mengurangi Stock";

        $save = InventoryModel::whereId($request->id)->update([
            'stock' => $newStock,
        ]);
        LogActivity::addToLog($status);

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $save
        ]);
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

        $detail = LayananModel::findOrFail($id);

        return view('layanan.edit', $title, compact(['detail']));
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
        $validasi = [
            'layanan' => 'required',
            'kode'    => 'required|min:2|max:3',
        ];

        $image = $request->file('icon');
        if ($image) $validasi += ['icon' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg'];
        $validator = Validator::make($request->all(), $validasi);

        if ($validator->fails()) {
            return back()->withErrors([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => $validator->messages()
            ]);
        }

        $update = [
            'name'      => $request->layanan,
            'kode'      => strtoupper($request->kode),
            'color'     => $request->colorPicker,
            'deskripsi' => $request->deskripsi
        ];

        $path = public_path('storage/icons_layanans/');
        File::isDirectory($path) ? null : File::makeDirectory($path, 0777, true, true);

        if($image){
            $filename = str_replace(" ","-", date('d-m-Y-H:i:s')."-".$image->getClientOriginalName());
            Image::make($image->getRealPath())->resize(512, 512)->save($path.$filename);
            $update += ['icon'  => $filename];
        }

        LayananModel::findOrFail($id)->update($update);

        // add voice
        $pathMp3 = public_path('storage/suara_mp3/');
        File::isDirectory($pathMp3) ? null : File::makeDirectory($pathMp3, 0777, true, true);

        $provider = new VoiceRssProvider("77139aba4f8346ad876d5816e2416fe5","id", 2);
        $tts = new TextToSpeech("$request->layanan", $provider);
        $tts->save($pathMp3.strtolower(str_replace(" ","_",$request->layanan)).".mp3");

        LogActivity::addToLog("Mengubah Layanan");

        return redirect('layanans');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = InventoryModel::findOrFail($id)->delete();
        LogActivity::addToLog("Menghapus Inventory");

        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => $res
        ]);
    }

}
