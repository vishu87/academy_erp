<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\User, App\Center;

class AccountsController extends Controller{ 

	public function index(){
		return view('manage.accounts.gst',['menu' => "accounts","sidebar" => "p_tax"]);
	}

	public function listData(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("pay_tax",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

		$list = DB::table('gst')->select('gst.*','states.state_name')->leftJoin('states','gst.state_id','=','states.id')->where("client_id",$user->client_id)->orderBy('id','DESC')->get();
		return Response::json($list, 200, array()); 

	}

	public function save(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("pay_tax",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }
		
		$validator = Validator::make(
			[
				"name" => $request->name,
				"gst_id" => $request->gst_id,
				"state_id" => $request->state_id
			],
			[
				"name" => "required",
				"gst_id" => "required",
				"state_id" => "required"
			]
		);
		if ($validator->fails()) {
			$data['success'] = false;
			$data['message'] = $validator->errors()->first();
		} else {

			$data = [
				"name" => $request->name,
				"gst_id" => $request->gst_id,
				"state_id" => $request->state_id,
				"vat_tin" => $request->vat_tin,
				"pan_no" => $request->pan_no,
				"registered_office" => $request->registered_office,
				"contact_person" => $request->contact_person,
				"contact_name" => $request->contact_name,
				"defaults" => $request->defaults
			];

			if (!$request->id){
				$data["client_id"] = $user->client_id;
				$id = DB::table('gst')->insertGetId($data);
				$data['success'] = true;
				$data['message'] = "Tax settings as saved successfully";
			} else {
				DB::table('gst')->where('id',$request->id)->where("client_id",$user->client_id)->update($data);
				$id = $request->id;
				$data['success'] = true;
				$data['message'] = "Tax settings as updated successfully";
			}

			if($request->defaults == 1){
				DB::table('gst')->where('id','!=',$id)->where("client_id",$user->client_id)->update(['defaults' => 0]);
			}

			$data['success'] = true;
			$data['message'] = "Successfully updated";
		}

		return Response::json($data, 200, array());
	}

	public function delete(Request $request){

		$user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("pay_tax",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

		$id = $request->id;
		$check = DB::table('gst')->where("client_id",$user->client_id)->where("id",$id)->first();
		if ($check) {
			DB::table('gst')->where('id',$id)->delete();
			$data['success'] = true;
			$data['message'] = "Tax settings as deleted successfully";
		}else{
			$data['success'] = false;
			$data['message'] = "Tax settings not found";
		}

		return Response::json($data, 200, []);
	}
}


                 