<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\User, App\Models\Lead, App\Models\LeadHistory, App\Models\Utilities, App\Models\LTHistory, App\Models\SpecialDiscount;

class MasterLeadsController extends Controller
{ 
    public function index(){
        return view('manage.masterLeads.index',["sidebar"=>"master-lead","menu" => "leads"]);
    }

    public function leadsFor(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        $data["leadsFor"] = DB::table('lead_for')->where('client_id',$user->client_id)->get();
        $data["success"] = true;
        $data["message"] = "";
        return Response::json($data, 200, []);
    }

    public function leadsStatus(Request $request){
        $data["leadStatus"] = DB::table('lead_status')->get();
        $data["success"] = true;
        $data["message"] = "";
        return Response::json($data, 200, []);
    }

    public function leadsReasons(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        $data["leadReasons"] = DB::table('lead_reasons')->where('client_id',$user->client_id)->get();
        $data["success"] = true;
        $data["message"] = "";
        return Response::json($data, 200, []);
    }

    public function leadsSources(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        $data["leadSources"] = DB::table('lead_sources')->where('client_id',$user->client_id)->get();
        $data["success"] = true;
        $data["message"] = "";
        return Response::json($data, 200, []);
    }

    public function leadForStore(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        $cre = [
            "label" => $request->label,
            "slug" => $request->slug
        ];
        $rules = [
            "label" => "required",
            "slug" => "required"

        ];

        $validator = Validator::make($cre,$rules);
        if($validator->fails()){
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
            return Response::json($data, 200, []);
        } else {
            $data = [
                "label" => $request->label,
                "client_id" => $user->client_id,
                "slug" => $request->slug
            ];

            if($request->id){
                DB::table('lead_for')->where('id',$request->id)->update($data);
                $data["message"] = "Data successfully updated...";
            } else {
                DB::table('lead_for')->insert($data);
                $data["message"] = "Data successfully inserted...";
            }
            $data["success"] = true;
            return Response::json($data, 200, []);
        }
    }

    public function leadsForDelete(Request $request, $lead_for_id){
        DB::table('lead_for')->where('id',$lead_for_id)->delete();
        $data["success"] = true;
        $data["message"] = "Data successfully deleted...";
        return Response::json($data, 200, []);
    }

    public function leadStatusStore(Request $request){
        $cre = [
            "status_value" => $request->status_value,
            "action_date_name" => $request->action_date_name,
            "date_req" => $request->date_req,
            "call_note_req" => $request->call_note_req,
            "reason_req" => $request->reason_req,
            "color" => $request->color
        ];
        $rules = [
            "status_value" => "required",
            "action_date_name" => "required",
            "date_req" => "required",
            "call_note_req" => "required",
            "reason_req" => "required",
            "color" => "required",
        ];

        $validator = Validator::make($cre,$rules);
        if($validator->fails()){
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
            return Response::json($data, 200, []);
        } else {
            $data = [
                "status_value" => $request->status_value,
                "action_date_name" => $request->action_date_name,
                "date_req" => $request->date_req,
                "call_note_req" => $request->call_note_req,
                "reason_req" => $request->reason_req,
                "color" => $request->color
            ];

            DB::table('lead_status')->where('id',$request->id)->update($data);
            $data["message"] = "Data successfully updated...";
            $data["success"] = true;
            return Response::json($data, 200, []);
        }
    }

    public function leadReasonStore(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        $cre = [
            "reason" => $request->reason
        ];
        $rules = [
            "reason" => "required"
        ];

        $validator = Validator::make($cre,$rules);
        if($validator->fails()){
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
            return Response::json($data, 200, []);
        } else {
            $data = [
                "reason" => $request->reason,
                "client_id" => $user->client_id
            ];

            if($request->id){
                DB::table('lead_reasons')->where('id',$request->id)->update($data);
                $data["message"] = "Data successfully updated...";
            } else {
                DB::table('lead_reasons')->insert($data);
                $data["message"] = "Data successfully inserted...";
            }
            $data["success"] = true;
            return Response::json($data, 200, []);
        }
    }

    public function leadsReasonDelete(Request $request, $lead_reason_id){
        DB::table('lead_reasons')->where('id',$lead_reason_id)->delete();
        $data["success"] = true;
        $data["message"] = "Data successfully deleted...";
        return Response::json($data, 200, []);
    }

    public function leadSourceStore(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        $cre = [
            "source" => $request->source
        ];
        $rules = [
            "source" => "required"
        ];

        $validator = Validator::make($cre,$rules);
        if($validator->fails()){
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
            return Response::json($data, 200, []);
        } else {
            $data = [
                "source" => $request->source,
                "client_id" => $user->client_id
            ];

            if($request->id){
                DB::table('lead_sources')->where('id',$request->id)->update($data);
                $data["message"] = "Data successfully updated...";
            } else {
                DB::table('lead_sources')->insert($data);
                $data["message"] = "Data successfully inserted...";
            }
            $data["success"] = true;
            return Response::json($data, 200, []);
        }
    }

    public function leadsSourceDelete(Request $request, $lead_source_id){
        DB::table('lead_sources')->where('id',$lead_source_id)->delete();
        $data["success"] = true;
        $data["message"] = "Data successfully deleted...";
        return Response::json($data, 200, []);
    }

}

                 