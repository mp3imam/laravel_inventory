<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use Alert;
use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    //
    public function index(Request $request){

        $title['title'] = 'Atur Notifikasi Pengguna';
        $title['li_1'] = 'notifikasi';
        $notifications = DatabaseNotification::orderBy('created_at','DESC')->paginate(10);

        return view('notif.index', $title, compact('notifications'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function markNotification(Request $request)
    {
        // dd($request->all());
        auth()->user()
            ->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        return redirect()->back();
    }

    public function notif_admin(){
        $title['title'] = '';
        $title['li_1'] = '';
        $menu = \DB::table('menu_notif')->where('role', 'admin')->get();

        return view('notif.admin', $title, compact('menu'));
    }

    public function notif_admin_update(Request $request){
        // dd($request->menu);
        if($request->menu){
            \DB::table('menu_notif')->where('role','admin')->whereIn('id', $request->menu)->update(['status' => 1]);
            \DB::table('menu_notif')->where('role','admin')->whereNotIn('id', $request->menu)->update(['status' => 0]);

        }
        else{
            \DB::table('menu_notif')->where('role','admin')->update(['status' => 0]);

        }
        Alert::success('Success', 'Data Berhasil Diubah.');

        return redirect()->route('notif.index');

    }

    public function notif_user(){
        $title['title'] = 'Notifikasi User';
        $title['li_1'] = 'user';

        $menu = \DB::table('menu_notif')->where('role', 'user')->get();

        return view('notif.user', $title, compact('menu'));
    }

    public function notif_user_update(Request $request){
        $title['title'] = '';
        $title['li_1'] = '';
        // dd($request->menu);
        if($request->menu){
            \DB::table('menu_notif')->where('role','user')->whereIn('id', $request->menu)->update(['status' => 1]);
            \DB::table('menu_notif')->where('role','user')->whereNotIn('id', $request->menu)->update(['status' => 0]);

        }
        else{
            \DB::table('menu_notif')->where('role','user')->update(['status' => 0]);

        }
        Alert::success('Success', 'Data Berhasil Diubah.');

        return redirect()->route('notif.index');

    }

    public function detail($uuid){
        $title['title'] = 'Detail Notifikasi';
        $title['li_1'] = 'detail';

        auth()->user()->unreadNotifications->where('id', $uuid)->markAsRead();

        $det = Notification::find($uuid);
        $val = $det->data;
        $detail = [];
        foreach(json_decode($val) as $dt){
            $detail[] = $dt;
        }
        $date = Carbon::parse($det->created_at)->format('d/M/Y H:m:s');
        return view('notif.detail', $title, compact('detail','date'));
    }

    public function saveToken(Request $request)
        {
            auth()->user()->update(['device_token'=>$request->token]);
            return response()->json(['token saved successfully.']);
        }

        /**
         * Write code on Method
         *
         * @return response()
         */
        public function sendNotification(Request $request)
        {
            //firebaseToken berisi seluruh user yang memiliki device_token. jadi notifnya akan dikirmkan ke semua user
            //jika kalian ingin mengirim notif ke user tertentu batasi query dibawah ini, bisa berdasarkan id atau kondisi tertentu

            $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

            $SERVER_API_KEY = 'AAAA7vLpI8g:APA91bE2fw6ssVkicjmlWcki3k55utPR2akDKJYMhSRNb9AW-yYrPs04raE4weSE5Bfzdm6vwkZm8myIIEV9n3zY6Nb52tvbLWBkf9KxYBS41HcQdsy7erf3HPTvnPLOaRYu0PnJpFSb';
            // dd($firebaseToken);
            $data = [
                // "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => $request->title,
                    "body" => $request->body,
                    "icon" => 'https://cdn.pixabay.com/photo/2016/05/24/16/48/mountains-1412683_960_720.png',
                    "content_available" => true,
                    "priority" => "high",
                ],
                "to" => 'dg3pRGMKRWW_yp7VpFptwR:APA91bHRazQlBweyYyM-Eg2ZZKL7H-x3hc70KPQPwcYUQQwn0W_asXknO8gzHU3pO-pSyteqrR_4LDvmJWe05-xNDz0AruCQXF7qNmW8IiHlWPAowlx2RCHLo8i_Kq7-5AIjLEZiQZuh'
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);

            dd($response);
            // Alert::success('Success', 'Notifikasi Berhasil Dikirim.');
            // return redirect()->back();
        }
}
