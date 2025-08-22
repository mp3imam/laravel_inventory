<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\MasterModule;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use DB, Alert;

class MasterModuleController extends Controller
{
    private $title = "Pengaturan Menu";
    private $li_1 = "Settings";

    public function index(Request $request, $name = "")
    {
        $id = auth()->user()->roles->pluck('id')->first();
        $getParent = Permission::select('permissions.*')
                    ->join('role_has_permissions', 'permissions.id','=', 'role_has_permissions.permission_id')
                    ->join('roles','role_has_permissions.role_id', '=', 'roles.id')
                    ->where('roles.id', $id)->where('module_parent', 0)->orderBy('module_position', 'ASC')->get();

        $data['title'] = $this->title;
        $data['li_1'] = $this->li_1;
        $data['menu'] = $getParent;

        return view('master.module.index', $data);
    }

    public function moduleReorder(Request $request)
    {
        $json = $request->nested_menu_array;
        $decoded_json = json_decode($json, TRUE);

        $simplified_list = [];
        $this->recur1($decoded_json, $simplified_list);

        DB::beginTransaction();
        try {
            $info = ["success" => FALSE];

            foreach ($simplified_list as $k => $v) {
                $menu = Permission::find($v['id']);
                $menu->fill([
                    "module_parent" => $v['module_parent'],
                    "module_position" => $v['module_position'],
                ]);

                $menu->save();
            }

            DB::commit();
            $info['success'] = TRUE;
        } catch (\Exception $e) {
            DB::rollback();
            $info['success'] = FALSE;
        }

        if ($info['success']) {
            Alert::success('Success', 'Berhasil Melakukan Perubahan.');

            return redirect('module');
        } else {
            // $request->session()->flash('error', "Something went wrong while updating...");
            return redirect('module');
        }
        Alert::success('Success', 'Berhasil Melakukan Perubahan.');

        LogActivity::addToLog("Mengubah Tampilan Menu Sidebar");

        return redirect('module');
    }

    public function recur1($nested_array = [], &$simplified_list = [])
    {
        static $counter = 0;

        foreach ($nested_array as $k => $v) {
            $module_position = $k + 1;
            $simplified_list[] = [
                "id" => $v['id'],
                "module_parent" => 0,
                "module_position" => $module_position
            ];

            if (!empty($v["children"])) {
                $counter += 1;
                $this->recur2($v['children'], $simplified_list, $v['id']);
            }
        }
    }

    public function recur2($sub_nested_array = [], &$simplified_list = [], $module_parent = NULL)
    {
        static $counter = 0;

        foreach ($sub_nested_array as $k => $v) {
            $module_position = $k + 1;
            $simplified_list[] = [
                "id" => $v['id'],
                "module_parent" => $module_parent,
                "module_position" => $module_position
            ];

            if (!empty($v["children"])) {
                $counter += 1;
                return $this->recur2($v['children'], $simplified_list, $v['id']);
            }
        }
    }

    public function moduleFilter(Request $request)
    {
        // return $this->index($request->name, $request->sorting);
    }

    public function create(Request $request)
    {
        $data['title'] = $this->title;
        $data['li_1'] = $this->li_1;

        return view('master.module.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon' => 'required',
            'url' => 'required',
            'parent' => 'required',
            'position' => 'required',
            'desc' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/master/module-create');
        } else {
            $data = new Permission();
            $data->name = $request->name;
            $data->module_icon = $request->icon;
            $data->module_url = $request->url;
            $data->module_parent = $request->parent;
            $data->module_position = $request->position;
            $data->module_description = $request->desc;
            $data->save();

            Alert::success('Success', 'Berhasil Melakukan Perubahan.');
            LogActivity::addToLog("Menambahkan Menu Sidebar");

            return redirect('/master/module');
        }
    }

    public function getById($id, Request $request)
    {
        $getData = Permission::where('id', '=', $id)->get();

        $data['title'] = $this->title;
        $data['li_1'] = $this->li_1;
        $data['datas'] = $getData;

        return view('master.module.edit', $data);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'icon' => 'required',
            'url' => 'required',
            'parent' => 'required',
            'position' => 'required',
            'desc' => 'required'
        ]);

        if ($validator->fails()) {
            // toast('Fill in all input', 'warning');
            // alert()->warning('Warning', 'Fill in all input');
            return redirect('/master/module-getById/' . $request->id);
        } else {
            $data = Permission::where('id', $request->id)->first();
            $data->name = $request->name;
            $data->module_icon = $request->icon;
            $data->module_url = $request->url;
            $data->module_parent = $request->parent;
            $data->module_position = $request->position;
            $data->module_description = $request->desc;
            $data->save();
            LogActivity::addToLog("Mengubah Menu Sidebar");

            Alert::success('Success', 'Berhasil Melakukan Perubahan.');
            return redirect('/master/module');
        }
    }

    public function delete($id, Request $request)
    {
        $data = Permission::where('id', $id)->first();
        $data->module_status = '2';
        $data->is_deleted = '1';
        $data->save();
        LogActivity::addToLog("Menghapus Menu Sidebar");

        // toast('Delete data success', 'success');
        // alert()->success('Success', 'Delete data success');
        return redirect('/master/module');
    }
}
