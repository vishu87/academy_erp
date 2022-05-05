<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\Models\User, App\Models\Lead, App\Models\LeadHistory, App\Models\Utilities, App\Models\LTHistory, App\Models\SpecialDiscount;

class LeadsController extends Controller{ 
    public function getIndexPage(){
        return view('manage.leads.index',["sidebar"=>"leads","menu" => "leads"]);
    }

    public function init(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("lead_op", $user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }
        
        $max_per_page = $request->max_per_page;
        $page_no = $request->page_no;

        $leads = Lead::listing();

        if(sizeof($request->cities) > 0){
            $leads = $leads->whereIn("leads.city_id",$request->cities);
        }
        
        if(sizeof($request->centers) > 0){
            $leads = $leads->whereIn("leads.center_id",$request->centers);
        }

        if(sizeof($request->status) > 0){
            $leads = $leads->whereIn("leads.status",$request->status);
        }

        if(sizeof($request->sources) > 0){
            $leads = $leads->whereIn("leads.lead_source",$request->sources);
        }

        if(sizeof($request->sub_sources) > 0){
            $leads = $leads->whereIn("leads.lead_sub_source",$request->sub_sources);
        }

        if($request->action_date_start != ""){
            $start_date = date("Y-m-d",strtotime($request->action_date_start));
            $leads = $leads->where("leads.action_date",">=",$start_date);
        }

        if($request->action_date_end != ""){
            $end_date = date("Y-m-d",strtotime($request->action_date_end));
            $leads = $leads->where("leads.action_date","<=",$end_date);
        }

        if($request->create_start != ""){
            $start_date = date("Y-m-d",strtotime($request->create_start));
            $leads = $leads->where("leads.created_at",">=",$start_date);
        }

        if($request->create_end != ""){
            $end_date = date("Y-m-d",strtotime($request->create_end));
            $leads = $leads->where("leads.created_at","<=",$end_date);
        }

        if($request->name){
            $leads = $leads->where("leads.name",'LIKE','%'.$request->name.'%');
        }

        if($request->mobile){
            $leads = $leads->where("leads.mobile",$request->mobile);
        }

        if($request->lead_for){
            $leads = $leads->where("leads.lead_for",$request->lead_for);
        }

        if( $request->assign_to){
            if($request->assign_to == '-1'){
                $leads = $leads->where("leads.assign_to",0);
            } else {
                $leads = $leads->where("leads.assign_to",$request->assign_to);
            }
        }

        $leads = $leads->where("leads.client_id",$user->client_id);

        if($request->type == "only_ids"){
            $data['success'] = true;
            $data['lead_ids'] = $leads->pluck("leads.id")->toArray();
            return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
        }

        $total = $leads->count();

        if($request->order_by){
            $leads = $leads->orderBy($request->order_by, $request->order_type);
        } else {
            $leads = $leads->orderBy("updated_at", "DESC");
        }

        $leads = $leads->skip(($page_no-1)*$max_per_page)->limit($max_per_page)->get();

        foreach ($leads as $lead) {
            $lead = $this->mapLead($lead);
        }

        $data['leads'] = $leads;
        $data['total'] = $total;
        $data['success'] = true;

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }

    private function mapLead($lead){
        $lead->mobile_trimmed = "xxxxxx".substr($lead->mobile, 6,4);
        if($lead->status == 3){
            $lead->bgcolor = '#FFA500';
            $lead->fontcolor = 'color:#fff';
        }elseif($lead->status == 4){
            $lead->bgcolor = '#9aed9a';
            $lead->fontcolor = 'color:#fff';
        }elseif($lead->status == 5){
            $lead->bgcolor = '#f26a6a';
            $lead->fontcolor = 'color:#fff';
        }

        if($lead->relevance == 1){
            $lead->rel_color = 'green';
        }elseif($lead->relevance == 2){
            $lead->rel_color = 'red';
        }elseif($lead->relevance == 3){
            $lead->rel_color = '#ffbf00';
        }
        
        $lead->action_date_dmy = Utilities::convertDate($lead->action_date);

        if($lead->assigned_to == 0) $lead->assigned_to = "";

        if($lead->dob){
            $lead->dob = Utilities::convertDate($lead->action_date);
        }

        return $lead;
    }

    public function parameters(Request $request){
        
        $user = User::AuthenticateUser($request->header("apiToken"));
        
        $data['status'] = Lead::status();
        $data['reasons'] = Lead::reasons();
        $data['lead_sources'] = Lead::lead_sources();
        // $data['sub_lead_sources'] = Lead::sub_lead_sources();
        $data['lead_for'] = Lead::lead_for();
        $data['states'] = Lead::states();
        // $data['relevance_list'] = Lead::relevance_list();
        $data['members'] = User::select('id as value','name as label')->where('role','!=',1)->orderBy('username','asc')->get();

        $data['success'] = true;

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }

