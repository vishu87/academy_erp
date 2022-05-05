<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Input, Redirect, Validator, Hash, Response, Session, DB, Request, App\User;

class ClientsController extends Controller{
    public function index(){
        return view('manage.clients.index');
    }

    public function getList(){
        $user = User::AuthenticateUser(Request::header("apiToken"));
        $list  = DB::table('clients')->get();
        $data['success'] = true;
        $data['list'] = $list;
        return Response::json($data, 200, array());
    }

    public function save(){
        $user = User::AuthenticateUser(Request::header("apiToken"));
        $client = Input::get('client');
        $validator = Validator::make($client,["name"=>"required","email"=>"required","phone"=>"required","address"=>"required"]);
        if ($validator->fails()) {
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
        }else{
            if ($client['id'] == 0) {
                DB::table('clients')->insert([
                    "name"=>$client["name"],
                    "email"=>$client["email"],
                    "phone"=>$client["phone"],
                    "address"=>$client["address"],
                ]);
                if ($user->parent_id  != 0) {
                    $parent_id = $user->parent_id;
                }else{
                    $parent_id = $user->id;
                }

                DB::table('users')->insert([
                    "username"=>$client["name"],
                    "password"=>Hash::make($client["name"]),
                    "email"=>$client["email"],
                    "parent_id"=>$parent_id,
                    "api_key"=>Hash::make($client["name"]),
                    "roles"=>4,
                ]);

                $data['success'] = true;
                $data['message'] = "data saved successfully";
            }else{
                DB::table('clients')->where('id',$client['id'])->update([
                    "name"=>$client["name"],
                    "email"=>$client["email"],
                    "phone"=>$client["phone"],
                    "address"=>$client["address"],
                ]);

                $data['success'] = true;
                $data['message'] = "data updated successfully";                
            }
        }

        return Response::json($data, 200, array());
    }

    public function delete(){
        $user = User::AuthenticateUser(Request::header("apiToken"));
        $id = Input::get('id');
        $check = DB::table('clients')->find($id);
        if ($check) {
            DB::table('clients')->where('id',$id)->delete();
            $data['success'] = true;
            $data['message'] = "client deleted successfully";
        }else{
            $data['success'] = false;
            $data['message'] = "something went wrong";
        }

        return Response::json($data, 200, array());
    }   
}


                 