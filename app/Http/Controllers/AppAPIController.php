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

	public function academyData(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));
        $user_access = User::getAccess("st-profile",$user->id);

		$students = DB::table('students')->select("students.id")->join("groups","groups.id","=","students.group_id")->join("center","center.id","=","groups.center_id")->join("city","city.id","=","center.city_id")->whereIn("students.inactive",[0,2]);
		if(!$user_access->all_access){
			$students = $students->whereIn("students.group_id",$user_access->group_ids);
		}
		$students = $students->where("students.client_id",$user->client_id)->count();

		$boys = DB::table('students')->select("students.id")->join("groups","groups.id","=","students.group_id")->join("center","center.id","=","groups.center_id")->join("city","city.id","=","center.city_id")->where("gender",1)->whereIn("students.inactive",[0,2]);
		if(!$user_access->all_access){
			$boys = $boys->whereIn("students.group_id",$user_access->group_ids);
		}
		$boys = $boys->where("students.client_id",$user->client_id)->count();

		$girls = DB::table('students')->select("students.id")->join("groups","groups.id","=","students.group_id")->join("center","center.id","=","groups.center_id")->join("city","city.id","=","center.city_id")->where("gender",2)->whereIn("students.inactive",[0,2]);
		if(!$user_access->all_access){
			$girls = $girls->whereIn("students.group_id",$user_access->group_ids);
		}
		$girls = $girls->where("students.client_id",$user->client_id)->count();

		$centers  = DB::table('center')->where("center_status",0);
		if(!$user_access->all_access){
			$centers = $centers->whereIn("center.id",$user_access->center_ids);
		}
		$centers = $centers->where("center.client_id",$user->client_id)->count();

		$rec_students = DB::table('students')->select('students.id','students.name','center.center_name','groups.group_name','students.pic')->join("groups","groups.id","=","students.group_id")->join("center","center.id","=","groups.center_id")->whereIn("students.inactive",[0,2]);
		if(!$user_access->all_access){
			$rec_students = $rec_students->whereIn("students.group_id",$user_access->group_ids);
		}
		$rec_students = $rec_students->where("students.client_id",$user->client_id)->orderBy('students.id', 'DESC')->limit(5)->get();

		foreach($rec_students as $student){
		    $student->pic = Utilities::getPicture($student->pic,'student');
		    $student->name = (strlen($student->name) > 25) ? substr($student->name,0,25)."..." : $student->name;
		}

		$groups   = [];

		$data['students'] = $students;
		$data['rec_students'] = $rec_students;
		$data['centers']  = $centers;
		$data['groups']   = $groups;
		$data['boys']   = $boys;
		$data['girls']   = $girls;
		$data['success']  = true;

		return Response::json($data,200,array());		
	}

}
