<?php

namespace App\Http\Controllers\API;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|min:3',
            'password_confirmation' =>'required|min:3',
            'nip' => 'unique:users|nullable'
        ],[
            'name.required' => 'Nama Tidak Boleh Kosong!',
            'email.required' => 'Email Tidak Boleh Kosong!',
            'email.unique' => 'Email Tidak Boleh Sama!',
            'password.required' => 'Password Tidak Boleh Kosong!',
            'password.min' => 'Password Tidak Boleh kurang dari 3!',
            'password.confirmed' => 'Password Tidak Sama!',
            'password_confirmation.required' => 'Password Tidak Boleh Kosong!',
            'nip.unique' => 'Nip Tidak Boleh Sama!',

        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->messages()->first()], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'password' => Hash::make($request->password)
         ]);
        $user->assignRole('user');

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json([
                'status'=> 200,
                'data' => $user,
                'message' => 'User telah berhasil registrasi'], 200);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['status'=> 401,
                'message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;
        LogActivity::addToLog("Login");

        return response()
            ->json([
                'status' => 200,
                'message' => 'Hi '.$user->name.', welcome to home',
                'data' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
                ], 200);
    }

    // method for user logout and delete token
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        LogActivity::addToLog("Logout");

        return response()->json([
            'status' => 200,
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ]);
    }
}
