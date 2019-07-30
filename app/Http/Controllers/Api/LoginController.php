<?php

namespace App\Http\Controllers\Api;

use App\Helpers\BergUtils;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Lcobucci\JWT\Token;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    //Please add this method
    public function login() {
        // get email and password from request
        $credentials = request(['email', 'password']);

        // try to auth and get the token using api authentication
        if (!$token = auth('api')->attempt($credentials)) {
            // if the credentials are wrong we send an unauthorized error in json format

           // return response()->json(['error' => 'Unauthorized'], 401);
            return BergUtils::return_types(401,'Invalid Email or Password');
        }

        $user =array(
            'bio_details'=>auth('api')->user(),
            'role_details'=> BergUtils::getUserRoles(auth('api')->user()->id),
            'permissions'=>BergUtils::getUserPermissions(auth('api')->user()->id)
        );

        $data = array(
            'user'=> $user,
            'token' => $token,
            'type' => 'bearer', // you can ommit this
            'expires' => auth('api')->factory()->getTTL() * 60, // time to expiration
        );

        return BergUtils::return_types(200,'Token successfully Generated', $data);

    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }




}
