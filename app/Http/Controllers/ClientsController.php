<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Redirect, Validator, Hash, Response, Session, DB, App\Models\User, App\Models\Client;

class ClientsController extends Controller{
    public function index(){
        return view('manage.clients.index',['menu' => "academy"]);
    }

    public function getList(Request $request){
        // $user = User::AuthenticateUser($request->header("apiToken"));
        $list  = DB::table('clients')->get();
        $data['success'] = true;
        $data['list'] = $list;
        return Response::json($data, 200, array());
    }

    public function save(Request $request){
        $cre = [
            "code" => $request->code,
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "address" => $request->address
        ];

        $rules = [
            "code"=>"required",
            "name"=>"required",
            "email"=>"required",
            "phone"=>"required",
            "address"=>"required"
        ];

        $validator = validator::make($cre,$rules);

        if ($validator->fails()) {
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
            return Response::json($data, 200, []);
        } else {
            if($request->id){
                $client = Client::find($request->id);
            } else {
                $client = new Client;
            }
            $client->code = $request->code;
            $client->name = $request->name;
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->address = $request->address;
            $client->save();

            $data['message'] = "data successfully saved";
            $data['success'] = true; 
            return Response::json($data, 200, []);
        }
    }

    public function delete($id){
        DB::table('clients')->where('id',$id)->delete();
        $data['success'] = true;
        $data['message'] = "client successfully deleted";
        return Response::json($data, 200, []);
    }   
}


                 