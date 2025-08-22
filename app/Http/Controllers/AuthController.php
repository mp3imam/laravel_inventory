<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

use App\Models\User;
use Hash;
use Session, Alert, DB;


class AuthController extends Controller
{
    //
    public function index()
    {

        return view('auth.login');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            // 'geetest_challenge' => 'required|geetest'

        ],
        [
            'email.required' => 'Email Tidak boleh Kosong',
            'password.required' => 'Password Tidak boleh Kosong'

        ]

    );

        $credentials =['email' => $request->email,
                        'password' => $request->password,
                        'active' => 1];


        if (Auth::attempt($credentials)) {
            toast('Login berhasil','success')
            ->autoClose(3000)
            ->timerProgressBar();

            // return redirect()->intended('dashboard');
            LogActivity::addToLog('Login Web');

            if (Auth::user()->roles[0]->name == 'user')
                return redirect()->intended('booking-layanan')
                ->withSuccess('You have Successfully loggedin');
            return redirect()->intended('inventory')
                        ->withSuccess('You have Successfully loggedin');
        }

        else{

            $executed = RateLimiter::attempt(
                'send-message: a' ,
                $perMinute = 5,
                function() {
                    // Send message...
                }

            );
            $user = DB::table('users')->where('email', $request->email);

            if (empty($user->first())) {
                return redirect()
                ->back()
                ->withInput()
                ->withErrors(['notfound' => 'Akun Tidak Ditemukan']);
            }

            if (! $executed) {
               if($user->first()->id !== 3 || $user->first()->email !== 'superadmin@gmail.com'){
                $user->update(['active' => 0]);
                return redirect()
                ->back()
                ->withInput()
                ->withErrors(['locked' => '5 kali Gagal, Akun Anda telah di non-aktifkan Mohon Hubungi Superadmin']);
               }

            }

            if ($user->first()->active === 0 ) {
                return redirect()
                ->back()
                ->withInput()
                ->withErrors(['locked' => 'Akun Anda telah di non-aktifkan, Mohon Hubungi Superadmin']);
            }

            return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors(['wrong' => 'Email atau Password Tidak Sesuai']);
        }

        return redirect('login');

    }

    /**
     * Write code on Method
     *
     * @return response()
     */

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        if(Auth::check()){
            if (Auth::user()->roles[0]->name == 'user') {
                return redirect()->route('booking.index');
            }


            return view('index');
        }


        return redirect("login")->withSuccess('Opps! You do not have access');
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();
        LogActivity::addToLog('Logout Web');

        return Redirect('login');
    }
}
