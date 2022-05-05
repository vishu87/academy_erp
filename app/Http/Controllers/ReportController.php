<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DateTime;

use Input, Redirect, Validator, Hash, Response, Session, DB;
use App\Models\User, App\Models\Center, App\Models\Group, App\Models\Student;

class ReportController extends Controller{
    public function centerIndex(){
        return view('reports.center_reports',["sidebar"=>"reports","menu" => "admin"]);
    }

    public function salesIndex(){

        $types = [
            "revenue" => "Subscription Revenue",
            "cash_flow" => "Cash Flow"
        ];

        return view('reports.sales',["sidebar"=>"reports","types"=>$types,"name" => "Sales Dashboard","menu" => "admin"]);
    }

    public function studentsIndex(){

        $types = [
            "renewals_pending" => "Renewals Pending",
            "inactive_marked" => "Inactive Marked",
            "enrolled" => "Enrolled",
            "renewed" => "Renewed",
        ];

        return view('reports.sales',["sidebar"=>"reports","types"=>$types,"name" => "Student Reports","menu" => "admin"]);
    }

    public function leadsIndex(){

        $types = [
            // "ds_enrolled" => "DS to Enrolled",
            "lms_pending" => "LMS Pending",
            "demo_scheduled" => "Demo Scheduled",
            "demo_attended" => "Demo Attended",
        ];

        return view('reports.sales',["sidebar"=>"reports","types"=>$types,"name" => "Lead Reports","menu" => "admin"]);
    }

