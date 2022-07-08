<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $page_title =  'Roles Permissions';
//        $roles = Role::with('users')->get();
        return view('roles-permissions.index',[
//            'roles'=> $roles,
            'page_title' => $page_title
        ]);
    }
    public function getIndex(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index

        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value


        // Total records
        $totalRecords = Role::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Role::select('count(*) as allcount')
            ->where('name', 'like', '%' . $searchValue . '%')
            ->count();



        // Get records, also we have included search filter as well
        $records = Role::with('permissions','users')
            ->where('name', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        foreach ($records as $record) {
//            $btn  = ' <a href="javascript:void(0)" onclick="editRolesPermissions('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editRolesPermissions"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteRolesPermissions"><i class="ti-trash"></i></a>';

            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "users" => $record->users,
                "permissions" => $record->permissions,
                "action" => $btn,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);


    }
    public function create(Request $request){
        $rules = [
            'nameRolesPermissions' =>'unique:roles,name'
        ];
        $message = [
            'nameRolesPermissions.unique'=>'Tên đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );

        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $role = Role::create(['name' => trim($request->nameRolesPermissions)]);
//        $permissions = preg_replace('/\s+/','',$request->userManagement);
        $permissions = $request->userManagement;
//        $this->createPermissions($permissions);
        $role->givePermissionTo($permissions);
        return response()->json(['success'=>'Thêm mới thành công']);
    }
    public function edit($id){
        $role = Role::findById($id);
        $permissions = $role->getAllPermissions();
        return response()->json([
            'role' =>$role,
            'permissions' =>$permissions
        ]);
    }
    public function update(Request $request)
    {
        $id = $request->id;
        $rules = [
            'nameRolesPermissions' =>'unique:roles,name,'.$id.',id',
        ];
        $message = [
            'nameRolesPermissions.unique'=>'Tên dự đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }
        $role = Role::find($id);
        $role->name  = trim($request->nameRolesPermissions);
        $role->save();
        $role->syncPermissions($request->userManagement);
        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id)
    {
        $role = Role::find($id);
        $role->syncPermissions([]);
        $role->delete();
        return response()->json(['success'=>'Xoá thành công']);

    }

    public function createPermissions($permissions){
        {
            if ($permissions != ''){
                foreach ($permissions as $permission){
                    Permission::updateOrcreate( [
                        'name' => $permission,
                    ]);
                }
            }

        }
    }
}
