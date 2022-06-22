<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Center , DB, App\Models\MailQueue;

class Lead extends Model
{
    protected $table = 'leads';

    public static function status(){
    	return DB::table('lead_status')->select("id as value","status_value as label","action_date_name","date_req","call_note_req","reason_req")->get();
    }

    public static function reasons($client_id){
    	return  DB::table('lead_reasons')->where("client_id",$client_id)->orderBy('id','desc')->get();
    }

    public static function lead_sources($client_id){
        // return DB::table('lead_sources')->where("client_id",$client_id)->select("id as value","source as label")->where('parent_id',0)->get();
    	return DB::table('lead_sources')->where("client_id",$client_id)->select("id as value","source as label")->get();
    }

    public static function lead_for_types($client_id){
        return DB::table('lead_for')->select("id as value","label")->where("client_id",$client_id)->get();
    }

    public static function sub_lead_sources(){
        return DB::table('lead_sources')->where('parent_id','!=',0)->get();
    }

    public static function all_cities(){
        return DB::table('cities')->orderBy('city_name')->get();
    }
    
    public static function states(){
        return DB::table('states')->orderBy('state_name')->get();
    }

    public static function relevance_list(){
        return [
            ["id"=>1,"name"=>"green","value"=>"high relevance"],
            ["id"=>2,"name"=>"Red","value"=>"low"],
            ["id"=>3,"name"=>"Amber","value"=>"medium"]
        ];
    }

    public static function listing(){
        return DB::table("leads")->select('leads.*','leads.name' ,'center.center_name','lead_status.status_value','lead_sources.source','lead_reasons.reason','users.name as assigned_member','groups.group_name','cities.city_name as client_city_name','states.state_name as client_state_name','lead_status.color as status_color')
            ->leftJoin('center','center.id','=','leads.center_id')
            ->leftJoin('lead_status','lead_status.id','=','leads.status')
            ->leftJoin('users','users.id','=','leads.assigned_to')
            ->leftJoin('lead_sources','lead_sources.id','=','leads.lead_source')
            ->leftJoin('groups','groups.id','=','leads.group_id')
            ->leftJoin('cities','cities.id','=','leads.client_city_id')
            ->leftJoin('states','states.id','=','leads.client_state_id')
            ->leftJoin('lead_reasons','lead_reasons.id','=','leads.reason_id');
    }

    public static function listingHistory(){
        return DB::table("lead_history")->select('lead_history.*','lead_status.status_value','users.name as assigned_member','m2.name as assigned_by')
            ->join('leads','leads.id','=','lead_history.lead_id')
            ->leftJoin('lead_status','lead_status.id','=','lead_history.status')
            ->leftJoin('users','users.id','=','lead_history.assigned_to')
            ->leftJoin('users as m2','m2.id','=','lead_history.created_by');
    }

    public static function storeOpenLead($open_lead){
        
        $lead = Lead::where("mobile",$open_lead->mobile)->where("client_id",$open_lead->client_id)->first();

        if(!$lead){
            $lead = new Lead;
            $lead->mobile = $open_lead->mobile;
            $lead->client_id = $lead->client_id;
            $lead->created_at = date("Y-m-d H:i:s");
            $lead->created_by = null;
            $lead->status = 8;
            $lead->lead_for = $open_lead->lead_for;
            $lead->lead_source = $open_lead->lead_source;
            $new_lead = true;
        } else {
            $new_lead = false;
        }

        if(!$lead->name) $lead->name = $open_lead->name;
        if(!$lead->gender && isset($open_lead->gender)) $lead->gender = $open_lead->gender;
        if(!$lead->dob && isset($open_lead->dob)) $lead->dob = Utilities::convertDateToDB($open_lead->dob);

        if(!$lead->client_email && isset($open_lead->client_email)) $lead->client_email = $open_lead->client_email;
        if(!$lead->lead_for && isset($open_lead->lead_for)) $lead->lead_for = $open_lead->lead_for;
        if(!$lead->lead_source && isset($open_lead->lead_source)) $lead->lead_source = $open_lead->lead_source;
        if(!$lead->remarks && isset($open_lead->remarks)) $lead->remarks = $open_lead->remarks;
        if(!$lead->city_id && isset($open_lead->city_id)) $lead->city_id = $open_lead->city_id;
        if(!$lead->document && isset($open_lead->document)) $lead->document = $open_lead->document;
        
        $lead->action_date = Utilities::convertDateToDB($open_lead->action_date);
        $lead->client_id = $open_lead->client_id;

        $lead->save();
        
        $last_history = LeadHistory::where("lead_id",$lead->id)->orderBy("created_at","DESC")->first();
        if($last_history){
            $leadHistory = new LeadHistory;
            $leadHistory->lead_id = $lead->id;
            $leadHistory->call_note = "Submission from webesite";
            $leadHistory->call_made = 0;
            $leadHistory->action_date = Utilities::convertDateToDB($open_lead->action_date);
            $leadHistory->status = $last_history->status;
            $leadHistory->assigned_to = $last_history->assigned_to;
            $leadHistory->save();
        } else {
            $leadHistory = new LeadHistory;
            $leadHistory->lead_id = $lead->id;
            $leadHistory->call_note = "Submission from webesite";
            $leadHistory->call_made = 0;
            $leadHistory->action_date = Utilities::convertDateToDB($open_lead->action_date);
            $leadHistory->status = 8;
            $leadHistory->save();
        }
        
    }

    // public static function cities($centers){
    // 	$cities = [];
    //     $city_ids = [];
    //     foreach ($centers as $center) {
    //         $city_ids[] = $center->city_id;
    //     }

    //     if(sizeof($city_ids) > 0){
    //         $cities = DB::table('city')->whereIn("id",$city_ids)->orderBy('city_name','ASC')->get();
            
