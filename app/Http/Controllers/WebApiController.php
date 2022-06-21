<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\Lead, App\Models\Client, App\Models\Registration, App\Models\Utilities;

class WebApiController extends Controller
{	

    public function stateCityCenter(){

        $cities = DB::table('city')->where('inactive',0)->get(); 
        $centers = DB::table('center')->get(); 
        $groups = DB::table('groups')->get(); 
        $states = DB::table('states')->get(); 

        $data['success'] = true;
        $data['cities'] = $cities;
        $data['centers'] = $centers;
        $data['groups'] = $groups;
        $data['states'] = $states;
        return Response::json($data, 200, []);
    }


    public function stateCity($state_id){
        $state_cities = DB::table('city')->where('inactive',0)->where('state_id',$state_id)->get(); 
        $data['success'] = true;
        $data['state_cities'] = $state_cities;
        return Response::json($data, 200, []);
    }

    public function store(Request $request){
        
        $client_code = $request->header("clientId");
        $client = Client::AuthenticateClient($client_code);
        $client_id = $client->id;

        $cre = [
            "name" => $request->name,
            "year" => $request->year,
            "month" => $request->month,
            "date" => $request->date,
            "gender" => $request->gender,
            "father_name" => $request->father_name,
            "mother_name" => $request->mother_name,
            "prim_email" => $request->prim_email,
            "prim_mobile" => $request->prim_mobile,
            "prim_relation_to_student" => $request->prim_relation_to_student,
            "address" => $request->address,
            "pin_code" => $request->pin_code,
            "training_city_id" => $request->training_city_id,
            "group_id" => $request->group_id,
            "kit_size" => $request->kit_size
        ];

        $rules = [
            "name" => "required",
            "year" => "required",
            "month" => "required",
            "date" => "required",
            "gender" => "required",
            "father_name" => "required",
            "mother_name" => "required",
            "prim_email" => "required",
            "prim_mobile" => "required",
            "prim_relation_to_student" => "required",
            "address" => "required",
            "pin_code" => "required",
            "training_city_id" => "required",
            "group_id" => "required",
            "kit_size" => "required"
        ];

        $validator = Validator::make($cre,$rules);

        if ($validator->fails()) {
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
            return Response::json($data, 200, []);
        } else {

            if($request->id){
                $registration = Registration::find($request->id);
                $message = "Data successfully updated....";
            } else {
                $registration = new Registration;
                $message = "Data successfully inserted....";
            }

            $registration->name = $request->name;
            $registration->dob = $request->year.'-'.$request->month.'-'. $request->date;
            $registration->gender = $request->gender;
            $registration->father = $request->father_name;
            $registration->mother = $request->mother_name;
            $registration->prim_email = $request->prim_email;
            $registration->prim_mobile = $request->prim_mobile;
            $registration->prim_relation_to_student = $request->prim_relation_to_student;
            $registration->sec_email = $request->sec_email;
            $registration->sec_mobile = $request->sec_mobile;
            $registration->sec_relation_to_student = $request->sec_relation_to_student;
            $registration->address = $request->address;
            $registration->pin_code = $request->pin_code;
            $registration->address_state_id = $request->address_state_id;
            $registration->address_city_id = $request->address_city_id;
            $registration->training_city_id = $request->training_city_id;
            $registration->training_center_id = $request->training_center_id;
            $registration->group_id = $request->group_id;
            $registration->kit_size = $request->kit_size;
            $registration->client_id = $client_id;
            $registration->type = "registration";
            $registration->save();

            $reg_data = DB::table('registrations')->select('registrations.*','states.state_name','addressCity.city_name as addressCity','trainingCity.city_name as trainingCity','center.center_name','groups.group_name')
            ->leftJoin('states','states.id','=','registrations.address_state_id')
            ->leftJoin('city as addressCity','addressCity.id','=','registrations.address_city_id')
            ->leftJoin('city as trainingCity','trainingCity.id','=','registrations.training_city_id')
            ->leftJoin('center','center.id','=','registrations.training_center_id')
            ->leftJoin('groups','groups.id','=','registrations.group_id')
            ->where('registrations.id',$registration->id)
            ->first();

            $reg_data->dob = Utilities::convertDateShow($reg_data->dob);

            $data['reg_data'] = $reg_data;
            $data['success'] = true;
            $data["message"] = $message;
            return Response::json($data, 200, []);

        }
    }

    public function storeLead(Request $request){

        $client_code = $request->header("clientId");
        $client = Client::AuthenticateClient($client_code);
        $client_id = $client->id;

        $cre = [
            "name" => $request->name,
            "year" => $request->year,
            "month" => $request->month,
            "date" => $request->date,
            "mobile" => $request->mobile,
            "email" => $request->email,
            "city_id" => $request->city_id
        ];

        $rules = [
            "name" => "required",
            "year" => "required",
            "month" => "required",
            "date" => "required",
            "mobile" => "required",
            "email" => "required",
            "city_id" => "required",
        ];

        $validator = Validator::make($cre,$rules);

        if ($validator->fails()) {
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
            return Response::json($data, 200, []);
        } else {

            $lead = new \stdClass;
            $lead->name = $request->name;
            $lead->dob = $request->year."-".$request->month."-".$request->date;
            $lead->mobile = $request->mobile;
            $lead->client_email = $request->email;
            $lead->city_id = $request->city_id;
            $lead->remarks = $request->remarks;
            $lead->document = $request->document;
            $lead->lead_for = $request->lead_for;
            $lead->client_id = $client_id;
            if($request->visit_date){
                $lead->action_date = Utilities::convertDateToDB($request->visit_date);
            } else {
                $lead->action_date = date("Y-m-d");
            }

            Lead::storeOpenLead($lead);

            $data['success'] = true;
            $data["message"] ="Lead is successfully saved";
            
            return Response::json($data, 200, []);

        }
    }

    public function getSchedule($group_id){

        $start_date = strtotime("today");
        $visit_time = "";

        $operation_days = DB::table("operation_days")->where("group_id",$group_id)->get();
        $days = [];
        foreach($operation_days as $item){
            $days[] = $item->day;
            if(!$visit_time){
                $visit_time = date("h:iA",strtotime($item->from_time));
            }
        }

        $final_dates = [];
        for ($i = 0; $i < 30; $i++) { 
            $date = $start_date + $i*86400;
            $day_of_week = date('w',$date) + 1;

            if(in_array($day_of_week, $days)){
                $final_dates[] = ["value"=>date("d-m-Y",$date),"label"=>date("D d-M",$date)];
            }

        }

        $data['success'] = true;
        $data['visit_dates'] = $final_dates;
        $data['visit_time'] = $visit_time;
        return Response::json($data, 200, []);
    }

    public function storeDemo(Request $request){
        
    }

}
