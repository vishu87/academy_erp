<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\GroupType, App\Models\User;

class DropDownMasterController extends Controller
{	
    public function index(){  
        User::pageAccess(25);
        return view('dropDownMaster.groupTypes.index', ['sidebar' => "groupType","menu" => "admin"]);
    }

    public function init(){
        $group_types = DB::table('group_types')->get();
        $data['group_types'] = $group_types;
        $data['success'] = true;   
        return Response::json($data,200,array());  
    }

    public function store(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        // $check_access = User::getAccess("performance-params",$user->id, -1);
        // if(!$check_access) {
        //     $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        // }

        $cre = [
            "name" => $request->name
        ];
        $validator = Validator::make($cre, [
            "name" => "required"
        ]);

        if ($validator->passes()) {

            if($request->id){
                $groupType = GroupType::where("id",$request->id)->where("client_id",$user->client_id)->first();
                $data['message'] = "Data is successfully updated";

            } else {
                $groupType = new GroupType;
                $data['message'] = "Data is successfully inserted";
            }
                $groupType->name = $request->name;
                $groupType->client_id = $user->client_id;
                $groupType->added_by = $user->id;
                $groupType->save();
                $data['success'] = true;   
        } else {
            $data["success"] = false;
            $data["message"] = $validator->errors()->first();
        }
        
        return Response::json($data,200,array());  
    }

    public function delete(Request $request, $id){

        $user = User::AuthenticateUser($request->header("apiToken"));
        
        // $check_access = User::getAccess("performance-params",$user->id, -1);
        // if(!$check_access) {
        //     $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        // }

        DB::table('group_types')->where('id',$id)->where('client_id',$user->client_id)->delete();
        $data['success'] = true;
        $data['message'] = "Data is successfully deleted";
        return Response::json($data,200,array()); 
    }

}