    public function revenueReport(Request $request){

        $user = User::AuthenticateUser($request->header("apiToken"));

        $month_name = date("F", strtotime ( '-1 month' , strtotime ( "today" ) )) ;
        
        // $from_date = date('Y-m-01',strtotime('last month'));
        // $to_date = date('Y-m-t',strtotime('last month'));

        $from_date = date('Y-m-d',strtotime('-1 month +1 day'));
        $to_date = date('Y-m-d',strtotime('today'));

        // $last_year_date = date("Y-m-01", strtotime ( '-12 month' , strtotime ( "today" ) )) ;
        $last_year_date = date("Y-m-d", strtotime ( '-12 months +1 day' , strtotime ( "today" ) )) ;

        // return $from_date.' - '.$last_year_date;

        if(date("n") <= 3){
            $year_ytd = date("Y") - 1;
        } else {
            $year_ytd = date("Y");
        }

        $date_ytd = $year_ytd."-04-01";


        $today = date("Y-m-d");

        $center_ids = [];
        $city_ids = [];

        // if($user->priv == 'admin'){
            $centers = Center::select('center.id as center_id','city_id')->where("center_status",0)->get();
        // } else {
        //     $centers = DB::table("members_priv")->select('center_id','center.city_id')->join("center","center.id","=","members_priv.center_id")->where("user_id",$user->id)->where("reports",1)->where("center.center_status",0)->get();
        // }

        foreach ($centers as $center) {
            array_push($center_ids, $center->center_id);
            array_push($city_ids, $center->city_id);
        }

        $payments_centers = [];
        $payments = DB::table("payment_history")->select("payment_history.amount","groups.center_id","payment_history.payment_date","payment_history.total_amount")->join('students','students.id','=','payment_history.student_id')->leftJoin("groups","groups.id","=","students.group_id")->whereIn('groups.center_id',$center_ids)->whereBetween('payment_history.payment_date',[ $last_year_date , date("today") ])->get();
        foreach ($payments as $payment) {
            if(!isset($payments_centers[$payment->center_id])){
                $payments_centers[$payment->center_id] = array(
                    "monthly" => 0,
                    "ltm" => 0,
                    "ytd" => 0
                );
            }
            $date = $payment->payment_date;

            // $payment->amount = ($payment->total_amount)?$payment->total_amount:$payment->amount;

            if($date >= $date_ytd && $date <= $today){
                $payments_centers[$payment->center_id]["ytd"] += $payment->amount;
            }

            if($date >= $last_year_date && $date <= $today){
                $payments_centers[$payment->center_id]["ltm"] += $payment->amount;
            }

            if($date >= $from_date && $date <= $to_date){
                $payments_centers[$payment->center_id]["monthly"] += $payment->amount;
            }
        }

        $overall_monthly = 0;
        $overall_ytd = 0;
        $overall_ltm = 0;
        $overall_active_students = 0;
        // $overall_coaches = 0;
        $overall_coordinator = 0;

        $covered_coach_ids = [];

        $cities = DB::table("city")->orderBy("city_name","ASC")->whereIn("id",$city_ids)->get();
        foreach ($cities as $city) {
            $centers = Center::where("city_id",$city->id)->get();
            
            $city->center_count = sizeof($centers);
            $monthly = 0;
            $ytd = 0;
            $ltm = 0;
            $active_students = 0;
            $coaches = 0;
            $coordinator = 0;

            $city_coach_ids = [];

            foreach ($centers as $count => $center) {

                $center->sn = $count+1;
                $groups = Group::where('center_id',$center->id)->pluck('id')->all();
                
                $center->active_students = Student::whereIn('group_id',$groups)->where('inactive',0)->count();
                $active_students += $center->active_students;
                $overall_active_students += $center->active_students;

                // $center->coaches = Member::where('center_id',$center->id)->where('priv','coach')->count();
                $groups_used = [];
                $total_coaches = 0;

                $center_coach_ids = [];

                $center_coaches = DB::table("center_operation_coaches")->select("operation_days.id","operation_days.group_id","operation_days.day","center_operation_coaches.coach_id")->join("operation_days","operation_days.id","=","center_operation_coaches.operation_id")->join("groups","groups.id","=","operation_days.group_id")->where("operation_days.center_id",$center->id)->where(function($query) use ($today){
                    $query->where("center_operation_coaches.end_date",">=",$today)->orWhereNull("center_operation_coaches.end_date");
                })->get();

                
                foreach ($center_coaches as $center_coach) {
                    if(!isset($groups_used[$center_coach->group_id])) {
                        $groups_used[$center_coach->group_id] = [];
                    }

                    if(!in_array($center_coach->coach_id, $groups_used[$center_coach->group_id]) && !in_array($center_coach->coach_id, $center_coach_ids)){
                        $total_coaches++;
                        $groups_used[$center_coach->group_id][] = $center_coach->coach_id;
                        $center_coach_ids[] = $center_coach->coach_id;
                    }

                    if(!in_array($center_coach->coach_id, $covered_coach_ids)){
                        $covered_coach_ids[] = $center_coach->coach_id;
                    }

                    if(!in_array($center_coach->coach_id, $city_coach_ids)){
                        $city_coach_ids[] = $center_coach->coach_id;
                    }
                }

                // $center_days = DB::table("operation_days")->where("operation_days.center_id",$center->id)->count();

                // // $center->coaches = ($center_days != 0)?ceil($center_coaches/$center_days):0;
                // $center->coaches = $center_coaches;

                $center->coaches = $total_coaches;

                // $coaches += $center->coaches;
                // $overall_coaches += $center->coaches;

                $center->coordinator = ($center->relationship_manager_id)?1:0;

                $coordinator += $center->coordinator;
                $overall_coordinator += $center->coordinator;

                if(isset($payments_centers[$center->id])){
                    
                    $center->monthly_revenue = $payments_centers[$center->id]["monthly"];
                    $monthly += $payments_centers[$center->id]["monthly"];
                    $overall_monthly += $payments_centers[$center->id]["monthly"];
                    $center->monthly_revenue_show = ReportController::convertCurrency($payments_centers[$center->id]["monthly"]);

                    $center->ytd = $payments_centers[$center->id]["ytd"];
                    $ytd += $payments_centers[$center->id]["ytd"];
                    $overall_ytd += $payments_centers[$center->id]["ytd"];
                    $center->ytd_show = ReportController::convertCurrency($payments_centers[$center->id]["ytd"]);

                    $center->ltm = $payments_centers[$center->id]["ltm"];
                    $ltm += $payments_centers[$center->id]["ltm"];
                    $overall_ltm += $payments_centers[$center->id]["ltm"];
                    $center->ltm_show = ReportController::convertCurrency($payments_centers[$center->id]["ltm"]);
                }

                if($center->coaches > 0){
                    $ratio = round($center->active_students/$center->coaches,1);
                } else {
                    $ratio = 0;
                }
                $center->ratio = $ratio;
            }
            $city->centers = $centers;
            $city->monthly = $monthly;
            $city->monthly_show = ReportController::convertCurrency($monthly);
            $city->ytd = $ytd;
            $city->ytd_show = ReportController::convertCurrency($ytd);
            $city->ltm = $ltm;
            $city->ltm_show = ReportController::convertCurrency($ltm);

            $city->active_students = $active_students;
            // $city->coaches = $coaches;
            $city->coaches = sizeof($city_coach_ids);
            $city->coordinator = $coordinator;

            if($city->coaches > 0){
                $city->ratio = round($city->active_students/$city->coaches,1);
            } else {
                $city->ratio = 0;
            }
        }

        $overall_coaches = sizeof($covered_coach_ids);

        if($overall_coaches > 0){
            $overall_ratio = round($overall_active_students/$overall_coaches,1);
        } else {
            $overall_ratio = 0;
        }

        $combined_data = array(
            "monthly" => $overall_monthly,
            "monthly_show" => ReportController::convertCurrency($overall_monthly),
            "ytd" => $overall_ytd,
            "ytd_show" => ReportController::convertCurrency($overall_ytd),
            "ltm" => $overall_ltm,
            "ltm_show" => ReportController::convertCurrency($overall_ltm),
            "active_students" => $overall_active_students,
            "coaches" => $overall_coaches,
            "coordinator" => $overall_coordinator,
            "ratio" => $overall_ratio
        );

        $annual_period = "\n".date("M-y",strtotime($last_year_date)) .' to '. date("M-y",strtotime($to_date));
        
        $data['combined_data'] = $combined_data;

        $data['revenue_month'] = $month_name;
        $data['annual_period'] = $annual_period;

        $data['success'] = true;
        $data['records'] = $cities;
        return Response::json($data,200,array());
    }

