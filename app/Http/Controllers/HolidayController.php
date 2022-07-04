<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DateTime;

use Input, Redirect, Validator, Hash, Response, Session, DB;
use App\Models\User, App\Models\Holiday, App\Models\Utilities;

class HolidayController extends Controller{
    
    public function index(){
        User::pageAccess(25);

        return view('manage.holiday.index',["sidebar"=>"holidays","menu" => "admin"]);
    }

    public function init(Request $request){
        // User::pageAccess(25);        
        $holidays = DB::table('holidays')->get();

        foreach ($holidays as  $holiday) {
            $holiday->date = Utilities::convertDate($holiday->date);
        }

        $data["holidays"] = $holidays;
        $data["success"] = true;
        $data["message"] = "";
        return Response::json($data, 200, []);
    }

    public function save(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        // User::pageAccess(25);

        if($request->id){
            $holidays = Holiday::find($request->id);
            $data["message"] = "Holiday successfully inserted";
        } else {
            $data["message"] = "Holiday successfully updated";
            $holidays =  new Holiday;
        }
        $holidays->name = $request->name;
        $holidays->date = Utilities::convertDateToDB($request->date);
        $holidays->client_id = $user->client_id;
        $holidays->added_by = $user->id;
        $holidays->save();

        $data["success"] = true;
        return Response::json($data, 200, []);
    }

    public function delete(Request $request, $id){

        $user = User::AuthenticateUser($request->header("apiToken"));

        DB::table('holidays')->where('id',$id)->where('client_id',$user->client_id)->delete();
        $data['success'] = true;
        $data['message'] = "Holidays is successfully deleted";
        return Response::json($data,200,array()); 
    }
}