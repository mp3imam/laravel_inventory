<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Emails;

class MailController extends Controller
{
    //
    public function getIndex(){

        $data = Emails::orderBy('created_at', 'desc')->get();
        return response()->json([
            'data' => $data
        ]);
    }
}
