<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;
use App\Models\Emails;
use App\Models\SatkerModel;
use Alert, DB, Config;
use App\Helpers\LogActivity;
use Illuminate\Support\Str;

class MailController extends Controller
{

    public function index(Request $request)
    {
        $title['title'] = 'Kustomisasi Email';
        $title['li_1'] = '';

        if(auth()->user()->id === 3){
            $data = Emails::orderBy('id','DESC')->paginate(5);

        }
        else{
            $data = Emails::where('satker_id', auth()->user()->satker_id)->orderBy('id','DESC')->paginate(5);
        }

        return view('emails.index',$title, compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create(){
        $title['title'] = 'Kustomisasi Email';
        $title['li_1'] = '';

        return view('emails.create', $title);
    }

    public function store(Request $request)
    {

        $satker = ['provinsi' => 'nullable', 'satker' => 'nullable'];
        if ($request->roles == 'Admin')
        $satker = ['provinsi' => 'required', 'satker' => 'required'];
        $this->validate($request, [
            'email' => 'required|email|unique:custom_emails,email',
            'title' => 'required',
            'body' => 'required',
            $satker
        ],
        [
            'email.unique' => 'Email Tidak boleh Sama',
            'password.required' => 'Password Tidak boleh Kosong'

        ]
    );

        $input = $request->all();

        if ($request->roles == 'Admin'){
            $input['provinsi_id'] = $request->provinsi;
            $input['satker_id'] = $request->satker;
            $input['layanan_id'] = $request->layanan_id;

        }
        // dd($request->all());
        $satker = SatkerModel::find($request->satker_id);
        $token = Str::random(64);

        $user = Emails::create([
            'email' => $request->email,
            'password' => $request->password,
            'hostname' => $request->hostname,
            'port' => $request->port,
            'title' => $request->title,
            'body' => $request->body,
            'status' => 0,
            'token' => $token,
            'provinsi_id' => $input['provinsi_id'],
            'satker_id' => $input['satker_id'],
            'layanan_id' => $input['layanan_id'] ?? '',
            'user_id' => auth()->user()->id
        ]);

        Mail::send('emails.custom',['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject($request->title);
        });

        Alert::success('Success', 'Email Berhasil Dikirim.');
        LogActivity::addToLog("Email Tersimpan");

        return redirect()->route('mail.index')->withErrors('error', 'Error Check !');
    }

    public function edit($id){
        $title['title'] = 'Kustomisasi Email';
        $title['li_1'] = '';

        $data = Emails::find($id);
        return view('emails.edit', $title, compact('data'));
    }

    public function update(Request $request, $id)
    {

        $satker = ['provinsi' => 'nullable', 'satker' => 'nullable'];
        if ($request->roles == 'Admin')
        $satker = ['provinsi' => 'required', 'satker' => 'required'];
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            $satker
        ]);

        $input = $request->all();

        if ($request->roles == 'Admin'){
            $input['provinsi_id'] = $request->provinsi;
            $input['satker_id'] = $request->satker;
        }
        $satker = SatkerModel::find($request->satker_id);

        $user = Emails::where('id', $id)->update([
            'username' => $request->username,
            'password' => $request->password,
            'host' => $request->host,
            'port' => $request->port,
            'title' => $request->title,
            'body' => $request->body,
            'provinsi_id' => $input['provinsi_id'],
            'satker_id' => $input['satker_id'],
            'layanan_id' => $input['layanan_id'],
            'user_id' => auth()->user()->id
        ]);

        Alert::success('Success', 'Email Berhasil Dikirim.');
        LogActivity::addToLog("Update Email");

        return redirect()->route('mail.index');
    }

    public function showApplyForm(Request $request,$token){
        $update = DB::table('custom_emails')
                              ->where([
                                'token' => $request->token
                              ])
                              ->first();
        // dd($update);
        if(!$update){
            return view('emails.invalid-token');
        }
        return view('emails.applyMailForm', ['token' => $token]);
    }

    public function submitApplyForm(Request $request){

        switch ($request->input('action')) {
            case 'accept':
                // Save model
                $update = DB::table('custom_emails')
                              ->where([
                                'token' => $request->token
                              ])
                              ->first();
                if(!$update){
                    return view('emails.invalid-token');
                }
                $user = Emails::where('email', $update->email)
                      ->update(['status' => 1, 'token' => null ]);
                return view('emails.success-mail');
                break;

            case 'reject':
                $update = DB::table('custom_emails')
                              ->where([
                                'token' => $request->token
                              ])
                              ->first();
                if(!$update){
                    return view('emails.invalid-token');
                }
                $user = Emails::where('email', $update->email)
                      ->update(['status' => 2, 'token' => null ]);
                return view('emails.reject-mail');
                break;
        }
    }

    public function testingMail(Request $request, $id){
        $res =  Emails::find($id);

        $mailData = [
                    'title' => $res->title,
                    'body' => $res->body,
                    'satker' => $res->satker_name];
        // dd($res->hostname == null);
        if($res->hostname == null || $res->hostname){
            //gmail
        $config = [
            'username' => $res->email,
            'password' =>$res->password
        ];
        dd($config);
        Config::set('mail.mailers.smtp', $config);
        Mail::to('sofyandamha@gmail.com')->send(new SendMail($mailData));

        }
        else{
            $config = [
                'transport' => 'smtp',
                'host' => $res->hostname,
                'port' =>$res->port,
                'encryption' => env('MAIL_ENCRYPTION', 'ssl'),
                'username' => $res->email,
                'password' =>$res->password,
            ];
            Config::set('mail.mailers.smtp', $config);
            Mail::to('sofyandamha@gmail.com')->send(new SendMail($mailData));

        }


        // Mail::to('sofyandamha@gmail.com')->send(new SendMail($mailData));
        Alert::success('Success', 'Email Berhasil Dikirim.');
        return redirect()->route('mail.index');
    }

    public function mailAccepted($id){

        $acc = Emails::find($id);

        $acc->update(['status' => 1]);

        return redirect()->route('mail.index');


    }
}
