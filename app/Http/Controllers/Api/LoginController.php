<?php

namespace App\Http\Controllers\Api;

use App\Helpers\BergUtils;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
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

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }


}
