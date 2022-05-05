<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\Lead;

class WebController extends Controller
{	

    public function registrations(){
        $heading = "Academy Registration Form";
        $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam";
        return view('web.registrations',[
            "heading" => $heading,
            "description" => $description,
            "logo_url" => "http://bu.chethanhp.com/wp-content/uploads/2022/01/Group-60782.png",
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => 1
        ]);
    }

    public function renewals(){
        return view('web.renewals',[
            "logo_url" => "http://bu.chethanhp.com/wp-content/uploads/2022/01/Group-60782.png",
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => 1
        ]);
    }

    public function demoShedule(){
        return view('web.demo_schedule',[
            "logo_url" => "http://bu.chethanhp.com/wp-content/uploads/2022/01/Group-60782.png",
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => 1
        ]);
    }

    public function lead($type){

        $heading = "Apply for Scholarship";
        $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam";

        return view('web.lead',[
            "heading" => $heading,
            "description" => $description,
            "logo_url" => "http://bu.chethanhp.com/wp-content/uploads/2022/01/Group-60782.png",
            "background" => "radial-gradient(at top left, #8E171A 5%, #000000 29%)",
            "client_id" => 1
        ]);
    }

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

    public function store(Request $request){

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
            "kit" => $request->kit,
            "fee_plan" => $request->fee_plan
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
            "kit" => "required",
            "fee_plan" => "required"
        ];

        $validator = Validator::make($cre,$rules);

        if ($validator->fails()) {
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
            return Response::json($data, 200, []);
        } else {

            $data = [
                "name" => $request->name,
                "dob" => $request->year.'-'.$request->month.'-'. $request->date,
                "gender" => $request->gender,
                "father" => $request->father_name,
                "mother" => $request->mother_name,
                "prim_email" => $request->prim_email,
                "prim_mobile" => $request->prim_mobile,
                "prim_relation_to_student" => $request->prim_relation_to_student,
                "sec_email" => $request->sec_email,
                "sec_mobile" => $request->sec_mobile,
                "sec_relation_to_student" => $request->sec_relation_to_student,
                "address" => $request->address,
                "pin_code" => $request->pin_code,
                "city_id" => $request->training_city_id,
                "center_id" => $request->training_center_id,
                "group_id" => $request->group_id,
                "kit" => $request->kit,
                "fee_plan" => $request->fee_plan,
            ];

            DB::table('onfield_registrations')->insert($data);
            $data['success'] = true;
            $data["message"] ="Data successfully inserted....";
            return Response::json($data, 200, []);

        }
    }

    public function storeLead(Request $request){

        $client_id = $request->header("clientId");

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
            $lead->client_id = $client_id;
            $lead->action_date = date("Y-m-d");

            Lead::storeOpenLead($lead);

            $data['success'] = true;
            $data["message"] ="Lead is successfully saved";
            
            return Response::json($data, 200, []);

        }
    }



}
