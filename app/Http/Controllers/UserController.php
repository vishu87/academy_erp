<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;

use App\Models\User, App\Models\MailQueue;

class UserController extends Controller {

	public function login(){

        // if(Auth::check()){
        //     return Redirect::to('/dashboard');
        // }

        // $url = Session::get('url.intended', "");

        // if($url){
        //     Session::forget('url.intended');
        // }

		// return view('login', ["url" => $url]);
		return view('login');
	}

	public function postLogin(Request $request){

		$cre = [
			"username" => $request->username,
			"password" => $request->password
		];

		$rules = [
			"username"=>"required",
			"password"=>"required"
		];

		$validator = Validator::make($cre,$rules);

		if($validator->passes()){

			$cre["inactive"] = 0;

			if(Auth::attempt($cre)){

				$user = Auth::user();
				if($user->user_type == 1){
					$access_rights = User::getAccessTabs(Auth::user()->id);
					if(sizeof($access_rights) == 0){
						Auth::logout();
						return Redirect::back("failure","You dont have access to any module, Kindly ask admin to add rights");
					}

					Session::put("access_rights",$access_rights);

					$all_sports = DB::table("sports")->pluck("sport_name","id")->toArray();
					$access_sports = DB::table("sports")->pluck("id")->toArray();

					Session::put("access_sports",$access_sports);
					Session::put("all_sports",$all_sports);

					$count = 0;
					foreach($access_rights as $key => $right){
						if($count == 0){
							$access_right_id = $right[0];
							$access_right = DB::table("access_rights")->select("location")->where("id",$access_right_id)->first();
							if($access_right){
								return Redirect::to($access_right->location);
							}
						}
						$count++;
						break;
					}
				} else {
					return Redirect::to("parents");
				}
                
			} else {
				return Redirect::back()->withInput()->with('failure','Invalid email or password');
			}
		} else {
            return Redirect::back()->withErrors($validator)->withInput()->with('failure',$validator->errors()->first());
		}
	}

	public function logout(){
		Auth::logout();
		return Redirect::to("/");
	}
	
	public function viewUsers(){
		return view('users.view_users',["sidebar"=>"users" ,"menu" => "admin"]);
	}

	public function viewAddUser(){
		return view('users.add_user',["update" => 0,"sidebar"=>"users" ,"menu" => "admin"]);
	}

	public function editUser($id){
		return view('users.add_user',["update"=>$id,"sidebar"=>"users" ,"menu" => "admin"]);
	}

	public function viewUserRoles(){
		return view('users.user_roles',["sidebar"=>"users" ,"menu" => "admin"]);
	}	

	public function viewAccessRights(){
		return view('users.user_rights',["sidebar"=>"users" ,"menu" => "admin"]);
	}

	public function staffAttendance(){
		return view('users.staff.attendance.index',["sidebar"=>"users" ,"menu" => "admin"]);
	}

	public function dashboard(){
		return view('dashboard');
	}

	public function switchDashboard($type){
		Session::put("dashboard",$type);
		if($type == "academy"){
			$link = "students";
		} else if($type == "accounts"){
			$link = "payments";
		} else if($type == "leads"){
			$link = "leads";
		} else if($type == "admin"){
			$link = "city";
		}else if($type == "inventory"){
			$link = "inventory/request";
		}else if($type == "communication"){
			$link = "communications/send-message";
		}
		return Redirect::to($link);
	}


    public function postForgetPassword(Request $request){
        $validator = Validator::make(["email"=>$request->email],["email"=>"required|email"]);
        
        if($validator->fails()){
        	$data['success'] = false;
			$data['message'] = $validator->errors()->first();
			return Response::json($data, 200, array());
        }
        
        $user = User::where('email',$request->email)->first();
        
        if(!$user){

        	$data['success'] = false;
			$data['message'] = "No user found with this email id";
			return Response::json($data, 200, array());
        }

        $rand_pwd = User::getRandPassword();
        
        $user->password = Hash::make($rand_pwd);
        $user->password_check = $rand_pwd;
        $user->save();

        $mail = new MailQueue;

        if($request->email == "admin"){
            $mail->mailto = $user->inactive_email;
        } else {
            $mail->mailto = $user->email;
        }

        $mail->subject = "Academy - Reset Password";
        $mail->content = view('mails',["user"=>$user , "type"=>"password_reset"]);
        $mail->save();

        $data['success'] = false;
		$data['message'] = "New password has been sent to your registered email id";
		return Response::json($data, 200, array());
    }

	// public function addAccessRights(){
	// 	$access_data = Input::get('rights_data'); 
	// 	if ($access_data) {
	// 		DB::table('access_rights')->insert([
	// 			"access_rights"=>$access_data['access_rights']
	// 		]);
	// 		$success["success"] = true;
	// 	}
	// 	else{
	// 		$success["success"] = false;
	// 	}
	// 	return json_encode(["success"=>$success]);
	// }

	// public function updateAccessRights(){
	// 	$access_data = Input::get('rights_data'); 
	// 	if ($access_data) {
	// 		DB::table('access_rights')->where('id',$access_data['id'])
	// 		->update([
	// 			"access_rights"=>$access_data['access_rights']
	// 		]);
	// 		$success["success"] = true;
	// 	}
	// 	else{
	// 		$success["success"] = false;
	// 	}
	// 	return json_encode(["success"=>$success]);
	// }

	// public function deleteAccessRights(){

	// 	$id = Input::get('roles')['id'];
	// 	if ($id) {
	// 		DB::table('access_rights')->where('id',$id)
	// 		->delete();
	// 		$success["success"] = true;
	// 	}
	// 	else{
	// 		$success["success"] = false;
	// 	}

	// 	return json_encode($success);
	// }
}
