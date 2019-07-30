<?php


namespace App\Helpers;


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
        $perms
    }

}
