<?php

namespace App\Http\Controllers\API;

use App\Models\Berita;
use App\Models\CategoryBerita;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageStoreRequest;
use App\Models\Image;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Image As Img;

class BeritaController extends Controller
{
    //

    public function getBerita(){

        $data = Berita::select('beritas.id','beritas.judul','beritas.berita','beritas.gambar','category_berita.name as kategori')
        ->join('category_berita', 'beritas.category_id','=','category_berita.id')
        ->orderBy('beritas.created_at','desc')
        ->get();

        return response()->json(['status' => 200, 'data'=> $data], 200);
    }
    public function imageStore(Request $request)
    {
        $this->validate($request, [
            'gambar' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'judul' => 'required'
        ]);
        $image_path = $request->file('gambar')->store('image');

        $data = Berita::create([
            'judul' => $request->judul,
            'berita' => $request->berita,
            'category_id' => $request->category_id,
            'gambar' => $image_path
        ]);

        return response($data, Response::HTTP_CREATED);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'judul' => 'required'
        ]);

        $input = $request->all();

        if ($image = $request->file('gambar')) {
            $destinationPath =  '/storage/berita/image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();

            $image->storeAs($destinationPath, $profileImage);
            $path = public_path('/image/' . $profileImage);
            $img = Img::make($image->getRealPath());
            $img->fit(200)->save($path);

            $input['gambar'] = "$profileImage";
        }

        $data = Berita::create($input);

        return response()->json([
            'status' => 200,
            'message' =>$data
        ], 200);
    }

    public function show($id){
        $data = Berita::find($id);
        if(!$data){
            return  response()->json([
                'status' => 404,
                'message' => 'Data Kosong'
            ]);
        }
        return  response()->json([
            'status' => 200,
            'message' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'judul' => 'required'
        ]);

        $berita = Berita::findOrFail($id);
        $image_path = public_path("storage/berita/image/". $berita->gambar);

        if(file_exists($image_path)){
            unlink($image_path);
        }

        $input = $request->all();

        if ($image = $request->file('gambar')) {
            $destinationPath = 'storage/berita/image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->storeAs($destinationPath, $profileImage);
            $input['gambar'] = "$profileImage";
        }else{
            unset($input['gambar']);
        }

        $berita->update($input);

        return  response()->json([
            'status' => 200,
            'message' => 'Berhasil Berhasil Diupdate !'
        ],200);
    }

    public function destroy($id){

        $data = Berita::findOrFail($id);
        $image_path = public_path("storage/berita/image/". $data->gambar);

        if(file_exists($image_path)){
            unlink($image_path);
        }

        $data->delete();

        return  response()->json([
            'status' => 200,
            'message' => 'Data Berhasil Dihapus !'
        ]);
    }

    public function byCategory(Request $request){

        $category = Berita::select('beritas.id','beritas.judul','beritas.gambar','category_berita.name as kategori')
                            ->join('category_berita', 'beritas.category_id','=','category_berita.id')
                            ->where('category_berita.name', $request->kategori)
                            ->get();


        if($category){
            return  response()->json([
                'status' => 200,
                'message' => 'Data Ditemukan!',
                'data' => $category
            ]);
        }
        else{
            return  response()->json([
                'status' => 404,
                'message' => 'Data tidak ditemukan!'
            ], 404);
        }

    }
}
