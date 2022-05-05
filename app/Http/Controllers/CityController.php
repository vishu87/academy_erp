<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\PaymentHistory, App\Models\PaymentItem, App\Models\User;

class CityController extends Controller{ 

    public function getCityPage(){
       return view('manage.cities.add_city',["sidebar"=>"city","menu" => "admin"]);
    }

    public function getCityList(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("admin_rights",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $city_list = DB::table('city')->select('city.id','city.city_name','city.base_city_id',
            'city.state_id','states.state_name')->leftJoin('states','states.id', '=', 'city.state_id')->where("client_id",$user->client_id)->where("inactive",0)->get();
        $data['city_list'] = $city_list;

        return Response::json($data, 200, array());
    }

    public function saveCity(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("admin_rights",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $validator = Validator::make([
            "city_name" => $request->city_name,
            "base_city_id" => $request->base_city_id,
            "state_id" => $request->state_id,
            ],[
            "city_name" => "required",
            "base_city_id" => "required",
            "state_id" => "required",
        ]);

        if ($validator->passes()) {
            if ($request->id) {
                DB::table('city')->where("id",$request->id)->where("client_id",$user->client_id)->update([
                    "city_name" => $request->city_name,
                    "base_city_id" => $request->base_city_id,
                    "state_id" => $request->state_id
                ]);
            } else {
                DB::table('city')->insert([
                    "city_name" => $request->city_name,
                    "base_city_id" => $request->base_city_id,
                    "state_id" => $request->state_id,
                    "client_id" => $user->client_id,
                    "created_at" => date("Y-m-d H:i:s")
                ]);
            }

            $data['success'] = true;
            $data['message'] = "City has been updated successfully";
        } else {
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
        }

        return Response::json($data, 200, array());
    }

    public function deleteCity(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $check_access = User::getAccess("admin_rights",$user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $city = DB::table("city")->where("id",$request->id)->where("client_id",$user->client_id)->first();
        if($city) {
            DB::table('city')->where('id',$request->id)->update([
                "inactive" => 1
            ]);
            $data['success'] = true;
            $data['message'] = "City is successfully marked inactive";
        } else {
            $data['success'] = false;
            $data['message'] = "City is not found";
        }
        return Response::json($data, 200, array());
    }
    
}


                 