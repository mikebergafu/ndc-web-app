<?php

use Illuminate\Http\Request;

header("Cache-Control: no-cache, must-revalidate");
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1'], function () {
    Route::group(['namespace' => 'Api'], function () {
        //Authentication
        Route::post('users/login', 'LoginController@login');
        // Route to create a new role
        Route::post('role', 'JwtAuthenticateController@createRole');
        // Route to create a new permission
        Route::post('permission', 'JwtAuthenticateController@createPermission');
        // Route to assign role to user
        Route::post('assign-role', 'JwtAuthenticateController@assignRole');
        // Route to attache permission to a role
        Route::post('attach-permission', 'JwtAuthenticateController@attachPermission');

        // Authentication route
        Route::post('authenticate', 'JwtAuthenticateController@authenticate');

        Route::group(['middleware' => ['ability:admin,create-users','jwt.auth']], function () {
            Route::get('users/auth', 'JwtAuthenticateController@index');
            //Regions
            Route::post('/regions', 'RegionController@store');
            Route::patch('/regions/{region}', 'RegionController@update');
            Route::delete('/regions/{region}', 'RegionController@destroy');

            //Users
            Route::resource('/users', 'UserController');

            //Roles
            Route::resource('/roles', 'RoleController');
        });


    });

});
