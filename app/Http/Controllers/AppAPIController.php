<?php

namespace App\Http\Controllers;

use Redirect, Validator, Hash, Response, Session, DB, Cache, Auth;

use Illuminate\Http\Request;

use App\Models\User, App\Models\Utilities;

class AppAPIController extends Controller {

	public function login(Request $request){

		$credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validator = Validator::make($credentials, $rules);
        if ($validator->passes()) {
        	
			if(Auth::attempt($credentials)){

				$user = new \stdClass;
				$user->username = Auth::user()->username;
				$user->apiToken = Auth::user()->api_key;
				$user->name = Auth::user()->name;
				$user->email = Auth::user()->email;
				$user->mobile = Auth::user()->mobile;
				$user->is_admin = Auth::user()->is_admin;

	        	$data["success"] = true;
	            $data["user"] = $user;
                
			} else {
				$data["success"] = false;
	            $data["message"] ="Username & password is invalid";
			}

        } else {
        	$data["success"] = false;
        	$data["message"] ="Please enter required fields";

        }

		return Response::json($data,200,array());		
	}

}
