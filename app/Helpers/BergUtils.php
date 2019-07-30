<?php


namespace App\Helpers;


use Illuminate\Support\Facades\DB;

class BergUtils
{

    public static function return_types($code='56',$message='', $data=null){

        switch($code)
        {
            case $code == '200';
                $arr = array(
                    'code'=> $code,
                    'message'=> $message,
                    'data'=> $data
                );
                $ret = response($arr, $arr['code']);
                break;

            case $code == '404';
                $arr = array(
                    'code'=> $code,
                    'message'=> $message,
                    'data'=> null
                );
                $ret = response($arr, $arr['code']);
                break;
            case $code == '401';
                $arr = array(
                    'code'=> $code,
                    'message'=> $message,
                    'data'=> null
                );
                $ret = response($arr, $arr['code']);
                break;
            default;
                $arr = array(
                    'code'=> 501,
                    'message'=> 'No such function is Implemented',
                    'data'=> null
                );
                $ret = response($arr, $arr['code']);
                break;
        }

        return $ret;
    }

    public static function getUserPermissions($user_id){
        $perms = DB::table('permissions')
            ->leftJoin('permission_role', 'permissions.id', 'permission_role.permission_id')
            ->leftJoin('role_user', 'permission_role.role_id', 'role_user.role_id')->where('user_id',$user_id)
            ->get();

        return $perms;
    }

}
