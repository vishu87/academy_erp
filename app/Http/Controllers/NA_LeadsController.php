<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Redirect, Validator, Hash, Response, Session, DB;
use App\User, App\Lead, App\LeadHistory, App\Utilities, App\LTHistory, App\SpecialDiscount;

class LeadsController extends Controller{ 
    public function getIndexPage(){
        return view('manage.leads.index',["sidebar"=>"leads","menu" => "leads"]);
    }

    public function init(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));
        
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

        $total = $leads->count();

        $leads = $leads->skip(($page_no-1)*$max_per_page)->limit($max_per_page)->get();

        foreach ($leads as $lead) {
            
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

            if($lead->assign_to == 0) $lead->assign_to = "";

            if($lead->dob){
                $lead->dob = Utilities::convertDate($lead->action_date);
            }

        }

        $data['leads'] = $leads;
        $data['total'] = $total;
        $data['success'] = true;

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }

    public function parameters(){
        
        $user = User::AuthenticateUser(Request::header("apiToken"));
        
        $data['status'] = Lead::status();
        $data['reasons'] = Lead::reasons();
        $data['lead_sources'] = Lead::lead_sources();
        $data['sub_lead_sources'] = Lead::sub_lead_sources();
        $data['states'] = Lead::states();
        $data['relevance_list'] = Lead::relevance_list();
        
        $data['cities'] = DB::table('city')->select('id','city_name')->get();
        $data['centers'] = DB::table('center')->select('id','center_name','city_id')->get();
        $data['groups'] = DB::table('groups')->select('id','group_name','center_id')->get();

        $data['members'] = User::select('id','username')->where('role','!=',1)->orderBy('username','asc')->get();

        $data['leads'] = $leads;
        $data['success'] = true;

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }

    public function getLeadsFullDetails(){
        $id = Input::get('id');
        $leads = Lead::where('leads.id',$id)->select("leads.*");

        $leads_data = $leads->addSelect('leads.name as student_name','leads.campaign_id','center.center_name','lead_status.status_value','lead_sources.source','lead_reasons.reason','members.name as assigned_member','groups.group_name','cities.city_name as client_city_name','states.state_name as client_state_name','c1.city_name as training_city','leads.lead_cost')
            ->leftJoin('center','center.id','=','leads.center_id')
            ->leftJoin('lead_status','lead_status.id','=','leads.status')
            ->leftJoin('members','members.id','=','leads.assign_to')
            ->leftJoin('lead_sources','lead_sources.id','=','leads.lead_source')
            ->leftJoin('cities','cities.id','=','leads.client_city_id')
            ->leftJoin('cities as c1','c1.id','=','leads.city_id')
            ->leftJoin('states','states.id','=','leads.client_state_id')
            ->leftJoin('groups','groups.id','=','leads.age_group')
            ->leftJoin('lead_reasons','lead_reasons.id','=','leads.reason_id')->get();

        return Response::json($leads_data, 200, array());
    }

    public function filter(){
        $user = User::AuthenticateUser(Request::header("apiToken"));
        $centers = Lead::centers($user);

        $max = 200;
        $request = Input::get("filter");
        $page_no = $request["pn"];

        $center_ids = [];
        $city_ids = [];

        foreach ($centers as $center) {
            $center_ids[] = $center->id;
            if(!in_array($center->city_id, $city_ids)){
                $city_ids[] = $center->city_id;
            }
        }

        $leads = Lead::select("leads.*");

        if($user->priv != "admin"){
            $city_ids[] = -1;
            $leads = $leads->where(function($query) use ($city_ids,$user){
                $query->whereIn("leads.city_id",$city_ids)->orWhere("leads.assign_to",$user->id);
            });
            // $leads = $leads->whereIn("leads.center_id",$center_ids);
        }

        if(sizeof($request["cities"]) > 0){
            $leads = $leads->whereIn("leads.city_id",$request["cities"]);
        }
        
        if(sizeof($request["centers"]) > 0){
            $leads = $leads->whereIn("leads.center_id",$request["centers"]);
        }

        if(sizeof($request["status"]) > 0){
            $leads = $leads->whereIn("leads.status",$request["status"]);
        }

        if(isset($request["campaign_id"])){
            $campaign_id = DB::table('campaign_filters')->where('code',$request["campaign_id"])->pluck('id')->first();
            $leads = $leads->where('leads.campaign_id',$campaign_id);
        }

        if(sizeof($request["sources"]) > 0){
            $leads = $leads->whereIn("leads.lead_source",$request["sources"]);
        }

        if($request["action_date_start"] != ""){
            $start_date = date("Y-m-d",strtotime($request["action_date_start"]));
            $leads = $leads->where("leads.action_date",">=",$start_date);
        }

        if($request["action_date_end"] != ""){
            $end_date = date("Y-m-d",strtotime($request["action_date_end"]));
            $leads = $leads->where("leads.action_date","<=",$end_date);
        }

        if($request["create_start"] != ""){
            $start_date = date("Y-m-d",strtotime($request["create_start"]));
            $leads = $leads->where("leads.created_at",">=",$start_date);
        }

        if($request["create_end"] != ""){
            $end_date = date("Y-m-d",strtotime($request["create_end"]));
            $leads = $leads->where("leads.created_at","<=",$end_date);
        }

        if(isset($request["updated_at"])?$request["updated_at"] != "":false){
            $updated_at = date("Y-m-d",strtotime($request["updated_at"]));
            $leads = $leads->where("leads.updated_at",">=",$updated_at);
        }

        if(isset($request["name"])?$request["name"] != "":false){
            $leads = $leads->where("leads.name",'LIKE','%'.$request["name"].'%');
        }

        if(isset($request["mobile"])?$request["mobile"] != "":false){
            $leads = $leads->where("leads.mobile",$request["mobile"]);
        }

        if(isset($request["lead_for"])?$request["lead_for"] != "":false){
            $leads = $leads->where("leads.lead_for",$request["lead_for"]);
        }

        if(isset($request["downloaded_app"])?$request["downloaded_app"]:false){
            $leads = $leads->join("customer","customer.mobile_number","=","leads.mobile");
        }

        if(isset($request["is_trial"])?$request["is_trial"]:false){
            $leads = $leads->where("leads.is_trial","=",1);
        }

        if(isset($request["address_cities"])){
            if(sizeof($request["address_cities"]) > 0){
                $leads = $leads->whereIn("leads.client_city_id",$request["address_cities"]);
            }
        }

        if(isset($request["trial_cities"])){
            if(sizeof($request["trial_cities"]) > 0){
                $leads = $leads->whereIn("leads.trial_city_id",$request["trial_cities"]);
            }
        }

        if(isset($request["assign_to"])?$request["assign_to"] != "":false){
            if($request["assign_to"] == '-1'){
                $leads = $leads->where("leads.assign_to",0);
            }else{

                $leads = $leads->where("leads.assign_to",$request["assign_to"]);
            }
        }

        $count = $leads->count();

        $leads = $leads->addSelect('leads.name as student_name','center.center_name','lead_status.status_value','lead_sources.source','lead_reasons.reason','users.name as assigned_member','groups.group_name','cities.city_name as client_city_name','states.state_name as client_state_name','c1.city_name as training_city','leads.lead_cost')
            ->leftJoin('center','center.id','=','leads.center_id')
            ->leftJoin('lead_status','lead_status.id','=','leads.status')
            ->leftJoin('users','users.id','=','leads.assign_to')
            ->leftJoin('lead_sources','lead_sources.id','=','leads.lead_source')
            ->leftJoin('cities','cities.id','=','leads.client_city_id')
            ->leftJoin('city as c1','c1.id','=','leads.city_id')
            ->leftJoin('states','states.id','=','leads.client_state_id')
            ->leftJoin('groups','groups.id','=','leads.age_group')
            ->leftJoin('lead_reasons','lead_reasons.id','=','leads.reason_id');

        if($request->sort_by){
            
            $leads = $leads->orderBy($request->sort_by, $request->sorting);

            if($request["sorting"] != ""){
                if($request["sort_by"]){
                    
                }
            }
        }

        
        if(isset($request["export_excel"])?$request["export_excel"] == 1:false){
            $leads = $leads->get();
        }else{
            $leads = $leads->skip(($page_no-1)*200)->limit(200)->get();
        }

        foreach ($leads as $lead) {

            $lead->mobile_trimmed = "xxxxxx".substr($lead->mobile, 6,4);

            $lead->dob = date("d-m-Y",strtotime($lead->dob));

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

            $lead->action_date_dmy = date("d-m-Y",strtotime($lead->action_date));

            if($lead->assign_to == 0) $lead->assign_to = "";
        }

        if(isset($request["export_excel"])?$request["export_excel"] == 1:false){
            include(app_path().'/ExcelExport/leads.php');
        }

        $data['leads'] = $leads;
        $data['count'] = $count;
        $data["total_pn"] = ceil($count/200);
        $data['success'] = true;

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }

    public function getAge(){
        $dob=date("Y-m-d",strtotime(Input::get('dob')));
        $diff = (date('Y') - date('Y',strtotime($dob)));
        return $diff;
    }

    public function getCampaignId(){
        $data['code'] = DB::table('campaign_filters')->where('product_id',Input::get('lead_for'))->where('source_id',Input::get('lead_source'))->where('sub_source_id',Input::get("lead_sub_source"))->pluck('code')->first();
        return Response::json($data,200,[]);
    }

    public function autoFillByPincode(){
        $pincode = Input::get('pincode');
        $office_names = DB::table('city_pincodes')->where('pincode',$pincode)->get();
        $data['office_names'] = $office_names;
        return Response::json($data,200,[]); 
    }

    public function submit(Request $request){
        
        $user = User::AuthenticateUser(Request::header("apiToken"));

        $request = Input::get('data');
        $rules = [
            "name" => "required",
            "gender" => "required",
            "dob" => "required|date",
            "city_id" => "required",
            "status" => "required",
            "assign_to" => "required",
            "lead_source" => "required",
            "lead_sub_source" => "required",
            "lead_for"=>"required"
        ];
        if(isset($request['id'])){
            $rules['mobile'] = "required|regex:/^([0-9\s\-\+\(\)]*)$/|digits:10|unique:leads,mobile,".$request['id'];
        }else{
            $rules['mobile'] = "required|unique:leads||regex:/^([0-9\s\-\+\(\)]*)$/|digits:10";

        }

        if($request['status'] == 5 || $request['status'] == 1 || $request['status'] == 11){
            $rules['reason_id'] = 'required';

            if(isset($request['reason_id'])){
                if(in_array($request['reason_id'],[13,22,35])){

                    $rules["call_note"] = "required";
                }
            }

        } else {
            $rules["action_date"]="required|date";
            $rules["call_note"] = "required";
        }

        $custom_msg = ["mobile.unique"=>"This mobile is already been taken in another lead ,Please try with another mobile number"] ;

        $validator = Validator::make($request,$rules,$custom_msg);

        if($validator->fails()){
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();

        } else {
            if($request['city_id'] != -1 && (!$request['center_id'])) {
                $data['success'] = false;
                $data['message'] = "Kindly select center";
                $data['pu'] = $request['age_group'];
                return Response::json($data,200,array());
            }

            if($request['status'] ==2 && !$request['demo_schedule_added'] && !$request['sms_text'] && $request['lead_for'] != 7){
                $data['schedule_demo'] = true;
                return Response::json($data,200,array());

            }
            if (isset($request['demo_schedule_added']) && isset($request['sms_text'])
                && isset($request['lead_for'])) {
                if($request['demo_schedule_added'] && !$request['sms_text'] && $request['lead_for'] != 7){
                    $action_date = date('Y-m-d',strtotime($request['action_date']));
                    $date_day = date('w',strtotime($request['action_date']));
                    $day = $date_day+1;

                    $operation_day = DB::table('operation_days')->where('center_id',$request['center_id'])->where('group_id',$request['age_group'])->where('day',$day)->first();
                    if($operation_day){
                        
                        $member = DB::table("users")->select("users.id","users.name","users.mobile")->join("center","center.cordinator_id","=","users.id")->where("center.id",$request['center_id'])->first();
                        $center = DB::table("center")->select("center_name","short_url")->where("center.id",$request['center_id'])->first();

                        if($member){
                            $data['member'] = $member;
                            $data['success'] = true;

                            $lead_names = explode(" ", $request['name']);
                            $member_names = explode(" ", $member->name);

                            $message = 'Dear '.$lead_names[0].'%0aYour trial with BBFS is scheduled for '.date("d M",strtotime($request['action_date'])).' at '.date("h:i A",strtotime($operation_day->from_time)).'%0aLocation: ';

                            $message .= ($center->short_url)?$center->short_url:$center->center_name;

                            $message .= '%0aFor any queries, call '.$member->mobile.'%0a%0a';
                            $message .= 'See you on the field!';
                            $data['show_sms'] = true;
                            $data['message'] = $message;
                        }else{
                            $data['success'] = false;
                            $data['message'] = 'No coach is assigned for this date';
                        }
                    }else{
                        $data['success'] = false;
                        $data['message'] = 'No schedule found for this date in this center';
                    }
                    return Response::json($data,200,[]);
                }
            }

            if (isset($request['id'])) {
                $lead = Lead::find($request['id']);
                $data['message'] = 'Lead details are updated successfully';
            }

            if(!isset($lead)){
                $lead = new Lead;
                $data['message'] = 'Lead details are added successfully';
                $lead->created_at = date("Y-m-d H:i:s");
            }
            $dob = date("Y-m-d",strtotime($request['dob']));
            $age = (date("Y") - date("Y",strtotime($dob)));

            $lead->name = $request['name'];
            $lead->age = $age;
            $lead->dob = $dob;
            $lead->pincode = $request['pincode'];
            $lead->gender = $request['gender'];
            $lead->mobile = $request['mobile'];
            $lead->client_email = $request['client_email'];
            $lead->client_address = $request['client_address'];
            $lead->client_city_id = $request['client_city_id'];
            $lead->client_state_id = $request['client_state_id'];
            $lead->client_city = isset($request['client_city'])?$request['client_city']:'';
            $lead->city_id = $request['city_id'];
            $lead->other_city_id = isset($request['other_city_id'])?$request['other_city_id']:'';
            if(Input::has('campaign_code')){
                if(Input::get('campaign_code') != ''){
                    $check_campaign = DB::table('campaign_filters')->where('code',strtoupper(Input::get('campaign_code')))->first();
                    if(!$check_campaign){
                        $data['success'] = false;
                        $data['message'] = "Invalid campaign id";
                        return Response::json($data,200,[]);
                    }else{
                        $lead->campaign_id = $check_campaign->id;
                    }
                }
            }
            $lead->center_id = $request['center_id'];
            $lead->age_group = $request['age_group'];
            $lead->status = $request['status'];
            $lead->last_call_note = isset($request['call_note'])?$request['call_note']:'';
            $lead->lead_cost = isset($request['lead_cost'])?$request['lead_cost']:'';
            $lead->relevance = $request['relevance'];
            $lead->lead_for = $request['lead_for'];
            
            
            if(isset($request['reason_id'])) $lead->reason_id = $request['reason_id'];

            $lead->created_by = $user->id;
            $lead->last_updated_by = $user->id;

            $lead->action_date = ($request['action_date'] != '')?date('Y-m-d',strtotime($request['action_date'])):NULL;

            $lead->lead_source = $request['lead_source'];
            $lead->lead_sub_source = $request['lead_sub_source'];
            $lead->assign_to = $request['assign_to'];
            
            $lead->save();

            $leadHistory = new LeadHistory;
            $leadHistory->lead_id = $lead->id;
            $leadHistory->call_note = isset($request['call_note'])?$request['call_note']:'';
            $lead->call_made = ($request['call_made'])?1:0;
            $leadHistory->action_date = $lead->action_date;

            $leadHistory->status = $request['status'];
            $leadHistory->assign_to = $request['assign_to'];
            $leadHistory->created_by = $user->id;
            $leadHistory->save();
            
            $lead = Lead::listing()->where('leads.id',$lead->id)->first();

            if($lead){
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
            }

            if(isset($request['sms_text'])){
                
                if($request['sms_text'] && $request['status'] == 2){
                    // Lead::sendSMS($request['mobile'],$request['sms_text']); commented while covid
                }
            }

            $data['success'] = true;
            $data['lead'] = $lead;
        }
        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }

    public function history($lead_id){
        $history_data = LeadHistory::select('lead_history.*','lead_status.status_value','users.name as assigned_member','m2.name as assigned_by')
            ->leftJoin('lead_status','lead_status.id','=','lead_history.status')
            ->leftJoin('users','users.id','=','lead_history.assign_to')
            ->leftJoin('users as m2','m2.id','=','lead_history.created_by')
            ->where('lead_id',$lead_id)
            ->orderBy('id','desc')
            ->get();
        $data['LTHistory'] = Lead::LTHistory($lead_id);
        $data['history'] = $history_data;
        $data['success'] = true;
        return Response::json($data,200,array());
    }

    public function updateLead(){
        
        $user = User::AuthenticateUser(Request::header("apiToken"));

        $request = Input::get('data');

        $check_duplicate_mobile = Lead::where('id','!=',$request['id'])->where('mobile',$request['mobile'])->first();
        $messages = ["age_group.not_in"=>"Please select age group"];
        
        $check = [
            "name"=>$request['name'],
            "gender"=>$request['gender'],
            "dob"=>$request['dob'],
            "mobile"=>$request['mobile'],
            "city_id"=>$request['city_id'],
            "age_group"=>$request['city_id'],
        ];
        $validator = Validator::make($check,[
            "name" => "required",
            "gender" => "required",
            "dob" => "required|date",
            "mobile" => "required|regex:/^([0-9\s\-\+\(\)]*)$/|digits:10|unique:leads,mobile,".$request['id'],
            "city_id" => "required",
            "age_group" => "required|not_in:0",
        ],$messages);
        if($validator->fails()){
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
        }else{

            if($request["city_id"] != -1 && (!isset($request["center_id"])) ) {
                $data['success'] = false;
                $data['message'] = "Kindly select center";
                return Response::json($data,200,array());
            }
            $dob = date("Y-m-d",strtotime($request['dob']));
            $age = (date("Y") - date("Y",strtotime($dob)));

            $lead = Lead::find($request['id']);
            $lead->name = $request['name'];
            $lead->age = $age;
            $lead->dob = $dob;
            $lead->mobile = $request['mobile'];
            $lead->gender = $request['gender'];

            $lead->lead_source = $request['lead_source'];
            $lead->lead_sub_source = $request['lead_sub_source'];
            
            $lead->client_email = $request['client_email'];
            $lead->client_address = $request['client_address'];
            $lead->client_city = $request['client_city'];

            $lead->client_city_id = $request['client_city_id'];
            $lead->client_state_id = $request['client_state_id'];

            $lead->last_updated_by = $user->id;
            $lead->city_id = $request['city_id'];
            $lead->other_city_id = $request['other_city_id'];
            $lead->center_id = $request['center_id'];
            $lead->age_group = $request['age_group'];
            $lead->lead_cost = $request['lead_cost'];
            $lead->relevance = $request['relevance'];
            $lead->lead_for = $request['lead_for'];

            if(Input::has('campaign_code')){
                if(Input::get('campaign_code') != ''){
                    $check_campaign = DB::table('campaign_filters')->where('code',strtoupper(Input::get('campaign_code')))->first();
                    if(!$check_campaign){
                        $data['success'] = false;
                        $data['message'] = "Invalid campaign id";
                        return Response::json($data,200,[]);
                    }else{
                        $lead->campaign_id = $check_campaign->id;
                    }
                }
            }

            $lead->save();
            
            $data['message'] = 'Lead details are updated successfully';

            $lead = Lead::listing()->where('leads.id',$lead->id)->first();
            if($lead){

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
            }
            $lead->history = LeadHistory::select('lead_history.*','lead_status.status_value','users.name as assigned_member','m2.name as assigned_by')
            ->leftJoin('lead_status','lead_status.id','=','lead_history.status')
            ->leftJoin('users','users.id','=','lead_history.assign_to')
            ->leftJoin('users as m2','m2.id','=','lead_history.created_by')
            ->where('lead_id',$lead->id)
            ->orderBy('id','desc')
            ->get();

            $data['success'] = true;
            $data['message'] = "Lead details updated successfully";
            $data['lead'] = $lead;
        }
        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }


    public function addNote(){
        $user = User::AuthenticateUser(Request::header("apiToken"));
        $request = Input::get('data');
        $lead = Lead::find($request['id']);
        $rules = [
            "status" => "required",
            "assign_to" => "required",
        ];
        
        if($request['status'] == 5 || $request['status'] == 1 || $request['status'] == 11){
            $rules['reason_id'] = 'required';

            if(isset($request['reason_id'])){
                if(in_array($request['reason_id'],[13,22,35])){

                    $rules["call_note"] = "required";
                }
            }

        } else {
            $rules["new_action_date"]="required|date";
            $rules["call_note"] = "required";
        }


        if($lead->status == 3 && $lead->is_trial != 1 && !in_array($request['status'], [2,3,4,6,8])){
            $data['success'] = false;
            $data['message'] = "Demo attended leads can only be marked as Enrolled";
            return Response::json($data,200,[]);
        }

        if($request['status'] != $lead->status){

            // if($request['status'] == 7 && $lead->status != 2){
            //  $data['success'] = false;
            //  $data['message'] = "Followup DS is applicable only to Demo Scheduled leads";
            //  return Response::json($data,200,[]);
            // }

            if($request['status'] == 8 && $lead->status != 3){
                $data['success'] = false;
                $data['message'] = "Followup DA is applicable only to Demo Attended leads";
                return Response::json($data,200,[]);
            }
        }

        if($request['assign_to'] == 0){
            $data['success'] = false;
            $data['message'] = "Please select assigned to";
            return Response::json($data,200,[]);    
        }
        
        $validator = Validator::make($request,$rules);

        if($validator->fails()){
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
        }else{

            if($request['status'] == 4 && $lead->status != 4){
                $lead->status = 6; // Mark Enrollment Pending
            }else{
                $lead->status = $request['status'];
            }
            $lead->assign_to = $request['assign_to'];
            $lead->last_call_note = isset($request['call_note']) ? $request['call_note'] : '';
            $lead->reason_id = $request['reason_id'];
            $lead->relevance = $request['relevance'];

            $lead->action_date = isset($request['new_action_date'] ) ? date('Y-m-d',strtotime($request['new_action_date'])) : NULL;

            $lead->last_updated_by = $user->id;

            if($request['city_id']){
                $lead->city_id = $request['city_id'];
            }
            if($request['center_id']){
                $lead->center_id = $request['center_id'];
            }
            if($request['age_group']){
                $lead->age_group = $request['age_group'];
            }

            if($request['is_trial']){
                $lead->is_trial = 1;
            } else {
                $lead->is_trial = 0;
            }
            
            if($request['trial_time']){
                $lead->trial_time = $request['trial_time'];
            }
            if($request['trial_venue']){
                $lead->trial_venue = $request['trial_venue'];
            }

            if($request['trial_city_id']){
                $lead->trial_city_id = $request['trial_city_id'];
            }

            if($request['status'] == 3){
                $lead->demo_attended_on = date('Y-m-d',strtotime($request['new_action_date']));
            }

            $lead->save();

            $leadHistory = new LeadHistory;
            $leadHistory->lead_id = $lead->id;
            $leadHistory->call_note = isset($request['call_note']) ? $request['call_note'] : '';
            
            $leadHistory->action_date = $lead->action_date;
            $leadHistory->assign_to = $request['assign_to'];

            $leadHistory->status = $lead->status;
            $leadHistory->created_by = $user->id;
            $leadHistory->call_made = isset($request['call_made'])?$request['call_made']:'';
            $leadHistory->save();

            $data['success'] = true;
            $lead = Lead::listing()->where('leads.id',$request['id'])->first();
            $lead->history = LeadHistory::select('lead_history.*','lead_status.status_value','users.name as assigned_member','m2.name as assigned_by')
            ->leftJoin('lead_status','lead_status.id','=','lead_history.status')
            ->leftJoin('users','users.id','=','lead_history.assign_to')
            ->leftJoin('users as m2','m2.id','=','lead_history.created_by')
            ->where('lead_id',$lead->id)
            ->orderBy('id','desc')
            ->get();

            if($lead){
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

            }

            if(isset($request['sms_text'])){
                if($request['sms_text']){
                    // $lead->mobile = 9548766941;
                    // Lead::sendSMS($lead->mobile , $request['sms_text']); commented while covid
                }
            }

            $data['success'] = true;
            $data['lead'] = $lead;
            $data['message'] = "Lead note added successfully";
        }
        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }

    public function transferLead(){
        $user = User::AuthenticateUser(Request::header("apiToken"));
        $request = Input::get('data');
        $cre = ["city_id"=>$request['city_id']];
        $rules = ["city_id"=>"required"];
        $validator = Validator::make($cre , $rules);
        if($validator->passes()){
            $lead = Lead::find($request['lead_id']);

            $transferHistory = new LTHistory;
            $transferHistory->lead_id = $lead->id;
            $transferHistory->old_city_id = $lead->city_id;
            $transferHistory->old_center_id = $lead->center_id;
            $transferHistory->new_city_id = $request['city_id'];
            $transferHistory->new_center_id = ($request['center_id'])?$request['center_id']:0;
            $transferHistory->transfer_by = $user->id;
            $transferHistory->save();

            $lead->city_id = $request['city_id'];
            $lead->center_id = ($request['center_id'])?$request['center_id']:0;
            $lead->age_group = ($request['age_group'])?$request['age_group']:0;
            $lead->save();


            $lead = Lead::listing()->where('leads.id',$request['lead_id'])->first();
            
            if($lead){

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
            }
            if($lead->relevance == 1){
                $lead->rel_color = 'green';
            }elseif($lead->relevance == 2){
                $lead->rel_color = 'red';
            }elseif($lead->relevance == 3){
                $lead->rel_color = '#ffbf00';
            }

            $data['success'] = true;
            $data['lead'] = $lead;
            $data['message'] = "Lead is transfered successfully";

        }else{
            $data['success'] = false;
            $data['message'] = $validator->errors()->first();
        }
        return Response::json($data,200,array());
    }


    public function checkAdvanceOptions(){
        $lead = Lead::find(Input::get('lead_id'));
        $data['success'] = true;
        $message = '';
        if(!$lead->city_id){
            $message .= 'Please update training city'."<br>";
            $data['success'] = false;
        }

        if(!$lead->center_id){
            $message .= 'Please update center'."<br>";
            $data['success'] = false;
        }

        if(!$lead->age_group){
            $message .= 'Please update age group';
            $data['success'] = false;
        }
        $data['message'] = $message;

        if($data['success']){

            $special_discount = SpecialDiscount::where('lead_id',Input::get('lead_id'))->first();
            if($special_discount){

                $data['special_discount'] = $special_discount;
            }
        }

        return Response::json($data,200,[]);

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

    public function selectAllFilterLeads(){
        $user = User::AuthenticateUser(Request::header("apiToken"));
        $centers = Lead::centers($user);
        $request = Input::get('data'); 

        $center_ids = [];
        $city_ids = [];

        foreach ($centers as $center) {
            $center_ids[] = $center->id;
            if(!in_array($center->city_id, $city_ids)){
                $city_ids[] = $center->city_id;
            }
        }

        $leads = Lead::select("leads.id");

        if($user->priv != "admin"){
            $city_ids[] = -1;
            $leads = $leads->whereIn("leads.city_id",$city_ids);
            // $leads = $leads->whereIn("leads.center_id",$center_ids);
        }

        if(sizeof($request['cities']) > 0){
            $leads = $leads->whereIn("leads.city_id",$request['cities']);
        }
        
        if(sizeof($request['centers']) > 0){
            $leads = $leads->whereIn("leads.center_id",$request['centers']);
        }

        if(sizeof($request['status']) > 0){
            $leads = $leads->whereIn("leads.status",$request['status']);
        }

        if(sizeof($request['sources']) > 0){
            $leads = $leads->whereIn("leads.lead_source",$request['sources']);
        }

        if(isset($request['action_date_start'])){
            $start_date = date("Y-m-d",strtotime($request['action_date_start']));
            $leads = $leads->where("leads.action_date",">=",$start_date);
        }

        if(isset($request['action_date_end'])){
            $end_date = date("Y-m-d",strtotime($request['action_date_end']));
            $leads = $leads->where("leads.action_date","<=",$end_date);
        }

        if(isset($request['create_start'])){
            $start_date = date("Y-m-d",strtotime($request['create_start']));
            $leads = $leads->where("leads.created_at",">=",$start_date);
        }

        if(isset($request['create_end'])){
            $end_date = date("Y-m-d",strtotime($request['create_end']));
            $leads = $leads->where("leads.created_at","<=",$end_date);
        }

        if(isset($request['name'])){
            $leads = $leads->where("leads.name",'LIKE','%'.$request['name'].'%');
        }

        if(isset($request['mobile'])){
            $leads = $leads->where("leads.mobile",$request['mobile']);
        }

        if(isset($request['lead_for'])){
            $leads = $leads->where("leads.lead_for",$request['lead_for']);
        }

        if(isset($request['downloaded_app'])){
            $leads = $leads->join("customer","customer.mobile_number","=","leads.mobile");
        }

        if(isset($request['is_trial'])){
            $leads = $leads->where("leads.is_trial","=",1);
        }

        if(isset($request['address_cities'])){
            if(sizeof($request['address_cities']) > 0){
                $leads = $leads->whereIn("leads.client_city_id",$request['address_cities']);
            }
        }

        if(isset($request['trial_cities'])){
            if(sizeof($request['trial_cities']) > 0){
                $leads = $leads->whereIn("leads.trial_city_id",$request['trial_cities']);
            }
        }

        $leads = $leads->pluck('id')->all();

        $data['lead_ids'] = $leads;
        $data['success'] = true;

        return Response::json($data,200,array(),JSON_NUMERIC_CHECK);
    }
}


                 