    public function getAge(){
        $dob=date("Y-m-d",strtotime(Input::get('dob')));
        $diff = (date('Y') - date('Y',strtotime($dob)));
        return $diff;
    }

    public function autoFillByPincode(){
        $pincode = Input::get('pincode');
        $office_names = DB::table('city_pincodes')->where('pincode',$pincode)->get();
        $data['office_names'] = $office_names;
        return Response::json($data,200,[]); 
    }

    public function storeLead(Request $request){
        
        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("lead_op", $user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $values = [
            "mobile" => $request->mobile,
            "name" => $request->name,
            "status" => $request->status,
            "assigned_to" => $request->assigned_to
        ];
        $rules = [
            "name" => "required",
            "status" => "required",
            "assigned_to" => "required"
        ];

        if( $request->id ){
            $rules['mobile'] = "required|regex:/^([0-9\s\-\+\(\)]*)$/|digits:10|unique:leads,mobile,".$request['id'];
        } else {
            $rules['mobile'] = "required|unique:leads|regex:/^([0-9\s\-\+\(\)]*)$/|digits:10";
        }

        $messages = ["mobile.unique"=>"This mobile is already been taken in another lead ,Please try with another mobile number"];

        $validator = Validator::make($values,$rules,$messages);

        $success = true;

        if($validator->fails()){
            $success = false;
            $message = $validator->errors()->first();
        } else {

            $status = DB::table("lead_status")->find($request->status);

            if(!$request->id){
                if($status->date_req){
                    if(!$request->action_date){
                        $success = false;
                        $message = $status->action_date_name." is required";
                    }
                }

                if($status->call_note_req){
                    if(!$request->call_note){
                        $success = false;
                        $message = "Call note is required";
                    }
                }

                if($status->reason_req){
                    if(!$request->reason_id){
                        $success = false;
                        $message = "Reason is required";
                    }
                }
            }

            if($success){

                $add_history = false;

                if($request->id){
                    $lead = Lead::where("id",$request->id)->where("client_id",$user->client_id)->first();
                    $message = 'Lead details are updated successfully';

                    if(!$lead) die("Not authorized");

                } else {

                    $add_history = true;

                    $lead = Lead::where("mobile",$request->mobile)->where("client_id",$user->client_id)->first();
                    if(!$lead){
                        $lead = new Lead;
                        $message = 'New lead is added successfully';
                        $lead->client_id = $user->client_id;
                        $lead->created_at = date("Y-m-d H:i:s");
                        $lead->created_by = $user->id;
                    } else {
                        $message = 'Update has been made on existing lead';
                    }
                }

                $dob = Utilities::convertDateToDB($request->dob);
                if($dob){
                    $age = (date("Y") - date("Y",strtotime($dob)));
                } else {
                    $age = null;
                }

                $lead->name = $request->name;
                $lead->mobile = $request->mobile;
                $lead->gender = $request->gender;
                $lead->age = $age;
                $lead->dob = $dob;
                $lead->client_email = $request->client_email;
                $lead->lead_for = $request->lead_for;
                $lead->lead_source = $request->lead_source;
                $lead->school_name = $request->school_name;
                $lead->class_studying = $request->class_studying;
                $lead->remarks = $request->remarks;
                
                $lead->pincode = $request->pincode;
                $lead->client_address = $request->client_address;
                $lead->client_city_id = $request->client_city_id;
                $lead->client_state_id = $request->client_state_id;

                $lead->city_id = $request->city_id;
                $lead->center_id = $request->center_id;
                $lead->group_id = $request->group_id;

                if($add_history){
                    $lead->status = $request->status;
                    $lead->last_call_note = $request->call_note;
                    $lead->reason_id = $request->reason_id;
                    $lead->last_updated_by = $user->id;
                    $lead->action_date = Utilities::convertDateToDB($request->action_date);
                    $lead->assigned_to = $request->assigned_to;
                }
                
                $lead->save();

                if($add_history){
                    $leadHistory = new LeadHistory;
                    $leadHistory->lead_id = $lead->id;
                    $leadHistory->call_note = $request->call_note;
                    $leadHistory->call_made = $request->call_made ? 1 : 0;
                    $leadHistory->action_date = Utilities::convertDateToDB($request->action_date);

                    $leadHistory->status = $request->status;
                    $leadHistory->assigned_to = $request->assigned_to;
                    $leadHistory->created_by = $user->id;
                    $leadHistory->save();
                }
                
                $lead = Lead::listing()->where('leads.id',$lead->id)->first();
                $lead = $this->mapLead($lead);

                $data['lead'] = $lead;
            }
        }

        $data['success'] = $success;
        $data['message'] = $message;

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }

    public function history(Request $request, $lead_id){
        
        $user = User::AuthenticateUser($request->header("apiToken"));
        $check_access = User::getAccess("lead_op", $user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $history_data = Lead::listingHistory()->where('lead_id',$lead_id)->where("leads.client_id",$user->client_id)->orderBy('id','desc')->get();

        $data['history'] = $history_data;
        $data['success'] = true;
        return Response::json($data,200,array());
    }

    // public function updateLead(){
        
    //     $user = User::AuthenticateUser(Request::header("apiToken"));

    //     $request = Input::get('data');

    //     $check_duplicate_mobile = Lead::where('id','!=',$request['id'])->where('mobile',$request['mobile'])->first();
    //     $messages = ["age_group.not_in"=>"Please select age group"];
        
    //     $check = [
    //         "name"=>$request['name'],
    //         "gender"=>$request['gender'],
    //         "dob"=>$request['dob'],
    //         "mobile"=>$request['mobile'],
    //         "city_id"=>$request['city_id'],
    //         "age_group"=>$request['city_id'],
    //     ];
    //     $validator = Validator::make($check,[
    //         "name" => "required",
    //         "gender" => "required",
    //         "dob" => "required|date",
    //         "mobile" => "required|regex:/^([0-9\s\-\+\(\)]*)$/|digits:10|unique:leads,mobile,".$request['id'],
    //         "city_id" => "required",
    //         "age_group" => "required|not_in:0",
    //     ],$messages);
    //     if($validator->fails()){
    //         $data['success'] = false;
    //         $data['message'] = $validator->errors()->first();
    //     }else{

    //         if($request["city_id"] != -1 && (!isset($request["center_id"])) ) {
    //             $data['success'] = false;
    //             $data['message'] = "Kindly select center";
    //             return Response::json($data,200,array());
    //         }
    //         $dob = date("Y-m-d",strtotime($request['dob']));
    //         $age = (date("Y") - date("Y",strtotime($dob)));

    //         $lead = Lead::find($request['id']);
    //         $lead->name = $request['name'];
    //         $lead->age = $age;
    //         $lead->dob = $dob;
    //         $lead->mobile = $request['mobile'];
    //         $lead->gender = $request['gender'];

    //         $lead->lead_source = $request['lead_source'];
    //         $lead->lead_sub_source = $request['lead_sub_source'];
            
    //         $lead->client_email = $request['client_email'];
    //         $lead->client_address = $request['client_address'];
    //         $lead->client_city = $request['client_city'];

    //         $lead->client_city_id = $request['client_city_id'];
    //         $lead->client_state_id = $request['client_state_id'];

    //         $lead->last_updated_by = $user->id;
    //         $lead->city_id = $request['city_id'];
    //         $lead->other_city_id = $request['other_city_id'];
    //         $lead->center_id = $request['center_id'];
    //         $lead->age_group = $request['age_group'];
    //         $lead->lead_cost = $request['lead_cost'];
    //         $lead->relevance = $request['relevance'];
    //         $lead->lead_for = $request['lead_for'];

    //         if(Input::has('campaign_code')){
    //             if(Input::get('campaign_code') != ''){
    //                 $check_campaign = DB::table('campaign_filters')->where('code',strtoupper(Input::get('campaign_code')))->first();
    //                 if(!$check_campaign){
    //                     $data['success'] = false;
    //                     $data['message'] = "Invalid campaign id";
    //                     return Response::json($data,200,[]);
    //                 }else{
    //                     $lead->campaign_id = $check_campaign->id;
    //                 }
    //             }
    //         }

    //         $lead->save();
            
    //         $data['message'] = 'Lead details are updated successfully';

    //         $lead = Lead::listing()->where('leads.id',$lead->id)->first();
    //         if($lead){

    //             $lead->mobile_trimmed = "xxxxxx".substr($lead->mobile, 6,4);

    //             if($lead->status == 3){
    //                 $lead->bgcolor = '#FFA500';
    //                 $lead->fontcolor = 'color:#fff';
    //             }elseif($lead->status == 4){
    //                 $lead->bgcolor = '#9aed9a';
    //                 $lead->fontcolor = 'color:#fff';
    //             }elseif($lead->status == 5){
    //                 $lead->bgcolor = '#f26a6a';
    //                 $lead->fontcolor = 'color:#fff';
    //             }

    //             if($lead->relevance == 1){
    //                 $lead->rel_color = 'green';
    //             }elseif($lead->relevance == 2){
    //                 $lead->rel_color = 'red';
    //             }elseif($lead->relevance == 3){
    //                 $lead->rel_color = '#ffbf00';
    //             }
    //         }
    //         $lead->history = LeadHistory::select('lead_history.*','lead_status.status_value','users.name as assigned_member','m2.name as assigned_by')
    //         ->leftJoin('lead_status','lead_status.id','=','lead_history.status')
    //         ->leftJoin('users','users.id','=','lead_history.assign_to')
    //         ->leftJoin('users as m2','m2.id','=','lead_history.created_by')
    //         ->where('lead_id',$lead->id)
    //         ->orderBy('id','desc')
    //         ->get();

    //         $data['success'] = true;
    //         $data['message'] = "Lead details updated successfully";
    //         $data['lead'] = $lead;
    //     }
    //     return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    // }


    public function addNote(Request $request){
        
        $user = User::AuthenticateUser($request->header("apiToken"));

        $check_access = User::getAccess("lead_op", $user->id, -1);
        if(!$check_access) {
            $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
        }

        $values = [
            "lead_id" => $request->id,
            "status" => $request->status,
            "assigned_to" => $request->assigned_to
        ];
        $rules = [
            "lead_id" => "required",
            "status" => "required",
            "assigned_to" => "required"
        ];

        $validator = Validator::make($values,$rules);

        $success = true;

        if($validator->fails()){
            $success = false;
            $message = $validator->errors()->first();
        } else {

            $lead = Lead::where("id",$request->id)->where("client_id",$user->client_id)->first();
            if(!$lead) {
                $data = ["success" => false, "message"=>"Not allowed"]; return Response::json($data, 200 ,[]);
            }
            
            $status = DB::table("lead_status")->find($request->status);

            if($status->date_req){
                if(!$request->action_date){
                    $success = false;
                    $message = $status->action_date_name." is required";
                }
            }

            if($status->call_note_req){
                if(!$request->call_note){
                    $success = false;
                    $message = "Call note is required";
                }
            }

            if($status->reason_req){
                if(!$request->reason_id){
                    $success = false;
                    $message = "Reason is required";
                }
            }

            if($success){

                $lead->status = $request->status;
                $lead->last_call_note = $request->call_note;
                $lead->reason_id = $request->reason_id;
                $lead->last_updated_by = $user->id;
                $lead->action_date = Utilities::convertDateToDB($request->action_date);
                $lead->assigned_to = $request->assigned_to;
                
                $lead->save();

                $leadHistory = new LeadHistory;
                $leadHistory->lead_id = $lead->id;
                $leadHistory->call_note = $request->call_note;
                $leadHistory->call_made = $request->call_made ? 1 : 0;
                $leadHistory->action_date = Utilities::convertDateToDB($request->action_date);

                $leadHistory->status = $request->status;
                $leadHistory->assigned_to = $request->assigned_to;
                $leadHistory->created_by = $user->id;
                $leadHistory->save();
                $message = "Note is successfully saved";

                $lead = Lead::listing()->where('leads.id',$lead->id)->first();
                $lead = $this->mapLead($lead);
                $data["lead"] = $lead;
            }
            
        }

        $data['success'] = $success;
        $data['message'] = $message;

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }

    public function bulk_upload(){
        set_time_limit(1200);

        $cities = DB::table("city")->select(DB::raw("LOWER(city_name) as city_name , id"))->pluck("id","city_name");
        $states = DB::table('cities')->pluck('state_id','id');
        $all_cities = DB::table('cities')->select(DB::raw("LOWER(city_name) as city_name , id"))->pluck('id','city_name');
        $centers = DB::table("center")->select(DB::raw("LOWER(center_name) as center_name , id"))->pluck("id","center_name");

        $sources = DB::table("lead_sources")->select(DB::raw("LOWER(source) as source , id"))->pluck("id","source");

        $reasons = DB::table("lead_reasons")->select(DB::raw("LOWER(reason) as reason , id"))->pluck("id","reason");
        $status_values = DB::table("lead_status")->select(DB::raw("LOWER(status_value) as status_value , id"))->pluck("id","status_value");

        $campaigns = DB::table('campaign_filters')->pluck("id","code")->all();

        $members = DB::table('members')->pluck('id','username')->all();

        $destination = 'uploads/';
        
        if(Input::hasFile('media')){
            $file = Input::file('media');
            $extension = Input::file('media')->getClientOriginalExtension();
            if($extension == "csv"){
                $name = 'Leads_'.strtotime("now").'.'.strtolower($extension);
                $file = $file->move($destination, $name);
                $filename = $destination.$name;
                $data["success"] = true;
            }else{
                $data['success'] = false;
                $data['message'] = 'Invalid file format';
                return Response::json($data,200,array());
            }
        }else{
            $data['success'] = false;
            $data['message'] ='file not found';
            return Response::json($data,200,array());
        }

        $uploadedfile = fopen($filename, 'r');
        $count = 1;
        while (($line = fgetcsv($uploadedfile)) !== FALSE) {
            if($count == 1) {
                $count++;
                continue;
            }
            $created_at = $line[0];
            $name = $line[1];
            $age = $line[2];
            $mobile = $line[3];
            $source = strtolower($line[4]);
            $status = strtolower($line[5]);
            $address = $line[6];
            $address_city = strtolower($line[7]);
            $center = strtolower($line[8]);
            $city = strtolower($line[9]);
            $reason = strtolower($line[10]);
            $call_note1_date = $line[11];
            $call_note1 = $line[12];
            $call_note2_date = $line[13];
            $call_note2 = $line[14];
            $remarks = $line[15];
            $lead_cost = $line[16];
            $client_email = $line[17];
            $sub_source = strtolower($line[18]);
            $campaign_id = $line[19];
            $assign_to = $line[20];

            if($campaign_id){
                $campaign_id = strtoupper($campaign_id);
            }

            $check = Lead::where("mobile",trim($mobile))->count();

            if(!$name || $check > 0){
                Lead::where("mobile",trim($mobile))->update(["status"=>1]);
                // continue;

            } else {

                $assign_to_member = (isset($members[$assign_to]))?$members[$assign_to]:NULL;

                $lead = new Lead;
                $lead->name = $name;
                $lead->age = $age;
                $lead->mobile = $mobile;
                $lead->lead_source = (isset($sources[$source]))?$sources[$source]:null;
                $lead->lead_sub_source = (isset($sources[$sub_source]))?$sources[$sub_source]:null;
                $lead->status = (isset($status_values[$status]))?$status_values[$status]:1;
                
                $lead->campaign_id = (isset($campaigns[$campaign_id]))?$campaigns[$campaign_id]:null;

                $lead->client_email = $client_email;

                $lead->client_address = $address;
                $lead->client_city_id = (isset($all_cities[$address_city])) ? $all_cities[$address_city] : -1;
                $lead->client_state_id = (isset($states[$lead->client_city_id])) ? $states[$lead->client_city_id] : -1;
                $lead->center_id = (isset($centers[$center]))?$centers[$center]:null;
                $lead->city_id = (isset($cities[$city]))?$cities[$city]:-1;

                if($lead->status == 5){
                    $lead->reason_id = (isset($reasons[$reason]))?$reasons[$reason]:5;
                    if($lead->reason_id == 5){
                        $lead->reason_others = $reason;
                    }
                }

                if($lead->status == 3){
                    $lead->demo_attended = 1;
                }

                $lead->created_at = date("Y-m-d",strtotime($created_at));
                $lead->assign_to = $assign_to_member;
                $lead->save();

                if($call_note2_date == ""){
                    $lead->last_call_note = $call_note1;
                } else {
                    $lead->last_call_note = $call_note2;
                }

                $lead->remarks = $remarks;
                $lead->lead_cost = $lead_cost;

                $lead->save();

                if($call_note1_date != ""){
                    $call_note = new LeadHistory;
                    $call_note->lead_id = $lead->id;
                    $call_note->call_note = $call_note1;
                    $call_note->created_at = date("Y-m-d",strtotime($call_note1_date));
                    if($call_note2_date == ""){
                        $call_note->status = $lead->status;
                    } else {
                        $call_note->status = 1;
                    }
                    $call_note->save();
                }

                if($call_note2_date != ""){
                    $call_note = new LeadHistory;
                    $call_note->lead_id = $lead->id;
                    $call_note->call_note = $call_note2;
                    $call_note->created_at = date("Y-m-d",strtotime($call_note2_date));
                    $call_note->status = $lead->status;
                    $call_note->save();
                }

                $count++;
            }

            
        }

        fclose($uploadedfile);
        unlink($filename);
        $data['message'] = ($count-2)." leads were uploaded sucessfully";
        $data['all_cities'] = $all_cities;
        return Response::json($data,200,array());
    }
}


                 