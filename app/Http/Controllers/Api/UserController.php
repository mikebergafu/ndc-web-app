<?php

namespace App\Http\Controllers\Api;

use App\Helpers\BergUtils;
use App\PermissionRole;
use App\Role;
use App\RoleUser;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index(Request $request)

    {
        $data = User::orderBy('id','DESC')->paginate(5);
        return BergUtils::return_types(200,'List of Users', $data);
        //return view('users.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $input =array(
            'roles'=>$request->get('roles')
        );


        foreach($input['roles'] as $role){
            $data = new RoleUser();
            $data->user_id = $user->id;
            $data->role_id = $role['id'];
            $data->save();
        }

        $token = JWTAuth::fromUser($user);

        $user =array(
            'bio_details'=>$user,
            'role_details'=> BergUtils::getUserRoles($user->id),
            'permissions'=>BergUtils::getUserPermissions($user->id)
        );

        $data = array(
            'user'=> $user,
            'token' => $token,
            'type' => 'bearer', // you can ommit this
            'expires' => auth('api')->factory()->getTTL() * 6000000000, // time to expiration
        );

        return BergUtils::return_types(200,'Token successfully Generated', $data);


        //return response()->json(compact('user','token'),201);
    }

    /**
 * Store a newly created resource in storage.
 *

     * @param  Request $request
 * @return Response
 */

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return BergUtils::return_types(200,'User created successfully', $user);

    }


    /**
     * Display the specified resource.
     * @param  int  $id
     * @return Response
     */

    public function show($id)

    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return Response
     */

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('users.edit',compact('user','roles','userRole'));
    }
    /**
 * Update the specified resource in storage.
     * @param  Request  $request
 * @param int  $id
 * @return Response
 */

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);


        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));
        }


        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
            ->with('success','User updated successfully');

    }


    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success','User deleted successfully');

    }
}
