<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {


        $header = [
            'title' => 'Users',
            'button' => [
//                'Create'            => ['id'=>'createMusics','style'=>'primary'],
                'Create'        => ['id'=>'createUsers','style'=>'success'],
//                'Update Multiple'   => ['id'=>'update_multipleMusics','style'=>'warning'],
            ]

        ];
        return view('users.index')->with(compact('header'));

//        $page_title =  'Users';
//        $action = ['create'];
//        $roles = Role::with('users')->get();
//        return view('users.index',[
//            'roles'=> $roles,
//            'action'=> $action,
//            'page_title' => $page_title
//        ]);
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
        $totalRecords = User::select('count(*) as allcount')->count();
        $totalRecordswithFilter = User::select('count(*) as allcount')
            ->where('name', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records, also we have included search filter as well
        $records = User::with('roles')
            ->where('name', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        foreach ($records as $record) {
//            $btn  = ' <a href="javascript:void(0)" onclick="editRolesPermissions('.$record->id.')" class="btn btn-warning"><i class="ti-pencil-alt"></i></a>';
            $btn = ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-warning editUsers"><i class="ti-pencil-alt"></i></a>';
            $btn .= ' <a href="javascript:void(0)" data-id="'.$record->id.'" class="btn btn-danger deleteUsers"><i class="ti-trash"></i></a>';

            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "roles" => $record->roles,
//                "permissions" => $record->permissions,
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
            'useremail' =>'unique:users,email',
            'username' =>'unique:users,name'
        ];
        $message = [
            'useremail.unique'=>'Mail đã tồn tại',
            'username.unique'=>'Tên đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $user =  User::create([
            'name' => $request->username,
            'email' => $request->useremail,
            'password' => $request->userpassword ?  Hash::make($request->userpassword) : Hash::make('Zxcv@1234') ,
        ]);
        $user->assignRole($request->userrole);
        return response()->json(['success'=>'Thêm mới thành công']);
    }
    public function edit($id){

        $user = User::with('roles')->find($id);
        return response()->json([
            'user' =>$user,
        ]);
    }
    public function update(Request $request)
    {

        $id = $request->id;
        $rules = [
            'useremail' =>'unique:users,email,'.$id.',id',
            'username' =>'unique:users,name,'.$id.',id',
        ];
        $message = [
            'useremail.unique'=>'Mail đã tồn tại',
            'username.unique'=>'Tên đã tồn tại',
        ];
        $error = Validator::make($request->all(),$rules, $message );
        if($error->fails()){
            return response()->json(['errors'=> $error->errors()->all()]);
        }

        $user = User::find($id);
        $user->name = $request->username;
        $user->email = $request->useremail;
        $user->password = !$request->userpassword ? $user->password : Hash::make($request->userpassword);
        $user->save();
        $user->syncRoles($request->userrole);


        return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function delete($id)
    {
        $role = Role::find($id);
        $role->syncPermissions([]);
        $role->delete();
        return response()->json(['success'=>'Xoá thành công']);

    }
}
