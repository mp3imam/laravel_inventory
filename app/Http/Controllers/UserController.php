<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB, Hash, Alert, Validator, File;
use Illuminate\Support\Arr;
use App\Rules\PasswordRule;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserNotification;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title['title'] = 'Pengelolaan Pengguna';
        $title['li_1'] = '';

        $user = Auth::user();
        $data = User::query()
        ->when($user->roles[0]->name == 'admin', function($q) use($user){
            return $q->whereId($user->id);
        })
        ->when($request->nama, function($q) use($request){
            return $q->where('name','like','%'.$request->nama.'%');
        })
        ->when($request->email, function($q) use($request){
            return $q->where('email','like',"%".$request->email."%");
        })
        ->when($request->satker, function($q) use($request){
            return $q->whereHas('satker', function ($query) use ($request){
                return $query->where('name', 'like', '%'.$request->satker.'%');
            });
        })->paginate(10);

        return view('users.index',$title, compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 10);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title['title'] = '';
        $title['li_1'] = '';

        $roles = Role::when(Auth::user()->roles[0]->name == 'admin', function($q){
            return $q->whereIn('id',[1,2]);
        })->pluck('name','name')->all();

        return view('users.create',$title, compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $satker = ['provinsi' => 'nullable', 'satker' => 'nullable'];
        if ($request->roles == 'Admin')
        $satker = ['provinsi' => 'required', 'satker' => 'required'];
        $validator = $this->validate($request, [
            'name' => 'required',
            'nip' => 'required',
            $satker,
            'email' => 'required|email|unique:users,email',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make('123456');
        if ($request->roles == 'Admin'){
            $input['provinsi_id'] = $request->provinsi;
            $input['satker_id'] = $request->satker;
        }

        $user = User::create($input);
        $user->assignRole($request->input('roles'));


        Alert::success('Success', 'Data Berhasil Disimpan.');
        LogActivity::addToLog("Menambahkan User Baru");

        $menu = \DB::table('menu_notif')->where('role','admin')
                                        ->where('menu','Tambah Pengelolaan Pengguna')
                                        ->first();
        if($menu->status == 1 ){
            $notif = auth()->user();
            $noted = 'Menambahkan User Baru di menu pengelolaan';
            $notif->notify(new NewUserNotification($notif, $noted));
        }

        return redirect()->route('users.index')
                        ->withErrors(array('message' => 'field is required.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $title['title'] = '';
        $title['li_1'] = '';

        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('users.edit',$title,compact('user','roles','userRole'));
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
            'name' => 'required',
            'nip' => 'nullable',
            'email' => 'required',
            // 'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = User::find($id);

        if ($request->roles[0] == 'admin'){
            $user->satker_id    = $request->input('satker');
            $user->provinsi_id  = $request->input('provinsi');
        }
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));

        Alert::success('Success', 'Data Berhasil Diubah.');
        LogActivity::addToLog("Mengubah User");
        $menu = \DB::table('menu_notif')->where('role','admin')
                                        ->where('menu','Edit Pengelolaan Pengguna')
                                        ->first();
        if($menu->status == 1 ){
            $notif = auth()->user();
            $noted = 'Mengubah User di menu pengelolaan';
            $notif->notify(new NewUserNotification($notif, $noted));
        }

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        User::find($id)->delete();

        LogActivity::addToLog("Menghapus User");

        $menu = \DB::table('menu_notif')->where('role','admin')
                                        ->where('menu','Hapus Pengelolaan Pengguna')
                                        ->first();
        if($menu->status == 1 ){
            $notif = auth()->user();
            $noted = 'Menghapus User di menu pengelolaan';
            $notif->notify(new NewUserNotification($notif, $noted));
        }
        return redirect()->route('users.index');
    }

    public function profile(){

        $title['title'] = 'List Profile User';
        $title['li_1'] = 'Profile';


        $data = auth()->user();
        return view('users.profile', $title, compact('data'));
    }

    public function profile_update(Request $request){

        $validator = $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'nip' => 'required'
        ]);

        $id = auth()->user()->id;

        $result = [
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip

        ];
        User::where('id', $id)->update($result);

        Alert::success('Success', 'Profile Berhasil Diubah.');

        return redirect()->back();
    }

    public function changePassword(){

        $title['title'] = '';
        $title['li_1'] = '';

        $data = User::orderBy('id','DESC')->paginate(5);

        return view('users.change-pass',$title);
    }

    public function update_avatar(Request $request){

        $request->validate([
            'avatar' => 'image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $id = auth()->user()->id;
        $profile = User::findOrFail($id);
        $image_path = public_path("storage/profile/image/". $profile->avatar);

        if(File::exists($image_path)) {
            File::delete($image_path);
        }

        $input = $request->all();

        if ($image = $request->file('avatar')) {
            $destinationPath = 'public/profile/image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->storeAs($destinationPath, $profileImage);
            $input['avatar'] = "$profileImage";
        }else{
            unset($input['avatar']);
        }
        $profile->update($input);

        return redirect()->back();
    }
    public function password_update(Request $request){

        $pass = new PasswordRule;
        $validator = Validator::make($request->all(), [
            'password' => ['required', new MatchOldPassword],
            'new_password' => ['required', $pass->min(3)->mixedCase()->numbers()],
            'password_confirmation' => ['same:new_password']
        ], [
            'new_password.required' => 'Password Tidak Boleh Kosong!',
            'password_confirmation' => 'Password Tidak Sama!',
            'password_confirmation.required' => 'Password Tidak Boleh Kosong!'
        ]);

        if($validator->fails()) {
            // dd($validator->errors());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Alert::success('Berhasil', 'Password Berhasil di ubah.');

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);

        return redirect()->back();
    }

    public function usersActive(Request $request, $id){

        User::where('id', $id)->update(['active' => 1]);

        Alert::success('Berhasil', 'Akun Berhasil Diaktifkan Kembali.');

        return redirect()->back();
    }
}