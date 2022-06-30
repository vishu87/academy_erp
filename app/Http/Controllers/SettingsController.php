<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DateTime;

use Input, Redirect, Validator, Hash, Response, Session, DB;
use App\Models\User, App\Models\Center, App\Models\Group, App\Models\Student;

class SettingsController extends Controller{
    
    public function index(){
        User::pageAccess(25);
        return view('manage.settings.index',["sidebar"=>"settings","menu" => "admin"]);
    }

    public function init(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $items = DB::table("setting_params")->where("category",$request->category)->orderBy("priority","ASC")->get();
        
        foreach($items as $item){
            $check = DB::table("setting_values")->where("client_id",$user->client_id)->where("param_id",$item->id)->first();
            if($check){
                $item->value = $check->value;
            }

            if($item->type == "image"){
                $item->resize = 0;
                $item->crop = 0;
                $item->thumb = 0;
                $item->width = 0;
                $item->height = 0;
                $fields = explode(',',$item->details);
                foreach($fields as $field){
                    $field_ar = explode(':',$field);
                    if(isset($field_ar[1])){
                        $item->{$field_ar[0]} = $field_ar[1];
                    }
                }
            }
            unset($item->details);
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