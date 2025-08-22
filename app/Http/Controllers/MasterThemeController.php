<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\MasterModule;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use DB;

class MasterThemeController extends Controller
{
    private $title = "Pengaturan Tema";
    private $li_1 = "Settings";

    public function index(Request $request)
    {
        $data['title'] = $this->title;
        $data['li_1'] = $this->li_1;

        return view('master.tema.index', $data);
    }
}
