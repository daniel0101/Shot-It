<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Route;

class LoginController extends Controller
{
    public function login(Request $request){
        $rule = [
            'email'=>'required',
            'password'=>'required'
        ];
        $this->validate($request,$rule);
        $params = $this->requestParams('password',['username'=>$request->email,'password'=>$request->password]);
        $request->request->add($params);
        $proxy = Request::create('oauth/token','POST');

        return Route::dispatch($proxy); //returns a response from the oauth server with access token and refresh token
    }

    public function requestParams($grant_type='password',$data){
        return [
            'grant_type'=>$grant_type,
            'client_id'=>'2',
            'client_secret'=>'jGJrceniq9PHN0AuEEQiWFXY58nMIxvz1x4kVix4',
            'username'=>$data['username'],
            'password'=>$data['password'],
            'scope'=>'*'
        ];
    }
}