    //     }

    //     return $cities;
    // }

    // public static function citiesfortransfer(){
    //     $cities = DB::table('city')->orderBy('city_name','ASC')->get();
    //     return $cities;

    // }

    // public static function centersfortransfer(){
    //     $centers = DB::table('center')->orderBy('center_name','ASC')->get();
    //     foreach ($centers as $center) {
    //         $center->age_groups =  DB::table('groups')->select('id','center_id','group_name')->orderBy('center_id','ASC')->where('center_id',$center->id)->get();
    //     }
    //     return $centers;

    // }

    // public static function centers($user){
    // 	$centers = [];

    //     // if($user->privilege == 'admin'){
    //     //     $centers =  Center::select('id','center_name','city_id','center_status','cordinator_id')->get();    
    //     // } else {
    //     //     $center_ids = DB::table("members_priv")->where("leads",1)->where("user_id",$user->id)->pluck("center_id")->all();
    //     //     if(sizeof($center_ids) > 0){
    //     //         $centers =  Center::select('id','center_name','city_id','center_status','cordinator_id')->whereIn("id",$center_ids)->get();
    //     //     }
    //     // }

    //     $centers =  Center::select('id','center_name','city_id','center_status','cordinator_id')->get();    

    	
    // 	foreach ($centers as $center) {
    // 		$center->age_groups = DB::table('groups')->select('id','group_name','group_status','age_group_category')->where('center_id',$center->id)->get();
    // 	}

    // 	return $centers;
    // }

    

    // public static function LTHistory($lead_id){
    //     $transferHistory = LTHistory::select('old_city.city_name as old_city','new_city.city_name as new_city','old_center.center_name as old_center','new_center.center_name as new_center','users.name as username')
    //         ->join('city as old_city','old_city.id','=','lead_transfer_history.old_city_id')
    //         ->join('city as new_city','new_city.id','=','lead_transfer_history.new_city_id')
    //         ->leftJoin('center as old_center','old_center.id','=','lead_transfer_history.old_center_id')
    //         ->leftJoin('center as new_center','new_center.id','=','lead_transfer_history.new_center_id')
    //         ->join('users','users.id','=','lead_transfer_history.transfer_by')

    //         ->where('lead_id',$lead_id)
    //         ->orderBy('lead_transfer_history.id','desc')
    //         ->get();
    //     return $transferHistory;
    // }	

    // public static function sendSMS($number, $msgtxt , $sms_type){

    //     // $number = "9634628573";
        
    //     $username = "info@bbfootballschools.com";
    //     $hash = "09264af3ac7bd308d5d4c5e3bb6e45b7fb8c7a5695050f766ace99bf0407c866";

    //     if($sms_type == 2){
    //         $sender = "MYBBFS"; // transactional
    //     }else{
    //         $sender = "308845"; // promotional
    //     }

    //     $sender = "MYBBFS"; // transactional

    //     $test = 0;

    //     $data = "username=".$username."&hash=".$hash."&message=".urlencode($msgtxt)."&sender=".$sender."&numbers=".$number."&test=".$test;
    //     $ch = curl_init('http://api.textlocal.in/send/?');
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     $result = curl_exec($ch); // This is the result from the API
    //     curl_close($ch);

    //     // print_r($result);
    //     // die();

    //     // return $result;

    // }

    // public static function storeSMS($number, $msgtxt,$sms_type){

    //     MailQueue::createSMS($number, $msgtxt,$sms_type);

    // }

    // public static function sendEmail($email, $subject, $content){
        
    //     include(app_path().'/libraries/MailinV2.php');

    //     $mailin = new \Mailin('https://api.sendinblue.com/v2.0', 'YSk1ZQvCj6h5GWtn');

    //     $to_array = array();

    //     $to_array[$email] = $email;

    //     $message = Lead::getContent($content);

    //     $data = array( "to" => $to_array,
    //         "from" => array('info@bbfootballschools.com', 'Bhaichung Bhutia Football Schools'),
    //         "subject" => $subject,
    //         "html" => $message
    //     );
        
    //     $res = $mailin->send_email($data);

    // }

    public static function getContent($content){
        $str = '<html><body>';
        $str .= $content;

        $image = "http://www.bbfootballschools.com/admin/students/images";
            $str .= '
          <br>
          <table cellpadding="0" cellspacing="5" border="0">
          <tr>
            <td style="width:90px"><img src="'.$image.'/logo.jpg" style="width:80px; height:auto" ></td>
            <td>
            <p style="font-size:28px;">Bhaichung Bhutia<br>Football Schools</p>
            </td>
          </tr>
          <tr>
            <td colspan="2" style="font-size:18px" align="middle">
              8448020010
              <br><a href="http://www.bbfootballschools.com">www.bbfootballschools.com</a>
            </td>
          </tr>
          <tr>
              <td colspan="2" align="middle">
              <a href="https://www.facebook.com/bbfschools/"><img src="'.$image.'/fb.png" style="width:30px; height:30px; margin-right:5px"></a>
              <a href="https://twitter.com/BBFSchools"><img src="'.$image.'/twitter.png" style="width:30px; height:30px; margin-right:5px"></a>
              <a href="https://www.instagram.com/bbfschools/"><img src="'.$image.'/insta.png" style="width:30px; height:30px; margin-right:5px"></a>
              <a href="https://www.youtube.com/user/bbfootballschools"><img src="'.$image.'/yt.png" style="width:30px; height:30px; margin-right:5px"></a>
              <a href="https://www.linkedin.com/company/bhaichung-bhutia-football-schools"><img src="'.$image.'/in.png" style="width:30px; height:30px; margin-right:5px"></a>
              </td>
          </tr>
          </table>';

        $str .= '</body></html>';

        return $str;
    }
}