    public function revenue(Request $request){
        $user = User::AuthenticateUser($request->header("apiToken"));
        $center_ids = $request->center_ids;
        $graph_data = $request->graph;

        $today = date("Y-m-d");

        $days = ["","Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
        $months = ["","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

        $data_points = [];

        $by_dates = false;

        if(isset($graph_data["start_date"])){
            if($graph_data["start_date"]){
                $graph_data["start_date"] = date("Y-m-d",strtotime($graph_data["start_date"]));
                $graph_data["end_date"] = date("Y-m-d",strtotime($graph_data["end_date"]));

                $duration = (strtotime($graph_data["end_date"]) - strtotime($graph_data["start_date"]))/86400;

                if($duration <= 40){
                    $graph_data["type"] = 2;
                } else {
                    $graph_data["type"] = 3;
                }
                $by_dates = true;
            }

        }

        if($graph_data["type"] == 1){
            
            $week_date = date("w");
            
            if($week_date == 0) $week_date = 7;

            for ($i = 1; $i <= $week_date; $i++) {
                $str = strtotime("- ".($week_date-$i)."days",strtotime($today));
                $date = date("Y-m-d",$str);

                if($i == 1) $start_date = $date;

                if($i == $week_date) $end_date = $date;

                $data_point = new DataPoint;
                $data_point->label = $days[$i];
                $data_point->date = $date;
                $data_point->type = "date";

                $data_points[] = $data_point;
                
            }
        }

        if($graph_data["type"] == 2){

            $month_str = date("Y-m",strtotime($today));

            if($by_dates){
                $start_date = $graph_data["start_date"];
                $end_date = $graph_data["end_date"];

            } else {
                $start_date = $month_str."-01";
                $end_date = $today;
            }

            $year_start = date("y",strtotime($start_date));
            $month_start = date("n",strtotime($start_date));
            $day_start = date("j",strtotime($start_date));

            $year_end = date("y",strtotime($end_date));
            $month_end = date("n",strtotime($end_date));
            $day_end = date("j",strtotime($end_date));

            for ($y=$year_start; $y <= $year_end ; $y++) { 
                for ($m = 1; $m <= 12; $m++) {

                    if($y == $year_start && $m < $month_start) continue;
                    if($y == $year_end && $m > $month_end) continue;
                    
                    for ($d = 1; $d <= 31; $d++) {

                        if($m == $month_start && $d < $day_start) continue;
                        if($m == $month_end && $d > $day_end) continue;
                        
                        if($d < 10) $day = "0".$d;
                        else $day = $d;

                        if($m < 10) $mon = "0".$m;
                        else $mon = $m;

                        $date = "20".$y."-".$mon."-".$day;

                        if(!ReportController::validateDate($date)){
                            continue;
                        }

                        $data_point = new DataPoint;
                        $data_point->label = $d;
                        $data_point->date = $date;
                        $data_point->type = "date";

                        $data_points[] = $data_point;

                    }   

                }
            }

        }

        if($graph_data["type"] == 3){

            if($by_dates){
                $start_date = $graph_data["start_date"];
                $end_date = $graph_data["end_date"];

            } else {
                $year_start = date("y");
                $start_date = "20".$year_start."-01-01";
                $end_date = $today;
            }

            $year_start = date("y",strtotime($start_date));
            $month_start = date("n",strtotime($start_date));

            $month_end = date("n",strtotime($end_date));
            $year_end = date("y",strtotime($end_date));

            for ($y=$year_start; $y <= $year_end ; $y++) { 
                for ($m = 1; $m <= 12; $m++) { 
                    if($y == $year_start){
                        if($m < $month_start) continue;
                    }
                    if($y == $year_end){
                        if($m > $month_end) continue;
                    }

                    $data_point = new DataPoint;
                    $data_point->label = $months[$m]."-".$y;
                    $data_point->month = $months[$m]."-".$y;
                    $data_point->type = "month";

                    $data_points[] = $data_point;

                }
            }
        }

        $payment_days = [];
        $payment_months = [];

        $payments = DB::table("payment_history")->select("payment_history.amount","groups.center_id","payment_history.payment_date","payment_history.total_amount")->join('students','students.id','=','payment_history.student_id')->join("groups","groups.id","=","students.group_id")->whereIn("groups.center_id",$center_ids)->whereBetween('payment_history.payment_date',[ $start_date , $end_date ])->get();

        foreach ($payments as $payment) {

            // if(!$payment->dor) continue;

            $date = $payment->payment_date;
            $month = date("M-y",strtotime($payment->payment_date));

            if(!isset($payment_days[$date])){
                $payment_days[$date] = 0;
            }

            if(!isset($payment_months[$month])){
                $payment_months[$month] = 0;
            }

            // $payment_days[$date] += ($payment->total_amount)?$payment->total_amount:$payment->amount;
            // $payment_months[$month] += ($payment->total_amount)?$payment->total_amount:$payment->amount;

            $payment_days[$date] += $payment->amount;
            $payment_months[$month] += $payment->amount;
        }


        foreach ($data_points as $data_point) {

            if($data_point->type == "date"){

                if(isset($payment_days[$data_point->date])){
                    
                    $data_point->value = $payment_days[$data_point->date];
                } else {
                    $data_point->value = 0;
                }
            }

            if($data_point->type == "month"){

                if(isset($payment_months[$data_point->month])){
                    
                    $data_point->value = $payment_months[$data_point->month];
                } else {
                    $data_point->value = 0;
                }
            }
        }

        $data["data_points"] = $data_points;
        return Response::json($data,200,array());
    }

    public static function convertCurrency($amount){
        if($amount < 1000){
            return $amount;
        } elseif($amount < 100000){
            return round($amount/1000,1)."K";
        } else {
            return round($amount/100000,1)."L";
        }
    }

    public static function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public $start_date_ref;

    public function report(){
        
        $report_type = Input::get("report_type");

        $month = Input::get("month");
        $year = Input::get("year");

        if(!$month){
            $month = date("m");
        }
        if(!$year){
            $year = date("Y");
        }

        // $report_type = "revenue";
        // $month = "10";
        // $year = "2019";

        $start_date = $year."-".$month."-01";
        $end_date = date("Y-m-t", strtotime($start_date));

        $start_date_ref = Input::get("date_ref");
        if($start_date_ref){
            $start_date_ref = date("Y-m-d", strtotime($start_date_ref));
        }
        $this->start_date_ref = $start_date_ref;

        $from_date = $start_date;
        $to_date = $end_date;

        $days = ($to_date - $from_date)/86400;

        $dates = [];
        $weeks = [];

        if($report_type == "revenue"){
           $centers_data = $this->getPaymentData($from_date, $to_date, $days);
        }

        if($report_type == "ds_enrolled"){
           $centers_data = $this->getDStoEnrolled($from_date, $to_date);
        }

        if($report_type == "lms_pending"){
           $all_data = $this->getLMSPending($start_date, $end_date);
           $centers_data = $all_data[0];
           $cities_data = $all_data[1];
           $members_data = $all_data[2];
        }

        if($report_type == "renewals_pending"){
           $centers_data = $this->getRenewalPending($from_date, $to_date);
        }

        if($report_type == "inactive_marked"){
           $centers_data = $this->getInactiveMarked($from_date, $to_date);
        }

        if($report_type == "cash_flow"){
           $all_data = $this->getCashFlow($start_date,$end_date);
           $centers_data = $all_data[0];
           $dates = $all_data[1];
           $weeks = $all_data[2];
        }

        if($report_type == "demo_scheduled" || $report_type == "demo_attended" || $report_type == "verbal_confirmation"){
           $all_data = $this->getLMSData($report_type,$start_date,$end_date);
           $centers_data = $all_data[0];
           $dates = $all_data[1];
           $weeks = $all_data[2];
        }

        if($report_type == "enrolled" ){
           $all_data = $this->getEnrolled($start_date,$end_date);
           $centers_data = $all_data[0];
           $dates = $all_data[1];
           $weeks = $all_data[2];
        }

        if($report_type == "renewed" ){
           $all_data = $this->getRenewed($start_date,$end_date);
           $centers_data = $all_data[0];
           $dates = $all_data[1];
           $weeks = $all_data[2];
        }

        $detailed = ["cash_flow","demo_scheduled","demo_attended","verbal_confirmation","enrolled","renewed"];

        $all_india = new AllIndia("All India");
        if(in_array($report_type, $detailed)){
            $all_india->data = new CenterData($dates,$weeks);
        }

        $cities = DB::table("city")->select("id","city_name as name")->where("city_status",0)->get();
        // $cities = DB::table("city")->select("id","city_name as name")->where("city_status",0)->where("id",24)->get();
        foreach ($cities as $city) {

            $total_value = 0;
            $centers = DB::table("center")->select("id","center_name as name","id as value")->where("city_id",$city->id)->where("center_status",0)->get();
            
            if(in_array($report_type, $detailed)){
                $city->data = new CenterData($dates,$weeks);
            }

            foreach ($centers as $center) {

                if(isset($centers_data[$center->id])){
                    if(is_object($centers_data[$center->id])){
                        $center->value = $centers_data[$center->id]->month;
                        $center->data = $centers_data[$center->id];
                    } else {
                        $center->value = $centers_data[$center->id];
                    }
                } else {
                    $center->data = new CenterData($dates,$weeks);
                    $center->value = 0;
                }
                $total_value += $center->value;
            }

            $city->centers = $centers;

            if($report_type == "lms_pending"){
                if(isset($cities_data[$city->id])){
                    $city->value = $cities_data[$city->id];
                } else {
                    $city->value = 0;
                }
            } else {
                $city->value = $total_value;
            }

            if(in_array($report_type, $detailed)){
                
                for ($i=0; $i < sizeof($city->data->days); $i++) { 
                    foreach ($city->centers as $center) {
                        $city->data->days[$i]->value += $center->data->days[$i]->value;
                        $all_india->data->days[$i]->value += $center->data->days[$i]->value;
                    }
                }

                for ($i=0; $i < sizeof($city->data->weeks); $i++) { 
                    foreach ($city->centers as $center) {
                        $city->data->weeks[$i]->value += $center->data->weeks[$i]->value;
                        $all_india->data->weeks[$i]->value += $center->data->weeks[$i]->value;
                    }
                }

            }

            $all_india->value += $city->value;

        }

        $members = [];
        if($report_type == "lms_pending"){
            $members = DB::table("users")->get();

            foreach ($members as $member) {
                if(isset($members_data[$member->id])){
                    $member->value = $members_data[$member->id];
                } else {
                    $member->value = 0;
                }
            }

            if($members_data[0] > 0){
                $members[] =  array("name" => "Not Assigned", "value" => $members_data[0]);
            }
        }

        $data["success"] = true;
        $data["start_date"] = $start_date;
        $data["end_date"] = $end_date;
        $data["all_india"] = $all_india;
        $data["cities"] = $cities;
        $data["members"] = $members;
        $data["dates"] = $dates;
        $data["weeks"] = $weeks;

        return Response::json($data,200,array());
    }

    private function getPaymentData($from_date, $to_date, $days){

        $centers_data = [];

        $payments = DB::table("payment_items")->select("payment_items.start_date","payment_items.end_date","payment_items.amount","groups.center_id")->join("payment_history","payment_history.id","=","payment_items.payment_history_id")->join("students","students.id","=","payment_history.student_id")->join("groups","groups.id","=","students.group_id")->where(function($query) use ($from_date,$to_date){

            $query->where(function($query1) use ($from_date, $to_date) {
                $query1->where("payment_items.start_date",">=",$from_date)->where("payment_items.start_date","<=",$to_date); //payments start date in the month
            })->orWhere(function($query2) use ($from_date, $to_date) {
                $query2->where("payment_items.start_date","<",$from_date)->where("payment_items.end_date",">",$to_date);
                // payments on complete span of the month
            })->orWhere(function($query3) use ($from_date, $to_date) {
                $query3->where("payment_items.start_date","<",$from_date)->whereBetween("payment_items.end_date",[$from_date,$to_date]);
                // payments on ending on this month
            });
        });

        $payments = $payments->get();

        foreach ($payments as $payment) {
            if(!isset($centers_data[$payment->center_id])) $centers_data[$payment->center_id] = 0;

            if($payment->start_date <= $from_date && $payment->end_date >= $to_date){
                $payment->month_days = $days;
            } else {
                if($payment->start_date < $from_date){
                    $month_days = (strtotime($payment->end_date) - strtotime($from_date))/86400;
                } else {
                    $month_days = (strtotime($to_date) - strtotime($payment->start_date))/86400;
                }

                if($month_days > $days) $month_days = $days;

                $payment->month_days = $month_days;
            }

            $payment->total_duration = (strtotime($payment->end_date) - strtotime($payment->start_date))/86400;
            if($payment->total_duration > 0){
                $payment->month_amount = round(($payment->month_days*$payment->amount)/$payment->total_duration);
            } else {
                $payment->month_amount = 0;
            }

            $centers_data[$payment->center_id] += $payment->month_amount;
        }

        return $centers_data;
    }

    private function getDStoEnrolled($from_date, $to_date){

        $centers_data = [];

        // $lead_ids = DB::table("lead_history")->select("lead_history.lead_id")->where("lead_history.status",2)->whereBetween("lead_history.action_date",[$from_date,$to_date])->orderBy("lead_history.action_date","DESC")->pluck("lead_id")->toArray();

        // $lead_ids = DB::table("students")->select("students.lead_id")->whereBetween("students.dos",[$from_date,$to_date])->pluck("lead_id")->toArray(); // get the leads which were enrolled by students dos

        // if(sizeof($lead_ids) > 0){
            //check if the leads were demo scheduleed
            $leads = DB::table("lead_history")->select("lead_history.lead_id","center.city_id","groups.center_id")->join("leads","leads.id","=","lead_history.lead_id")->join("students","leads.id","=","students.lead_id")->join("groups","groups.id","=","students.group_id")->join("center","center.id","=","groups.center_id")->where("lead_history.status",2)->whereBetween("students.dos",[$from_date,$to_date])->get();
            $covered = [];
            $final_leads = [];
            foreach ($leads as $lead) {
                if(!in_array($lead->lead_id,$covered)){
                    $final_leads[] = $lead;
                    $covered[] = $lead->lead_id;
                }
            }
        // } else {
        //     $final_leads = [];
        // }

        foreach ($final_leads as $lead) {
            if(!isset($centers_data[$lead->center_id])) $centers_data[$lead->center_id] = 0;
            $centers_data[$lead->center_id] += 1;
        }
        
        return $centers_data;
    }

    private function getLMSPending($from_date, $to_date){

        $centers_data = [];
        $cities_data = [];
        $members_data = [];

        $today = date("Y-m-d");

        // $leads = DB::table("leads")->select("id as lead_id","city_id","center_id")->where("leads.action_date",[$from_date, $to_date])->where("action_date","<",$today)->whereIn("leads.status",[1,7,8])->get();
        $leads = DB::table("leads")->select("id as lead_id","city_id","center_id","assign_to")->where("leads.created_at","<=",$to_date)->where("action_date","<",$today)->whereIn("leads.status",[1,7,8])->get();

        foreach ($leads as $lead) {

            if($lead->center_id){
                if(!isset($centers_data[$lead->center_id])) $centers_data[$lead->center_id] = 0;
                $centers_data[$lead->center_id] += 1;
            }

            if($lead->city_id){
                if(!isset($cities_data[$lead->city_id])) $cities_data[$lead->city_id] = 0;
                $cities_data[$lead->city_id] += 1;
            }

            if(!isset($members_data[$lead->assign_to])) $members_data[$lead->assign_to] = 0;
            $members_data[$lead->assign_to] += 1;
            
        }
        
        return [$centers_data, $cities_data,$members_data];
    }

    private function getRenewalPending($from_date, $to_date){

        $centers_data = [];

        // $students = DB::table("students")->select("students.id","groups.center_id")->join("groups","groups.id","=","students.group_id")->whereBetween("students.doe",[$from_date, $to_date])->where("students.active",0)->get();

        $students = DB::table("students")->select("students.id","groups.center_id")->join("groups","groups.id","=","students.group_id")->where("students.doe","<",$from_date)->where("students.inactive",0)->get();

        foreach ($students as $student) {

            if($student->center_id){
                if(!isset($centers_data[$student->center_id])) $centers_data[$student->center_id] = 0;
                $centers_data[$student->center_id] += 1;
            }
            
        }
        
        
        return $centers_data;
    }

    private function getInactiveMarked($from_date, $to_date){

        $centers_data = [];

        $students = DB::table("inactive")->select("inactive.student_id","groups.center_id")->join("students","students.id","=","inactive.student_id")->join("groups","groups.id","=","students.group_id")->whereBetween("inactive.inactive_from",[$from_date, $to_date])->get();

        $student_ids = [];

        foreach ($students as $student) {
            if($student->center_id && !in_array($student->student_id, $student_ids)){
                if(!isset($centers_data[$student->center_id])) $centers_data[$student->center_id] = 0;
                $centers_data[$student->center_id] += 1;
                $student_ids[] = $student->student_id;
            }
        }
        
        
        return $centers_data;
    }

    private function getCashFlow($start_date,$end_date){

        $centers_data = [];
        
        $periods = $this->getDaysNWeeks($start_date, $end_date);
        $dates = $periods[0];
        $weeks = $periods[1];

        $payments = DB::table("payment_history")->select("payment_history.amount","payment_history.payment_date","groups.center_id")->join("students","students.id","=","payment_history.student_id")->join("groups","groups.id","=","students.group_id")->whereBetween("payment_history.payment_date",[ $start_date, $end_date ])->get();
        foreach ($payments as $payment) {
            if(!isset($centers_data[$payment->center_id])){
                $centers_data[$payment->center_id] = new CenterData($dates,$weeks);
            }
            $payment->dor = strtotime($payment->payment_date);
            $p_week = date("W",$payment->dor);
            $p_date = date("Y-m-d",$payment->dor);

            foreach ($centers_data[$payment->center_id]->days as $day) {
                if($day->date == $p_date){
                    $day->value += $payment->amount;
                }
            }

            foreach ($centers_data[$payment->center_id]->weeks as $week) {
                if($week->week == $p_week){
                    $week->value += $payment->amount;
                }
            }
            $centers_data[$payment->center_id]->month += $payment->amount;

        }
        
        return [$centers_data, $dates, $weeks];
    }

    private function getLMSData($report_type,$start_date,$end_date){

        $centers_data = [];
        
        $periods = $this->getDaysNWeeks($start_date, $end_date);
        $dates = $periods[0];
        $weeks = $periods[1];

        if($report_type == "demo_scheduled"){
            $lead_status = [2];
        }

        if($report_type == "demo_attended"){
            $lead_status = [3];
        }

        if($report_type == "verbal_confirmation"){
            $lead_status = [8];
        }

        if($report_type == "enrolled"){
            $lead_status = [4];
        }

        $leads = DB::table("lead_history")->distinct("lead_history.lead_id")->select("lead_history.action_date","leads.center_id")->join("leads","leads.id","=","lead_history.lead_id")->whereIn("lead_history.status",$lead_status)->whereBetween("lead_history.action_date",[$start_date,$end_date])->get();

        foreach ($leads as $lead) {
            if(!isset($centers_data[$lead->center_id])){
                $centers_data[$lead->center_id] = new CenterData($dates,$weeks);
            }
            $p_week = date("W",strtotime($lead->action_date));
            $p_date = date("Y-m-d",strtotime($lead->action_date));

            foreach ($centers_data[$lead->center_id]->days as $day) {
                if($day->date == $p_date){
                    $day->value += 1;
                }
            }

            foreach ($centers_data[$lead->center_id]->weeks as $week) {
                if($week->week == $p_week){
                    $week->value += 1;
                }
            }
            $centers_data[$lead->center_id]->month += 1;

        }
        
        return [$centers_data, $dates, $weeks];
    }

    private function getEnrolled($start_date,$end_date){

        $centers_data = [];
        
        $periods = $this->getDaysNWeeks($start_date, $end_date);
        $dates = $periods[0];
        $weeks = $periods[1];

        $payments = DB::table("students")->select("students.id","students.dos","groups.center_id")->join("groups","groups.id","=","students.group_id")->whereBetween("students.dos",[$start_date,$end_date])->get();

        foreach ($payments as $payment) {
            
            if(!isset($centers_data[$payment->center_id])){
                $centers_data[$payment->center_id] = new CenterData($dates,$weeks);
            }
            $payment->dor = strtotime($payment->dos);
            $p_week = date("W",$payment->dor);
            $p_date = date("Y-m-d",$payment->dor);

            foreach ($centers_data[$payment->center_id]->days as $day) {
                if($day->date == $p_date){
                    $day->value += 1;
                }
            }

            foreach ($centers_data[$payment->center_id]->weeks as $week) {
                if($week->week == $p_week){
                    $week->value += 1;
                }
            }
            $centers_data[$payment->center_id]->month += 1;

        }
        
        return [$centers_data, $dates, $weeks];
    }

    private function getRenewed($start_date,$end_date){

        $centers_data = [];
        
        $periods = $this->getDaysNWeeks($start_date, $end_date);
        $dates = $periods[0];
        $weeks = $periods[1];

        $payments = DB::table("payment_items")->select("payment_items.id","payment_history.payment_date","groups.center_id")->join("payment_history","payment_history.id","=","payment_items.payment_history_id")->join("students","students.id","=","payment_history.student_id")->join("groups","groups.id","=","students.group_id")->whereRaw(" payment_items.start_date != students.dos ")->whereNotNull("payment_items.start_date")->whereBetween("payment_history.payment_date",[$start_date , $end_date ])->get();

        foreach ($payments as $payment) {
            
            if(!isset($centers_data[$payment->center_id])){
                $centers_data[$payment->center_id] = new CenterData($dates,$weeks);
            }

            $payment->dor = strtotime($payment->payment_date);

            $p_week = date("W",$payment->dor);
            $p_date = date("Y-m-d",$payment->dor);

            foreach ($centers_data[$payment->center_id]->days as $day) {
                if($day->date == $p_date){
                    $day->value += 1;
                }
            }

            foreach ($centers_data[$payment->center_id]->weeks as $week) {
                if($week->week == $p_week){
                    $week->value += 1;
                }
            }
            $centers_data[$payment->center_id]->month += 1;

        }
        
        return [$centers_data, $dates, $weeks];
    }

    private function getDaysNWeeks($start_date, $end_date){

        $mon_weeks = [];
        $days = [];
        $today = date("Y-m-d");

        $start_date_ref = $this->start_date_ref;

        $d_end = $end_date;
        $d_start = date("Y-m-d",strtotime($d_end) - 86400*7);

        if($today >= $start_date && $today <= $end_date){
            $d_end = $today;
            $d_start = date("Y-m-d",strtotime($d_end) - 86400*7);
        }

        if($start_date_ref){
            if($start_date_ref >= $start_date && $start_date_ref <= $end_date){
                $d_end = $start_date_ref;
                $d_start = date("Y-m-d",strtotime($d_end) - 86400*7);
                // if($d_end > $end_date){
                //     $d_end = $start_date;
                // }
            }
        }

        for ($i=0; $i <= 31; $i++) { 
            $date_ref_tm = strtotime($start_date) + $i*86400;
            $date_ref = date("Y-m-d",$date_ref_tm);

            if($date_ref >= $d_start && $date_ref <= $d_end){
                $days[] = $date_ref;
            }

            if($date_ref <= $end_date){
                $week = date("W",$date_ref_tm);
                if(!in_array($week, $mon_weeks)){
                    $mon_weeks[] = $week;
                }
            }

        }

        $dates = [];

        for ($i = 0; $i <= 7; $i++) { 
            if(isset($days[sizeof($days) - $i])){
                $dates[] = $days[sizeof($days) - $i];
            }
        }

        $dates = array_reverse($dates);

        return [$dates, $mon_weeks];
    }

}

class DataPoint
{
    public $label;
    public $value;
    public $date;
    public $month;
    public $type;

}

/**
 * 
 */
class Revenue
{
    
    public $yearly;
    public $q1;
    public $q2;
    public $q3;
    public $q4;
    public $m1;
    public $m2;
    public $m3;
    public $m4;
    public $m5;
    public $m6;
    public $m7;
    public $m8;
    public $m9;
    public $m10;
    public $m11;
    public $m12;
}

class CenterData {
    public $days;
    public $weeks;
    public $month;

    function __construct($dates, $weeks) {
        $dt = [];
        foreach ($dates as $date) {
            $entry = new CenterDataDate($date);
            $dt[] = $entry;
        }
        $this->days = $dt;

        $wk = [];
        foreach ($weeks as $week) {
            $entry = new CenterDataWeek($week);
            $wk[] = $entry;
        }
        $this->weeks = $wk;

        $this->month = 0;        
    }
}


class CenterDataDate {
    public $date;
    public $value;

    function __construct($date) {
        $this->date = $date;
        $this->value = 0;
    }
}

class CenterDataWeek {
    public $week;
    public $value;

    function __construct($week) {
        $this->week = $week;
        $this->value = 0;
    }
}

class AllIndia {
    public $name;
    public $value;

    function __construct($name) {
        $this->name = $name;
        $this->value = 0;
    }
}