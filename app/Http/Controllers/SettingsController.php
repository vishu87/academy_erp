<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DateTime;

use Input, Redirect, Validator, Hash, Response, Session, DB;
use App\Models\User, App\Models\Center, App\Models\Group, App\Models\Student;

class SettingsController extends Controller{
    
    public function index(){
        return view('manage.settings.index',["sidebar"=>"settings","menu" => "admin"]);
    }

    public function init(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $items = DB::table("setting_params")->where("category",$request->category)->get();
        
        foreach($items as $item){
            $check = DB::table("setting_values")->where("client_id",$user->client_id)->where("param_id",$item->id)->first();
            if($check){
                $item->value = $check->value;
            }
        }

        $data["success"] = true;
        $data["items"] = $items;

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }


    public function saveSettings(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        
        foreach($request->items as $item){
            $check = DB::table("setting_values")->where("client_id",$user->client_id)->where("param_id",$item["id"])->first();
            if(!$check){
                if(isset($item["value"])){
                    if($item["value"]){
                        DB::table("setting_values")->insert(array(
                            "client_id" => $user->client_id,
                            "param_id" => $item["id"],
                            "value" => $item["value"],
                            "modified_by" => $user->id
                        ));
                    }
                }
            } else {
                DB::table("setting_values")->where("id",$check->id)->update(array(
                    "value" => $item["value"],
                    "modified_by" => $user->id
                ));
            }
        }

        $data["success"] = true;

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }

}